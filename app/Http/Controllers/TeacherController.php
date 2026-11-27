<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function uploadPhoto(Request $request, Teacher $teacher)
    {
        // Validasi file yang di-upload
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Hapus foto lama jika ada
        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }

        // Simpan foto baru
        $path = $request->file('photo')->store('teacher_photos', 'public');

        // Update path foto di database
        $teacher->photo = $path;
        $teacher->save();

        // Kirim kembali URL foto baru agar bisa ditampilkan langsung di frontend
        return response()->json([
            'success' => true,
            'photo_url' => asset('storage/' . $path)
        ]);
    }

    public function index()
    {
        $teachers = Teacher::ordered()->get();
        return view('admin.tables.teacher.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tables.teacher.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
            'position' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'additional_duties' => 'nullable|string|max:500',
            'order_column' => 'nullable|integer|min:0',
            'school' => 'required|in:Amaliah 1,Amaliah 2,Amaliah 1 & 2,Staff',
            'category' => 'nullable|string|max:100',
        ], [
            'name.required' => 'Nama guru / staf harus diisi.',
            'position.required' => 'Jabatan harus diisi.',
            'school.required' => 'Unit sekolah / staf harus dipilih.',
            'school.in' => 'Pilihan unit sekolah tidak valid.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, jpg, gif, webp.',
            'photo.max' => 'Ukuran gambar tidak boleh lebih dari 5MB.',
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('teacher_photos', 'public');
            $validatedData['photo'] = $photoPath;
        }

        Teacher::create($validatedData);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru / staf berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        return view('admin.tables.teacher.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        return view('admin.tables.teacher.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
            'position' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'additional_duties' => 'nullable|string|max:500',
            'order_column' => 'nullable|integer|min:0',
            'school' => 'required|in:Amaliah 1,Amaliah 2,Amaliah 1 & 2,Staff',
            'category' => 'nullable|string|max:100',
        ], [
            'name.required' => 'Nama guru / staf harus diisi.',
            'position.required' => 'Jabatan harus diisi.',
            'school.required' => 'Unit sekolah / staf harus dipilih.',
            'school.in' => 'Pilihan unit sekolah tidak valid.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, jpg, gif, webp.',
            'photo.max' => 'Ukuran gambar tidak boleh lebih dari 5MB.',
        ]);

        if ($request->hasFile('photo')) {
            if ($teacher->photo && !str_contains($teacher->photo, 'default-user')) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $photoPath = $request->file('photo')->store('teacher_photos', 'public');
            $validatedData['photo'] = $photoPath;
        }

        $teacher->update($validatedData);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru / staf berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        if ($teacher->photo && !str_contains($teacher->photo, 'default-user')) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru / staf berhasil dihapus!');
    }

    /**
     * Unduh template CSV/Excel untuk impor data guru & staf.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Nama Lengkap',
            'Unit / Sekolah',
            'Jabatan',
            'Mata Pelajaran',
            'Tugas Tambahan / Job Tambahan',
            'Kategori',
            'Foto (Opsional)'
        ];

        $samples = [
            [
                'Isma Tri Pamungkas, S.Pd., Gr',
                'Amaliah 1',
                'Kepala Sekolah',
                'Pendidikan Agama Islam',
                '-',
                'Struktural',
                ''
            ],
            [
                'Dr. Gugun Gunadi, S.Pd.I., M.Pd.',
                'Amaliah 2',
                'Kepala Sekolah',
                'Bahasa Indonesia',
                '-',
                'Struktural',
                ''
            ],
            [
                'Rd. Hendriawan Tansyah, S.T., Gr., ACA., MTA.',
                'Amaliah 1',
                'Wakil Kepala Sekolah Bidang Kurikulum',
                'Produktif PPLG',
                'Guru Produktif PPLG, Wali Kelas XII PPLG 3',
                'Struktural',
                ''
            ],
            [
                'Tisna Sudrajat, S.Kom., Gr., ACA.',
                'Amaliah 1 & 2',
                'Guru Kejuruan RPL',
                'Pemrograman Web & Perangkat Bergerak',
                '-',
                'Produktif',
                ''
            ],
            [
                'Ahmad Fauzi, S.AP.',
                'Staff',
                'Kepala Tata Usaha',
                '',
                '-',
                'Administrasi / TU',
                ''
            ],
            [
                'Herdiana',
                'Staff',
                'Pramubakti',
                '',
                '-',
                'Kebersihan',
                ''
            ],
            [
                'Hendi Wijaya',
                'Staff',
                'Security',
                '',
                '-',
                'Keamanan',
                ''
            ],
        ];

        $handle = fopen('php://temp', 'w');
        fputs($handle, "\xEF\xBB\xBF"); // BOM UTF-8 untuk Excel

        fputcsv($handle, $headers);
        foreach ($samples as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_guru_dan_staf.csv"',
        ]);
    }

    /**
     * Ekspor seluruh data guru dan staf ke file CSV kompatibel Excel (UTF-8 BOM).
     */
    public function export()
    {
        $teachers = Teacher::ordered()->get();

        $headers = [
            'No',
            'Nama Lengkap',
            'Unit / Sekolah',
            'Jabatan',
            'Mata Pelajaran',
            'Tugas Tambahan / Job Tambahan',
            'Kategori',
            'Urutan Hierarki',
        ];

        $handle = fopen('php://temp', 'w');
        fputs($handle, "\xEF\xBB\xBF"); // BOM UTF-8 untuk Excel

        fputcsv($handle, $headers);
        foreach ($teachers as $idx => $t) {
            fputcsv($handle, [
                $idx + 1,
                $t->name,
                $t->school,
                $t->position,
                $t->subject ?? '-',
                $t->additional_duties ?? '-',
                $t->category ?? '-',
                $t->order_column ?? ($idx + 1),
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        $filename = 'data_guru_dan_staf_smk_amaliah_' . date('Ymd_His') . '.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Impor massal data guru dan staf dari file CSV atau Excel (.xlsx).
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ], [
            'file.required' => 'Pilih file CSV atau Excel yang akan diimpor.',
            'file.max' => 'Ukuran file tidak boleh lebih dari 10MB.',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        // Selalu gunakan mode sinkronisasi pintar (upsert): data lama & foto aman, jabatan & mapel diperbarui otomatis
        $updateExisting = true;

        if (!in_array($ext, ['csv', 'txt', 'xlsx', 'xls'])) {
            return redirect()->back()->with('error', 'Format file tidak didukung. Harap unggah file .csv atau .xlsx.');
        }

        $rows = [];

        if ($ext === 'xlsx' || $ext === 'xls') {
            $rows = $this->parseXlsxFile($file->getPathname());
        } else {
            $rows = $this->parseCsvFile($file->getPathname());
        }

        if (empty($rows)) {
            return redirect()->back()->with('error', 'File impor kosong atau format data tidak dapat dibaca.');
        }

        $headerRow = array_shift($rows);
        $colMap = $this->mapImportHeaders($headerRow);

        if (!isset($colMap['name'])) {
            return redirect()->back()->with('error', 'Kolom "Nama" atau "Nama Lengkap" tidak ditemukan pada baris judul file.');
        }

        $successCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $sessionMap = [];

        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                $name = trim($row[$colMap['name']] ?? '');

                if ($name === '' || $name === '-') {
                    continue;
                }

                $rawSchool = isset($colMap['school']) ? trim($row[$colMap['school']] ?? '') : '';
                $rawCategory = isset($colMap['category']) ? trim($row[$colMap['category']] ?? '') : '';
                $position = isset($colMap['position']) ? trim($row[$colMap['position']] ?? '') : '';
                $subject = isset($colMap['subject']) ? trim($row[$colMap['subject']] ?? '') : null;
                $photo = isset($colMap['photo']) ? trim($row[$colMap['photo']] ?? '') : null;

                // Ekstrak tugas tambahan (mendukung single atau multiple kolom tugas tambahan)
                $additionalDuties = null;
                if (!empty($colMap['additional_duties'])) {
                    $dutyParts = [];
                    foreach ((array) $colMap['additional_duties'] as $dIdx) {
                        $val = trim($row[$dIdx] ?? '');
                        if ($val !== '' && $val !== '-') {
                            $dutyParts[] = $val;
                        }
                    }
                    if (!empty($dutyParts)) {
                        $additionalDuties = implode(', ', $dutyParts);
                    }
                }

                $school = $this->normalizeSchool($rawSchool, $rawCategory, $position);
                $category = $this->normalizeCategory($rawCategory, $school, $position, $subject);

                if ($position === '') {
                    $position = ($school === 'Staff') ? 'Staf Kependidikan' : 'Guru';
                }

                $orderColumn = isset($colMap['order_column']) && is_numeric(trim($row[$colMap['order_column']] ?? ''))
                    ? (int) trim($row[$colMap['order_column']])
                    : ($index + 1);

                $baseKey = $this->normalizeBaseName($name);

                // Cek apakah guru ini sudah ada di sesi impor saat ini atau di database
                $existing = $sessionMap[$baseKey] ?? $this->findTeacherForImport($name);

                if ($existing) {
                    if ($updateExisting) {
                        // Jika guru ini terdaftar di sekolah yang berbeda, jadikan 'Amaliah 1 & 2'
                        $finalSchool = $existing->school;
                        if ($existing->school !== $school && $existing->school !== 'Staff' && $school !== 'Staff') {
                            $finalSchool = 'Amaliah 1 & 2';
                        } elseif ($existing->school === 'Staff' && $school !== 'Staff') {
                            $finalSchool = $school;
                        }

                        // Gabungkan mata pelajaran dan tugas tambahan tanpa duplikasi
                        $mergedSubject = $this->combineCommaValues($existing->subject, $subject);
                        $mergedDuties = $this->combineCommaValues($existing->additional_duties, $additionalDuties);

                        // Ambil nama terpanjang (yang menyertakan gelar lengkap)
                        $finalName = (strlen($name) > strlen($existing->name)) ? $name : $existing->name;
                        $finalOrder = min($existing->order_column ?? 999, $orderColumn ?? 999);

                        $updateData = [
                            'name' => $finalName,
                            'school' => $finalSchool,
                            'position' => $position ?: $existing->position,
                            'subject' => $mergedSubject,
                            'additional_duties' => $mergedDuties,
                            'order_column' => $finalOrder,
                            'category' => $category ?: $existing->category,
                        ];
                        if (!empty($photo)) {
                            $updateData['photo'] = $photo;
                        }
                        $existing->update($updateData);
                        $sessionMap[$baseKey] = $existing;
                        $updatedCount++;
                    } else {
                        $skippedCount++;
                    }
                } else {
                    $created = Teacher::create([
                        'name' => $name,
                        'school' => $school,
                        'position' => $position,
                        'subject' => $subject,
                        'additional_duties' => $additionalDuties,
                        'order_column' => $orderColumn,
                        'category' => $category,
                        'photo' => !empty($photo) ? $photo : null,
                    ]);
                    $sessionMap[$baseKey] = $created;
                    $successCount++;
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses data impor: ' . $e->getMessage());
        }

        $msg = "Proses impor selesai: {$successCount} data baru ditambahkan";
        if ($updatedCount > 0) {
            $msg .= ", {$updatedCount} data diperbarui / disinkronkan";
        }
        if ($skippedCount > 0) {
            $msg .= ", {$skippedCount} data dilewati";
        }
        $msg .= " (tanpa duplikasi nama).";

        return redirect()->route('admin.teachers.index')->with('success', $msg);
    }

    /**
     * Bersihkan duplikasi nama guru & staf di database dan tetapkan satu nama saja.
     */
    public function deduplicate()
    {
        $teachers = Teacher::all();
        $groups = $teachers->groupBy(fn($t) => $this->normalizeBaseName($t->name));
        $mergedCount = 0;
        $deletedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($groups as $base => $records) {
                if ($records->count() > 1) {
                    $primary = $records->sortBy(fn($r) => $r->order_column ?? 999)->first();

                    $allSubjects = [];
                    $allDuties = [];
                    $schools = [];

                    foreach ($records as $r) {
                        if (!empty($r->subject)) {
                            foreach (explode(',', $r->subject) as $s) {
                                $s = trim($s);
                                if ($s !== '' && !in_array($s, $allSubjects)) $allSubjects[] = $s;
                            }
                        }
                        if (!empty($r->additional_duties)) {
                            foreach (explode(',', $r->additional_duties) as $d) {
                                $d = trim($d);
                                if ($d !== '' && !in_array($d, $allDuties)) $allDuties[] = $d;
                            }
                        }
                        if (!empty($r->school)) {
                            $schools[] = $r->school;
                        }
                    }

                    $uniqueSchools = array_unique($schools);
                    $finalSchool = $primary->school;
                    if (count($uniqueSchools) > 1 || in_array('Amaliah 1 & 2', $uniqueSchools)) {
                        $finalSchool = 'Amaliah 1 & 2';
                    }

                    $longestName = $records->sortByDesc(fn($r) => strlen($r->name))->first()->name;

                    $primary->update([
                        'name' => $longestName,
                        'school' => $finalSchool,
                        'subject' => !empty($allSubjects) ? implode(', ', $allSubjects) : $primary->subject,
                        'additional_duties' => !empty($allDuties) ? implode(', ', $allDuties) : $primary->additional_duties,
                    ]);

                    foreach ($records as $r) {
                        if ($r->id !== $primary->id) {
                            $r->delete();
                            $deletedCount++;
                        }
                    }
                    $mergedCount++;
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membersihkan duplikasi: ' . $e->getMessage());
        }

        if ($deletedCount > 0) {
            return redirect()->route('admin.teachers.index')
                ->with('success', "Pembersihan selesai: {$mergedCount} nama yang terduplikasi ({$deletedCount} data ganda) berhasil disatukan menjadi satu nama tetap!");
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Semua data guru dan staf sudah bersih dan tidak ada nama yang terduplikasi.');
    }

    /**
     * Cari guru yang cocok untuk impor, baik dari nama persis maupun nama dasar tanpa gelar.
     */
    protected function findTeacherForImport(string $name): ?Teacher
    {
        $name = trim($name);
        $exact = Teacher::where('name', $name)->first();
        if ($exact) {
            return $exact;
        }

        $baseKey = $this->normalizeBaseName($name);
        $all = Teacher::all();
        foreach ($all as $t) {
            if ($this->normalizeBaseName($t->name) === $baseKey) {
                return $t;
            }
        }

        return null;
    }

    /**
     * Normalisasi nama dasar (tanpa gelar dan tanda baca) untuk pencocokan pintar.
     */
    protected function normalizeBaseName(string $name): string
    {
        $clean = explode(',', $name)[0];
        $clean = preg_replace('/^(drs?|dra|dr|prof|h|hj)\.?\s+/i', '', trim($clean));
        return strtolower(trim(preg_replace('/[^a-zA-Z]/', '', $clean)));
    }

    /**
     * Gabungkan dua string nilai berpemisah koma tanpa duplikasi.
     */
    protected function combineCommaValues(?string $first, ?string $second): ?string
    {
        $items = [];
        if (!empty($first)) {
            foreach (explode(',', $first) as $item) {
                $item = trim($item);
                if ($item !== '' && !in_array($item, $items)) {
                    $items[] = $item;
                }
            }
        }
        if (!empty($second)) {
            foreach (explode(',', $second) as $item) {
                $item = trim($item);
                if ($item !== '' && !in_array($item, $items)) {
                    $items[] = $item;
                }
            }
        }
        return !empty($items) ? implode(', ', $items) : null;
    }

    /**
     * Baca baris-baris dari file CSV dengan auto-detect delimiter (, atau ;) dan stream parser.
     */
    protected function parseCsvFile(string $path): array
    {
        $content = file_get_contents($path);
        if ($content === false || trim($content) === '') {
            return [];
        }

        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }

        $firstLine = strtok($content, "\r\n");
        $commaCount = substr_count($firstLine, ',');
        $semiCount = substr_count($firstLine, ';');
        $tabCount = substr_count($firstLine, "\t");

        $delimiter = ',';
        if ($semiCount > $commaCount && $semiCount > $tabCount) {
            $delimiter = ';';
        } elseif ($tabCount > $commaCount && $tabCount > $semiCount) {
            $delimiter = "\t";
        }

        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $content);
        rewind($stream);

        $rows = [];
        while (($row = fgetcsv($stream, 0, $delimiter)) !== false) {
            if (!empty(array_filter($row, fn($v) => trim((string)$v) !== ''))) {
                $rows[] = $row;
            }
        }
        fclose($stream);

        return $rows;
    }

    /**
     * Baca baris-baris dari file .xlsx menggunakan native ZipArchive & SimpleXML.
     */
    protected function parseXlsxFile(string $path): array
    {
        if (!class_exists('ZipArchive')) {
            return $this->parseCsvFile($path);
        }

        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            return [];
        }

        $sharedStrings = [];
        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedStringsXml) {
            $xml = @simplexml_load_string($sharedStringsXml);
            if ($xml && isset($xml->si)) {
                foreach ($xml->si as $si) {
                    $sharedStrings[] = (string) ($si->t ?? ($si->r->t ?? ''));
                }
            }
        }

        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        if (!$sheetXml) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entryName = $zip->getNameIndex($i);
                if (preg_match('#^xl/worksheets/sheet\d+\.xml$#i', $entryName)) {
                    $sheetXml = $zip->getFromName($entryName);
                    break;
                }
            }
        }
        $zip->close();

        if (!$sheetXml) {
            return [];
        }

        $xml = @simplexml_load_string($sheetXml);
        if (!$xml || !isset($xml->sheetData->row)) {
            return [];
        }

        $rows = [];
        foreach ($xml->sheetData->row as $r) {
            $rowValues = [];
            foreach ($r->c as $c) {
                $type = (string) $c['t'];
                $val = (string) $c->v;
                if ($type === 's' && isset($sharedStrings[(int) $val])) {
                    $rowValues[] = $sharedStrings[(int) $val];
                } else {
                    $rowValues[] = $val;
                }
            }
            if (!empty(array_filter($rowValues, fn($v) => trim($v) !== ''))) {
                $rows[] = $rowValues;
            }
        }

        return $rows;
    }

    /**
     * Memetakan nama kolom header ke atribut model Teacher.
     */
    protected function mapImportHeaders(array $headerRow): array
    {
        $map = [
            'additional_duties' => [],
        ];

        foreach ($headerRow as $index => $col) {
            $name = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', (string)$col)));

            if (in_array($name, ['nama', 'namalengkap', 'name', 'fullname', 'namaguru', 'namagurustaf', 'namapendidik'])) {
                $map['name'] = $index;
            } elseif (in_array($name, ['sekolah', 'unit', 'school', 'unitsekolah', 'lembaga', 'penempatan'])) {
                $map['school'] = $index;
            } elseif (str_contains($name, 'tambahan') || in_array($name, ['jobtambahan', 'obtambahan', 'additionalduties', 'tugastambahan', 'tugasextra', 'duties'])) {
                $map['additional_duties'][] = $index;
            } elseif (in_array($name, ['jabatan', 'position', 'posisi', 'role'])) {
                $map['position'] = $index;
            } elseif (in_array($name, ['tugas'])) {
                if (!isset($map['position'])) {
                    $map['position'] = $index;
                } else {
                    $map['additional_duties'][] = $index;
                }
            } elseif (in_array($name, ['matapelajaran', 'mapel', 'subject', 'pelajaran', 'mengajar'])) {
                $map['subject'] = $index;
            } elseif (in_array($name, ['kategori', 'category', 'kelompok', 'jenis', 'tipe'])) {
                $map['category'] = $index;
            } elseif (in_array($name, ['urutan', 'order', 'ordercolumn', 'nomor', 'no', 'rank', 'hierarki', 'prioritas'])) {
                $map['order_column'] = $index;
            } elseif (in_array($name, ['foto', 'photo', 'gambar', 'image', 'fotofile', 'fotoguru'])) {
                $map['photo'] = $index;
            }
        }

        return $map;
    }

    /**
     * Normalisasi nilai unit/sekolah ke ENUM yang sah.
     */
    protected function normalizeSchool(string $val, string $category = '', string $position = ''): string
    {
        $lower = strtolower(trim($val));
        $lowerCat = strtolower(trim($category));
        $lowerPos = strtolower(trim($position));

        if (str_contains($lower, 'staff') || str_contains($lower, 'staf') || str_contains($lower, 'tu') || str_contains($lower, 'kependidikan')) {
            return 'Staff';
        }
        if (str_contains($lowerCat, 'staff') || str_contains($lowerCat, 'staf') || str_contains($lowerCat, 'tu') || str_contains($lowerCat, 'laboran') || str_contains($lowerCat, 'teknisi') || str_contains($lowerCat, 'perpustakaan') || str_contains($lowerCat, 'keamanan') || str_contains($lowerCat, 'kebersihan')) {
            if ($val === '') {
                return 'Staff';
            }
        }
        if (str_contains($lowerPos, 'tata usaha') || str_contains($lowerPos, 'satpam') || str_contains($lowerPos, 'laboran') || str_contains($lowerPos, 'pustakawan')) {
            if ($val === '') {
                return 'Staff';
            }
        }
        if (str_contains($lower, '1') && str_contains($lower, '2')) {
            return 'Amaliah 1 & 2';
        }
        if (str_contains($lower, 'gabungan') || str_contains($lower, 'keduanya')) {
            return 'Amaliah 1 & 2';
        }
        if (str_contains($lower, '2')) {
            return 'Amaliah 2';
        }
        if ($val === '' && $category === 'Struktural') {
            return 'Amaliah 1 & 2';
        }
        return 'Amaliah 1';
    }

    /**
     * Normalisasi kategori ke opsi standar yang rapi.
     */
    protected function normalizeCategory(string $rawCat, string $school, string $position = '', ?string $subject = null): string
    {
        $c = strtolower(trim($rawCat));
        $p = strtolower(trim($position));
        $s = strtolower(trim($subject ?? ''));

        // Cek kategori Staf
        if (str_contains($c, 'tu') || str_contains($c, 'administrasi') || str_contains($p, 'tata usaha') || str_contains($p, 'administrasi') || str_contains($p, 'persuratan') || str_contains($p, 'resepsionis') || str_contains($p, 'kesiswaan')) {
            return 'Administrasi / TU';
        }
        if (str_contains($c, 'laboran') || str_contains($c, 'lab') || str_contains($p, 'laboran') || str_contains($p, 'asisten lab') || str_contains($p, 'laboratorium')) {
            return 'Laboran';
        }
        if (str_contains($c, 'teknisi') || str_contains($c, 'it') || str_contains($p, 'teknisi') || str_contains($p, 'maintenance') || str_contains($p, 'jaringan') || str_contains($p, 'web developer') || str_contains($p, 'videographer') || str_contains($p, 'fotographer') || str_contains($p, 'editor') || str_contains($p, 'operator') || str_contains($p, 'sarana')) {
            return 'Teknisi';
        }
        if (str_contains($c, 'perpus') || str_contains($c, 'pustaka') || str_contains($p, 'perpus') || str_contains($p, 'pustakawan') || str_contains($p, 'perpustakaan')) {
            return 'Perpustakaan';
        }
        if (str_contains($c, 'keuangan') || str_contains($c, 'bendahara') || str_contains($p, 'keuangan') || str_contains($p, 'kasir') || str_contains($p, 'bendahara')) {
            return 'Keuangan';
        }
        if (str_contains($c, 'keamanan') || str_contains($c, 'satpam') || str_contains($c, 'security') || str_contains($p, 'satpam') || str_contains($p, 'keamanan') || str_contains($p, 'security')) {
            return 'Keamanan';
        }
        if (str_contains($c, 'kebersihan') || str_contains($c, 'sarpras') || str_contains($c, 'caraka') || str_contains($c, 'ob') || str_contains($c, 'pramubakti') || str_contains($p, 'kebersihan') || str_contains($p, 'office boy') || str_contains($p, 'pramubakti') || str_contains($p, 'ob')) {
            return 'Kebersihan';
        }
        if ($school === 'Staff' || str_contains($c, 'staf') || str_contains($c, 'kependidikan')) {
            return 'Staf';
        }

        // Cek kategori Guru
        if (str_contains($c, 'struktur') || str_contains($c, 'pimpinan') || str_contains($p, 'direktur') || str_contains($p, 'kepala sekolah') || str_contains($p, 'kepala smk') || str_contains($p, 'wakasek') || str_contains($p, 'wakil kepala') || str_contains($p, 'kepala kompetensi') || str_contains($p, 'kaprog') || str_contains($p, 'koordinator') || str_contains($p, 'pembina osis')) {
            return 'Struktural';
        }
        if (str_contains($c, 'produktif') || str_contains($c, 'kejuruan') || str_contains($p, 'produktif') || str_contains($s, 'rpl') || str_contains($s, 'tkj') || str_contains($s, 'dkv') || str_contains($s, 'akuntansi') || str_contains($s, 'pemrograman') || str_contains($s, 'jaringan')) {
            return 'Produktif';
        }
        if (str_contains($c, 'normatif') || str_contains($s, 'agama') || str_contains($s, 'pai') || str_contains($s, 'ppkn') || str_contains($s, 'pancasila') || str_contains($s, 'sejarah') || str_contains($s, 'seni')) {
            return 'Normatif';
        }
        if (str_contains($c, 'adaptif') || str_contains($s, 'matematika') || str_contains($s, 'bahasa') || str_contains($s, 'inggris') || str_contains($s, 'indonesia') || str_contains($s, 'ipa') || str_contains($s, 'pjok') || str_contains($s, 'olahraga')) {
            return 'Adaptif';
        }

        return 'Umum';
    }
}
