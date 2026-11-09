<?php

namespace Tests\Concerns;

use App\Models\SchoolDownload as LogoDownload;
use App\Models\User;
use App\Services\DownloadService as LogoDownloadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait InteractsWithLogoDownloads
{
    protected function setUpLogoDownloads(): void
    {
        config([
            'app.key' => 'base64:'.base64_encode(str_repeat('t', 32)),
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'database.connections.sqlite.foreign_key_constraints' => true,
        ]);
        DB::purge();
        foreach ([
            '0001_01_01_000000_create_users_table.php',
            '2026_02_27_143752_add_role_to_users_table.php',
            '2025_09_10_070433_create_major_table.php',
            '2026_02_28_062855_create_spmb_settings_table.php',
            '2026_10_05_000001_add_crud_fields_to_spmb_settings_table.php',
            '2026_10_05_000002_add_popup_fields_to_spmb_settings_table.php',
            '2026_10_05_000003_add_popup_layout_fields_to_spmb_settings_table.php',
            '2026_11_05_000001_create_logo_downloads_table.php',
            '2026_11_05_000002_add_category_to_logo_downloads_table.php',
            '2026_11_05_000003_expand_download_mime_type.php',
            '2026_11_06_000001_add_major_to_school_downloads.php',
        ] as $file) {
            Artisan::call('migrate', ['--force' => true, '--path' => 'database/migrations/'.$file]);
        }
        Storage::fake('local');
        Storage::fake('public');
    }

    protected function logoAttributes(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Logo Resolusi Tinggi', 'description' => 'File asli dari sekolah.', 'category' => 'logo',
            'is_active' => true, 'dark_preview' => false, 'sort_order' => 0,
        ], $overrides);
    }

    protected function uploadedLogo(array $overrides = [], ?UploadedFile $file = null): LogoDownload
    {
        $user = User::factory()->create(['role' => 'admin']);

        return app(LogoDownloadService::class)->create(
            $this->logoAttributes($overrides), $file ?? UploadedFile::fake()->image('logo-asli.png', 1200, 1200), $user->id
        );
    }

    protected function docxUpload(array $extraEntries = [], ?string $document = null): UploadedFile
    {
        $path = tempnam(storage_path('framework/testing'), 'docx-');
        try {
            $zip = new \ZipArchive;
            $zip->open($path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            $zip->addFromString('[Content_Types].xml', '<?xml version="1.0"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/></Types>');
            $zip->addFromString('_rels/.rels', '<?xml version="1.0"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/></Relationships>');
            $zip->addFromString('word/document.xml', $document ?? '<?xml version="1.0"?><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body><w:p><w:r><w:t>Contoh Laporan PKL</w:t></w:r></w:p></w:body></w:document>');
            foreach ($extraEntries as $name => $content) {
                $zip->addFromString($name, $content);
            }
            $zip->close();

            return UploadedFile::fake()->createWithContent('contoh-laporan-pkl.docx', file_get_contents($path));
        } finally {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }
}
