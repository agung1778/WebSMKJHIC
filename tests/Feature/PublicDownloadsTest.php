<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\InteractsWithLogoDownloads;
use Tests\TestCase;

class PublicDownloadsTest extends TestCase
{
    use InteractsWithLogoDownloads;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpLogoDownloads();
    }

    public function test_guests_only_see_published_uploaded_logos_in_order(): void
    {
        $later = $this->uploadedLogo(['name' => 'Logo Kedua', 'sort_order' => 20]);
        $first = $this->uploadedLogo(['name' => 'Logo Pertama', 'sort_order' => 1, 'dark_preview' => true]);
        $hidden = $this->uploadedLogo(['name' => 'Logo Rahasia', 'is_active' => false]);

        $this->get(route('public.downloads.index'))->assertOk()
            ->assertSeeInOrder(['Logo Pertama', 'Logo Kedua'])
            ->assertSee(route('public.downloads.file', $first))
            ->assertSee(route('public.downloads.preview', $later))
            ->assertSee('1200 × 1200 px')
            ->assertSee('download-preview--dark')
            ->assertDontSee($hidden->name)
            ->assertDontSee('assets/logo/amaliah.png');
        $this->assertGuest();
    }

    public function test_download_and_preview_use_the_exact_original_bytes(): void
    {
        $file = UploadedFile::fake()->image('logo-berkualitas.png', 2048, 2048);
        $original = file_get_contents($file->getRealPath());
        $logo = $this->uploadedLogo([], $file);

        $download = $this->get(route('public.downloads.logo', $logo));
        $download->assertOk()->assertDownload('logo-berkualitas.png')
            ->assertHeader('Content-Type', 'image/png')
            ->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->assertSame($original, file_get_contents($download->baseResponse->getFile()->getPathname()));

        $preview = $this->get(route('public.downloads.preview', $logo))->assertOk();
        $this->assertSame($original, file_get_contents($preview->baseResponse->getFile()->getPathname()));
        Storage::disk('public')->assertMissing($logo->file_path);
    }

    public function test_pdf_can_be_downloaded_but_not_rendered_inline(): void
    {
        $logo = $this->uploadedLogo([], UploadedFile::fake()->createWithContent('logo-vektor.pdf', "%PDF-1.4\n1 0 obj\n<<>>\nendobj\n%%EOF"));
        $this->get(route('public.downloads.index'))->assertOk()->assertSee('Dokumen PDF');
        $this->get(route('public.downloads.logo', $logo))->assertOk()->assertDownload('logo-vektor.pdf')
            ->assertHeader('Content-Type', 'application/pdf');
        $this->get(route('public.downloads.preview', $logo))->assertNotFound();
    }

    public function test_hidden_missing_deleted_and_unknown_logos_return_404(): void
    {
        $hidden = $this->uploadedLogo(['is_active' => false]);
        foreach (['public.downloads.logo', 'public.downloads.preview'] as $route) {
            $this->get(route($route, $hidden))->assertNotFound();
            $this->get(route($route, 999999))->assertNotFound();
        }
        $missing = $this->uploadedLogo(['name' => 'File Hilang']);
        Storage::disk('local')->delete($missing->file_path);
        $this->get(route('public.downloads.index'))->assertOk()->assertDontSee('File Hilang');
        $this->get(route('public.downloads.logo', $missing))->assertNotFound();
        $this->get('/unduhan/logo/%2e%2e%2f.env/download')->assertNotFound();
        $this->get('/unduhan/logo/amaliah/png')->assertNotFound();
    }

    public function test_empty_catalog_does_not_fall_back_to_bundled_logos(): void
    {
        $this->get(route('public.downloads.index'))->assertOk()
            ->assertSee('Berkas belum tersedia')
            ->assertSee('role="status"', false)
            ->assertDontSee('class="download-link"', false);
    }

    public function test_public_navigation_and_footer_link_to_downloads(): void
    {
        $html = $this->get('/kebijakan-privasi')->assertOk()->getContent();
        $this->assertSame(3, substr_count($html, 'href="'.route('public.downloads.index').'"'));
        $this->assertStringContainsString('public-mobile-download', $html);
    }
}
