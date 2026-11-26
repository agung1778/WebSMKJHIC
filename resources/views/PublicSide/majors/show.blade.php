@extends('layouts.public-app')

@section('title', ($major->abbreviation ? $major->abbreviation . ' — ' : '') . $major->name . ' | SMK Amaliah 1 & 2')
@section('description', Str::limit(strip_tags($major->description), 160, '...'))

@php
    $amaliahGreen = '#63cd00';
    $amaliahDark = '#1c1c1d';
    $brandGreen = '#00875a';

    // Data profile komprehensif per jurusan untuk tampilan modern edukatif
    $majorDatabase = [
        'PPLG' => [
            'abbr' => 'PPLG',
            'school' => 'SMK Amaliah 1',
            'quota' => '90 peserta',
            'lead' => 'Belajar mengubah ide menjadi website, aplikasi, dan gim melalui pemrograman, desain UI/UX, serta pengelolaan data.',
            'for_who_paragraphs' => [
                'Rekayasa Perangkat Lunak (RPL) / PPLG adalah konsentrasi keahlian yang mempelajari cara membuat aplikasi, website, dan game dari awal hingga siap digunakan.',
                'Di PPLG, siswa tidak hanya belajar menulis kode program, tetapi juga belajar merancang tampilan aplikasi, mengelola data, membuat game, bekerja dalam tim, serta menggunakan teknologi yang digunakan di dunia kerja.',
                'Pembelajaran PPLG terus dikembangkan mengikuti perkembangan teknologi dan kebutuhan industri, sehingga siswa memiliki bekal keterampilan yang sesuai dengan dunia usaha dan dunia industri (DUDI).',
                'Selain keterampilan teknis, siswa juga dilatih untuk memiliki kemampuan yang dibutuhkan di dunia kerja, seperti: berpikir logis dan memecahkan masalah, bekerja sama dalam tim, berkomunikasi dengan baik, mengatur dan menyelesaikan proyek, serta berpikir kreatif dan menghasilkan ide baru.',
                'Dengan begitu, siswa tidak hanya bisa membuat program, tetapi juga mampu bekerja sama, menyelesaikan masalah, dan mengembangkan sebuah proyek dari awal sampai selesai.',
                'Selain bekerja, lulusan juga dapat membangun usaha sendiri di bidang teknologi atau membuat startup.',
                'Karena teknologi terus berkembang dan digunakan hampir di semua bidang. Dengan belajar PPLG, kamu mendapatkan kesempatan untuk belajar membuat sesuatu yang nyata, mulai dari website, aplikasi, hingga game.',
                'PPLG cocok bagi siswa yang suka komputer, tertarik dengan teknologi, senang membuat sesuatu, suka memecahkan masalah, atau ingin belajar bagaimana aplikasi dan game dibuat.',
                'PPLG bukan sekadar belajar komputer. PPLG adalah tempat untuk belajar bagaimana mengubah ide menjadi sebuah karya digital.'
            ],
            'skills' => [
                'Pemrograman dan cara kerja aplikasi',
                'Pembuatan website',
                'Database dan pengenalan cloud',
                'Pengembangan gim',
                'Desain UI/UX',
                'Version control dan DevOps dasar'
            ],
            'careers' => [
                'Programmer / Software Developer',
                'Web Developer',
                'Mobile App Developer',
                'Game Developer',
                'UI/UX Designer',
                'Freelancer IT atau wirausaha teknologi',
                'Studi lanjut: Teknik Informatika, Sistem Informasi, atau Rekayasa Perangkat Lunak'
            ],
            'lab_title' => 'Ruang & Fasilitas Praktik',
            'lab_desc' => 'Laboratorium PPLG digunakan untuk praktik pengembangan perangkat lunak dan gim. Situs resmi sekolah menyediakan laboratorium komputer terstandar industri, akses internet berkecepatan tinggi, server dan jaringan internal, perangkat lunak desain dan coding modern, serta LMS sebagai pendukung pembelajaran.',
            'lab_img' => 'assets/image/Classmeet.JPG',
            'kakom_name' => 'Selvia Prihastyani, S.Kom., Gr.',
            'kakom_photo' => null
        ],
        'TJKT' => [
            'abbr' => 'TJKT',
            'school' => 'SMK Amaliah 1',
            'quota' => '108 peserta',
            'lead' => 'Mempelajari instalasi jaringan, administrasi server, keamanan siber, fiber optik, dan teknologi cloud modern.',
            'for_who_paragraphs' => [
                'Teknik Jaringan Komputer dan Telekomunikasi (TJKT) adalah konsentrasi keahlian yang membekali siswa dengan keahlian perakitan komputer, instalasi jaringan kabel dan nirkabel, fiber optik, serta administrasi server.',
                'Siswa dilatih mengonfigurasi perangkat jaringan terstandar industri seperti router, switch, access point, dan server berbasis Linux serta Windows.',
                'Pembelajaran dirancang selaras dengan sertifikasi industri (seperti MikroTik MTCNA dan Cisco CCNA dasar) agar lulusan siap terjun langsung ke dunia kerja maupun melanjutkan ke jenjang perguruan tinggi.',
                'TJKT sangat cocok bagi siswa yang gemar membongkar pasang perangkat keras, tertarik pada infrastruktur internet, keamanan jaringan, dan sistem server.'
            ],
            'skills' => [
                'Perakitan & Troubleshooting Komputer/Laptop',
                'Administrasi Jaringan & Server Linux/Windows',
                'Routing, Switching, & Manajemen Bandwidth',
                'Instalasi & Penyambungan Fiber Optik (Splicing)',
                'Keamanan Siber Dasar & Firewall Network',
                'Cloud Computing & Virtualisasi Server'
            ],
            'careers' => [
                'Network Engineer / Administrator Jaringan',
                'IT Support & Technical Support Specialist',
                'System Administrator / Operator Server',
                'Teknisi Fiber Optik & Telekomunikasi ISP',
                'Wirausaha Jasa Layanan IT & Instalasi Jaringan',
                'Studi lanjut: Teknik Komputer, Teknik Telekomunikasi, atau Cyber Security'
            ],
            'lab_title' => 'Laboratorium Jaringan & Cisco/MikroTik',
            'lab_desc' => 'Laboratorium TJKT dilengkapi perangkat rack server, routerboard MikroTik, switch manageable Cisco, fusion splicer fiber optik, dan perlengkapan pengujian jaringan lengkap untuk simulasi infrastruktur dunia kerja nyata.',
            'lab_img' => 'assets/image/DroneView.jpg',
            'kakom_name' => 'Septiyandi Utama, A.Md',
            'kakom_photo' => null
        ],
        'DKV' => [
            'abbr' => 'DKV',
            'school' => 'SMK Amaliah 1',
            'quota' => '72 peserta',
            'lead' => 'Mengasah kreativitas visual melalui ilustrasi digital, fotografi, videografi, periklanan, dan branding multimedia.',
            'for_who_paragraphs' => [
                'Desain Komunikasi Visual (DKV) adalah konsentrasi keahlian yang memadukan seni grafis, teknologi digital, dan komunikasi pemasaran untuk menghasilkan karya visual yang bernilai jual tinggi.',
                'Siswa belajar merancang logo, identitas merek, materi promosi cetak dan digital, fotografi produk studio, serta produksi video periklanan profesional.',
                'Siswa diajak berpikir kritis dalam menerjemahkan kebutuhan klien menjadi solusi desain visual yang memikat dan tepat sasaran.',
                'Jurusan ini sangat ideal bagi mereka yang memiliki ketertarikan tinggi pada seni visual, menggambar, fotografi, editing video, dan dunia periklanan kreatif.'
            ],
            'skills' => [
                'Desain Grafis & Ilustrasi Digital (Adobe Suite)',
                'Fotografi Studio & Editing Foto Profesional',
                'Videografi Komersial & Editing Video Sinematik',
                'Brand Identity, Logo Design, & Desain Kemasan',
                'Desain Konten Media Sosial & Advertising',
                'Tipografi & Tata Letak Publikasi Media'
            ],
            'careers' => [
                'Graphic Designer / Visual Designer',
                'Videographer & Video Editor',
                'Fotografer Studio & Commercial Photographer',
                'Brand Identity Designer / Creative Junior',
                'Social Media Specialist & Content Creator',
                'Studi lanjut: Desain Komunikasi Visual, Periklanan, atau Sinematografi'
            ],
            'lab_title' => 'Studio Foto & Lab Multimedia DKV',
            'lab_desc' => 'Fasilitas DKV menyediakan studio foto lengkap dengan lighting profesional, background studio, kamera DSLR/Mirrorless, drawing tablet, dan komputer berspesifikasi rendering grafis tinggi.',
            'lab_img' => 'assets/image/17Agustus.JPG',
            'kakom_name' => 'Mudin, S.I.Kom',
            'kakom_photo' => null
        ],
        'ANIMASI' => [
            'abbr' => 'ANIMASI',
            'school' => 'SMK Amaliah 1',
            'quota' => '36 peserta',
            'lead' => 'Menciptakan karakter dan visual bergerak melalui animasi 2D, 3D modeling, rigging, texturing, dan visual effects.',
            'for_who_paragraphs' => [
                'Konsentrasi Keahlian Animasi mempersiapkan siswa menjadi animator profesional yang menguasai alur produksi animasi dari pra-produksi, produksi, hingga pasca-produksi.',
                'Siswa mempelajari pembuatan storyboard, perancangan karakter, prinsip 12 prinsip animasi, modeling 3D, rigging, pencahayaan digital, hingga proses rendering.',
                'Didukung kurikulum industri kreatif, siswa dibimbing membuat film animasi pendek dan aset visual untuk industri game dan periklanan.',
                'Sangat cocok bagi siswa yang gemar menggambar karakter, menonton film animasi, dan ingin mewujudkan ide cerita menjadi tayangan visual bergerak.'
            ],
            'skills' => [
                'Concept Art, Desain Karakter, & Storyboarding',
                'Prinsip Dasar Animasi 2D Tradisional & Digital',
                '3D Modeling & Sculpting Karakter/Aset',
                'Rigging & 3D Character Animation',
                'Pencahayaan (Lighting), Texturing, & Rendering',
                'Audio Dubbing & Compositing Pascaproduksi'
            ],
            'careers' => [
                '2D / 3D Animator Studio',
                '3D Asset / Character Modeler',
                'Storyboard Artist & Illustrator',
                'Motion Graphic Artist',
                'VFX Junior Artist & Compositor',
                'Studi lanjut: Animasi Digital, Desain Game, atau Seni Rupa Murni'
            ],
            'lab_title' => 'Studio Animasi & Render Station',
            'lab_desc' => 'Studio Animasi SMK Amaliah dilengkapi pen display tablet profesional, workstation rendering bertenaga tinggi, software animasi terstandar studio industri, dan bilik rekaman suara dubbing.',
            'lab_img' => 'assets/image/Classmeet.JPG',
            'kakom_name' => 'Muhamad Rendi N, S.I.Kom',
            'kakom_photo' => null
        ],
        'MP' => [
            'abbr' => 'MP',
            'school' => 'SMK Amaliah 2',
            'quota' => '108 peserta',
            'lead' => 'Menguasai tata kelola administrasi modern, korespondensi digital, manajemen kearsipan, dan pelayanan prima perkantoran.',
            'for_who_paragraphs' => [
                'Manajemen Perkantoran dan Layanan Bisnis (MP) membekali siswa dengan keahlian otomatisasi administrasi perkantoran, komunikasi bisnis multibahasa, dan pelayanan prima.',
                'Siswa dilatih mengelola dokumen fisik dan elektronik, mengorganisasi rapat dan perjalanan bisnis, mengelola kas kecil, serta mempraktikkan etika humas profesional.',
                'Di era serba digital, siswa MP juga menguasai aplikasi cloud office, manajemen kearsipan terkomputerisasi, dan sistem ERP perkantoran modern.',
                'Program ini sangat tepat untuk siswa yang rapi, komunikatif, teliti, dan bercita-cita berkarir di lingkungan korporat, BUMN, perbankan, atau instansi pemerintahan.'
            ],
            'skills' => [
                'Korespondensi Bisnis Bahasa Indonesia & Inggris',
                'Manajemen Kearsipan Digital & Cloud Storage',
                'Aplikasi Otomatisasi Perkantoran (Office Suite Modern)',
                'Pengelolaan Kas Kecil & Pembukuan Kas Kantor',
                'Pelayanan Prima (Customer Service Excellence)',
                'Tata Kelola Rapat, Notula, & Protokoler Bisnis'
            ],
            'careers' => [
                'Sekretaris Junior / Executive Administrative Assistant',
                'Staff Administrasi Perkantoran & Personalia (HR)',
                'Customer Service Representative / Front Desk Officer',
                'Digital Record Controller / Arsiparis Dokumen',
                'Staff Humas & Event Organizer Protokoler',
                'Studi lanjut: Administrasi Bisnis, Manajemen, atau Hubungan Masyarakat'
            ],
            'lab_title' => 'Laboratorium Simulasi Kantor Modern',
            'lab_desc' => 'Lab Manajemen Perkantoran dirancang menyerupai kantor korporat modern lengkap dengan meja resepsionis, ruang rapat eksekutif, filling cabinet digital, serta komputer terhubung jaringan sistem kearsipan.',
            'lab_img' => 'assets/image/DroneView.jpg',
            'kakom_name' => 'Dwi Handayani, S.Kom., Gr.',
            'kakom_photo' => null
        ],
        'AKL' => [
            'abbr' => 'AKL',
            'school' => 'SMK Amaliah 2',
            'quota' => '108 peserta',
            'lead' => 'Menguasai pembukuan keuangan, perpajakan, audit dasar, dan pengoperasian software akuntansi modern.',
            'for_who_paragraphs' => [
                'Akuntansi dan Keuangan Lembaga (AKL) membekali siswa dengan kemampuan menyusun laporan keuangan perusahaan jasa, dagang, dan manufaktur sesuai Standar Akuntansi Keuangan (SAK).',
                'Siswa dilatih mengoperasikan software akuntansi komputer terkemuka seperti Accurate, MYOB, dan Spreadsheet tingkat lanjut untuk otomasi laporan keuangan.',
                'Selain pembukuan, siswa juga mempelajari perpajakan Indonesia (PPh dan PPN), akuntansi perbankan, transaksi kas, piutang, dan dasar-dasar audit.',
                'Sangat cocok bagi siswa yang cermat, teliti, menyukai perhitungan angka, analisis data keuangan, dan memiliki integritas kejujuran tinggi.'
            ],
            'skills' => [
                'Siklus Akuntansi Perusahaan Jasa, Dagang, & Manufaktur',
                'Software Akuntansi Komputer (Accurate, MYOB, Spreadsheet)',
                'Perhitungan Pajak Penghasilan (PPh) & PPN',
                'Pengelolaan Kas, Piutang, Utang, & Aset Tetap',
                'Penyusunan Laporan Keuangan Digital & Budgeting',
                'Dasar-dasar Pemeriksaan Akuntansi (Auditing)'
            ],
            'careers' => [
                'Staff Akuntansi / Junior Accountant',
                'Staff Keuangan / Kasir Perusahaan',
                'Staff Administrasi Perpajakan Perusahaan/Konsultan',
                'Teller & Customer Service Lembaga Keuangan',
                'Junior Auditor Assistant',
                'Studi lanjut: Akuntansi, Manajemen Keuangan, atau Perpajakan'
            ],
            'lab_title' => 'Laboratorium Komputer Akuntansi',
            'lab_desc' => 'Lab Akuntansi dilengkapi komputer berlisensi software akuntansi profesional, aplikasi simulasi perbankan, dan materi studi kasus laporan keuangan perusahaan riil.',
            'lab_img' => 'assets/image/17Agustus.JPG',
            'kakom_name' => 'Mila Karmila, SE',
            'kakom_photo' => null
        ],
        'BR' => [
            'abbr' => 'BR',
            'school' => 'SMK Amaliah 2',
            'quota' => '72 peserta',
            'lead' => 'Mempelajari strategi pemasaran modern, pengelolaan toko ritel, visual merchandising, e-commerce, dan digital marketing.',
            'for_who_paragraphs' => [
                'Bisnis Retail (BR) adalah konsentrasi keahlian yang berfokus pada manajemen toko ritel modern, visual merchandising, serta penjualan produk melalui platform digital dan e-commerce.',
                'Siswa belajar tata kelola kasir POS (Point of Sales), penataan barang (display), manajemen persediaan stok, customer relationship, serta promosi digital di marketplace.',
                'Didukung kemitraan bersama jaringan ritel terkemuka nasional, siswa mendapatkan pengalaman magang dan praktik operasional ritel secara langsung.',
                'Sangat tepat bagi siswa yang berjiwa dinamis, menyukai negosiasi bisnis, pemasaran produk, dan ingin menjadi wirausahawan ritel mandiri.'
            ],
            'skills' => [
                'Operasional Toko Ritel Modern & Penataan Display Produk',
                'Sistem Kasir Terkomputerisasi (Point of Sales)',
                'Pemasaran Digital & Manajemen Marketplace E-Commerce',
                'Teknik Negosiasi, Promosi Penjualan, & Pelayanan Pelanggan',
                'Manajemen Persediaan Barang (Stock Opname & Gudang)',
                'Analisis Tren Pasar & Perilaku Konsumen'
            ],
            'careers' => [
                'Supervisor Toko Ritel / Assistant Store Manager',
                'Digital Marketing Specialist & Admin Marketplace',
                'Visual Merchandiser Toko Modern',
                'Staff Logistik & Pengelolaan Stok Ritel',
                'Wirausaha Toko Ritel / Online Shop Owner',
                'Studi lanjut: Manajemen Pemasaran, Bisnis Digital, atau Logistik'
            ],
            'lab_title' => 'Business Center & Lab Ritel Modern',
            'lab_desc' => 'Business Center SMK Amaliah berfungsi sebagai mini market laboratorium siswa, dilengkapi mesin kasir POS barcode scanner, rak display standar ritel nasional, dan ruang operasional e-commerce.',
            'lab_img' => 'assets/image/Classmeet.JPG',
            'kakom_name' => 'Reni Oktaviani, SE',
            'kakom_photo' => null
        ],
        'LPS' => [
            'abbr' => 'LPS',
            'school' => 'SMK Amaliah 2',
            'quota' => '72 peserta',
            'lead' => 'Memahami operasional perbankan syariah, transaksi syariah, teller service, dan pengelolaan produk keuangan Islam.',
            'for_who_paragraphs' => [
                'Layanan Perbankan Syariah (LPS) mendidik siswa menjadi tenaga profesional di lembaga keuangan syariah dengan pemahaman akad muamalah, akuntansi syariah, dan etika Islami.',
                'Siswa mempraktikkan operasional frontliner (Customer Service dan Teller Bank Syariah), administrasi pembiayaan, tabungan mudharabah, dan deposito syariah.',
                'Pembelajaran didukung simulasi Bank Mini Syariah di lingkungan sekolah sehingga siswa terbiasa melayani nasabah dengan standar perbankan sesungguhnya.',
                'Sangat ideal bagi siswa yang ingin mendalami ekonomi syariah, memiliki ketelitian tinggi, dan berorientasi pada pelayanan masyarakat yang berkeadilan syariah.'
            ],
            'skills' => [
                'Prinsip Fiqih Muamalah & Akad Perbankan Syariah',
                'Keterampilan Operasional Teller & Customer Service Bank',
                'Akuntansi Transaksi Perbankan Syariah',
                'Administrasi Tabungan, Deposito, & Pembiayaan Syariah',
                'Simulasi Transaksi Core Banking System Mini',
                'Etika Komunikasi Islami & Pelayanan Prima'
            ],
            'careers' => [
                'Teller Bank Syariah / BMT / Lembaga Keuangan',
                'Customer Service Bank & Lembaga Keuangan Syariah',
                'Staff Administrasi Pembiayaan & Tabungan Syariah',
                'Staff Pemasaran Produk Keuangan Syariah',
                'Pengelola Koperasi Simpan Pinjam Syariah',
                'Studi lanjut: Perbankan Syariah, Ekonomi Islam, atau Asuransi Syariah'
            ],
            'lab_title' => 'Bank Mini Syariah SMK Amaliah',
            'lab_desc' => 'Fasilitas Bank Mini Syariah lengkap dengan konter teller, meja customer service, antrean elektronik, mesin hitung uang, dan aplikasi perbankan simulasi untuk pelayanan transaksi riil siswa.',
            'lab_img' => 'assets/image/DroneView.jpg',
            'kakom_name' => 'Aidina Barek Hoda, SE',
            'kakom_photo' => null
        ],
        'DPB' => [
            'abbr' => 'DPB',
            'school' => 'SMK Amaliah 2',
            'quota' => '72 peserta',
            'lead' => 'Mengembangkan kreativitas fashion melalui pembuatan pola, teknik menjahit busana, draping, bordir, dan bisnis busana.',
            'for_who_paragraphs' => [
                'Desain dan Produksi Busana (DPB / Tata Busana) adalah wadah bagi siswa untuk mengembangkan bakat di industri fashion dari sketsa hingga busana siap pakai berstandar tinggi.',
                'Siswa diajarkan menggambar anatomi tubuh dan desain mode, membuat pola busana (manual dan komputer), memotong bahan, menjahit busana wanita, pria, hingga gaun pesta adibusana (haute couture).',
                'Siswa juga dibekali kemampuan wirausaha butik, perhitungan harga pokok produksi busana, draping, serta penyelesaian hiasan busana seperti bordir dan payet.',
                'Sangat cocok bagi siswa yang memiliki minat mendalam pada tren fashion, kreativitas mendesain baju, kerajinan tangan tekstil, dan ingin menjadi perancang busana mandiri.'
            ],
            'skills' => [
                'Fashion Sketching & Ilustrasi Desain Mode',
                'Pembuatan Pola Dasar, Pecah Pola, & Draping Busana',
                'Teknik Menjahit Busana Wanita, Pria, & Gaun Pesta',
                'Hiasan Busana (Bordir Digital/Manual, Payet, & Aplikasi)',
                'Pemilihan Tekstil, Cutting Kain, & Finishing Kualitas Ekspor',
                'Manajemen Butik Busana & Fashionpreneurship'
            ],
            'careers' => [
                'Fashion Designer / Perancang Busana Mandiri',
                'Pattern Maker (Pembuat Pola Industri Busana/Garmen)',
                'Penjahit Kustom / Custom Dressmaker & Tailor',
                'Fashion Stylist & Visual Merchandiser Butik',
                'Pemilik Butik / Usaha Rumah Mode Mandiri',
                'Studi lanjut: Tata Busana, Fashion Merchandising, atau Kriya Tekstil'
            ],
            'lab_title' => 'Workshop Tata Busana & Butik Amaliah',
            'lab_desc' => 'Workshop Busana dilengkapi mesin jahit industri berkecepatan tinggi, mesin jahit obras, dressform manekin standar internasional, meja potong pola luas, serta setrika uap industri.',
            'lab_img' => 'assets/image/17Agustus.JPG',
            'kakom_name' => 'Tri Ekawati, S.Pd., Gr.',
            'kakom_photo' => null
        ]
    ];

    // Deteksi kode jurusan yang cocok
    $abbrCandidate = strtoupper(trim($major->abbreviation ?? ''));
    if (empty($abbrCandidate)) {
        $n = strtolower($major->name);
        if (str_contains($n, 'perangkat lunak') || str_contains($n, 'gim') || str_contains($n, 'pplg') || str_contains($n, 'rpl')) $abbrCandidate = 'PPLG';
        elseif (str_contains($n, 'komputer dan jaringan') || str_contains($n, 'tjkt') || str_contains($n, 'tkj')) $abbrCandidate = 'TJKT';
        elseif (str_contains($n, 'komunikasi visual') || str_contains($n, 'dkv')) $abbrCandidate = 'DKV';
        elseif (str_contains($n, 'animasi')) $abbrCandidate = 'ANIMASI';
        elseif (str_contains($n, 'perkantoran') || str_contains($n, 'mplb')) $abbrCandidate = 'MP';
        elseif (str_contains($n, 'akuntansi') || str_contains($n, 'akl')) $abbrCandidate = 'AKL';
        elseif (str_contains($n, 'retail') || str_contains($n, 'ritel') || str_contains($n, 'bisnis')) $abbrCandidate = 'BR';
        elseif (str_contains($n, 'perbankan') || str_contains($n, 'syariah') || str_contains($n, 'lps')) $abbrCandidate = 'LPS';
        elseif (str_contains($n, 'busana') || str_contains($n, 'dpb')) $abbrCandidate = 'DPB';
    }

    $profile = $majorDatabase[$abbrCandidate] ?? [
        'abbr' => $major->abbreviation ?: 'PROG',
        'school' => $major->tag ?: 'SMK Amaliah',
        'quota' => '72 peserta',
        'lead' => Str::limit(strip_tags($major->description), 140, '...'),
        'for_who_paragraphs' => [strip_tags($major->description)],
        'skills' => $major->advantage ? array_filter(explode("\n", $major->advantage)) : ['Kompetensi Terstandar Industri', 'Praktik Kejuruan Mendalam', 'Pengembangan Karakter & Soft Skills'],
        'careers' => ['Tenaga Profesional Bidang Kejuruan', 'Wirausahawan Mandiri', 'Studi Lanjut ke Perguruan Tinggi'],
        'lab_title' => 'Ruang & Fasilitas Praktik',
        'lab_desc' => 'Fasilitas dan sarana pendukung pembelajaran praktik kejuruan dirancang terstandar industri untuk mengasah kompetensi siswa.',
        'lab_img' => 'assets/image/Classmeet.JPG',
        'kakom_name' => $major->competency_head ?: 'Kepala Kompetensi Keahlian',
        'kakom_photo' => $major->competency_head_photo
    ];

    $schoolTag = $major->tag ?: $profile['school'];
    $abbr = $major->abbreviation ?: $profile['abbr'];
    $leadText = $profile['lead'];
    $quota = $profile['quota'];

    // Gambar banner / lab foto
    $heroMainImg = $major->image ? img_url($major->image, 1200, 750) : asset($profile['lab_img']);
    $kakomName = $major->competency_head ?: $profile['kakom_name'];
    $kakomInitial = strtoupper(mb_substr(trim($kakomName), 0, 1));
@endphp

@section('content')

    <div class="bg-[#fbfcfd] text-[#1c1c1d]">

        {{-- BREADCRUMB ATAS --}}
        <div class="border-b border-gray-200/80 bg-white">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
                <nav aria-label="Breadcrumb" class="overflow-x-auto scrollbar-hide">
                    <ol class="inline-flex items-center gap-2 text-xs sm:text-sm text-gray-500 whitespace-nowrap">
                        <li>
                            <a href="/" class="hover:text-gray-900 transition-colors">Beranda</a>
                        </li>
                        <li>
                            <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                        </li>
                        <li>
                            <a href="{{ route('public.majors.index') }}" class="hover:text-gray-900 transition-colors">Program Keahlian</a>
                        </li>
                        <li>
                            <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                        </li>
                        <li class="font-semibold text-gray-900 truncate max-w-xs sm:max-w-md" aria-current="page">
                            {{ $major->name }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- ============================ HERO SECTION ============================ --}}
        <section class="bg-white border-b border-gray-200/80">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-14">
                <div class="major-hero-grid grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center">

                    {{-- Kolom Kiri: Informasi Jurusan & Tombol Aksi --}}
                    <div class="major-hero-left lg:col-span-7 flex flex-col justify-center">

                        {{-- Badge Atas --}}
                        <div class="inline-flex items-center gap-3 p-1.5 pr-4 rounded-xl bg-gray-50 border border-gray-200 shadow-2xs mb-4 sm:mb-6 w-fit">
                            <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-[#00875a] font-black text-xs shadow-xs">
                                @if($major->logo)
                                    <img src="{{ img_url($major->logo, 100, 100) }}" alt="{{ $abbr }}" class="w-6 h-6 object-contain">
                                @else
                                    <i class="fa-solid fa-graduation-cap"></i>
                                @endif
                            </div>
                            <div class="leading-none">
                                <span class="block text-[9.5px] font-extrabold uppercase tracking-widest text-[#00875a]">Kenali Program Keahlian</span>
                                <span class="text-xs font-bold text-gray-900 mt-0.5 block">{{ $schoolTag }} · {{ $abbr }}</span>
                            </div>
                        </div>

                        {{-- Judul Besar --}}
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 tracking-tight leading-[1.15]">
                            {{ $major->name }}
                        </h1>

                        {{-- Paragraf Pengantar --}}
                        <p class="mt-4 text-base sm:text-lg text-gray-600 leading-relaxed max-w-2xl font-normal">
                            {{ $leadText }}
                        </p>

                        {{-- Tombol Tindakan --}}
                        <div class="mt-6 sm:mt-8 flex flex-wrap items-center gap-3">
                            <a href="#tentang-jurusan"
                                style="background-color: #00875a; color: #ffffff;"
                                class="inline-flex items-center gap-2 bg-[#00875a] hover:bg-[#00704a] text-white font-bold text-sm sm:text-base px-6 py-3 rounded-xl transition-all duration-200 shadow-sm hover:shadow hover:-translate-y-0.5">
                                Kenali Jurusannya <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                            <a href="https://wa.me/6285649011449?text={{ rawurlencode('Halo Panitia SPMB SMK Amaliah, saya ingin bertanya tentang jurusan ' . $major->name . '.') }}"
                                target="_blank" rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-800 font-bold text-sm sm:text-base px-6 py-3 rounded-xl border border-gray-300 transition-all duration-200 shadow-2xs">
                                Tanya Panitia
                            </a>
                        </div>

                        {{-- Catatan Kecil di Bawah Tombol --}}
                        <div class="mt-4 flex items-center gap-2 text-xs sm:text-sm text-gray-500 font-medium">
                            <i class="fa-regular fa-circle-check text-[#00875a] text-sm flex-shrink-0"></i>
                            <span>Temukan jurusan yang sesuai minat dan rencana masa depanmu.</span>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Foto Ruang Praktik / Laboratorium --}}
                    <div class="major-hero-right lg:col-span-5">
                        <div class="bg-white rounded-2xl border border-gray-200 p-2.5 sm:p-3 shadow-md hover:shadow-lg transition-shadow">
                            <div class="relative rounded-xl overflow-hidden aspect-[16/10] bg-gray-100">
                                <img src="{{ $heroMainImg }}"
                                    alt="Ruang Praktik {{ $major->name }}"
                                    class="w-full h-full object-cover">
                                <span class="absolute top-3 right-3 bg-black/75 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full border border-white/20">
                                    {{ $abbr }}
                                </span>
                            </div>
                            <div class="pt-3 pb-1 px-2 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#00875a] flex items-center justify-center text-sm flex-shrink-0 border border-emerald-100">
                                    <i class="fa-solid fa-chalkboard-user"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Mengenal lebih dekat</p>
                                    <p class="text-xs sm:text-sm font-bold text-gray-900 truncate">{{ $major->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- ============================ STICKY SUB-NAV ============================ --}}
        <nav class="sticky top-[72px] sm:top-[80px] lg:top-[135px] z-30 bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-2xs">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-start lg:justify-center gap-2 sm:gap-3 overflow-x-auto scrollbar-hide py-2.5 sm:py-3">
                    <a href="#tentang-jurusan" class="subnav-btn active">
                        <i class="fa-solid fa-book-open"></i> Tentang Jurusan
                    </a>
                    <a href="#yang-dipelajari" class="subnav-btn">
                        <i class="fa-solid fa-laptop-code"></i> Yang Dipelajari
                    </a>
                    <a href="#peluang-lulusan" class="subnav-btn">
                        <i class="fa-solid fa-briefcase"></i> Peluang Lulusan
                    </a>
                    <a href="#ruang-praktik" class="subnav-btn">
                        <i class="fa-solid fa-chalkboard"></i> Ruang Praktik
                    </a>
                    <a href="#kenali-kakom" class="subnav-btn">
                        <i class="fa-solid fa-user-tie"></i> Kenali Kakom
                    </a>
                </div>
            </div>
        </nav>

        {{-- ============================ MAIN CONTENT (2 KOLOM) ============================ --}}
        <section class="py-10 sm:py-14 lg:py-16">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="major-main-grid grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 xl:gap-12 items-start">

                    {{-- ==================== KOLOM KIRI (2/3) ==================== --}}
                    <div class="major-content-col lg:col-span-8 space-y-8 lg:space-y-10">

                        {{-- KARTU 1: MULAI DARI SINI (Jurusan ini untuk apa?) --}}
                        <article id="tentang-jurusan" class="scroll-mt-[195px] lg:scroll-mt-[200px] bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 lg:p-10 shadow-xs hover:border-gray-300 transition-colors">
                            <span class="inline-block text-xs font-extrabold uppercase tracking-widest text-[#00875a] mb-2">
                                Mulai Dari Sini
                            </span>
                            <h2 class="text-2xl sm:text-3xl lg:text-[32px] font-extrabold text-gray-900 tracking-tight mb-6">
                                Jurusan ini untuk apa?
                            </h2>

                            <div class="text-gray-700 leading-relaxed text-sm sm:text-base space-y-4">
                                @foreach($profile['for_who_paragraphs'] as $paragraph)
                                    @if(str_contains($paragraph, 'keterampilan teknis') || str_contains($paragraph, 'kemampuan yang dibutuhkan'))
                                        <p>{{ $paragraph }}</p>
                                        <div class="my-4 flex flex-wrap gap-2 pt-1 pb-1">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200/80">
                                                <i class="fa-solid fa-check text-[10px] text-[#00875a]"></i> Berpikir Logis & Problem Solving
                                            </span>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200/80">
                                                <i class="fa-solid fa-check text-[10px] text-[#00875a]"></i> Kerja Sama Tim (Teamwork)
                                            </span>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200/80">
                                                <i class="fa-solid fa-check text-[10px] text-[#00875a]"></i> Komunikasi Efektif
                                            </span>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200/80">
                                                <i class="fa-solid fa-check text-[10px] text-[#00875a]"></i> Manajemen & Eksekusi Proyek
                                            </span>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200/80">
                                                <i class="fa-solid fa-check text-[10px] text-[#00875a]"></i> Kreativitas & Ide Inovatif
                                            </span>
                                        </div>
                                    @else
                                        <p>{{ $paragraph }}</p>
                                    @endif
                                @endforeach
                            </div>

                            @if($major->description && !str_contains($major->description, 'Pelajari pemrograman'))
                                <div class="mt-6 pt-6 border-t border-gray-100 prose max-w-none text-gray-700 text-sm sm:text-base">
                                    {!! \App\Support\HtmlSanitizer::clean($major->description) !!}
                                </div>
                            @endif

                            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <a href="{{ route('public.about.index') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-[#00875a] hover:underline">
                                    Sumber: profil resmi sekolah <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>

                            {{-- Callout Box Hijau Lembut --}}
                            <div class="mt-6 bg-[#f0fdf4] border border-[#bbf7d0] rounded-xl p-4 sm:p-5 flex items-start gap-3.5">
                                <i class="fa-regular fa-circle-check text-[#00875a] text-lg mt-0.5 flex-shrink-0"></i>
                                <p class="text-xs sm:text-sm text-gray-700 leading-relaxed font-medium">
                                    Masih mempertimbangkan pilihan? Baca keterampilan yang dipelajari, lihat ruang praktiknya, lalu tanyakan hal yang ingin kamu ketahui kepada panitia.
                                </p>
                            </div>
                        </article>

                        {{-- KARTU 2: BEKAL KETERAMPILAN (Apa yang akan kamu pelajari?) --}}
                        <article id="yang-dipelajari" class="scroll-mt-[195px] lg:scroll-mt-[200px] bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 lg:p-10 shadow-xs hover:border-gray-300 transition-colors">
                            <span class="inline-block text-xs font-extrabold uppercase tracking-widest text-[#00875a] mb-2">
                                Bekal Keterampilan
                            </span>
                            <h2 class="text-2xl sm:text-3xl lg:text-[32px] font-extrabold text-gray-900 tracking-tight">
                                Apa yang akan kamu pelajari?
                            </h2>
                            <p class="mt-2 text-sm sm:text-base text-gray-500 mb-6">
                                Gambaran kompetensi dan praktik yang dikenalkan di jurusan ini.
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
                                @foreach($profile['skills'] as $index => $skill)
                                    @php $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT); @endphp
                                    <div class="bg-white border border-gray-200/90 hover:border-[#00875a] hover:shadow-xs rounded-xl p-3.5 sm:p-4 flex items-center gap-3.5 transition-all group">
                                        <span class="w-8 h-8 rounded-lg bg-emerald-50 text-[#00875a] font-extrabold text-xs flex items-center justify-center flex-shrink-0 border border-emerald-100 group-hover:bg-[#00875a] group-hover:text-white transition-colors">
                                            {{ $num }}
                                        </span>
                                        <span class="text-xs sm:text-sm font-semibold text-gray-800 leading-snug group-hover:text-gray-900">
                                            {{ $skill }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </article>

                        {{-- KARTU 3: SETELAH LULUS (Ke mana keterampilan ini bisa membawamu?) --}}
                        <article id="peluang-lulusan" class="scroll-mt-[195px] lg:scroll-mt-[200px] bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 lg:p-10 shadow-xs hover:border-gray-300 transition-colors">
                            <span class="inline-block text-xs font-extrabold uppercase tracking-widest text-[#00875a] mb-2">
                                Setelah Lulus
                            </span>
                            <h2 class="text-2xl sm:text-3xl lg:text-[32px] font-extrabold text-gray-900 tracking-tight">
                                Ke mana keterampilan ini bisa membawamu?
                            </h2>
                            <p class="mt-2 text-sm sm:text-base text-gray-500 mb-6">
                                Kenali pilihan bidang kerja, usaha, atau studi lanjutan yang relevan.
                            </p>

                            <div class="space-y-3 sm:space-y-3.5">
                                @foreach($profile['careers'] as $career)
                                    <div class="flex items-center gap-3 text-sm sm:text-base text-gray-700 font-medium group hover:text-gray-900 transition-colors">
                                        <i class="fa-solid fa-arrow-right text-xs text-[#00875a] flex-shrink-0 group-hover:translate-x-1 transition-transform"></i>
                                        <span class="leading-relaxed">{{ $career }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </article>

                        {{-- KARTU 4: LIHAT LINGKUNGAN BELAJARNYA (Ruang & Fasilitas Praktik) --}}
                        <article id="ruang-praktik" class="scroll-mt-[195px] lg:scroll-mt-[200px] bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 lg:p-10 shadow-xs hover:border-gray-300 transition-colors">
                            <span class="inline-block text-xs font-extrabold uppercase tracking-widest text-[#00875a] mb-2">
                                Lihat Lingkungan Belajarnya
                            </span>
                            <h2 class="text-2xl sm:text-3xl lg:text-[32px] font-extrabold text-gray-900 tracking-tight">
                                Ruang & Fasilitas Praktik
                            </h2>
                            <p class="mt-2 text-sm sm:text-base text-gray-500 mb-6">
                                Kenali tempat dan sarana untuk mengembangkan keterampilanmu.
                            </p>

                            <div class="rounded-2xl overflow-hidden border border-gray-200 bg-gray-100 shadow-xs group">
                                <img src="{{ $heroMainImg }}"
                                    alt="Ruang Praktik {{ $major->name }}"
                                    class="w-full h-auto object-cover max-h-[460px] group-hover:scale-[1.02] transition-transform duration-500">
                            </div>

                            <p class="mt-3 text-xs text-gray-400 italic">
                                Ruang praktik {{ $major->name }} · Klik foto untuk melihat lebih jelas.
                            </p>

                            <p class="mt-4 text-sm sm:text-base text-gray-700 leading-relaxed font-normal">
                                {{ $profile['lab_desc'] }}
                            </p>
                        </article>

                        {{-- TESTIMONI ALUMNI (JIKA ADA) --}}
                        @if($major->testimonials && $major->testimonials->isNotEmpty())
                            <article class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 lg:p-10 shadow-xs">
                                <span class="inline-block text-xs font-extrabold uppercase tracking-widest text-[#00875a] mb-2">
                                    Cerita Alumni
                                </span>
                                <h2 class="text-2xl sm:text-3xl lg:text-[32px] font-extrabold text-gray-900 tracking-tight mb-6">
                                    Kata Mereka Para Alumni
                                </h2>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($major->testimonials as $testimonial)
                                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 flex flex-col justify-between">
                                            <p class="text-sm text-gray-700 italic leading-relaxed">
                                                "{{ $testimonial->description }}"
                                            </p>
                                            <div class="mt-4 pt-3 border-t border-gray-200/60 flex items-center gap-3">
                                                @if($testimonial->photo)
                                                    <img src="{{ img_url($testimonial->photo, 200, 200) }}" alt="{{ $testimonial->name }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border border-gray-200 shadow-2xs">
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-[#00875a] font-bold text-sm flex items-center justify-center flex-shrink-0">
                                                        {{ strtoupper(mb_substr(trim($testimonial->name), 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div class="min-w-0">
                                                    <p class="text-sm font-bold text-gray-900 truncate">{{ $testimonial->name }}</p>
                                                    <p class="text-xs text-gray-500 font-medium">Alumni {{ $abbr }} ({{ $testimonial->alumni_year }})</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </article>
                        @endif

                    </div>

                    {{-- ==================== KOLOM KANAN / SIDEBAR (1/3) ==================== --}}
                    <aside class="major-sidebar-col lg:col-span-4 space-y-6">

                        {{-- WIDGET 1: ORANG DI BALIK JURUSAN (Kenali Kakom) --}}
                        <div id="kenali-kakom" class="scroll-mt-[195px] lg:scroll-mt-[200px] bg-white rounded-2xl border border-gray-200 p-6 sm:p-7 shadow-xs">
                            <span class="inline-block text-[11px] font-extrabold uppercase tracking-widest text-[#00875a] mb-1">
                                Orang Di Balik Jurusan
                            </span>
                            <h3 class="text-xl font-extrabold text-gray-900 tracking-tight">
                                Kenali Kakom
                            </h3>
                            <p class="text-xs text-gray-500 mb-5">Kepala Kompetensi Keahlian</p>

                            {{-- Frame Foto Kakom --}}
                            <div class="rounded-2xl overflow-hidden border border-gray-200 bg-gray-50 p-2.5 shadow-2xs text-center">
                                @if($major->competency_head_photo)
                                    <img src="{{ img_url($major->competency_head_photo, 400, 450) }}"
                                        alt="{{ $kakomName }}"
                                        class="w-full aspect-[4/5] object-cover rounded-xl shadow-xs">
                                @else
                                    <div class="w-full aspect-[4/5] rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex flex-col items-center justify-center p-6 text-gray-400">
                                        <div class="w-20 h-20 rounded-full bg-white shadow-xs flex items-center justify-center text-3xl font-extrabold text-[#00875a] border border-gray-200 mb-3">
                                            {{ $kakomInitial }}
                                        </div>
                                        <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Kepala Kompetensi</span>
                                        <span class="text-[11px] text-gray-400 mt-1">{{ $abbr }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4 text-center sm:text-left">
                                <h4 class="text-base sm:text-lg font-extrabold text-gray-900 leading-snug">
                                    {{ $kakomName }}
                                </h4>
                                <p class="text-xs text-gray-500 font-medium mt-1">
                                    Kepala Kompetensi {{ $major->name }}
                                </p>
                            </div>

                            <div class="mt-5 pt-4 border-t border-gray-100">
                                <a href="https://wa.me/6285649011449?text={{ rawurlencode('Halo Kakom ' . $abbr . ', saya ingin konsultasi seputar kurikulum dan program jurusan ' . $major->name . '.') }}"
                                    target="_blank" rel="noopener noreferrer"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-50 text-gray-800 text-xs sm:text-sm font-bold transition shadow-2xs hover:shadow-xs">
                                    Tanya Tentang Jurusan <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>

                        {{-- WIDGET 2: INFORMASI PENDAFTARAN (SPMB) - STICKY DESKTOP --}}
                        <div class="bg-[#f0fdf4]/80 border border-[#bbf7d0] rounded-2xl p-6 sm:p-7 shadow-xs lg:sticky lg:top-[200px]">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-[#00875a] flex items-center justify-center text-lg mb-4 shadow-2xs">
                                <i class="fa-regular fa-file-lines"></i>
                            </div>

                            <h3 class="text-xl font-extrabold text-gray-900 tracking-tight">
                                Tertarik dengan jurusan ini?
                            </h3>
                            <p class="mt-2 text-xs sm:text-sm text-gray-600 leading-relaxed font-normal">
                                Pelajari alur SPMB, lalu pilih unit sekolah dan jurusan saat mengisi pendaftaran. Ketersediaan mengikuti kuota dan periode pendaftaran.
                            </p>

                            {{-- Metadata Kuota & Unit --}}
                            <div class="mt-5 space-y-2.5 py-4 border-y border-[#bbf7d0]/80 text-xs sm:text-sm">
                                <div class="flex items-center justify-between text-gray-600">
                                    <span class="font-medium">Unit sekolah</span>
                                    <span class="font-bold text-gray-900">{{ $schoolTag }}</span>
                                </div>
                                <div class="flex items-center justify-between text-gray-600">
                                    <span class="font-medium">Program</span>
                                    <span class="font-bold text-gray-900">{{ $abbr }}</span>
                                </div>
                                <div class="flex items-center justify-between text-gray-600">
                                    <span class="font-medium">Kuota</span>
                                    <span class="font-bold text-gray-900">{{ $quota }}</span>
                                </div>
                            </div>

                            {{-- Tombol Aksi Pendaftaran & Informasi --}}
                            <div class="mt-5 space-y-2.5">
                                <a href="https://ppdb.smkamaliah.sch.id/login" target="_blank" rel="noopener noreferrer"
                                    style="background-color: #00875a; color: #ffffff;"
                                    class="w-full inline-flex items-center justify-center gap-2 bg-[#00875a] hover:bg-[#00704a] text-white font-bold text-sm py-3 px-4 rounded-xl transition shadow-xs hover:shadow hover:-translate-y-0.5">
                                    Pendaftaran Online (SPMB) <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>

                                <a href="https://ppdb.smkamaliah.sch.id" target="_blank" rel="noopener noreferrer"
                                    class="w-full inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 border border-gray-300 text-gray-800 font-bold text-xs sm:text-sm py-2.5 px-4 rounded-xl transition shadow-2xs hover:shadow-xs">
                                    Panduan & Alur Pendaftaran
                                </a>
                            </div>

                            <div class="mt-5 text-center">
                                <a href="{{ route('public.majors.index') }}" class="text-xs font-bold text-[#00875a] hover:underline inline-flex items-center gap-1.5">
                                    Lihat jurusan lainnya
                                </a>
                            </div>
                        </div>

                    </aside>

                </div>
            </div>
        </section>

        {{-- ============================ EKSPLORASI JURUSAN LAINNYA (GRID BAWAH) ============================ --}}
        @if(isset($otherMajors) && $otherMajors->isNotEmpty())
            <section class="border-t border-gray-200/80 bg-white py-12 sm:py-16">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
                        <div>
                            <span class="text-xs font-extrabold uppercase tracking-widest text-[#00875a] block mb-1">
                                Rekomendasi Pilihan Lain
                            </span>
                            <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                                Program Keahlian Lainnya di SMK Amaliah
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Kenali pilihan jurusan lainnya langsung tanpa harus kembali ke halaman awal.
                            </p>
                        </div>
                        <a href="{{ route('public.majors.index') }}"
                            class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-[#00875a] hover:underline whitespace-nowrap">
                            Lihat Semua 9 Jurusan <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($otherMajors->take(3) as $cardMajor)
                            <a href="{{ route('public.majors.show', $cardMajor) }}"
                                class="group bg-white rounded-2xl border border-gray-200 p-5 shadow-2xs hover:shadow-md hover:border-[#00875a] hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center p-2 shadow-2xs group-hover:scale-105 transition-transform">
                                            @if($cardMajor->logo)
                                                <img src="{{ img_url($cardMajor->logo, 100, 100) }}" alt="{{ $cardMajor->abbreviation }}" class="w-8 h-8 object-contain">
                                            @else
                                                <i class="fa-solid fa-graduation-cap text-[#00875a]"></i>
                                            @endif
                                        </div>
                                        <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500 px-2.5 py-1 rounded-full bg-gray-100">
                                            {{ $cardMajor->tag ?: 'SMK Amaliah' }}
                                        </span>
                                    </div>
                                    <h4 class="text-lg font-extrabold text-gray-900 group-hover:text-[#00875a] transition-colors leading-snug">
                                        {{ $cardMajor->abbreviation ? $cardMajor->abbreviation . ' — ' : '' }}{{ $cardMajor->name }}
                                    </h4>
                                    <p class="mt-2 text-xs text-gray-600 line-clamp-2 leading-relaxed">
                                        {{ Str::limit(strip_tags($cardMajor->description), 100, '...') }}
                                    </p>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-xs font-bold text-[#00875a]">
                                    <span>Pelajari Profil Jurusan</span>
                                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

    </div>

    {{-- Script Subnav Smooth Scroll & Active Indicator --}}
    <style>
        @media (min-width: 1024px) {
            .major-hero-grid {
                display: grid !important;
                grid-template-columns: repeat(12, minmax(0, 1fr)) !important;
                gap: 2.5rem !important;
                align-items: center !important;
            }
            .major-hero-left {
                grid-column: span 7 / span 7 !important;
                min-width: 0 !important;
            }
            .major-hero-right {
                grid-column: span 5 / span 5 !important;
                min-width: 0 !important;
            }
            .major-main-grid {
                display: grid !important;
                grid-template-columns: repeat(12, minmax(0, 1fr)) !important;
                gap: 2.5rem !important;
                align-items: start !important;
            }
            .major-content-col {
                grid-column: span 8 / span 8 !important;
                min-width: 0 !important;
                width: 100% !important;
            }
            .major-sidebar-col {
                grid-column: span 4 / span 4 !important;
                min-width: 0 !important;
                width: 100% !important;
            }
        }
        @media (max-width: 1023px) {
            .major-hero-grid,
            .major-main-grid {
                display: flex !important;
                flex-direction: column !important;
                gap: 2rem !important;
            }
            .major-hero-left,
            .major-hero-right,
            .major-content-col,
            .major-sidebar-col {
                width: 100% !important;
                min-width: 0 !important;
            }
        }

        .subnav-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            white-space: nowrap;
            color: #4b5563;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }
        .subnav-btn:hover {
            background: #f9fafb;
            color: #111827;
            border-color: #d1d5db;
        }
        .subnav-btn.active {
            background: #f0fdf4;
            color: #00875a;
            border-color: #86efac;
            font-weight: 700;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const navLinks = document.querySelectorAll('.subnav-btn');
            const sections = document.querySelectorAll('article[id], div[id="kenali-kakom"]');

            function onScroll() {
                const scrollPos = window.scrollY + 220;
                sections.forEach(sec => {
                    const top = sec.offsetTop;
                    const height = sec.offsetHeight;
                    const id = sec.getAttribute('id');
                    if (scrollPos >= top && scrollPos < top + height) {
                        navLinks.forEach(link => {
                            if (link.getAttribute('href') === '#' + id) {
                                link.classList.add('active');
                            } else {
                                link.classList.remove('active');
                            }
                        });
                    }
                });
            }

            window.addEventListener('scroll', onScroll, { passive: true });
        });
    </script>

@endsection
