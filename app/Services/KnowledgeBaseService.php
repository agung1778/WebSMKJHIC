<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\AiKnowledgeChunk;
use App\Models\Extracurricular;
use App\Models\Facility;
use App\Models\Major;
use App\Models\News;
use App\Models\Partner;
use App\Models\SchoolProgram;
use App\Models\SpmbSetting;
use App\Models\Teacher;
use App\Models\Testimonial;
use App\Models\Writing;
use Illuminate\Support\Collection;

class KnowledgeBaseService
{
    /**
     * Kontak & identitas sekolah (mengikuti data statis di layout/footer).
     */
    protected const SCHOOL = [
        'name'      => 'SMK Amaliah 1 & 2 Ciawi',
        'address'   => 'Jl. Raya Jl. Tol Jagorawi No.1, Ciawi, Kec. Ciawi, Kabupaten Bogor, Jawa Barat 16720',
        'phone'     => '0856-1922-827 / 0856-4901-1449',
        'whatsapp'  => '6285649011449',
        'email'     => 'smkamaliahciawi@gmail.com',
        'instagram' => '@smkamaliah',
        'youtube'   => 'SMK Amaliah Ciawi',
    ];

    /**
     * Ambang skor cosine agar sebuah chunk dianggap kandidat jawaban.
     * Dinaikkan dari 0.05 (terlalu longgar, memicu jawaban tidak nyambung)
     * menjadi nilai yang jauh lebih ketat.
     */
    protected const MIN_SCORE = 0.20;

    /**
     * Minimal jumlah kata bermakna (token) yang harus cocok antara pertanyaan
     * dan konten agar dianggap relevan. Mencegah kecocokan palsu 1 kata tunggal.
     */
    protected const MIN_OVERLAP = 2;

    /**
     * Skor sangat kuat yang bisa lolos walau overlap kata hanya sedikit
     * (mis. nama jurusan/topik spesifik yang jarang di dalam konten).
     */
    protected const STRONG_SCORE = 0.38;

    /**
     * Bangun seluruh knowledge base dari database website ke tabel ai_knowledge_chunks.
     * Memangkas data lama (khusus chunk) lalu menyimpan ulang.
     */
    public function rebuild(): int
    {
        AiKnowledgeChunk::query()->delete();

        $chunks = collect()
            ->concat($this->majorChunks())
            ->concat($this->spmbChunks())
            ->concat($this->facilityChunks())
            ->concat($this->extracurricularChunks())
            ->concat($this->newsChunks())
            ->concat($this->teacherChunks())
            ->concat($this->partnerChunks())
            ->concat($this->testimonialChunks())
            ->concat($this->programChunks())
            ->concat($this->achievementChunks())
            ->concat($this->aboutChunks());

        // Gabungkan chunk yang berasal dari ENTITAS yang sama (mis. jurusan
        // dengan konten panjang yang terpecah menjadi beberapa chunk dengan
        // judul/topik kembar) menjadi satu chunk utuh. Ini mencegah knowledge
        // base "bertumpuk" sehingga jawaban tidak berulang/overlap.
        $chunks = $chunks
            ->groupBy(fn (array $c) => $c['source_type'] . '|' . ($c['source_id'] ?? ''))
            ->map(function (Collection $group) {
                $first = $group->first();
                $first['content'] = $group
                    ->pluck('content')
                    ->filter(fn ($c) => trim((string) $c) !== '')
                    ->implode("\n\n");
                return $first;
            })
            ->values();

        // Batasi agar knowledge base tidak terlalu besar.
        $chunks = $chunks->take(500);

        foreach ($chunks as $chunk) {
            AiKnowledgeChunk::create($chunk);
        }

        return count($chunks);
    }

    /**
     * Ambil chunk paling relevan untuk sebuah pertanyaan (token cosine similarity).
     */
    public function retrieve(string $question, int $limit = 5): array
    {
        $queryVector = $this->tokenize($question);

        if (empty($queryVector)) {
            return [];
        }

        $queryTokens = array_keys($queryVector);

        // Ambil semua chunk (batasi untuk performa pada data besar).
        $chunks = AiKnowledgeChunk::query()->latest('id')->limit(5000)->get();

        $scored = $chunks->map(function (AiKnowledgeChunk $chunk) use ($queryVector, $queryTokens) {
            $contentVector = $this->tokenize($chunk->title . ' ' . $chunk->content);
            $score = $this->cosineSimilarity($queryVector, $contentVector);

            // Petakan token query mana yang benar-benar muncul di konten.
            $overlap = 0;
            foreach ($queryTokens as $token) {
                if (isset($contentVector[$token])) {
                    $overlap++;
                }
            }

            return [
                'chunk'   => $chunk,
                'score'   => $score,
                'overlap' => $overlap,
            ];
        })
            // Wajib: overlap kata bermakna memadai ATAU skor sangat kuat
            // (lihat isPlausibleMatch untuk aturan detail).
            ->filter(fn ($item) => $this->isPlausibleMatch($item))
            ->sortByDesc('score')
            ->take($limit)
            ->values();

        return $scored->map(fn ($item) => $item['chunk'])->all();
    }

    /**
     * Ambil chunk berdasarkan pencarian kata kunci (fallback tanpa vektor).
     * Hanya mengembalikan chunk bila ada minimal MIN_OVERLAP kata bermakna
     * yang benar-benar cocok sebagai kata utuh — mencegah kecocokan palsu.
     */
    public function searchByKeywords(string $question, int $limit = 5): array
    {
        $tokens = $this->tokenize($question);

        if (empty($tokens)) {
            return [];
        }

        $queryTokens = array_keys($tokens);
        $chunks = AiKnowledgeChunk::query()->latest('id')->limit(5000)->get();

        return $chunks->map(function (AiKnowledgeChunk $chunk) use ($queryTokens) {
            $haystack = mb_strtolower($chunk->title . ' ' . $chunk->content);
            $hits = 0;
            foreach ($queryTokens as $token) {
                if (mb_strpos($haystack, $token) !== false) {
                    $hits++;
                }
            }

            return [
                'chunk' => $chunk,
                'hits'  => $hits,
            ];
        })->filter(fn ($item) => $item['hits'] >= self::MIN_OVERLAP)
            ->sortByDesc('hits')
            ->take($limit)
            ->values()
            ->map(fn ($item) => $item['chunk'])
            ->all();
    }

    /**
     * Nilai skor tertinggi untuk sebuah pertanyaan (untuk validasi relevansi).
     */
    public function bestScore(string $question): float
    {
        $queryVector = $this->tokenize($question);

        if (empty($queryVector)) {
            return 0.0;
        }

        $best = 0.0;
        AiKnowledgeChunk::query()->latest('id')->limit(5000)->get()
            ->each(function (AiKnowledgeChunk $chunk) use ($queryVector, &$best) {
                $contentVector = $this->tokenize($chunk->title . ' ' . $chunk->content);
                $best = max($best, $this->cosineSimilarity($queryVector, $contentVector));
            });

        return round($best, 4);
    }

    /**
     * Cegah kecocokan dangkal: query acak yang hanya menumpang 1 kata umum
     * di dalam konten (mis. "berapa harga iphone" -> kata "harga" di Bisnis Retail)
     * tidak boleh dianggap relevan.
     */
    protected function isPlausibleMatch(array $item): bool
    {
        // Dianggap relevan bila overlap kata bermakna memadai ATAU skor sangat kuat.
        return $item['overlap'] >= self::MIN_OVERLAP || $item['score'] >= self::STRONG_SCORE;
    }

    /** --- Pembangun chunk per sumber --- */

    protected function majorChunks(): Collection
    {
        $items = [];
        foreach (Major::orderBy('id', 'asc')->get() as $m) {
            $parts = ['Jurusan: ' . $m->name];
            if (! empty($m->tag)) {
                $parts[] = 'Tag/keahlian: ' . $m->tag;
            }
            if (! empty($m->description)) {
                $parts[] = $this->strip($m->description);
            }
            $content = $this->chunk(implode("\n", $parts));
            foreach ($content as $i => $c) {
                $items[] = $this->make('major', $m->id, $m->name, $c);
            }
        }

        return collect($items);
    }

    protected function spmbChunks(): Collection
    {
        $spmb = SpmbSetting::first();
        if (! $spmb) {
            return collect();
        }

        $parts = ['Informasi Penerimaan Murid Baru (SPMB/PPDB) SMK Amaliah 1 & 2 Ciawi'];
        $parts[] = 'Status pendaftaran: ' . ($spmb->status ?? 'tidak tersedia');
        if (! empty($spmb->wave_name)) {
            $parts[] = 'Gelombang: ' . $spmb->wave_name;
        }
        if (! empty($spmb->period_date)) {
            $parts[] = 'Periode: ' . $spmb->period_date;
        }
        if (! empty($spmb->quota_note)) {
            $parts[] = 'Kuota: ' . $spmb->quota_note;
        }
        if (! empty($spmb->registration_link)) {
            $parts[] = 'Link pendaftaran online: ' . $spmb->registration_link;
        }

        return collect([$this->make('spmb', $spmb->id, 'SPMB / PPDB', $this->chunk(implode("\n", $parts))[0])]);
    }

    protected function facilityChunks(): Collection
    {
        $items = [];
        foreach (Facility::orderBy('id', 'asc')->get() as $f) {
            $parts = ['Fasilitas: ' . $f->name];
            if (! empty($f->type)) {
                $parts[] = 'Kategori: ' . $f->type;
            }
            if (! empty($f->description)) {
                $parts[] = $this->strip($f->description);
            }
            $items[] = $this->make('facility', $f->id, $f->name, $this->chunk(implode("\n", $parts))[0]);
        }

        return collect($items);
    }

    protected function extracurricularChunks(): Collection
    {
        $items = [];
        foreach (Extracurricular::orderBy('id', 'asc')->get() as $e) {
            $parts = ['Ekstrakurikuler: ' . $e->name];
            if (! empty($e->type)) {
                $parts[] = 'Tipe: ' . $e->type . (str_contains($e->type, 'Wajib') ? ' (wajib)' : ' (pilihan)');
            }
            if (! empty($e->coach)) {
                $parts[] = 'Pembina: ' . $e->coach;
            }
            if (! empty($e->description)) {
                $parts[] = $this->strip($e->description);
            }
            $items[] = $this->make('extracurricular', $e->id, $e->name, $this->chunk(implode("\n", $parts))[0]);
        }

        return collect($items);
    }

    protected function newsChunks(): Collection
    {
        $items = [];
        foreach (News::orderBy('date_published', 'desc')->take(30)->get() as $n) {
            $items[] = $this->make('news', $n->id, $n->title, $this->chunk($this->strip($n->description ?? ''))[0]);
        }

        return collect($items);
    }

    protected function teacherChunks(): Collection
    {
        $items = [];
        foreach (Teacher::orderBy('id', 'asc')->get() as $t) {
            $parts = ['Guru / tenaga pendidik: ' . $t->name];
            if (! empty($t->position)) {
                $parts[] = 'Jabatan: ' . $t->position;
            }
            if (! empty($t->subject)) {
                $parts[] = 'Mata pelajaran: ' . $t->subject;
            }
            if (! empty($t->category)) {
                $parts[] = 'Kategori: ' . $t->category;
            }
            // Tidak sertakan data pribadi/NIP.
            $items[] = $this->make('teacher', $t->id, $t->name, $this->chunk(implode("\n", $parts))[0]);
        }

        return collect($items);
    }

    protected function partnerChunks(): Collection
    {
        $items = [];
        foreach (Partner::orderBy('id', 'asc')->take(40)->get() as $p) {
            $parts = ['Mitra industri: ' . $p->name];
            if (! empty($p->sector)) {
                $parts[] = 'Bidang: ' . $p->sector;
            }
            if (! empty($p->city)) {
                $parts[] = 'Lokasi: ' . $p->city;
            }
            if (! empty($p->description)) {
                $parts[] = $this->strip($p->description);
            }
            $items[] = $this->make('partner', $p->id, $p->name, $this->chunk(implode("\n", $parts))[0]);
        }

        return collect($items);
    }

    protected function testimonialChunks(): Collection
    {
        $items = [];
        foreach (Testimonial::with('major')->orderBy('id', 'desc')->take(30)->get() as $t) {
            $parts = ['Testimoni: ' . $t->name];
            if (! empty($t->major)) {
                $parts[] = 'Jurusan: ' . $t->major->name;
            }
            if (! empty($t->alumni_year)) {
                $parts[] = 'Angkatan: ' . $t->alumni_year;
            }
            if (! empty($t->description)) {
                $parts[] = $this->strip($t->description);
            }
            $items[] = $this->make('testimonial', $t->id, $t->name, $this->chunk(implode("\n", $parts))[0]);
        }

        return collect($items);
    }

    protected function programChunks(): Collection
    {
        $items = [];
        foreach (SchoolProgram::orderBy('id', 'asc')->get() as $p) {
            $parts = ['Program / kegiatan sekolah: ' . $p->name];
            if (! empty($p->description)) {
                $parts[] = $this->strip($p->description);
            }
            $items[] = $this->make('program', $p->id, $p->name, $this->chunk(implode("\n", $parts))[0]);
        }

        return collect($items);
    }

    protected function achievementChunks(): Collection
    {
        $items = [];
        foreach (Achievement::orderBy('id', 'desc')->take(40)->get() as $a) {
            $parts = ['Prestasi: ' . $a->title];
            if (! empty($a->category)) {
                $parts[] = 'Kategori: ' . $a->category;
            }
            if (! empty($a->level)) {
                $parts[] = 'Tingkat: ' . $a->level;
            }
            if (! empty($a->winner)) {
                $parts[] = 'Pemenang: ' . $a->winner;
            }
            if (! empty($a->date)) {
                $parts[] = 'Tanggal: ' . $a->date;
            }
            $items[] = $this->make('achievement', $a->id, $a->title, $this->chunk(implode("\n", $parts))[0]);
        }

        return collect($items);
    }

    protected function aboutChunks(): Collection
    {
        $items = [];
        $map = [
            'About'     => 'Tentang / profil sekolah',
            'VisiMisi'  => 'Visi dan Misi',
            'History'   => 'Sejarah sekolah',
            'Foundation' => 'Yayasan',
        ];

        foreach ($map as $title => $label) {
            $writing = Writing::where('title', $title)->orderBy('release_date', 'desc')->first();
            if ($writing && ! empty(trim((string) $writing->content))) {
                $items[] = $this->make('about', $writing->id, $label, $this->chunk($this->strip($writing->content))[0]);
            }
        }

        // Informasi kontak & identitas.
        $s = self::SCHOOL;
        $contact = "Kontak dan identitas " . $s['name'] . ":\n"
            . "Alamat: {$s['address']}\n"
            . "Telepon: {$s['phone']}\n"
            . "Email: {$s['email']}\n"
            . "WhatsApp: https://wa.me/{$s['whatsapp']}\n"
            . "Instagram: {$s['instagram']}\n"
            . "YouTube: {$s['youtube']}";
        $items[] = $this->make('about', null, 'Kontak & Alamat', $this->chunk($contact)[0]);

        return collect($items);
    }

    /** --- Helper --- */

    protected function make(string $type, $id, ?string $title, string $content): array
    {
        return [
            'source_type' => $type,
            'source_id'   => $id,
            'title'       => $title,
            'content'     => $content,
        ];
    }

    protected function strip(?string $text): string
    {
        $text = preg_replace('/\s+/u', ' ', trim(strip_tags((string) $text)));
        return mb_strimwidth($text, 0, 800, '…');
    }

    protected function chunk(string $text): array
    {
        $text = trim(preg_replace('/\s+/u', ' ', $text));
        if (mb_strlen($text) <= 200) {
            return [$text];
        }

        // Pecah per kalimat sederhana lalu gabungkan hingga max 200 kata.
        $sentences = preg_split('/(?<=[.!?])\s+/u', $text);
        $chunks = [];
        $current = '';

        foreach ($sentences as $sentence) {
            if (mb_strlen($current) + mb_strlen($sentence) <= 200 || $current === '') {
                $current .= ' ' . $sentence;
            } else {
                $chunks[] = trim($current);
                $current = $sentence;
            }
        }

        if (trim($current) !== '') {
            $chunks[] = trim($current);
        }

        return array_values(array_filter($chunks));
    }

    protected function tokenize(string $text): array
    {
        // Tokenisasi: pecah kata, buang kata stop umum bahasa Indonesia.
        $stop = ['yang', 'apa', 'di', 'ke', 'dari', 'dan', 'atau', 'ini', 'itu', 'ada', 'untuk',
            'dengan', 'sekolah', 'smk', 'amaliah', 'siapa', 'berapa', 'mana', 'saya', 'kamu', 'nya',
            'tentang', 'info', 'informasi', 'tolong', 'please', 'bisa', 'mau', 'ingin', 'apakah',
            'dimana', 'yang', 'akan', 'saja', 'juga', 'kalau', 'kalo', 'adakah', 'bagaimana', 'jelaskan',
            'cara', 'buat', 'membuat', 'kenapa', 'mengapa', 'kapan', 'apasaja', 'tolong', 'boleh',
            'dimanakah', 'bagaima', 'jelas', 'tolongin', 'minta', 'mohon', 'beritahu', 'kasih',
            'mau', 'inginnya', 'seperti', 'semua', 'berapa', 'liat', 'lihat', 'cari', 'bantu', 'mungkin'];

        $tokens = preg_split('/[^a-z0-9]+/u', mb_strtolower($text)) ?: [];

        $result = [];
        foreach ($tokens as $token) {
            $token = trim($token);
            if (mb_strlen($token) < 3) {
                continue;
            }
            if (in_array($token, $stop, true)) {
                continue;
            }
            $result[$token] = ($result[$token] ?? 0) + 1;
        }

        return $result;
    }

    protected function cosineSimilarity(array $a, array $b): float
    {
        $denom = sqrt(array_sum(array_map(fn ($x) => $x * $x, $a)) * array_sum(array_map(fn ($x) => $x * $x, $b)));

        if ($denom == 0) {
            return 0.0;
        }

        $dot = 0.0;
        foreach ($a as $token => $count) {
            if (isset($b[$token])) {
                $dot += $count * $b[$token];
            }
        }

        return $dot / $denom;
    }
}
