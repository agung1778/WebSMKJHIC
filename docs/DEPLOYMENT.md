# Deploy ke hosting / cPanel

Script ini untuk instalasi Laravel yang sudah berjalan. PHP CLI, Git, `realpath`, dependency Composer, dan `.env` server harus sudah tersedia. Jalankan dari Terminal/SSH hosting, bukan dari browser. Jangan unggah seluruh folder project ke folder publik.

Penting: `npm run build` tidak membangun atau menyalin `public/admin/admin-theme.css`; tema dashboard adalah aset statis. Jika Document Root berbeda dari folder `project/public`, deploy aset dengan script di bawah agar CSS dan JavaScript admin sama dengan versi aplikasi. Aplikasi juga menyediakan fallback route untuk tema jika file statis belum tersalin, tetapi deploy aset tetap diperlukan agar semua aset tetap sinkron.

## Pengaturan sekali

Di folder project tempat `artisan` berada:

```bash
git pull --ff-only origin master
cp scripts/deploy.env.example .deploy.env
```

Edit `.deploy.env`. Isi `DEPLOY_PUBLIC_PATH` dengan **Document Root domain** dari cPanel → Domains. Contoh `/home/akun/public_html`, atau folder khusus domain jika berbeda. Gunakan path absolut, bukan URL. Jangan arahkan ke folder project yang berisi `.env`.

Jika Document Root sudah langsung menunjuk ke `public` dalam project, gunakan path tersebut; penyalinan aset akan dilewati karena sumber dan tujuan sama.

```bash
bash scripts/deploy.sh --dry-run
bash scripts/deploy.sh
```

File `.deploy.env` tidak ikut Git. Jika PHP CLI default berbeda dengan versi aplikasi, atur `DEPLOY_PHP_BIN` ke executable PHP hosting yang sesuai.

## Pembaruan berikutnya

```bash
bash scripts/deploy.sh
```

Script menjalankan `git pull --ff-only`, menyalin aset statis yang terdaftar di Git ke document root, lalu menjalankan `route:clear`, `view:clear`, `config:cache`, dan `view:cache`. Cukup satu perintah; bukan webhook otomatis setiap push. Jika `composer.lock` atau kebutuhan build berubah pada pembaruan lain, dependency/build tetap perlu ditangani sesuai perubahan tersebut.

Jika sudah melakukan `git pull`:

```bash
bash scripts/deploy.sh --skip-pull
```

Tanpa file konfigurasi, lokasi juga dapat diberikan langsung:

```bash
bash scripts/deploy.sh --public-path /home/akun/public_html
```

## Batas keamanan

- Hanya menyalin aset statis dari `public/admin`, `public/assets`, `public/build`, `public/css`, `public/js`, serta favicon/robots/manifest yang terdaftar di Git.
- Tidak menyalin `.env`, PHP, `index.php`, `.htaccess`, `storage`, atau file unggahan; tidak menghapus file tujuan.
- File aset dengan nama yang sama diperbarui. Folder tujuan harus benar agar tidak menimpa aset website lain.
- Tidak menjalankan migrasi, seeder, `key:generate`, `composer install`, atau `npm build` secara otomatis.
- Perubahan lokal dan pergantian branch tidak dibuang/dipaksakan; target symlink di dalam document root ditolak.
- Jika proses terputus, periksa apakah deploy masih berjalan sebelum menghapus folder `.deploy.lock`. Perbaiki kegagalan yang dilaporkan, lalu jalankan ulang. Penyalinan file bersifat atomik per file, bukan rollback seluruh deployment.
- `--dry-run` memakai checkout saat ini, tidak mengambil versi remote atau mengubah file/cache. `--skip-cache` tersedia untuk sinkronisasi aset saja.

Setelah berhasil, pastikan `https://smkamaliah.sch.id/admin/admin-theme.css` menampilkan CSS (HTTP 200), bukan 404. Bersihkan cache CDN/hosting bila ada, kemudian hard refresh browser.

## Aktivasi CRUD unduhan berkas

Pada pembaruan yang menambahkan modul Unduhan Berkas, jalankan migration tabel dan kategorinya dari folder project. Script deploy tidak menjalankan migration secara otomatis:

```bash
php artisan migrate --path=database/migrations/2026_11_05_000001_create_logo_downloads_table.php --force
php artisan migrate --path=database/migrations/2026_11_05_000002_add_category_to_logo_downloads_table.php --force
php artisan migrate --path=database/migrations/2026_11_05_000003_expand_download_mime_type.php --force
php artisan migrate --path=database/migrations/2026_11_06_000001_add_major_to_school_downloads.php --force
```

Menu admin: **Website → Unduhan Berkas** (`/admin/downloads`). Kategori yang tersedia adalah **Logo Sekolah**, **Logo Perusahaan**, dan **Contoh Laporan PKL**. Halaman publik `/unduhan` hanya menampilkan berkas aktif dan menyediakan filter kategori. Admin dapat menambah, mengubah file/informasi/kategori, mengatur urutan/latar pratinjau logo, menyembunyikan, dan menghapus berkas. Logo perusahaan memakai format yang sama dengan logo sekolah (PNG, JPG/JPEG, WebP, atau PDF). Ganti kategori dengan format file yang tidak sesuai harus disertai file pengganti.

Laporan PKL dapat dikaitkan ke satu jurusan melalui pilihan dari data menu **Jurusan** yang sudah ada. Kosongkan pilihan untuk **Umum / Semua Jurusan**. Laporan lama tetap menjadi contoh umum sampai admin memilih jurusannya. Berkas kategori Logo tidak menggunakan jurusan.

Di halaman publik, pilih kategori **Contoh Laporan PKL**, lalu filter **Semua Jurusan**, **Umum / Lintas Jurusan**, atau jurusan tertentu. Filter jurusan bersifat tepat: memilih satu jurusan hanya menampilkan laporan jurusan tersebut; contoh umum dapat dilihat melalui pilihan Umum. Filter serupa tersedia di daftar admin. Mengganti jurusan tidak mengubah file. Jika data jurusan dihapus, laporan dan file tetap ada dengan klasifikasi Umum; tidak dihapus mengikuti jurusan.

File baru disimpan privat di `storage/app/downloads`, bukan di document root. File logo sebelumnya di `storage/app/logo-downloads` tetap didukung. Pratinjau dan unduhan dilayani melalui controller; tidak memerlukan `storage:link`. Sertakan kedua folder dalam backup data server dan jangan hapus saat deploy. Tidak ada kompresi, resize, atau konversi file. Tabel `logo_downloads` sengaja dipertahankan agar data lama tidak dipindah/dihapus; migration kategori memberi nilai `logo` kepada data sebelumnya. URL admin/logo lama tetap tersedia sebagai alias.

Format logo: PNG, JPG/JPEG, WebP, PDF. Format laporan PKL: PDF dan DOCX tanpa macro. Maksimal 10 MB per file. SVG, DOC/DOCM, macro, dan objek aktif/embedded tidak diterima. DOCX membutuhkan ekstensi PHP ZIP dan DOM serta diperiksa struktur paket/XML-nya tanpa mengekstrak atau menjalankan dokumen. PDF/DOCX hanya diunduh sebagai lampiran, tidak dirender inline. Gunakan contoh laporan yang tidak memuat data pribadi. Pastikan batas PHP hosting (`upload_max_filesize`, `post_max_size`) mengizinkan ukuran unggahan yang dipakai, misalnya `10M` dan `12M`.

## Tes script deploy

```bash
bash tests/deploy-smoke.sh
```

Tes menggunakan repository Git lokal, domain tiruan, dan PHP tiruan di folder sementara; tidak menyentuh server atau database aplikasi. Hasil fixture disimpan untuk inspeksi. Tes symlink dilewati jika sistem tidak mendukung pembuatannya.
