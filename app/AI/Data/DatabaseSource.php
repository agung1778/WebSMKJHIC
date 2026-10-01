<?php

namespace App\AI\Data;

<<<<<<< HEAD
=======
use App\AI\Knowledge\KnowledgeRecord;
>>>>>>> 6370583c48bc189c4dbb3cee9a4971f0925a062c
use App\Models\Achievement;
use App\Models\Extracurricular;
use App\Models\Facility;
use App\Models\Major;
use App\Models\News;
<<<<<<< HEAD
use App\Models\Navigation;
use App\Models\Partner;
use App\Models\PkkProject;
=======
use App\Models\Partner;
>>>>>>> 6370583c48bc189c4dbb3cee9a4971f0925a062c
use App\Models\SchoolLeader;
use App\Models\SchoolProgram;
use App\Models\SchoolSetting;
use App\Models\SpmbSetting;
<<<<<<< HEAD
use App\Models\Testimonial;
use App\Models\Teacher;
use App\Models\Writing;
use Illuminate\Support\Facades\Schema;

/**
 * Sumber pengetahuan #1: TABEL DATABASE WEBSITE.
 *
 * Sifatnya READ-ONLY. Kode di file ini tidak pernah memanggil insert/update/
 * delete — engine hanya boleh membaca. Daftar tabel dibatasi whitelist di
 * config('ai.sources.database.tables') supaya AI tidak bisa menyentuh data
 * di luar yang diizinkan (mis. users, sessions, traffic).
 */
class DatabaseSource extends BaseSource
{
    /** Tabel yang TIDAK boleh pernah dibaca engine. */
    public const FORBIDDEN = [
        'users', 'sessions', 'password_reset_tokens', 'cache', 'jobs',
        'job_batches', 'failed_jobs', 'personal_access_tokens', 'traffic_visitors',
        'traffic_clicks', 'ai_messages', 'ai_conversations', 'ai_search_logs',
    ];

    public function name(): string
    {
        return 'database';
    }

    public function enabled(): bool
    {
        return (bool) \App\AI\Support\AIConfig::sources('database', 'enabled', true);
    }

    /** @return array<int,string> */
    public function tables(): array
    {
        $configured = (array) \App\AI\Support\AIConfig::sources('database', 'tables', []);

        return array_values(array_filter(
            $configured,
            fn ($t) => ! in_array($t, self::FORBIDDEN, true) && Schema::hasTable($t)
        ));
    }

    // =====================================================================
    // SYNC — tarik semua record
    // =====================================================================

    /**
     * @return array<int,array>
     */
    public function sync(): array
    {
        $records = [];

        foreach ($this->builders() as $type => $builder) {
            try {
                foreach ($builder() as $record) {
                    $records[] = $record;
                }
            } catch (\Throwable $e) {
                // Satu tabel rusak tidak boleh menghentikan seluruh indexing.
                report($e);
            }
        }

        return $records;
    }

    /**
     * @return array<string,callable>
     */
    protected function builders(): array
    {
        return [
            'major'          => fn () => $this->majors(),
            'teacher'        => fn () => $this->teachers(),
            'facility'       => fn () => $this->facilities(),
            'news'           => fn () => $this->news(),
            'writing'        => fn () => $this->writings(),
            'extracurricular'=> fn () => $this->extracurriculars(),
            'partner'        => fn () => $this->partners(),
            'testimonial'    => fn () => $this->testimonials(),
            'achievement'    => fn () => $this->achievements(),
            'program'        => fn () => $this->programs(),
            'leader'         => fn () => $this->leaders(),
            'pkk'            => fn () => $this->pkkProjects(),
            'school_setting' => fn () => $this->schoolSettings(),
            'spmb'           => fn () => $this->spmbSettings(),
            'navigation'     => fn () => $this->navigations(),
        ];
    }

    /** @return array<int,array> */
    protected function majors(): array
    {
        return Major::orderBy('id')->get()->map(function ($m) {
            $lines = array_filter([
                $m->description ? $this->stripHtml($m->description) : null,
                $m->tag ? 'Tag: '.$m->tag : null,
                $m->advantage ? 'Keunggulan: '.$this->stripHtml($m->advantage) : null,
                $m->competency_head ? 'Kepala Kompetensi: '.$m->competency_head : null,
            ]);

            return $this->makeRecord(
                'major-'.$m->id,
                'major',
                $m->name.($m->abbreviation ? ' ('.$m->abbreviation.')' : ''),
                implode("\n", $lines) ?: 'Program keahlian '.$m->name.' di SMK Amaliah 1 & 2 Ciawi.',
                'Jurusan',
                $this->url('/majors/'.$m->id),
                [
                    'name'          => $m->name,
                    'abbreviation'  => $m->abbreviation,
                    'advantage'     => $m->advantage,
                    'competency_head' => $m->competency_head,
                ]
            );
        })->all();
    }

    /** @return array<int,array> */
    protected function teachers(): array
    {
        return Teacher::orderBy('id')->get()->map(function ($t) {
            $meta = array_filter([
                $t->position ? 'Jabatan: '.$t->position : null,
                $this->cleanMeta($t->subject) ? 'Mapel: '.$this->cleanMeta($t->subject) : null,
                $this->cleanMeta($t->school) ? 'Unit: '.$this->cleanMeta($t->school) : null,
                $this->cleanMeta($t->category) ? 'Kategori: '.$this->cleanMeta($t->category) : null,
            ], fn ($v) => $v !== null && $v !== '');

            return $this->makeRecord(
                'teacher-'.$t->id,
                'teacher',
                $t->name,
                $t->name.' — '.implode(', ', $meta).'.',
                'Guru',
                $this->url('/teachers/'.$t->id),
                [
                    'name'     => $t->name,
                    'position' => $t->position,
                    'subject'  => $t->subject,
                    'school'   => $t->school,
                    'category' => $t->category,
                ]
            );
        })->all();
    }

    /** @return array<int,array> */
    protected function facilities(): array
    {
        return Facility::orderBy('id')->get()->map(function ($f) {
            $desc = $f->description ? $this->stripHtml($f->description) : '';

            return $this->makeRecord(
                'facility-'.$f->id,
                'facility',
                $f->name,
                $f->name.($f->type ? ' ('.$f->type.')' : '').($desc ? ' — '.$desc : ''),
                $f->type ?: 'Fasilitas',
                $this->url('/facilities/'.$f->id),
                ['name' => $f->name, 'type' => $f->type, 'description' => $desc]
            );
        })->all();
    }

    /** @return array<int,array> */
    protected function news(): array
    {
        return News::orderBy('id')->get()->map(function ($n) {
            $desc = $n->description ? $this->stripHtml($n->description) : '';
            $date = $n->date_published ? $n->date_published->format('d M Y') : null;

            return $this->makeRecord(
                'news-'.$n->id,
                'news',
                $n->title,
                trim(($date ? 'Terbit: '.$date.'. ' : '').$desc),
                'Berita',
                $this->url('/news/'.$n->id),
                ['title' => $n->title, 'date' => $date, 'description' => $desc]
            );
        })->all();
    }

    /** @return array<int,array> */
    protected function writings(): array
    {
        return Writing::orderBy('id')->get()->map(function ($w) {
            $content = $this->stripHtml($w->content);

            return $this->makeRecord(
                'writing-'.$w->id,
                'writing',
                $w->title,
                $content,
                'Tulisan',
                null,
                ['title' => $w->title, 'content' => $content]
            );
        })->all();
    }

    /** @return array<int,array> */
    protected function extracurriculars(): array
    {
        return Extracurricular::orderBy('id')->get()->map(function ($e) {
            $desc = $e->description ? $this->stripHtml($e->description) : '';
            $meta = array_filter([
                $e->type ? 'Jenis: '.$e->type : null,
                $e->coach ? 'Pembina: '.$e->coach : null,
                $e->contact ? 'Kontak: '.$e->contact : null,
            ]);

            return $this->makeRecord(
                'extracurricular-'.$e->id,
                'extracurricular',
                $e->name,
                $e->name.(count($meta) ? ' — '.implode(', ', $meta).'.' : '').($desc ? ' '.$desc : ''),
                $e->type ?: 'Ekstrakurikuler',
                $this->url('/extracurriculars/'.$e->id),
                ['name' => $e->name, 'type' => $e->type, 'coach' => $e->coach]
            );
        })->all();
    }

    /** @return array<int,array> */
    protected function partners(): array
    {
        return Partner::orderBy('id')->get()->map(function ($p) {
            $desc = $p->description ? $this->stripHtml($p->description) : '';
            $meta = array_filter([
                $p->sector ? 'Sektor: '.$p->sector : null,
                $p->city ? 'Kota: '.$p->city : null,
                $p->partnership_date ? 'Kerja sama sejak: '.$p->partnership_date : null,
                $p->company_contact ? 'Kontak: '.$p->company_contact : null,
            ]);

            return $this->makeRecord(
                'partner-'.$p->id,
                'partner',
                $p->name,
                $p->name.(count($meta) ? ' — '.implode(', ', $meta).'.' : '').($desc ? ' '.$desc : ''),
                $p->sector ?: 'Mitra Industri',
                $this->url('/partners/'.$p->id),
                ['name' => $p->name, 'sector' => $p->sector, 'city' => $p->city]
            );
        })->all();
    }

    /** @return array<int,array> */
    protected function testimonials(): array
    {
        return Testimonial::orderBy('id')->get()->map(function ($t) {
            $major = $t->major_id ? Major::find($t->major_id) : null;
            $desc = $t->description ? $this->stripHtml($t->description) : '';

            $head = 'Testimoni alumni'
                . ($t->alumni_year ? ' tahunKelulusan '.$t->alumni_year : '')
                . ($major ? ' jurusan '.$major->name : '');

            return $this->makeRecord(
                'testimonial-'.$t->id,
                'testimonial',
                'Testimoni '.$t->name,
                $head.($desc ? ': '.$desc : ''),
                $major ? $major->name : 'Testimoni',
                $this->url('/testimonials/'.$t->id),
                ['name' => $t->name, 'alumni_year' => $t->alumni_year, 'major' => $major?->name]
            );
        })->all();
    }

    /** @return array<int,array> */
    protected function achievements(): array
    {
        return Achievement::orderBy('id')->get()->map(function ($a) {
            $desc = $a->description ? $this->stripHtml($a->description) : '';
            $meta = array_filter([
                $a->category ? 'Kategori: '.$a->category : null,
                $a->level ? 'Tingkat: '.$a->level : null,
                $a->winner ? 'Pemenang: '.$a->winner : null,
                $a->date ? 'Tanggal: '.$a->date : null,
            ]);

            return $this->makeRecord(
                'achievement-'.$a->id,
                'achievement',
                $a->title,
                $a->title.(count($meta) ? ' — '.implode(', ', $meta).'.' : '').($desc ? ' '.$desc : ''),
                $a->category ?: 'Prestasi',
                $this->url('/achievements/'.$a->id),
                ['title' => $a->title, 'category' => $a->category, 'level' => $a->level, 'winner' => $a->winner]
            );
        })->all();
    }

    /** @return array<int,array> */
    protected function programs(): array
    {
        return SchoolProgram::where('status', SchoolProgram::STATUS_PUBLISHED)
            ->orderBy('id')->get()->map(function ($p) {
                $desc = $p->description ? $this->stripHtml($p->description) : '';

                return $this->makeRecord(
                    'program-'.$p->id,
                    'program',
                    $p->name,
                    $p->name.($desc ? ' — '.$desc : ''),
                    'Program Sekolah',
                    $this->url('/programs/'.$p->id),
                    ['name' => $p->name]
                );
            })->all();
    }

    /** @return array<int,array> */
    protected function leaders(): array
    {
        return SchoolLeader::where('is_active', true)->orderBy('order_column')->get()->map(function ($l) {
            $meta = array_filter([
                $l->position ? 'Jabatan: '.$l->position : null,
                $l->school ? 'Unit: '.$l->school : null,
                $l->quote ? 'Kutipan: '.$this->stripHtml($l->quote) : null,
            ]);

            return $this->makeRecord(
                'leader-'.$l->id,
                'leader',
                $l->name,
                $l->name.(count($meta) ? ' — '.implode(', ', $meta).'.' : ''),
                'Pimpinan Sekolah',
                null,
                ['name' => $l->name, 'position' => $l->position, 'school' => $l->school]
            );
        })->all();
    }

    /** @return array<int,array> */
    protected function pkkProjects(): array
    {
        return PkkProject::orderBy('id')->get()->map(function ($p) {
            $major = $p->major_id ? Major::find($p->major_id) : null;
            $desc = $p->description ? $this->stripHtml($p->description) : '';
            $meta = array_filter([
                $major ? 'Jurusan: '.$major->name : null,
                $p->brand_name ? 'Brand: '.$p->brand_name : null,
                $p->category ? 'Kategori: '.$p->category : null,
                $p->student_class ? 'Kelas: '.$p->student_class : null,
                $p->price ? 'Harga: '.$p->price : null,
            ]);

            return $this->makeRecord(
                'pkk-'.$p->id,
                'pkk',
                $p->title,
                $p->title.(count($meta) ? ' — '.implode(', ', $meta).'.' : '').($desc ? ' '.$desc : ''),
                $p->category ?: 'Projek P5/PKK',
                $this->url('/pkk'),
                ['title' => $p->title, 'brand' => $p->brand_name, 'major' => $major?->name]
            );
        })->all();
    }

    /**
     * Ubah nilai kosong/tanda hubung ("-", "--", "n/a") menjadi null supaya
     * tidak masuk ke knowledge record sebagai data palsu.
     */
    protected function cleanMeta(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '' || in_array(mb_strtolower($value), ['-', '--', 'n/a', 'na', 'null'], true)) {
            return null;
        }

        return $value;
    }

    /** @return array<int,array> */
    protected function schoolSettings(): array
    {
        if (! Schema::hasTable('school_settings')) {
            return [];
        }

        $row = SchoolSetting::first();

        if (! $row) {
            return [];
        }

        $lines = array_filter([
            $row->jumlah_siswa ? 'Jumlah peserta didik: '.$row->jumlah_siswa : null,
            $row->tahun_ajaran ? 'Tahun ajaran: '.$row->tahun_ajaran : null,
        ]);

        return [$this->makeRecord(
            'school_setting-1',
            'school_setting',
            'Data Peserta Didik',
            implode("\n", $lines) ?: 'Data peserta didik belum diisi.',
            'Data Sekolah',
            null,
            ['jumlah_siswa' => $row->jumlah_siswa, 'tahun_ajaran' => $row->tahun_ajaran]
        )];
    }

    /** @return array<int,array> */
    protected function spmbSettings(): array
    {
        if (! Schema::hasTable('spmb_settings')) {
            return [];
        }

        $row = SpmbSetting::first();

        if (! $row) {
            return [];
        }

        $lines = array_filter([
            $row->status ? 'Status pendaftaran: '.$row->status : null,
            $row->wave_name ? 'Gelombang: '.$row->wave_name : null,
            $row->period_date ? 'Periode: '.$row->period_date : null,
            $row->quota_note ? $this->stripHtml($row->quota_note) : null,
            $row->registration_link ? 'Link pendaftaran: '.$row->registration_link : null,
        ]);

        return [$this->makeRecord(
            'spmb-1',
            'spmb',
            'Penerimaan Murid Baru (SPMB)',
            implode("\n", $lines) ?: 'Informasi SPMB belum diisi.',
            'SPMB',
            $this->url('/ppdb'),
            [
                'status'  => $row->status,
                'wave'    => $row->wave_name,
                'period'  => $row->period_date,
                'link'    => $row->registration_link,
            ]
        )];
    }

    /** @return array<int,array> */
    protected function navigations(): array
    {
        if (! Schema::hasTable('navigations')) {
            return [];
        }

        return Navigation::where('is_active', true)->orderBy('order')->get()->map(function ($n) {
            return $this->makeRecord(
                'navigation-'.$n->id,
                'navigation',
                'Menu: '.$n->title,
                'Menu navigasi website: '.$n->title.' -> '.$n->url,
                'Navigasi',
                $n->url,
                ['title' => $n->title, 'url' => $n->url]
            );
        })->all();
    }

    // =====================================================================
    // SEARCH & GET
    // =====================================================================

    /**
     * Pencarian ringan di sisi sumber (prefilter). Skoring akhir tetap
     * dikerjakan RelevanceEngine di atas index — method ini hanya
     * membuang kandidat yang hopeless supaya tidak diproses semua.
     *
     * @param  array<int,string>  $tokens
     * @return array<int,array>
     */
    public function search(string $query, array $tokens): array
    {
        if ($tokens === []) {
            return [];
        }

        $needle = mb_strtolower($query);

        $scored = [];
        foreach ($this->getAll() as $record) {
            $haystack = mb_strtolower($record['title'].' '.$record['content']);
            $hit = 0;

            foreach ($tokens as $token) {
                if (mb_strlen($token) < 2) {
                    continue;
                }
                if (str_contains($haystack, $token)) {
                    $hit++;
                }
            }

            $phraseHit = $needle !== '' && str_contains($haystack, $needle) ? 2 : 0;

            if ($hit > 0 || $phraseHit > 0) {
                $scored[] = ['record' => $record, 'hits' => $hit + $phraseHit];
            }
        }

        usort($scored, fn ($a, $b) => $b['hits'] <=> $a['hits']);

        return array_column(array_slice($scored, 0, 200), 'record');
    }

    public function get(string $uid): ?array
    {
        foreach ($this->getAll() as $record) {
            if ($record['uid'] === $uid) {
                return $record;
            }
        }

        return null;
    }

    /**
     * Semua record tabel (di-cache per request).
     *
     * @return array<int,array>
     */
    public function getAll(): array
    {
        if (! isset($this->cache['all'])) {
            $this->cache['all'] = $this->sync();
        }

        return $this->cache['all'];
    }

    /**
     * @return array<int,array>
     */
    public function byType(string $type): array
    {
        return array_values(array_filter(
            $this->getAll(),
            fn ($r) => ($r['type'] ?? null) === $type
        ));
=======
use App\Models\Teacher;
use App\Models\Testimonial;
use App\Models\Writing;
use Throwable;

/**
 * Sumber knowledge dari database website.
 *
 * Setiap baris tabel diubah menjadi satu atau beberapa KnowledgeRecord atomik
 * ("Jurusan X ada", "Guru Y mengajar Z") agar relevance scoring dan
 * context building bisa bekerja pada tingkat fakta, bukan dokumen besar.
 *
 * Query bersifat read-only.
 */
class DatabaseSource implements KnowledgeSource
{
    protected array $records = [];
    protected array $errors = [];

    public function fetch(): array
    {
        $this->records = [];
        $this->errors = [];

        $this->majors()
            ->spmb()
            ->facilities()
            ->extracurriculars()
            ->news()
            ->teachers()
            ->partners()
            ->testimonials()
            ->programs()
            ->achievements()
            ->writings()
            ->leaders()
            ->schoolStats();

        return $this->records;
    }

    /** @return array<int, string> */
    public function errors(): array
    {
        return $this->errors;
    }

    protected function push(array $records): self
    {
        foreach ($records as $record) {
            if ($record instanceof KnowledgeRecord) {
                $this->records[] = $record;
            }
        }

        return $this;
    }

    /** Jalankan query dengan proteksi error agar satu tabel rusak tidak mematikan index. */
    protected function guard(callable $callback, string $label): self
    {
        try {
            $this->push($callback());
        } catch (Throwable $e) {
            $this->errors[] = $label . ': ' . $e->getMessage();
        }

        return $this;
    }

    // ------------------------------------------------------------------
    // Jurusan
    // ------------------------------------------------------------------

    protected function majors(): self
    {
        return $this->guard(function () {
            $out = [];

            foreach (Major::orderBy('id')->get() as $major) {
                $url = url('/majors/' . $major->id);

                $out[] = KnowledgeRecord::make(
                    'major_exists',
                    'major',
                    'Jurusan ' . $major->name,
                    'SMK Amaliah memiliki jurusan ' . $major->name
                        . ($major->abbreviation ? ' (' . $major->abbreviation . ')' : '') . '.'
                )
                    ->withSourceId((string) $major->id)
                    ->withSourceUrl($url)
                    ->withKeywords(array_filter([
                        $major->name,
                        $major->abbreviation,
                        'jurusan',
                        'prodi',
                        'program studi',
                    ]))
                    ->withMetadata(['field' => 'existence']);

                $out[] = KnowledgeRecord::make(
                    'major_description',
                    'major',
                    'Deskripsi Jurusan ' . $major->name,
                    $major->description
                )
                    ->withSourceId((string) $major->id)
                    ->withSourceUrl($url)
                    ->withKeywords(array_filter([
                        $major->name,
                        $major->abbreviation,
                        'deskripsi',
                        'tentang',
                        'jurusan',
                    ]))
                    ->withMetadata(['field' => 'description']);

                if (! empty($major->tag)) {
                    $out[] = KnowledgeRecord::make(
                        'major_tag',
                        'major',
                        'Keahlian Jurusan ' . $major->name,
                        $major->tag
                    )
                        ->withSourceId((string) $major->id)
                        ->withSourceUrl($url)
                        ->withKeywords(array_filter([
                            $major->name,
                            'keahlian',
                            'kompetensi',
                            'tag',
                        ]))
                        ->withMetadata(['field' => 'tag']);
                }

                if (! empty($major->advantage)) {
                    $out[] = KnowledgeRecord::make(
                        'major_advantage',
                        'major',
                        'Keunggulan Jurusan ' . $major->name,
                        $major->advantage
                    )
                        ->withSourceId((string) $major->id)
                        ->withSourceUrl($url)
                        ->withKeywords(array_filter([
                            $major->name,
                            'keunggulan',
                            'advantages',
                            'kelebihan',
                        ]))
                        ->withMetadata(['field' => 'advantage']);
                }
            }

            return $out;
        }, 'majors');
    }

    // ------------------------------------------------------------------
    // Pendaftaran (SPMB)
    // ------------------------------------------------------------------

    protected function spmb(): self
    {
        return $this->guard(function () {
            $spmb = SpmbSetting::first();

            if (! $spmb) {
                return [];
            }

            $url = url('/');
            $status = $spmb->status === 'Buka' ? 'sedang dibuka' : 'sedang ditutup';

            $out = [];
            $out[] = KnowledgeRecord::make('spmb_status', 'spmb', 'Status Pendaftaran Murid Baru',
                'Penerimaan murid baru (SPMB/PPDB) SMK Amaliah saat ini ' . $status . '.')
                ->withSourceId((string) $spmb->id)
                ->withSourceUrl($url)
                ->withKeywords(['spmb', 'ppdb', 'pendaftaran', 'murid baru', 'daftar', 'status', 'buka', 'tutup'])
                ->withMetadata(['field' => 'status', 'raw_status' => $spmb->status]);

            // Hanya sertakan detail yang benar-benar terisi.
            $detail = array_filter([
                $spmb->wave_name !== null && trim((string) $spmb->wave_name) !== ''
                    ? 'Gelombang: ' . $spmb->wave_name : null,
                $spmb->period_date !== null && trim((string) $spmb->period_date) !== ''
                    ? 'Periode: ' . $spmb->period_date : null,
                $spmb->quota_note !== null && trim((string) $spmb->quota_note) !== ''
                    ? 'Kuota: ' . $spmb->quota_note : null,
                $spmb->registration_link !== null && trim((string) $spmb->registration_link) !== ''
                    ? 'Pendaftaran online: ' . $spmb->registration_link : null,
            ]);

            if ($detail !== []) {
                $out[] = KnowledgeRecord::make('spmb_detail', 'spmb', 'Detail Pendaftaran Murid Baru',
                    implode("\n", $detail))
                    ->withSourceId((string) $spmb->id)
                    ->withSourceUrl($url)
                    ->withKeywords(['spmb', 'ppdb', 'gelombang', 'periode', 'kuota', 'pendaftaran', 'daftar'])
                    ->withMetadata(['field' => 'detail']);
            }

            return $out;
        }, 'spmb_settings');
    }

    // ------------------------------------------------------------------
    // Fasilitas
    // ------------------------------------------------------------------

    protected function facilities(): self
    {
        return $this->guard(function () {
            $out = [];

            foreach (Facility::orderBy('id')->get() as $facility) {
                $out[] = KnowledgeRecord::make(
                    'facility',
                    'facility',
                    'Fasilitas ' . $facility->name,
                    trim('Fasilitas ' . $facility->name . ' (kategori: ' . $facility->type . '). '
                        . (string) $facility->description)
                )
                    ->withSourceId((string) $facility->id)
                    ->withSourceUrl(url('/facilities/' . $facility->id))
                    ->withKeywords(array_filter([
                        $facility->name,
                        'fasilitas',
                        $facility->type,
                    ]))
                    ->withMetadata(['field' => 'facility', 'type' => $facility->type]);
            }

            return $out;
        }, 'facilities');
    }

    // ------------------------------------------------------------------
    // Ekstrakurikuler
    // ------------------------------------------------------------------

    protected function extracurriculars(): self
    {
        return $this->guard(function () {
            $out = [];

            foreach (Extracurricular::orderBy('id')->get() as $extra) {
                $lines = [
                    'Ekstrakurikuler ' . $extra->name . ' adalah kegiatan ' . strtolower((string) $extra->type) . '.',
                    'Pembina: ' . $extra->coach,
                ];

                if (! empty($extra->description)) {
                    $lines[] = (string) $extra->description;
                }

                $out[] = KnowledgeRecord::make(
                    'extracurricular',
                    'extracurricular',
                    'Ekstrakurikuler ' . $extra->name,
                    implode("\n", array_filter($lines))
                )
                    ->withSourceId((string) $extra->id)
                    ->withSourceUrl(url('/extracurriculars/' . $extra->id))
                    ->withKeywords(array_filter([
                        $extra->name,
                        'ekstrakurikuler',
                        'ekskul',
                        'kegiatan',
                        strtolower((string) $extra->type),
                    ]))
                    ->withMetadata(['field' => 'extracurricular', 'type' => $extra->type]);
            }

            return $out;
        }, 'extracurriculars');
    }

    // ------------------------------------------------------------------
    // Berita
    // ------------------------------------------------------------------

    protected function news(): self
    {
        return $this->guard(function () {
            $out = [];

            foreach (News::orderByDesc('date_published')->limit(100)->get() as $news) {
                $out[] = KnowledgeRecord::make(
                    'news',
                    'news',
                    $news->title,
                    trim($news->title . "\n" . (string) $news->description)
                )
                    ->withSourceId((string) $news->id)
                    ->withSourceUrl(url('/news/' . $news->id))
                    ->withKeywords(['berita', 'news', 'informasi', 'kegiatan'])
                    ->withMetadata([
                        'field' => 'news',
                        'date_published' => optional($news->date_published)->toDateString(),
                    ]);
            }

            return $out;
        }, 'news');
    }

    // ------------------------------------------------------------------
    // Guru
    // ------------------------------------------------------------------

    protected function teachers(): self
    {
        return $this->guard(function () {
            $out = [];

            foreach (Teacher::orderBy('id')->get() as $teacher) {
                $lines = [
                    $teacher->name,
                    'Jabatan: ' . $teacher->position,
                ];

                if (! empty($teacher->subject)) {
                    $lines[] = 'Mata pelajaran: ' . $teacher->subject;
                }

                if (! empty($teacher->school)) {
                    $lines[] = 'Unit: ' . $teacher->school;
                }

                $out[] = KnowledgeRecord::make(
                    'teacher',
                    'teacher',
                    'Guru ' . $teacher->name,
                    implode("\n", $lines)
                )
                    ->withSourceId((string) $teacher->id)
                    ->withSourceUrl(url('/teachers/' . $teacher->id))
                    ->withKeywords(array_filter([
                        $teacher->name,
                        $teacher->subject,
                        'guru',
                        'pengajar',
                        strtolower((string) $teacher->position),
                    ]))
                    ->withMetadata([
                        'field' => 'teacher',
                        'subject' => $teacher->subject,
                        'school' => $teacher->school,
                        'position' => $teacher->position,
                    ]);
            }

            return $out;
        }, 'teachers');
    }

    // ------------------------------------------------------------------
    // Mitra industri
    // ------------------------------------------------------------------

    protected function partners(): self
    {
        return $this->guard(function () {
            $out = [];

            foreach (Partner::orderBy('id')->limit(100)->get() as $partner) {
                $lines = [
                    $partner->name . ' adalah mitra industri sekolah.',
                ];

                if (! empty($partner->sector)) {
                    $lines[] = 'Bidang: ' . $partner->sector;
                }

                if (! empty($partner->city)) {
                    $lines[] = 'Kota: ' . $partner->city;
                }

                if (! empty($partner->description)) {
                    $lines[] = (string) $partner->description;
                }

                $out[] = KnowledgeRecord::make(
                    'partner',
                    'partner',
                    'Mitra Industri ' . $partner->name,
                    implode("\n", $lines)
                )
                    ->withSourceId((string) $partner->id)
                    ->withSourceUrl(url('/partners/' . $partner->id))
                    ->withKeywords(array_filter([
                        $partner->name,
                        'mitra',
                        'industri',
                        'perusahaan',
                        'kerjasama',
                        'partner',
                        $partner->sector,
                    ]))
                    ->withMetadata(['field' => 'partner', 'sector' => $partner->sector]);
            }

            return $out;
        }, 'partners');
    }

    // ------------------------------------------------------------------
    // Testimoni
    // ------------------------------------------------------------------

    protected function testimonials(): self
    {
        return $this->guard(function () {
            $out = [];

            foreach (Testimonial::with('major')->orderByDesc('id')->limit(50)->get() as $item) {
                $lines = ['Testimoni alumni ' . $item->name];

                if ($item->major) {
                    $lines[0] .= ' (Jurusan ' . $item->major->name . ')';
                }

                if (! empty($item->alumni_year)) {
                    $lines[] = 'Angkatan: ' . $item->alumni_year;
                }

                if (! empty($item->description)) {
                    $lines[] = (string) $item->description;
                }

                $out[] = KnowledgeRecord::make(
                    'testimonial',
                    'testimonial',
                    'Testimoni ' . $item->name,
                    implode("\n", $lines)
                )
                    ->withSourceId((string) $item->id)
                    ->withSourceUrl(url('/testimonials/' . $item->id))
                    ->withKeywords(array_filter([
                        $item->name,
                        'testimoni',
                        'alumni',
                        'opini',
                        $item->major->name ?? null,
                    ]))
                    ->withMetadata(['field' => 'testimonial']);
            }

            return $out;
        }, 'testimonials');
    }

    // ------------------------------------------------------------------
    // Program sekolah
    // ------------------------------------------------------------------

    protected function programs(): self
    {
        return $this->guard(function () {
            $out = [];

            foreach (SchoolProgram::orderBy('id')->get() as $program) {
                if ($program->status !== 'published') {
                    continue;
                }

                $out[] = KnowledgeRecord::make(
                    'program',
                    'program',
                    'Program ' . $program->name,
                    trim('Program sekolah ' . $program->name . "\n" . (string) $program->description)
                )
                    ->withSourceId((string) $program->id)
                    ->withSourceUrl(url('/programs/' . $program->id))
                    ->withKeywords(array_filter([
                        $program->name,
                        'program',
                        'kegiatan',
                        'sekolah',
                    ]))
                    ->withMetadata(['field' => 'program']);
            }

            return $out;
        }, 'school_programs');
    }

    // ------------------------------------------------------------------
    // Prestasi
    // ------------------------------------------------------------------

    protected function achievements(): self
    {
        return $this->guard(function () {
            $out = [];

            foreach (Achievement::orderByDesc('date')->limit(100)->get() as $item) {
                $lines = [
                    'Prestasi: ' . $item->title,
                    'Tingkat: ' . $item->level,
                    'Kategori: ' . $item->category,
                ];

                if (! empty($item->winner)) {
                    $lines[] = 'Pemenang: ' . $item->winner;
                }

                if (! empty($item->description)) {
                    $lines[] = (string) $item->description;
                }

                $out[] = KnowledgeRecord::make(
                    'achievement',
                    'achievement',
                    'Prestasi ' . $item->title,
                    implode("\n", $lines)
                )
                    ->withSourceId((string) $item->id)
                    ->withSourceUrl(url('/achievements/' . $item->id))
                    ->withKeywords(array_filter([
                        $item->title,
                        'prestasi',
                        'juara',
                        'lomba',
                        'pemenang',
                        $item->level,
                    ]))
                    ->withMetadata(['field' => 'achievement', 'level' => $item->level]);
            }

            return $out;
        }, 'achievements');
    }

    // ------------------------------------------------------------------
    // Halaman profil (visi, misi, sejarah, yayasan)
    // ------------------------------------------------------------------

    protected function writings(): self
    {
        return $this->guard(function () {
            $routes = [
                'About' => ['about', 'Tentang Sekolah'],
                'VisiMisi' => ['about/vision', 'Visi dan Misi'],
                'History' => ['about/history', 'Sejarah Sekolah'],
                'Foundation' => ['about/foundation', 'Yayasan Sekolah'],
            ];

            $out = [];

            foreach ($routes as $title => [$path, $label]) {
                $writing = Writing::where('title', $title)->orderByDesc('release_date')->first();

                if (! $writing || trim((string) $writing->content) === '') {
                    continue;
                }

                $out[] = KnowledgeRecord::make(
                    'about',
                    'about',
                    $label,
                    (string) $writing->content
                )
                    ->withSourceId((string) $writing->id)
                    ->withSourceUrl(url('/' . $path))
                    ->withKeywords(array_merge(
                        [$label, 'profil', 'sekolah', 'about'],
                        preg_split('/\s+/u', mb_strtolower($label)) ?: []
                    ))
                    ->withMetadata(['field' => 'about', 'page' => $label]);
            }

            return $out;
        }, 'writings');
    }

    // ------------------------------------------------------------------
    //Leaders
    // ------------------------------------------------------------------

    protected function leaders(): self
    {
        return $this->guard(function () {
            $out = [];

            foreach (SchoolLeader::where('is_active', 1)->orderBy('order_column')->get() as $leader) {
                $lines = [
                    $leader->name,
                    'Jabatan: ' . $leader->position,
                    'Unit: ' . $leader->school,
                ];

                if (! empty($leader->quote)) {
                    $lines[] = (string) $leader->quote;
                }

                $out[] = KnowledgeRecord::make(
                    'leader',
                    'leader',
                    $leader->position . ' - ' . $leader->name,
                    implode("\n", $lines)
                )
                    ->withSourceId((string) $leader->id)
                    ->withSourceUrl(url('/about'))
                    ->withKeywords(array_filter([
                        $leader->name,
                        $leader->position,
                        'pimpinan',
                        'kepala',
                        'direktur',
                        'ketua',
                    ]))
                    ->withMetadata(['field' => 'leader', 'position' => $leader->position]);
            }

            return $out;
        }, 'school_leaders');
    }

    // ------------------------------------------------------------------
    // Statistik sekolah
    // ------------------------------------------------------------------

    protected function schoolStats(): self
    {
        return $this->guard(function () {
            $setting = SchoolSetting::first();

            if (! $setting) {
                return [];
            }

            $lines = [
                'Jumlah siswa: ' . $setting->jumlah_siswa,
            ];

            if (! empty($setting->tahun_ajaran)) {
                $lines[] = 'Tahun ajaran: ' . $setting->tahun_ajaran;
            }

            return [
                KnowledgeRecord::make('school_stat', 'school_stat', 'Statistik Sekolah', implode("\n", $lines))
                    ->withSourceId((string) $setting->id)
                    ->withSourceUrl(url('/'))
                    ->withKeywords(['jumlah', 'siswa', 'statistik', 'tahun', 'ajaran', 'terbanyak', 'banyak'])
                    ->withMetadata([
                        'field' => 'stat',
                        'jumlah_siswa' => $setting->jumlah_siswa,
                        'tahun_ajaran' => $setting->tahun_ajaran,
                    ]),
            ];
        }, 'school_settings');
>>>>>>> 6370583c48bc189c4dbb3cee9a4971f0925a062c
    }
}