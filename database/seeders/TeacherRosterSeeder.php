<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherRosterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $raw = <<<'TXT'
1.0,"Yolanda Yursal Rustam, SH",Direktur Vokasi dan Kejuruan,,,1.0,"Intan Wahyu Annisaa, SE.",Kepala Keuangan dan Akuntansi,Guru Produktif AK & Administrasi Umum,
2.0,"Rd. Hendriawan Tansyah, S.T., Gr., ACA., MTA.",Wakil Kepala Sekolah Bidang Kurikulum,Guru Produktif PPLG,Wali Kelas XII PPLG 3,2.0,"Siti Wadiah, SE.I., MM., Gr.",Wakil Kepala Sekolah Bidang Kurikulum,Guru Produktif MP,-
3.0,"Isma Tri Pamungkas, S.Pd., Gr",Kepala SMK Amaliah 1,-,-,3.0,"Gilang Gumilar, SE.I., M.Pd., Gr.",Wakil Kepala Sekolah Bidang Kesiswaan,Guru Produktif LPS,-
4.0,"Amirudin Muslim, M.M., M.Pd.,Gr",Kepala SMK Amaliah 2,-,-,4.0,"Yulianti Ramadhan, S.Pd., Gr.",Wakil Kepala Sekolah Bidang Hubungan Industri,Guru B. Inggris & TOEIC,Wali Kelas XII MP 3
5.0,"Abdul Azis Sudrajat, S.Kom., Gr., MOS.",Wakil Kepala Sekolah Bidang Sarana dan Prasarana,Guru Teknologi Perkantoran,Wali Kelas XI PPLG 2,5.0,"Heni Nuraeni, SE., Gr.",Guru Produktif BR & MP,-,-
6.0,"Septyandi Utama, A.Md.",Kepala Kompetensi TKJ,Guru Produktif TKJ,Wali Kelas XII TJKT 1,6.0,"Mila Karmila, SE., Gr.",Kepala Kompetensi Akuntansi,Guru Produktif AK,Wali Kelas XII AK
7.0,"M. Rendi Nurmansyah, S.I.Kom., Gr",Kepala Kompetensi AN,Guru Produktif Animasi,Wali Kelas X AN,7.0,"Tri Ekawati, S.Pd., Gr",Kepala Kompetensi Desain Produksi Busana,Guru Produktif DPB,Wali Kelas XII DPB
8.0,"Selvia Prihastyani, S.Kom., Gr.",Kepala Kompetensi PPLG,Guru Produktif PPLG,Wali Kelas XII PPLG 1,8.0,"Reni Oktaviani, SE., Gr",Kepala Kompetensi Bisnis Ritel,Guru Produktif BR,Wali Kelas XII BR
9.0,"Mudin, S.I.Kom",Kepala Kompetensi DKV,Guru Produktif DKV,Wali Kelas XI DKV,9.0,"Aidina Barek Hoda, SE.",Kepala Kompetensi Layanan Perbankan Syariah,Guru Produktif LPS,Wali Kelas XII LPS
10.0,"Yeni Latifah, S.Kom., Gr.",Kepala Tata Usaha,Guru Informatika,Wali Kelas XII TJKT 3,10.0,"Dwi Handayani, S.Kom., Gr.",Kepala Kompetensi Manajemen Perkantoran,Guru Produktif MP & KKA,Wali Kelas XII MP 1
11.0,"Ami Listiami, S.P",Koordinator BK,Guru IPAS & BK,Wali Kelas XII PPLG 2,11.0,Dra. Eti Nurhayati,Koordinator BK,Guru MTK & BK,Wali Kelas XII MP 2
12.0,"Muhamad Romdoni, S.I.Kom",Wakil Kepala Sekolah Bidang Hubungan Industri,Guru Teknologi Perkantoran & Produktif TKJ,Wali Kelas XI AN,12.0,"Rahmi Putri Handayani, SE.I., Gr",Koordinator Keagamaan,Guru Produktif LPS,Wali Kelas XI LPS
13.0,"Muhammad Fendra Kari, M.Pd., Gr",Koordinator Keagamaan,Guru B. Inggris,Wali Kelas X PPLG 1,13.0,"Siti Murti Dewi, S.AP",Pembina Osis,"Guru Produktif AK, DPB",-
14.0,"Muhamad Fikri, S.Kom., MOS",Koordinator BKK,Guru Produktif PPLG,Operator,14.0,"Abdul Azis Sudrajat, S.Kom., Gr.",Guru Informatika,-,-
15.0,"Mimi Sumiati, M.Pd., Gr.",Guru B. Inggris  dan TOEIC,Wali Kelas XII AN,-,15.0,"Siti Sarah Monika, S.Pd., Gr",Guru B. Inggris,-,Wali Kelas X AK 1
16.0,"Ernawati, S.Pd., Gr",Guru B. Indonesia,Wali Kelas XI PPLG 1,-,16.0,"Wenny Ismawati, S.Pd., Gr",Guru B. Indonesia,-,Wali Kelas X MP 1
17.0,"Septa Sopiatun, S.Pd., Gr.",Guru SBK & KIK,-,Wali Kelas X PPLG 2,17.0,"Siti Nurjanah Nasution, S.Pd., Gr",Guru PJOK,-,Wali Kelas X BR 1
18.0,"Ana Luciana, S.Pd., Gr.",Guru B. Indonesia,-,Wali Kelas XII TJKT 2,18.0,"Siti Amaliah, S.Pd",Guru KIK,-,Wali Kelas XI MP 1
19.0,"Hadi Pasito, S.Si., Gr.",Guru IPAS,Wali Kelas X TJKT 2,-,19.0,"Hadi Pasito, S.Si., Gr",Guru IPAS,-,-
20.0,"Syahrul Ramdoni, S.I.Kom., Gr",Wakil Kepala Sekolah Bidang Kesiswaan,Wali Kelas XI PPLG 2,-,20.0,"Septa Sopiatun, S.Pd., Gr.",Guru SBK &KIK,-,-
21.0,"Tuti Susilawati, S.Pd., Gr.",Guru PKN & Sejarah,Wali Kelas X DKV 2,-,21.0,"Else As Syavira, S. Mat., Gr",Guru MTK,-,Wali Kelas XI DPB
22.0,"Yogi Ahmad Nugraha, SE., Gr",Guru PJOK,Wali Kelas X TJKT 1,-,22.0,"Muhammad Sayuti, SH",Pembina Osis,Guru PKN,Wali Kelas X AK 2
23.0,"Rani Oktaviani, S.Pd.",Guru Akhlak Tauhid & PAI,Wali Kelas X TJKT 3,-,23.0,"Riris Maurisal Jannah, S.Pd","Guru B. Arab, PAI, Akhlak Tauhid",-,Wali Kelas XI AK 2
24.0,"Tia Agustiani, S.Kom",Guru Produktif Animasi,-,-,24.0,"Tuti Susilawati, S.Pd., Gr",Guru Sejarah,-,-
25.0,Yudistira Pratama Aristiawan,Guru Produktif Animasi,-,-,25.0,"Dede Jaenudin, SE., M.A.P",Guru Produktif AK,-,-
26.0,"Aldino Ogi, S.Kom",Kepala Laboratorium,-,-,26.0,"Akwal Syahril, S.H.I",Guru PAI,-,-
27.0,"H. Oce Komarudin, S.Ag",Guru PAI & Akhlak Tauhid,-,-,27.0,"Siti Nurafifah, S.Pd., Gr",Kepala Kompetensi DPB,Guru Produktif DPB,Wali Kelas X DPB
28.0,"Fitri Zuhria Fiani, S.Kom.",Guru Produktif TKJ,-,-,28.0,"Rismayanti, SE.",Guru Produktif MP,-,Wali Kelas X MP 3
29.0,"Iqbal Firdaus, A.Md",Guru Produktif TKJ,Wali Kelas XI TKJ 2,-,29.0,"Sylvia Rachmawati, SM.",Guru Produktif MP,-,Wali Kelas XI MP 2
30.0,"Deden Zaenudin, S.Pd",Guru B. Inggris,-,-,30.0,"Siti Nuranisah, S.Pd",Guru B. Indonesia & B. Simda,-,Wali Kelas XI MP 3
31.0,Muhammad Subhan Hidayat,Guru Produktif TKJ,Wali Kelas XI TKJ 1,-,31.0,"Annisa Riani, SE",Guru Produktif MP,-,Wali Kelas X MP 2
32.0,"Rizky Septiyadi Wirakusumah, S.Kom",Guru Produktif TKJ,-,-,32.0,"Zahra Azkia Ibrahim, SM",Guru Produktif MP & AK,-,Wali Kelas XI AK 1
33.0,"Intan Sri Mulyani, S.Kom",Guru Produktif PPLG,Wali Kelas X PPLG 3,-,33.0,Siti Nur Alfina,Staff Resepsionis,Guru Produktif DPB,-
34.0,"Siti Sahidah, S.Pd",Guru B. Arab & Akhlak Tauhid,-,-,34.0,"Siti Sahidah, S.Pd",Guru B. Arab & Akhlak Tauhid,-,Wali Kelas X LPS
35.0,"Neylanur Maulidiyah, S.Li",Guru B. Jepang,-,-,35.0,"Khairunnisa, S.Psi",Guru BK,-,-
36.0,"Mulyana, SE",Guru Sejarah,-,-,36.0,"Niar Handayani Utami, SS",Guru B. Jepang,-,Wali Kelas XI BR
37.0,"Siti Nuranisah, S.Pd",Guru B. Indonesia & B. Sunda,-,-,37.0,"Muhammad Abdul Malik, S.Pd",Guru Akhlak Tauhid & PAI,-,Wali Kelas X BR 2
38.0,"Khairunnisa, S.Psi",Guru BK,-,-,38.0,"Annisa, S.Pd",Guru B. Inggris,-,-
39.0,"Mamay Sumarni, S.Pd.",Guru MTK,Wali Kelas X DKV 1,-,39.0,"Nurul Febrianti Rahmanissa, SM","Guru Informatika & Produktif MP, LPS, BR",-,-
40.0,"Sahnur Mulya, S.T.P",Guru MTK,-,-,40.0,"Mulyana, SE",Guru Sejarah & Produktif AK,-,-
41.0,"Anisa Maulida Jamil, S.H",Guru PKN ,-,-,41.0,"M. Iksal Ilham, SM",Guru Produktif BR,-,-
42.0,"Niar Handayani Utami, SS",Guru B. Jepang,-,-,42.0,"Dedi Setiana, SM",Operator,Guru Produktif MP,-
43.0,"Puspa Citra, M.Kom",Guru MTK,-,-,43.0,"Ratu Maulani Khusnul Khotimah, S.Mat",Guru MTK,-,-
44.0,Naufal Najwa Al Mughniy,Guru Produktif PPLG,-,-,44.0,Arif Hidayat,Staff Sarana & Prasarana,-,-
45.0,Julianti Shofariah Solihah,Guru Akhlak Tauhid,-,-,45.0,Muhammad Kemal Irfany Azhar,Staff Kesiswaan,-,-
46.0,Bintang Akbar Sutjipto,Guru Produktif PPLG,-,-,46.0,Herdiana,Pramubakti,-,-
47.0,Muhammad Fikri,Guru Produktif PPLG,-,-,47.0,"Saepulloh, S.Kom",Videographer,Laboran LPS,-
48.0,"Japar Sodik, S.Kom",Staff Perpustakaan,Guru Produktif PPLG,-,48.0,Nara Meisyarani,Staff Kesiswaan,-,-
49.0,"U. Kusnadi, S.Pd.I",Guru PAI,-,-,49.0,Hendi Wijaya,Security,-,-
50.0,"Tisna Sudrajat, M.Kom., Gr",Guru Produktif AN,-,-,50.0,Wahyudi Sunarya,Security,-,-
51.0,Muhammad Yasir,Staff Sarana dan Prasarana,-,-,51.0,Halim Mahmud,Pramubakti,-,-
52.0,Ajang Arif,Pramubakti,-,-,52.0,Naufal Najwa Al Mughniy,Fotographer,Laboran AK,-
53.0,Asep Endang,Pramubakti,-,-,53.0,Salsa Syakira,Staff Keamanan ,-,-
54.0,Siti Lestari Maulidina,Staff Persuratan,-,-,54.0,Muhamad Fajr Maulana,Editor Video,-,-
55.0,Indah Sylviana Gunawan,Staff Bendahara,-,-,55.0,Salwa Al Sakinah Baehaki,Staff Persuratan,-,-
56.0,Agung Gumelar,Web Developer,Laboran PPLG,-,56.0,Muhammad Fahmi Alfarizi,Pramubakti,-,-
57.0,Moch Wildan Alif K,Pramubakti,-,-,57.0,Indra Bayu Wana Putra,Staff Bendahara,-,-
58.0,Denis Saputra,Pramubakti,-,-,58.0,Dewi Mulyasari,Staff Bendahara,-,-
59.0,Maulidina Aprilia,Staff Bendahara,-,-,59.0,-,-,15,
TXT;

        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $raw);
        rewind($stream);

        $rows = [];
        while (($row = fgetcsv($stream)) !== false) {
            $rows[] = $row;
        }
        fclose($stream);

        $records = [];

        foreach ($rows as $idx => $r) {
            // Block 1 (SMK Amaliah 1 & Shared)
            $n1 = trim($r[1] ?? '');
            if ($n1 !== '' && $n1 !== '-') {
                $records[] = $this->parseRow($r[1], $r[2] ?? '', $r[3] ?? '', $r[4] ?? '', 'Amaliah 1', $idx);
            }

            // Block 2 (SMK Amaliah 2)
            $n2 = trim($r[6] ?? '');
            if ($n2 !== '' && $n2 !== '-') {
                $records[] = $this->parseRow($r[6], $r[7] ?? '', $r[8] ?? '', $r[9] ?? '', 'Amaliah 2', $idx);
            }
        }

        // Satukan data yang memiliki nama yang sama (deduplikasi ke satu nama tetap)
        $deduplicated = [];
        foreach ($records as $item) {
            $baseKey = strtolower(trim(preg_replace('/[^a-zA-Z]/', '', explode(',', $item['name'])[0])));
            $baseKey = preg_replace('/^(drs?|dra|dr|prof|h|hj)/i', '', $baseKey);

            if (!isset($deduplicated[$baseKey])) {
                $deduplicated[$baseKey] = $item;
            } else {
                $existing = $deduplicated[$baseKey];

                // Jika terdaftar di dua sekolah, tetapkan ke 'Amaliah 1 & 2'
                $finalSchool = $existing['school'];
                if ($existing['school'] !== $item['school'] && $existing['school'] !== 'Staff' && $item['school'] !== 'Staff') {
                    $finalSchool = 'Amaliah 1 & 2';
                }

                // Gabungkan mata pelajaran tanpa duplikasi
                $mergedSubjects = [];
                foreach ([$existing['subject'], $item['subject']] as $sub) {
                    if (!empty($sub)) {
                        foreach (explode(',', $sub) as $s) {
                            $s = trim($s);
                            if ($s !== '' && !in_array($s, $mergedSubjects)) $mergedSubjects[] = $s;
                        }
                    }
                }

                // Gabungkan tugas tambahan tanpa duplikasi
                $mergedDuties = [];
                foreach ([$existing['additional_duties'], $item['additional_duties']] as $dut) {
                    if (!empty($dut)) {
                        foreach (explode(',', $dut) as $d) {
                            $d = trim($d);
                            if ($d !== '' && !in_array($d, $mergedDuties)) $mergedDuties[] = $d;
                        }
                    }
                }

                $longestName = (strlen($item['name']) > strlen($existing['name'])) ? $item['name'] : $existing['name'];
                $highestRank = min($existing['order_column'], $item['order_column']);

                $deduplicated[$baseKey] = [
                    'name' => $longestName,
                    'position' => $existing['position'] ?: $item['position'],
                    'subject' => !empty($mergedSubjects) ? implode(', ', $mergedSubjects) : null,
                    'additional_duties' => !empty($mergedDuties) ? implode(', ', $mergedDuties) : null,
                    'school' => $finalSchool,
                    'category' => $existing['category'] ?: $item['category'],
                    'order_column' => $highestRank,
                ];
            }
        }

        Teacher::query()->delete();

        DB::beginTransaction();
        try {
            foreach ($deduplicated as $item) {
                Teacher::create($item);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function parseRow(string $name, string $position, string $t1, string $t2, string $defaultSchool, int $index = 0): array
    {
        $name = trim($name);
        $position = trim($position);
        $t1 = ($t1 === '-' || trim($t1) === '') ? '' : trim($t1);
        $t2 = ($t2 === '-' || trim($t2) === '') ? '' : trim($t2);

        $duties = [];
        if ($t1 !== '') $duties[] = $t1;
        if ($t2 !== '') $duties[] = $t2;
        $additionalDuties = !empty($duties) ? implode(', ', $duties) : null;

        // Ekstrak mata pelajaran jika tertera
        $subject = null;
        if (str_starts_with($position, 'Guru ')) {
            $subject = trim(substr($position, 5));
        }
        foreach ($duties as $d) {
            if (str_starts_with($d, 'Guru ')) {
                $mapel = trim(substr($d, 5));
                if (empty($subject)) {
                    $subject = $mapel;
                } else {
                    $subject .= ', ' . $mapel;
                }
            }
        }

        // Tentukan Unit / Sekolah
        $school = $defaultSchool;
        $pLower = strtolower($position);

        if (str_contains($pLower, 'direktur') || str_contains($name, 'Yolanda Yursal')) {
            $school = 'Amaliah 1 & 2';
        } elseif (str_contains($pLower, 'kepala smk amaliah 2') || str_contains($name, 'Amirudin Muslim')) {
            $school = 'Amaliah 2';
        } elseif (str_contains($pLower, 'kepala smk amaliah 1') || str_contains($name, 'Isma Tri Pamungkas')) {
            $school = 'Amaliah 1';
        } elseif ($pLower === 'pramubakti' || str_contains($pLower, 'security') || str_contains($pLower, 'staff bendahara') || str_contains($pLower, 'staff persuratan') || str_contains($pLower, 'staff sarana')) {
            if (empty($subject)) {
                $school = 'Staff';
            }
        }

        // Tentukan Kategori
        $category = $this->resolveCategory($position, $subject, $school);

        // Jika posisi Pramubakti, percantik jadi 'Pramubakti (OB)'
        if ($pLower === 'pramubakti') {
            $position = 'Pramubakti (OB)';
        }

        $orderColumn = $this->calculateHierarchyRank($position, $name, $category, $index);

        return [
            'name' => $name,
            'position' => $position,
            'subject' => $subject,
            'additional_duties' => $additionalDuties,
            'school' => $school,
            'category' => $category,
            'order_column' => $orderColumn,
        ];
    }

    protected function calculateHierarchyRank(string $position, string $name, string $category, int $index): int
    {
        $p = strtolower($position);
        $n = strtolower($name);

        if (str_contains($p, 'direktur') || str_contains($n, 'yolanda')) return 1;
        if (str_contains($p, 'kepala smk amaliah 1') || str_contains($n, 'isma tri')) return 2;
        if (str_contains($p, 'kepala smk amaliah 2') || str_contains($n, 'amirudin muslim')) return 3;
        if (str_contains($p, 'wakil kepala') || str_contains($p, 'wakasek')) return 10 + $index;
        if (str_contains($p, 'kepala kompetensi') || str_contains($p, 'kaprog')) return 30 + $index;
        if (str_contains($p, 'kepala tata usaha') || str_contains($p, 'koordinator') || str_contains($p, 'pembina osis') || str_contains($p, 'kepala keuangan')) return 50 + $index;
        if ($category === 'Produktif' || str_contains($p, 'guru produktif')) return 100 + $index;
        if ($category === 'Normatif' || $category === 'Adaptif' || $category === 'Umum') return 200 + $index;
        if ($category === 'Laboran' || $category === 'Teknisi' || $category === 'Perpustakaan' || $category === 'Administrasi / TU' || $category === 'Keuangan') return 300 + $index;
        if ($category === 'Keamanan') return 400 + $index;
        if ($category === 'Kebersihan' || str_contains($p, 'pramubakti') || str_contains($p, 'ob')) return 500 + $index;

        return 999;
    }

    protected function resolveCategory(string $position, ?string $subject, string $school): string
    {
        $p = strtolower($position);
        $s = strtolower($subject ?? '');

        if (str_contains($p, 'direktur') || str_contains($p, 'kepala sekolah') || str_contains($p, 'kepala smk') || str_contains($p, 'wakil kepala') || str_contains($p, 'kepala kompetensi') || str_contains($p, 'koordinator') || str_contains($p, 'pembina osis')) {
            return 'Struktural';
        }
        if (str_contains($p, 'tata usaha') || str_contains($p, 'persuratan') || str_contains($p, 'resepsionis') || str_contains($p, 'kesiswaan')) {
            return 'Administrasi / TU';
        }
        if (str_contains($p, 'pramubakti') || str_contains($p, 'kebersihan') || str_contains($p, 'ob') || str_contains($p, 'office boy')) {
            return 'Kebersihan';
        }
        if (str_contains($p, 'security') || str_contains($p, 'keamanan') || str_contains($p, 'satpam')) {
            return 'Keamanan';
        }
        if (str_contains($p, 'bendahara') || str_contains($p, 'keuangan') || str_contains($p, 'kasir') || str_contains($p, 'akuntansi')) {
            return 'Keuangan';
        }
        if (str_contains($p, 'laboratorium') || str_contains($p, 'laboran')) {
            return 'Laboran';
        }
        if (str_contains($p, 'web developer') || str_contains($p, 'videographer') || str_contains($p, 'fotographer') || str_contains($p, 'editor') || str_contains($p, 'operator') || str_contains($p, 'sarana')) {
            return 'Teknisi';
        }
        if (str_contains($p, 'perpustakaan') || str_contains($p, 'pustaka')) {
            return 'Perpustakaan';
        }
        if (str_contains($p, 'produktif') || str_contains($s, 'produktif') || str_contains($s, 'pplg') || str_contains($s, 'tkj') || str_contains($s, 'animasi') || str_contains($s, 'dkv') || str_contains($s, 'ak') || str_contains($s, 'mp') || str_contains($s, 'dpb') || str_contains($s, 'br') || str_contains($s, 'lps')) {
            return 'Produktif';
        }
        if (str_contains($p, 'pai') || str_contains($s, 'pai') || str_contains($s, 'agama') || str_contains($s, 'akhlak') || str_contains($s, 'pkn') || str_contains($s, 'sejarah') || str_contains($s, 'sbk')) {
            return 'Normatif';
        }
        if (str_contains($s, 'mtk') || str_contains($s, 'indonesia') || str_contains($s, 'inggris') || str_contains($s, 'jepang') || str_contains($s, 'sunda') || str_contains($s, 'arab') || str_contains($s, 'ipas') || str_contains($s, 'pjok')) {
            return 'Adaptif';
        }
        if (str_contains($s, 'bk') || str_contains($p, 'bk') || str_contains($s, 'kik') || str_contains($s, 'informatika')) {
            return 'Umum';
        }
        if ($school === 'Staff') {
            return 'Staf';
        }
        return 'Umum';
    }
}
