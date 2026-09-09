<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\Extracurricular;
use App\Models\Facility;
use App\Models\Major;
use App\Models\News;
use App\Models\SchoolProgram;
use App\Models\SpmbSetting;
use App\Models\Teacher;
use App\Models\Writing;

/**
 * ChatbotService — "Tanya AI".
 *
 * Seluruh jawaban bersifat OFFLINE / LOKAL, diambil langsung dari database
 * website (tidak ada panggilan ke API AI eksternal). Terdiri dari 2 lapisan:
 *
 *   LAPISAN 1 — Kata kunci sederhana (TANPA database)
 *       Sapaan, terima kasih, perkenalan, dan info dinamis singkat
 *       (jurusan/ekskul/kontak/alamat/jam layanan) yang memakai daftar statis
 *       sebagai fallback bila tabel memang kosong.
 *
 *   LAPISAN 2 — Pencarian di database (scoring kata kunci)
 *       Pertanyaan dipecah menjadi kata-kata kunci, lalu tiap kata dicocokkan
 *       ke kolom-kolom relevan pada tiap tabel (jurusan, guru, ekskul, berita,
 *       fasilitas, program/kegiatan, tulisan statis). Baris dengan skor
 *       tertinggi (>= 1 kata cocok) yang dipilih dan disusun rapi.
 *
 * Penyesuaian nama Tabel/Kolom:
 *   Project ini memakai nama tabel & kolom berikut (lihat folder database/migrations):
 *     - jurusan       : tabel "majors"             (name, description, tag, advantage)
 *     - guru/staf     : tabel "teachers"            (name, position, subject, category, school)
 *     - ekskul        : tabel "extracurriculars"    (name, type, description, coach)
 *     - berita        : tabel "news"                (title, description, date_published)
 *     - fasilitas     : tabel "facilities"          (name, type, description)
 *     - kegiatan/prog : tabel "school_programs"     (name, description)
 *     - statis        : tabel "writings"            (title, content) untuk Sejarah/VisiMisi/Tentang/Yayasan
 *     - SPMB          : tabel "spmb_settings"
 *   Bila di project Anda nama tabel/kolom berbeda (mis. "extracurricular"
 *   singular tanpa "s", kolom "body" bukan "description", dst.) cukup ubah
 *   nama model & atribut pada konstanta TABLE_CONFIG di bawah.
 */
class ChatbotService
{
    /**
     * Konfigurasi tabel yang dicari pada LAPISAN 2.
     * Setiap entri:
     *   - handler : closure untuk membangun jawaban (menerima hasil baris & pertanyaan).
     *   - fields  : kolom yang dicocokkan untuk scoring.
     * SELALU sesuaikan nama model/atribut dengan database Anda.
     */
    protected const TABLE_CONFIG = [
        'jurusan' => [
            'model'   => Major::class,
            'label'   => 'Jurusan',
            'fields'  => ['name', 'description', 'tag', 'advantage'],
            'order'   => ['id', 'asc'],
        ],
        'guru' => [
            'model'   => Teacher::class,
            'label'   => 'Guru & Staf',
            'fields'  => ['name', 'position', 'subject', 'category', 'school'],
            'order'   => ['id', 'asc'],
        ],
        'ekskul' => [
            'model'   => Extracurricular::class,
            'label'   => 'Ekstrakurikuler',
            'fields'  => ['name', 'type', 'description', 'coach'],
            'order'   => ['id', 'asc'],
        ],
        'berita' => [
            'model'   => News::class,
            'label'   => 'Berita',
            'fields'  => ['title', 'description'],
            'order'   => ['date_published', 'desc'],
        ],
        'fasilitas' => [
            'model'   => Facility::class,
            'label'   => 'Fasilitas',
            'fields'  => ['name', 'type', 'description'],
            'order'   => ['id', 'asc'],
        ],
        'kegiatan' => [
            'model'   => SchoolProgram::class,
            'label'   => 'Program & Kegiatan',
            'fields'  => ['name', 'description'],
            'order'   => ['id', 'asc'],
        ],
    ];

    /**
     * Identitas & kontak sekolah (fallback statis bila di DB tidak ada,
     * atau bila pertanyaan adalah "kontak/alamat". Ubah bila data pindah).
     */
    protected const SCHOOL = [
        'name'              => 'SMK Amaliah 1 & 2 Ciawi',
        'address'           => 'Jl. Raya Jl. Tol Jagorawi No.1, Ciawi, Kec. Ciawi, Kabupaten Bogor, Jawa Barat 16720',
        'phone'             => '0856-1922-827 / 0856-4901-1449',
        'whatsapp'          => '6285649011449',
        'email'             => 'smkamaliahciawi@gmail.com',
        'instagram'         => '@smkamaliah',
        'youtube'           => 'SMK Amaliah Ciawi',
        'service_hours'     => 'Senin–Jumat, 07.30–15.30 WIB',
    ];

    /**
     * Kata yang dianggap TIDAK bermakna dan dibuang saat pecah kata kunci.
     * (dipakai pada LAPISAN 2)
     */
    protected const STOP_WORDS = [
        'apa', 'dimana', 'kapan', 'berapa', 'yang', 'di', 'ke', 'dan', 'atau',
        'tolong', 'saya', 'mau', 'ingin', 'info', 'informasi', 'apakah', 'ada',
        'adakah', 'ini', 'itu', 'untuk', 'tentang', 'dengan', 'bagaimana', 'mana',
        'saja', 'juga', 'bisa', 'silahkan', 'jelaskan', 'cekidot', 'kamu', 'nya',
        // Kata identitas sekolah: sangat umum muncul di hampir semua konten,
        // sehingga TIDAK boleh dihitung sebagai kata kunci pencocokan.
        // (mis. "apa itu smk amaliah" tidak boleh cocok dengan berita/ekskul acak).
        'smk', 'amaliah', 'sekolah', 'siswa', 'murid', 'kelas', 'tahun', 'ajaran',
        // Kata pengisi umum yang tidak bermakna untuk scoring.
        'seperti', 'begitu', 'tersebut', 'sih', 'ly', 'dulu', 'dong', 'kak',
    ];

    /**
     * Kata kunci penentu topik untuk LAPISAN 2 (penemuan cepat).
     */
    protected const TOPIC_KEYWORDS = [
        'jurusan'   => ['jurusan', 'major', 'keahlian', 'kompetensi', 'rpl', 'tkj', 'dkv', 'akuntansi', 'busana', 'perbankan'],
        'guru'      => ['guru', 'pendidik', 'pengajar', 'staf', 'staff', 'wali', 'kepala sekolah', 'tenaga'],
        'ekskul'    => ['ekskul', 'eskul', 'ekstrakurikuler', 'extra', 'pramuka', 'paskibra', 'futsal', 'basket'],
        'berita'    => ['berita', 'news', 'artikel', 'postingan', 'kabar', 'terbaru'],
        'fasilitas' => ['fasilitas', 'facility', 'sarana', 'prasarana', 'lab', 'laboratorium', 'perpustakaan', 'aula', 'mushola'],
        'kegiatan'  => ['kegiatan', 'agenda', 'event', 'program', 'acara', 'mpls'],
    ];

    /** Skor minimal agar sebuah baris dianggap jawaban (sesuai spek: >= 1). */
    protected const MIN_SCORE = 1;

    /**
     * Akronim jurusan/mapel -> istilah yang dipakai di kolom subject guru.
     * Berguna untuk pertanyaan seperti "Siapa saja guru RPL?".
     * Sesuaikan dengan penamaan mapel/subject di tabel teachers Anda.
     */
    protected const SUBJECT_ALIASES = [
        'rpl'           => ['pplg', 'rpl', 'rekayasa perangkat lunak'],
        'tkj'           => ['tjkt', 'tkj', 'teknik komputer'],
        'animasi'       => ['an', 'animasi'],
        'dkv'           => ['dkv', 'desain komunikasi visual'],
        'mp'            => ['mp', 'manajemen perkantoran'],
        'lps'           => ['lps', 'layanan perbankan', 'perbankan'],
        'dpb'           => ['dpb', 'busana'],
        'ak'            => ['akl', 'ak', 'akuntansi'],
        'br'            => ['br', 'retail'],
        'matematika'    => ['matematika'],
        'bahasa inggris'=> ['bahasa inggris', 'inggris'],
    ];

    protected KnowledgeBaseService $knowledgeBase;

    public function __construct(KnowledgeBaseService $knowledgeBase)
    {
        $this->knowledgeBase = $knowledgeBase;
    }

    /**
     * Pipelines utama chatbot.
     */
    public function ask(?string $question): array
    {
        $q = $this->normalize((string) $question);

        if ($q === '') {
            return ['answered' => true, 'reply' => $this->greeting()];
        }

        // ---- LAPISAN 1: Kata kunci sederhana (tanpa DB) ----
        $layer1 = $this->layerKeywordSimple($q);
        if ($layer1 !== null) {
            return ['answered' => true, 'reply' => $layer1];
        }

        // ---- LAPISAN 2: Pencarian di database ----
        $layer2 = $this->layerDatabaseSearch($q);
        if ($layer2 !== null) {
            return ['answered' => true, 'reply' => $layer2];
        }

        // ---- FALLBACK: Knowledge base lokal (opsional, semua dari DB sendiri) ----
        $kb = $this->askFromKnowledgeBase($q);
        if ($kb !== null) {
            return ['answered' => true, 'reply' => $kb];
        }

        // ---- JAWABAN DEFAULT: minta klarifikasi ----
        return [
            'answered' => false,
            'reply'    => $this->clarification(),
        ];
    }

    // =====================================================================
    //  LAPISAN 1 — KATA KUNCI SEDERHANA (TANPA DATABASE)
    // =====================================================================

    protected function layerKeywordSimple(string $q): ?string
    {
        // Sapaan
        if ($this->matchAny($q, ['halo', 'hai', 'hi', 'hello', 'assalamu', 'selamat'])) {
            return $this->greeting();
        }

        // Terima kasih
        if ($this->matchAny($q, ['terima kasih', 'makasih', 'thanks', 'thank you', 'thx', 'trims'])) {
            return "Sama-sama! 😊 Senang bisa membantu. Kalau ada pertanyaan lain "
                . "seputar SMK Amaliah 1 & 2 Ciawi, jangan ragu bertanya lagi ya 🏫";
        }

        // Perkenalan asisten
        if ($this->matchAny($q, ['kamu siapa', 'siapa kamu', 'kamu itu', 'nama kamu',
            'kamu robot', 'asisten', 'apa kamu', 'kamu siapa sih'])) {
            return $this->chatbotIdentity();
        }

        // Kontak / alamat (statis; walau juga bisa muncul dari tabel settings bila ada)
        if ($this->matchAny($q, ['kontak', 'alamat', 'lokasi', 'telp', 'telepon',
            'whatsapp', 'email', 'instagram', 'youtube',
            'dimana sekolah', 'di mana sekolah', 'dimanakah sekolah'])) {
            return $this->contactAnswer();
        }

        // Info dinamis singkat — dipakai sebagai FALLBACK STATIS bila tabel kosong.
        // Bila tabel terisi, biarkan LAPISAN 2 yang menjawab (list/detail lebih akurat).
        if ($this->matchAny($q, ['jurusan', 'program keahlian', 'major'])) {
            if (Major::query()->count() === 0) {
                return $this->shortMajorsAnswer();
            }
        }
        if ($this->matchAny($q, ['ekskul', 'eskul', 'ekstrakurikuler', 'extra'])) {
            if (Extracurricular::query()->count() === 0) {
                return $this->shortExtracurricularAnswer();
            }
        }
        if ($this->matchAny($q, ['jam layanan', 'jam operasional', 'jam belajar', 'jam buka'])) {
            return $this->hoursAnswer();
        }

        return null;
    }

    // ---- Jawaban singkat LAPISAN 1 (fallback statis; tetap cek DB bila isi) ----

    protected function shortMajorsAnswer(): string
    {
        $majors = Major::orderBy('id', 'asc')->get();

        if ($majors->isEmpty()) {
            // Fallback statis bila tabel kosong.
            return "SMK Amaliah 1 & 2 Ciawi menyediakan beragam jurusan, di antaranya "
                . "**Rekayasa Perangkat Lunak (RPL)**, **Teknik Komputer & Jaringan (TKJ)**, "
                . "**Desain Komunikasi Visual (DKV)**, dan lainnya.\n\n"
                . "Ketik nama jurusan untuk info detail, misalnya \"RPL\". 😊";
        }

        $names = $majors->pluck('name')->map(fn ($n) => '• ' . $n)->implode("\n");
        return "SMK Amaliah 1 & 2 Ciawi memiliki jurusan:\n\n{$names}\n\n"
            . "Tanyakan nama jurusan untuk info lebih detail, ya 😊";
    }

    protected function shortExtracurricularAnswer(): string
    {
        $exc = Extracurricular::orderBy('id', 'asc')->get();

        if ($exc->isEmpty()) {
            return "Eskul di SMK Amaliah cukup beragam — ada **Pramuka**, **Paskibra**, "
                . "organisasi keagamaan, olahraga, seni, dan lainnya.\n\n"
                . "Tanyakan nama ekskul untuk detail, misalnya \"ekskul pramuka\". 😊";
        }

        $names = $exc->pluck('name')->map(fn ($n) => '• ' . $n)->implode("\n");
        return "Ekstrakurikuler di SMK Amaliah:\n\n{$names}\n\n"
            . "Tanyakan nama ekskul untuk info lebih detail 😊";
    }

    protected function hoursAnswer(): string
    {
        return "Jam layanan **" . self::SCHOOL['name'] . "**:\n\n"
            . "🕒 " . self::SCHOOL['service_hours'] . "\n\n"
            . "Layanan Tanya AI tersedia kapan saja 😊";
    }

    // =====================================================================
    //  LAPISAN 2 — PENCARIAN DI DATABASE (SCORING KATA KUNCI)
    // =====================================================================

    protected function layerDatabaseSearch(string $q): ?string
    {
        // PENGAWAL TOPIK: Chatbot ini khusus menjawab seputar SMK. Bila pertanyaan
        // sama sekali tidak menyentuh topik sekolah, lewati pencarian DB agar
        // beralih ke knowledge-base / klarifikasi — mencegah jawaban tidak nyambung
        // (mis. "berapa harga iphone", "siapa presiden", "cara membuat kue").
        if (! $this->hasSchoolTopic($q)) {
            return null;
        }

        // PROFIL SEKOLAH: bila pertanyaan menyentuh topik sekolah tetapi setelah
        // kata-kata identitas ("smk", "amaliah", "sekolah", dst.) dibuang tidak
        // tersisa kata kunci entitas sama sekali, jawab profil singkat —
        // mencegah pertanyaan seperti "apa itu smk amaliah" nyasar ke berita atau
        // ekskul acak hanya karena nama sekolah muncul di kontennya.
        if (empty($this->significantWords($q))) {
            $profile = $this->schoolProfileAnswer();
            if ($profile !== null) {
                return $profile;
            }
        }

        // 0) Kepala sekolah (paling spesifik, sebelum pencarian nama guru agar
        //    pertanyaan "siapa kepala sekolahnya" tidak jatuh ke guru bernama lain).
        if ($this->matchAny($q, ['kepala sekolah', 'kepala smk', 'kepala amaliah'])) {
            $headmaster = $this->headmasterAnswer($q);
            if ($headmaster !== null) {
                return $headmaster;
            }
        }

        // 1) Nama guru spesifik (paling kuat, dicoba pertama).
        $teacher = $this->findBestTeacher($q);
        if ($teacher !== null) {
            return $this->teacherDetail($teacher);
        }

        // 1b) Guru berdasarkan mapel/bidang, mis. "Siapa saja guru RPL?".
        if ($this->matchAny($q, ['guru', 'pendidik', 'pengajar', 'staf', 'staff', 'siapa saja'])) {
            $teachersBySubject = $this->findTeachersBySubject($q);
            if ($teachersBySubject !== null) {
                return $teachersBySubject;
            }
        }

        // 2) Nama jurusan spesifik (termasuk akronim).
        $majorDetail = $this->findSpecificMajor($q);
        if ($majorDetail !== null) {
            return $majorDetail;
        }

        // 2b) Daftar jurusan ("Ada jurusan apa saja?", "daftar jurusan").
        if ($this->matchAny($q, ['jurusan apa', 'jurusan apa saja', 'jurusan apa aja',
            'daftar jurusan', 'jurusan yang ada', 'jurusan di smk', 'jurusan ada apa'])) {
            return $this->shortMajorsAnswer();
        }

        // 3) Nama ekskul / fasilitas / program spesifik via scoring umum.
        $specific = $this->findSpecificNamed($q, ['ekskul', 'fasilitas', 'kegiatan']);
        if ($specific !== null) {
            return $specific;
        }

        // 4) Tulis statis (Sejarah / Visi Misi / Tentang / Yayasan).
        $writing = $this->findWriting($q);
        if ($writing !== null) {
            return $writing;
        }

        // 5) SPMB / PPDB.
        // "daftar" dipakai sebagai kata kunci SPMB, KECUALI bila konteksnya
        // justru meminta daftar entitas (mis. "daftar guru", "daftar fasilitas",
        // "daftar ekskul", "daftar program") — kasus itu dijawab oleh
        // entityListAnswer() di bawah, bukan info SPMB.
        // Kata pemicu KUAT (jelas SPMB/PPDB).
        $strongSpmb = $this->matchAny($q, [
            'spmb', 'ppdb', 'pendaftaran', 'masuk sekolah', 'cara daftar',
            'cara mendaftar', 'syarat daftar', 'syarat pendaftaran', 'syarat masuk',
        ]);

        // Kata pemicu LEMAH ('daftar','biaya','kuota','gelombang','syarat') hanya
        // dianggap SPMB bila disertai konteks sekolah, agar pertanyaan di luar
        // topik (mis. "berapa biaya hidup di jakarta") tidak dijawab SPMB.
        $hasSchoolContext = $this->matchAny($q, [
            'sekolah', 'smk', 'amaliah', 'siswa', 'murid', 'masuk', 'kelas',
        ]);
        $weakSpmb = $this->matchAny($q, ['daftar', 'biaya', 'kuota', 'gelombang', 'syarat']);

        if (! $this->isEntityListRequest($q) && ($strongSpmb || ($hasSchoolContext && $weakSpmb))) {
            return $this->spmbAnswer();
        }

        // 6) Prestasi siswa ("ada prestasi?", "juara apa saja", "prestasi terbaru").
        // Didahulukan daripada berita agar kalimat yang memuat kata "prestasi"
        // (mis. "prestasi terbaru") tidak jatuh ke berita.
        if ($this->matchAny($q, ['prestasi', 'juara', 'penghargaan', 'achievement', 'trofi', 'medali'])) {
            $achievements = $this->achievementsAnswer();
            if ($achievements !== null) {
                return $achievements;
            }
        }

        // 6a) Berita terbaru (bila terdeteksi topik berita).
        if ($this->matchAny($q, self::TOPIC_KEYWORDS['berita'])) {
            return $this->newsListAnswer();
        }

        // 6c) Keunggulan sekolah ("apa keunggulan/kelebihan sekolah") — rangkum
        //     dari kolom advantage tiap jurusan agar tidak jatuh ke berita acak.
        if ($this->matchAny($q, ['keunggulan', 'kelebihan', 'unggulan', 'kenapa harus', 'alasan masuk'])) {
            $advantages = $this->advantagesAnswer();
            if ($advantages !== null) {
                return $advantages;
            }
        }

        // 6d) Daftar entitas: "daftar/apa saja/sebutkan + [guru|ekskul|fasilitas|program]".
        $listAnswer = $this->entityListAnswer($q);
        if ($listAnswer !== null) {
            return $listAnswer;
        }

        // 7) Scoring umum per tabel (prioritas sesuai urutan TABLE_CONFIG).
        foreach (self::TABLE_CONFIG as $topic => $cfg) {
            $best = $this->bestRow($q, $cfg['model'], $cfg['fields'], $cfg['order']);
            if ($best !== null) {
                return $this->formatRowAnswer($topic, $cfg['label'], $best);
            }
        }

        return null;
    }

    /**
     * Deteksi apakah pertanyaan menyentuh topik sekolah. Jadi penjaga agar
     * LAPISAN 2 hanya berjalan untuk pertanyaan yang relevan dengan konten SMK.
     */
    protected function hasSchoolTopic(string $q): bool
    {
        // 1) Kata kunci topik sekolah (jurusan, guru, ekskul, berita, fasilitas, dll).
        foreach (self::TOPIC_KEYWORDS as $keywords) {
            if ($this->matchAny($q, $keywords)) {
                return true;
            }
        }

        // 2) Kata kunci lintas-topik: kontak, SPMB, tulisan statis, jam layanan.
        //    Catatan: kata "biaya" sengaja TIDAK dipakai sebagai penentu topik,
        //    karena sering muncul pada konteks di luar sekolah ("biaya hidup").
        if ($this->matchAny($q, [
            'kontak', 'alamat', 'lokasi', 'telp', 'telepon', 'whatsapp', 'email',
            'instagram', 'youtube', 'spmb', 'ppdb', 'pendaftaran', 'daftar', 'gelombang',
            'kuota', 'sejarah', 'visi', 'misi', 'profil', 'tentang', 'yayasan',
            'syarat', 'beasiswa', 'prestasi', 'akreditasi',
        ])) {
            return true;
        }

        // 3) Nama entitas nyata yang dikenal (guru/jurusan/ekskul/fasilitas/program).
        $norm = $this->normalize((string) $q);

        if ($this->namedEntityPresent($norm)) {
            return true;
        }

        // 4) Kata sapaan & konteks umum yang terkait sekolah.
        if ($this->matchAny($norm, ['sekolah', 'smk', 'amaliah', 'siswa', 'murid', 'kelas'])) {
            return true;
        }

        return false;
    }

    /**
     * Cek apakah query memuat NAMA dari entitas yang ada di database (guru,
     * jurusan, ekskul, fasilitas, program). Dipakai sebagai penguat bahwa
     * pertanyaan memang tentang konten sekolah.
     */
    protected function namedEntityPresent(string $norm): bool
    {
        // Nama guru: hanya dihitung bila ada kecocokan nama yang substansial
        // (seluruh nama atau minimal 2 suku kata) — bukan 1 nama depan yang
        // kebetulan cocok (mis. "idul fitri" -> guru bernama Fitri).
        foreach (Teacher::query()->pluck('name') as $name) {
            $tname = $this->normalize((string) $name);
            if ($tname === '') {
                continue;
            }

            if ($this->contains($norm, $tname)) {
                return true;
            }

            $nameWords = preg_split('/\s+/', $tname) ?: [];
            $matchedWords = 0;
            foreach ($nameWords as $word) {
                if (mb_strlen($word) >= 3 && $this->matchesWord($norm, $word)) {
                    $matchedWords++;
                }
            }
            if ($matchedWords >= 2) {
                return true;
            }
        }

        // Nama jurusan / ekskul / fasilitas / program.
        foreach ([
            Major::query()->pluck('name'),
            Extracurricular::query()->pluck('name'),
            Facility::query()->pluck('name'),
            SchoolProgram::query()->pluck('name'),
        ] as $names) {
            foreach ($names as $name) {
                $n = $this->normalize((string) $name);
                if ($n !== '' && $this->contains($norm, $n)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Cari satu baris dengan skor kata kunci tertinggi pada sebuah model,
     * di atas ambang MIN_SCORE. Mengembalikan [row, score, hitFields].
     */
    protected function bestRow(string $q, string $model, array $fields, array $order): ?array
    {
        $queryWords = $this->significantWords($q);
        if (empty($queryWords)) {
            return null;
        }

        $rows = $model::orderBy($order[0], $order[1])->get();

        $best = null;
        $bestScore = 0;
        $bestName = '';

        foreach ($rows as $row) {
            // Gabungkan semua field relevan jadi satu "haystack" untuk dihitung skor.
            $haystack = $this->rowHaystack($row, $fields);
            if ($haystack === '') {
                continue;
            }

            // Haystack khusus field label (name/title) untuk penguat relevansi.
            $label = $this->normalize((string) ($row->{$fields[0]} ?? ''));

            $score = 0;
            $labelHits = 0;
            foreach ($queryWords as $word) {
                if ($this->contains($haystack, $word)) {
                    $score++;
                    if ($label !== '' && $this->contains($label, $word)) {
                        $labelHits++;
                    }
                }
            }

            // Relevansi kuat: minimal 2 kata cocok DAN setidaknya 1 kata cocok di
            // nama/title, ATAU nama/title lengkap muncul di pertanyaan.
            // Ini mencegah baris "menang" hanya karena teks panjangnya (deskripsi,
            // isi berita) kebetulan memuat kata-kata umum dari pertanyaan.
            $fullLabelMatch = $label !== '' && $this->contains($q, $label);
            if (($score >= 2 && $labelHits >= 1) || $fullLabelMatch) {
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $best = $row;
                    $bestName = $row->{$fields[0]} ?? '';
                }
            }
        }

        if ($best === null || $bestScore < self::MIN_SCORE) {
            return null;
        }

        return ['row' => $best, 'score' => $bestScore, 'name' => $bestName, 'fields' => $fields];
    }

    protected function rowHaystack($row, array $fields): string
    {
        $parts = [];
        foreach ($fields as $field) {
            $value = $row->{$field} ?? null;
            if ($value !== null && $value !== '') {
                $parts[] = $this->normalize(is_string($value) ? $value : (string) $value);
            }
        }

        return implode(' ', $parts);
    }

    /**
     * Format jawaban dari satu baris hasil scoring, rapi dengan **judul** bold.
     */
    protected function formatRowAnswer(string $topic, string $label, array $best): string
    {
        $row = $best['row'];
        $fields = $best['fields'];
        $title = trim((string) $row->{$fields[0]} ?? '');

        // Maksimal 1 hasil kuat → detail baris.
        $reply = "**{$title}**\n";

        foreach ($this->detailFields($topic) as $field => $fieldLabel) {
            $value = $this->clean($row->{$field} ?? null);
            if ($value !== '' && $value !== strtolower($title)) {
                // Tanggal tampil rapi ("2026-08-31" -> "31 Agu 2026").
                if (in_array($field, ['date_published', 'date'], true)) {
                    $value = $this->formatDate($value);
                }
                $reply .= "\n*{$fieldLabel}:* {$value}";
            }
        }

        $description = $this->clean($row->description ?? null);
        if ($description !== '' && ! $this->contains($this->normalize($description), $this->normalize($title))) {
            $reply .= "\n\n{$description}";
        }

        return $reply;
    }

    /**
     * Kolom detail yang ingin ditampilkan per topik, selain name/title.
     */
    protected function detailFields(string $topic): array
    {
        return match ($topic) {
            'guru'      => ['position' => 'Jabatan', 'subject' => 'Mata pelajaran', 'category' => 'Kategori', 'school' => 'Sekolah'],
            'ekskul'    => ['type' => 'Tipe', 'coach' => 'Pembina'],
            'fasilitas' => ['type' => 'Kategori'],
            'berita'    => ['date_published' => 'Tanggal'],
            'kegiatan'  => [],
            default     => [],
        };
    }

    // ---- Cari khusus: guru ----

    /**
     * Jawab pertanyaan tentang kepala sekolah berdasarkan posisi di tabel guru.
     * Bila query menyebut spesifik Amaliah 1 / Amaliah 2, tampilkan yang sesuai.
     */
    protected function headmasterAnswer(string $q): ?string
    {
        $teachers = Teacher::query()
            ->where('position', 'like', '%kepala smk%')
            ->orderBy('id', 'asc')
            ->get();

        if ($teachers->isEmpty()) {
            return null;
        }

        // Fokus ke tim kepala smk (bukan kepala kompetensi/TU).
        $headmasters = $teachers
            ->filter(fn (Teacher $t) => preg_match('/kepala smk/i', (string) $t->position))
            ->values();

        if ($headmasters->isEmpty()) {
            return null;
        }

        if ($this->matchAny($q, ['amaliah 1', 'smk amaliah 1'])) {
            $headmasters = $headmasters->filter(fn (Teacher $t) => str_contains((string) $t->school, '1')
                || str_contains((string) $t->position, '1'));
        } elseif ($this->matchAny($q, ['amaliah 2', 'smk amaliah 2'])) {
            $headmasters = $headmasters->filter(fn (Teacher $t) => str_contains((string) $t->school, '2')
                || str_contains((string) $t->position, '2'));
        }

        if ($headmasters->isEmpty()) {
            return null;
        }

        $lines = $headmasters->take(5)->map(fn (Teacher $t) => '• **' . $t->name . '** — ' . $t->position)->implode("\n");
        return "Berikut kepala sekolah SMK Amaliah:\n\n{$lines}";
    }

    protected function findBestTeacher(string $q): ?Teacher
    {
        $teachers = Teacher::orderBy('id', 'asc')->get();
        if ($teachers->isEmpty()) {
            return null;
        }

        $queryWords = $this->significantWords($q);

        // Penanda bahwa pertanyaan memang tentang guru (bukan sekadar kebetulan
        // satu kata nama cocok, mis. "idul fitri" -> guru bernama Fitri).
        $teacherIntent = $this->matchAny($q, [
            'guru', 'staf', 'staff', 'pendidik', 'pengajar', 'tenaga', 'kepala',
            'wali', 'pak', 'pakde', 'bu', 'buk', 'ibu', 'ustadz', 'ustad',
        ]);

        $best = null;
        $bestScore = 0;
        foreach ($teachers as $teacher) {
            $norm = $this->normalize((string) $teacher->name);
            if ($norm === '') {
                continue;
            }

            $score = 0;
            foreach ($queryWords as $word) {
                if ($this->contains($norm, $word)) {
                    $score++;
                }
            }
            // Nama persis / hampir persis → skor maksimal agar lebih dominan.
            $fullNeedle = implode(' ', $queryWords);
            $fullMatch = $fullNeedle !== '' && $this->contains($norm, $fullNeedle);
            if ($fullMatch) {
                $score = max($score, 3);
            }

            // Tanpa penanda intent yang jelas, guru hanya boleh cocok bila ada
            // kecocokan nama yang substansial (multi-kata / nama lengkap),
            // bukan satu kata nama depan yang kebetulan muncul di pertanyaan.
            if (! $teacherIntent && ! $fullMatch && $score < 2) {
                continue;
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $teacher;
            }
        }

        return ($best !== null && $bestScore > 0) ? $best : null;
    }

    // ---- Cari khusus: guru berdasarkan mapel/bidang ----

    protected function findTeachersBySubject(string $q): ?string
    {
        $teachers = Teacher::orderBy('id', 'asc')->get();
        if ($teachers->isEmpty()) {
            return null;
        }

        // Cari istilah mapel yang muncul di pertanyaan (via alias kelompok).
        $subjectNeedles = [];
        foreach (self::SUBJECT_ALIASES as $subject => $aliases) {
            foreach ($aliases as $alias) {
                // Alias pendek (<= 3 huruf) dicocokkan sebagai KATA UTUH agar
                // "ak" tidak cocok di dalam "pak", "an" tidak di "kapan", dst.
                $found = mb_strlen($alias) <= 3
                    ? $this->matchesWord($q, $alias)
                    : $this->contains($q, $alias);

                if ($found) {
                    // Gunakan SEMUA alias dari grup yang cocok, bukan cuma yang
                    // muncul di query. Contoh: query "guru rpl" harus memuat
                    // guru ber-subject "PPLG".
                    $subjectNeedles = array_merge($subjectNeedles, $aliases);
                    break;
                }
            }
        }

        if (empty($subjectNeedles)) {
            return null;
        }

        // Filter guru yang subject/position-nya mengandung istilah tersebut.
        $filtered = $teachers->filter(function (Teacher $t) use ($subjectNeedles) {
            $haystack = $this->normalize(
                ($t->subject ?? '') . ' ' . ($t->position ?? '') . ' ' . ($t->category ?? '')
            );
            foreach ($subjectNeedles as $needle) {
                if ($this->contains($haystack, $needle)) {
                    return true;
                }
            }
            return false;
        });

        if ($filtered->isEmpty()) {
            return null;
        }

        $names = $filtered->take(15)->map(fn ($t) => '• ' . $t->name)->implode("\n");
        $total = $filtered->count();
        $extra = $total - 15;
        $more = $extra > 0 ? "\n\n... dan {$extra} lainnya." : '';

        return "Berikut guru/tenaga pendidik yang mengampu bidang terkait:\n\n"
            . "{$names}{$more}\n\n"
            . "Total: **{$total}** orang. Ketik nama guru untuk detail.";
    }

    // ---- Cari khusus: jurusan (termasuk akronim) ----

    protected function findSpecificMajor(string $q): ?string
    {
        $majors = Major::orderBy('id', 'asc')->get();
        if ($majors->isEmpty()) {
            return null;
        }

        // Nama lengkap.
        foreach ($majors as $major) {
            if ($this->contains($q, $this->normalize((string) $major->name))) {
                return $this->majorDetail($major);
            }
        }

        // Akronim.
        $acronyms = [
            'rpl' => 'Rekayasa Perangkat Lunak',
            'tkj' => 'Teknik Komputer dan Jaringan',
            'dkv' => 'Desain Komunikasi Visual',
            'lps' => 'Layanan Perbankan Syariah',
            'dpb' => 'Desain dan Produksi Busana',
            'ak'  => 'Akuntansi',
        ];
        foreach ($acronyms as $acro => $fullName) {
            if ($this->matchesWord($q, $acro)) {
                $major = $majors->firstWhere('name', $fullName);
                if ($major) {
                    return $this->majorDetail($major);
                }
            }
        }

        return null;
    }

    // ---- Cari khusus: ekskul / fasilitas / program (nama spesifik) ----

    protected function findSpecificNamed(string $q, array $topics): ?string
    {
        foreach ($topics as $topic) {
            $cfg = self::TABLE_CONFIG[$topic] ?? null;
            if (! $cfg) {
                continue;
            }

            $rows = $cfg['model']::orderBy($cfg['order'][0], $cfg['order'][1])->get();
            foreach ($rows as $row) {
                $name = $this->normalize((string) $row->{$cfg['fields'][0]});
                if ($name !== '' && $this->contains($q, $name)) {
                    return $this->formatRowAnswer($topic, $cfg['label'], [
                        'row' => $row, 'score' => 99, 'name' => $row->{$cfg['fields'][0]}, 'fields' => $cfg['fields'],
                    ]);
                }
            }
        }

        return null;
    }

    // ---- Tulis statis (Sejarah / VisiMisi / Tentang / Yayasan) ----

    protected function findWriting(string $q): ?string
    {
        $map = [
            'History'    => ['sejarah', 'berdiri', 'awal', 'perjalanan'],
            'VisiMisi'   => ['visi', 'misi', 'tujuan', 'cita'],
            'About'      => ['tentang', 'profil', 'about'],
            'Foundation' => ['yayasan', 'foundation', 'naungan'],
        ];

        $labels = [
            'History'    => 'Sejarah',
            'VisiMisi'   => 'Visi & Misi',
            'About'      => 'Profil',
            'Foundation' => 'Yayasan',
        ];

        foreach ($map as $title => $keywords) {
            if (! $this->matchAny($q, $keywords)) {
                continue;
            }

            $writing = Writing::where('title', $title)->orderBy('release_date', 'desc')->first();
            if ($writing && ! empty(trim((string) $writing->content))) {
                $content = $this->clean($writing->content);
                return "**" . ($labels[$title] ?? $title) . " SMK Amaliah 1 & 2 Ciawi**\n\n"
                    . $this->truncate($content, 800);
            }

            // Topik spesifik yang ditanyakan tapi datanya belum ada di website —
            // jawab jujur dan berhenti di sini (jangan biarkan pencarian umum
            // menjawab dengan topik yang tidak relevan).
            if (in_array($title, ['History', 'VisiMisi', 'Foundation'], true)) {
                $label = $labels[$title] ?? ucfirst(strtolower($title));
                return "Mohon maaf, informasi *{$label}* belum tersedia di website kami. 🙏\n"
                    . "Silakan tanyakan hal lain seputar jurusan, guru, ekstrakurikuler, fasilitas, atau SPMB/PPDB.";
            }
        }

        return null;
    }

    // =====================================================================
    //  JAWABAN KHUSUS (SPMB, BERITA, DETAIL)
    // =====================================================================

    /**
     * Profil singkat sekolah — dipakai untuk pertanyaan identitas sekolah
     * ("apa itu smk amaliah", "tentang sekolah", "gambaran sekolahnya") yang
     * tidak memuat kata kunci entitas spesifik.
     */
    protected function schoolProfileAnswer(): ?string
    {
        $s = self::SCHOOL;

        $writing = Writing::where('title', 'History')->orderBy('release_date', 'desc')->first();
        $history = $writing && ! empty(trim((string) $writing->content))
            ? $this->truncate($this->clean($writing->content), 600)
            : '';

        $reply = "**Tentang {$s['name']}**\n\n";
        if ($history !== '') {
            $reply .= $history . "\n\n";
        }
        $reply .= "📍 {$s['address']}\n"
            . "📞 {$s['phone']}\n"
            . "🕒 {$s['service_hours']}\n\n"
            . 'Tanyakan hal yang lebih spesifik, misalnya **jurusan**, **SPMB/PPDB**, '
            . '**fasilitas**, atau **ekstrakurikuler**, agar saya bisa bantu lebih detail 😊';

        return $reply;
    }

    protected function spmbAnswer(): string
    {
        $spmb = SpmbSetting::first();

        $rows = collect([
            'Status pendaftaran' => $spmb?->status ?? 'Tidak ada data',
            'Gelombang'          => $spmb?->wave_name ?: 'Informasi menyusul',
            'Periode'            => $spmb?->period_date ?: 'Belum diumumkan',
            'Kuota'              => $spmb?->quota_note ?: '-',
        ])->filter(fn ($v) => $v && $v !== '-')
          ->map(fn ($v, $k) => "• **{$k}**: {$v}")
          ->implode("\n");

        $link = $spmb?->registration_link ?: 'https://spmb.smkamaliah.sch.id/login';

        return "Penerimaan Murid Baru (SPMB) **SMK Amaliah 1 & 2 Ciawi**:\n\n"
            . "{$rows}\n\n"
            . "Kamu bisa mendaftar online di: {$link}";
    }

    protected function newsListAnswer(): string
    {
        $news = News::orderBy('date_published', 'desc')->take(10)->get();
        if ($news->isEmpty()) {
            return 'Saat ini belum ada berita terbaru.';
        }

        $list = $news->map(function ($item) {
            $date = $item->date_published
                ? \Carbon\Carbon::parse($item->date_published)->format('d M Y')
                : '-';
            return "• **{$item->title}** ({$date})";
        })->implode("\n");

        return "Berita terbaru di SMK Amaliah:\n\n{$list}\n\n"
            . "Ketik judul berita untuk info lebih detail.";
    }

    protected function achievementsAnswer(): ?string
    {
        $achievements = Achievement::orderBy('date', 'desc')->take(8)->get();
        if ($achievements->isEmpty()) {
            return null;
        }

        $list = $achievements->map(function ($item) {
            $title = $item->title ?? $item->name ?? '';
            $meta = trim(collect([
                $item->level,
                $item->winner,
                $item->category,
            ])->filter(fn ($v) => ! empty($v))->implode(' | '));

            return $meta !== ''
                ? "• **{$title}**\n  {$meta}"
                : "• **{$title}**";
        })->implode("\n\n");

        return "Berikut beberapa prestasi SMK Amaliah:\n\n{$list}";
    }

    protected function advantagesAnswer(): ?string
    {
        $majors = Major::orderBy('id', 'asc')->get();

        $points = $majors->map(function (Major $m) {
            $adv = trim((string) preg_replace('/\s+/u', ' ', strip_tags((string) $m->advantage)));
            if ($adv === '') {
                return null;
            }
            return "• **{$m->name}:** " . $this->truncate($adv, 160);
        })->filter()->values()->take(6);

        if ($points->isEmpty()) {
            return null;
        }

        return "Beberapa keunggulan di SMK Amaliah 1 & 2 Ciawi:\n\n"
            . $points->implode("\n") . "\n\n"
            . "Ketik nama jurusan untuk info yang lebih lengkap.";
    }

    /**
     * Cek apakah query meminta DAFTAR entitas (guru/ekskul/fasilitas/program),
     * mis. "daftar guru", "fasilitas apa saja", "sebutkan ekskul".
     * Dipakai untuk membedakan kata "daftar" yang bermakna "mendaftar/pendaftaran"
     * (SPMB) dari yang bermakna "menampilkan daftar" (list entitas).
     */
    protected function isEntityListRequest(string $q): bool
    {
        $listSignal = $this->matchAny($q, [
            'apa saja', 'apa aja', 'apa ya', 'daftar', 'sebutkan', 'macam', 'yang ada',
            'nama', 'list', 'ada apa', 'pilih', 'semua',
        ]);
        if (! $listSignal) {
            return false;
        }

        $entityTypes = [
            'guru', 'pendidik', 'pengajar', 'staf', 'staff', 'wali',
            'ekskul', 'eskul', 'ekstrakurikuler', 'extra',
            'fasilitas', 'sarana', 'prasarana',
            'program', 'kegiatan', 'acara', 'agenda', 'event',
            'jurusan', 'kompetensi', 'keahlian',
        ];

        foreach ($entityTypes as $type) {
            if ($this->contains($q, $type)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Jawab pertanyaan yang meminta DAFTAR entitas (guru, ekskul, fasilitas,
     * program). Deteksi frasa seperti "daftar X", "X apa saja", "sebutkan X".
     */
    protected function entityListAnswer(string $q): ?string
    {
        // Deteksi niat meminta daftar: frasa kunci ATAU kombinasi kata topik + isyarat daftar.
        $listSignal = fn () => $this->matchAny($q, [
            'apa saja', 'apa aja', 'apa ya', 'daftar', 'sebutkan', 'macam', 'yang ada',
            'nama', 'list', 'ada apa', 'pilih', 'semua',
        ]);

        $entry = function (array $types) use ($q, $listSignal) {
            if (! $listSignal() && ! $this->contains($q, 'apa')) {
                return false;
            }
            foreach ($types as $type) {
                if ($this->contains($q, $type)) {
                    return true;
                }
            }
            return false;
        };

        if ($entry(['guru', 'pendidik', 'pengajar', 'staf', 'staff'])) {
            if (Teacher::query()->count() > 0) {
                $names = Teacher::orderBy('id', 'asc')->pluck('name')->map(fn ($n) => '• ' . $n)->implode("\n");
                return "Berikut guru/tenaga pendidik di SMK Amaliah:\n\n{$names}\n\n"
                    . "Ketik nama guru untuk info lebih detail.";
            }
        }

        if ($entry(['ekskul', 'eskul', 'ekstrakurikuler', 'extra'])) {
            if (Extracurricular::query()->count() > 0) {
                return $this->shortExtracurricularAnswer();
            }
        }

        if ($entry(['fasilitas', 'sarana', 'prasarana'])) {
            if (Facility::query()->count() > 0) {
                $names = Facility::orderBy('id', 'asc')->pluck('name')->map(fn ($n) => '• ' . $n)->implode("\n");
                return "Fasilitas di SMK Amaliah:\n\n{$names}\n\n"
                    . "Tanyakan nama fasilitas untuk info lebih detail 😊";
            }
        }

        if ($entry(['program', 'kegiatan', 'acara', 'agenda', 'event'])) {
            if (SchoolProgram::query()->count() > 0) {
                $names = SchoolProgram::orderBy('id', 'asc')->pluck('name')->map(fn ($n) => '• ' . $n)->implode("\n");
                return "Program & kegiatan di SMK Amaliah:\n\n{$names}";
            }
        }

        return null;
    }

    protected function majorDetail(Major $major): string
    {
        $reply = "**{$major->name}**\n";
        $desc = $this->clean($major->description);
        if ($desc !== '') {
            $reply .= $desc . "\n";
        }
        if (! empty($major->tag)) {
            $reply .= "\n*Tag/keahlian:* {$major->tag}";
        }
        if (! empty($major->advantage)) {
            $reply .= "\n\n*Keunggulan:*\n"
                . collect(preg_split('/[\r\n]+/', $major->advantage))
                    ->filter(fn ($l) => trim($l) !== '')
                    ->map(fn ($l) => '• ' . trim($l))
                    ->implode("\n");
        }
        if (! empty($major->competency_head)) {
            $reply .= "\n\n*Ketua Kompetensi:* {$major->competency_head}";
        }

        return $reply;
    }

    protected function teacherDetail(Teacher $teacher): string
    {
        $parts = ["**{$teacher->name}**"];
        if (! empty($teacher->position)) {
            $parts[] = '*Jabatan:* ' . $teacher->position;
        }
        if (! empty($teacher->subject)) {
            $parts[] = '*Mata pelajaran:* ' . $teacher->subject;
        }
        if (! empty($teacher->category)) {
            $parts[] = '*Kategori:* ' . $teacher->category;
        }
        if (! empty($teacher->school)) {
            $parts[] = '*Sekolah:* ' . $teacher->school;
        }

        return implode("\n", $parts);
    }

    // =====================================================================
    //  JAWABAN UMUM
    // =====================================================================

    protected function greeting(): string
    {
        return "Halo! 👋 Selamat datang di **SMK Amaliah 1 & 2 Ciawi**. "
            . "Saya asisten virtual yang siap membantu. Saya bisa menjawab seputar:\n\n"
            . "- **Jurusan** (RPL, TKJ, DKV, dan lainnya)\n"
            . "- **SPMB/PPDB & pendaftaran**\n"
            . "- **Fasilitas & ekstrakurikuler**\n"
            . "- **Guru & tenaga pendidik**\n"
            . "- **Berita & prestasi sekolah**\n"
            . "- **Alamat & kontak sekolah**\n\n"
            . "Mau tanya apa hari ini? 😊";
    }

    protected function chatbotIdentity(): string
    {
        return "Saya adalah **AI Asisten SMK Amaliah** 🤖 — asisten virtual resmi "
            . "SMK Amaliah 1 & 2 Ciawi. Saya menjawab berdasarkan data **lokal** dari "
            . "website sekolah, jadi tidak perlu koneksi API AI eksternal.\n\n"
            . "Yang bisa saya bantu:\n"
            . "- Info **jurusan**, **SPMB/PPDB**, **fasilitas**, **ekstrakurikuler**\n"
            . "- Info **guru**, **berita**, **prestasi**, **program**, dan **kontak** sekolah\n\n"
            . "Cukup ketik pertanyaanmu, misalnya:\n"
            . "\"Ada jurusan apa saja?\" atau \"Siapa saja guru RPL?\" 😉";
    }

    protected function contactAnswer(): string
    {
        $s = self::SCHOOL;
        return "Informasi Kontak & Lokasi:\n\n"
            . "🏫 **{$s['name']}**\n"
            . "📍 {$s['address']}\n"
            . "🕒 {$s['service_hours']}\n"
            . "📞 {$s['phone']}\n"
            . "📧 {$s['email']}\n"
            . "💬 WhatsApp: https://wa.me/{$s['whatsapp']}\n"
            . "📸 Instagram: {$s['instagram']}\n"
            . "▶️ YouTube: {$s['youtube']}";
    }

    protected function clarification(): string
    {
        return "Maaf, saya belum menemukan informasi yang kamu tanyakan. 🙏\n\n"
            . "Coba tanyakan seputar:\n"
            . "- **Jurusan** (RPL, TKJ, DKV, dll)\n"
            . "- **SPMB/PPDB** & pendaftaran\n"
            . "- **Fasilitas** & **ekstrakurikuler**\n"
            . "- **Guru** & tenaga pendidik\n"
            . "- **Berita** & prestasi sekolah\n"
            . "- **Kontak** & alamat sekolah\n\n"
            . "Ketik pertanyaanmu dengan lebih jelas ya 😊";
    }

    // =====================================================================
    //  FALLBACK: KNOWLEDGE BASE LOKAL (semua data dari DB sendiri)
    // =====================================================================

    protected function askFromKnowledgeBase(string $q): ?string
    {
        // Chatbot ini khusus sekolah: pertanyaan di luar topik SMK (mis. "siapa
        // presiden indonesia", "harga iphone") tidak perlu digali dari knowledge
        // base — cukup ke klarifikasi agar tidak menjawab asal.
        if (! $this->hasSchoolTopic($q)) {
            return null;
        }

        $chunks = $this->knowledgeBase->retrieve($q, 5);

        // Bila pencarian vektor tidak menemukan hasil yang meyakinkan,
        // coba rawu kata kunci — tetapi hanya bila jawaban benar-benar relevan.
        if (empty($chunks)) {
            $candidates = $this->knowledgeBase->searchByKeywords($q, 5);
            // Jangan langsung percaya: validasi dengan skor relevansi global.
            if ($this->knowledgeBase->bestScore($q) >= 0.18) {
                $chunks = $candidates;
            }
        }

        if (empty($chunks)) {
            return null;
        }

        $seen = [];
        $unique = [];
        foreach ($chunks as $chunk) {
            $key = ($chunk->source_type ?? '') . '|' . ($chunk->title ?? '');
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $unique[] = $chunk;
        }

        $parts = [];
        foreach ($unique as $chunk) {
            $label = $chunk->title ?? ucfirst($chunk->source_type);
            $content = trim((string) $chunk->content);
            if ($content === '') {
                continue;
            }
            $parts[] = "**{$label}**\n" . $content;
        }

        if (empty($parts)) {
            return null;
        }

        return "Berdasarkan data dari website, berikut yang saya temukan:\n\n"
            . implode("\n\n", $parts);
    }

    // =====================================================================
    //  HELPER
    // =====================================================================

    /**
     * Normalisasi teks: huruf kecil, hapus aksen & karakter non alfanumerik.
     */
    protected function normalize(?string $text): string
    {
        $text = mb_strtolower(trim((string) $text));
        $text = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'],
            ['a', 'e', 'i', 'o', 'u', 'u', 'n'],
            $text
        );

        return preg_replace('/[^a-z0-9\s]/', '', $text) ?? '';
    }

    protected function contains(string $haystack, string $needle): bool
    {
        return $needle !== '' && str_contains($haystack, $needle);
    }

    /**
     * Cek apakah query mengandung salah satu keyword.
     * Keyword pendek (<= 3 huruf) dicocokkan sebagai KATA UTUH agar kata seperti
     * "hi" tidak match di dalam kata panjang ("hidup", "semangat"), dan "ak"
     * tidak match di dalam "pak"/"makasih".
     */
    protected function matchAny(string $q, array $keywords): bool
    {
        foreach ($keywords as $kw) {
            if (mb_strlen($kw) <= 3) {
                if ($this->matchesWord($q, $kw)) {
                    return true;
                }
            } else {
                if ($this->contains($q, $kw)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Cek apakah keyword muncul sebagai KATA utuh (bukan bagian kata lain).
     * Berguna untuk akronim pendek seperti "rpl".
     */
    protected function matchesWord(string $q, string $word): bool
    {
        return $word !== '' && preg_match('/\b' . preg_quote($word, '/') . '\b/', $q) === 1;
    }

    /**
     * Kata-kata bermakna dari query (>= 3 huruf, bukan stop word).
     */
    protected function significantWords(string $q): array
    {
        $words = preg_split('/\s+/', $q) ?: [];
        $result = [];
        foreach ($words as $word) {
            $word = trim($word);
            if (mb_strlen($word) >= 3 && ! in_array($word, self::STOP_WORDS, true)) {
                $result[] = $word;
            }
        }

        return $result;
    }

    /**
     * Bersihkan teks: hapus tag HTML & rapikan spasi.
     */
    protected function clean($value): string
    {
        if (is_null($value)) {
            return '';
        }
        return trim((string) preg_replace('/\s+/u', ' ', strip_tags((string) $value)));
    }

    /**
     * Format tanggal agar tampil ramah ("2026-08-31" -> "31 Agu 2026").
     */
    protected function formatDate(string $value): string
    {
        $value = trim($value);
        if ($value === '' || strtotime($value) === false) {
            return $value;
        }

        return \Illuminate\Support\Carbon::parse($value)->format('d M Y');
    }

    protected function truncate(string $text, int $length): string
    {
        $text = preg_replace('/\s+/', ' ', $text);
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, $length) . '…';
    }
}
