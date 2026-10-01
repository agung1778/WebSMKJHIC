<?php

namespace App\AI\Data;

use App\Models\Achievement;
use App\Models\Extracurricular;
use App\Models\Facility;
use App\Models\Major;
use App\Models\News;
use App\Models\Navigation;
use App\Models\Partner;
use App\Models\PkkProject;
use App\Models\SchoolLeader;
use App\Models\SchoolProgram;
use App\Models\SchoolSetting;
use App\Models\SpmbSetting;
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
    }
}