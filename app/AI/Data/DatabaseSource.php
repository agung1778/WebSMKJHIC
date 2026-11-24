<?php

namespace App\AI\Data;

use App\AI\Knowledge\KnowledgeRecord;
use App\Models\Achievement;
use App\Models\Extracurricular;
use App\Models\Facility;
use App\Models\Major;
use App\Models\News;
use App\Models\Partner;
use App\Models\SchoolLeader;
use App\Models\SchoolProgram;
use App\Models\SchoolSetting;
use App\Models\SpmbSetting;
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
    }
}