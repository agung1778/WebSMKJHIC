<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeDownloadsTest extends TestCase
{
    public function test_home_card_links_to_the_existing_catalog_and_categories(): void
    {
        $view = $this->view('partials.home-downloads');

        $view->assertSee('Pusat Unduhan')->assertSee('Lihat Unduhan')
            ->assertSee(route('public.downloads.index'), false)
            ->assertSee(route('public.downloads.index', ['kategori' => 'logo']), false)
            ->assertSee(route('public.downloads.index', ['kategori' => 'pkl']), false)
            ->assertSee('aria-labelledby="home-downloads-title"', false);
    }

    public function test_home_card_is_between_the_major_section_and_news(): void
    {
        $template = file_get_contents(resource_path('views/welcome.blade.php'));
        $major = strpos($template, 'Major Competency');
        $downloads = strpos($template, "@include('partials.home-downloads')");
        $news = strpos($template, 'Baca Berita Terbaru Kami');

        $this->assertNotFalse($major);
        $this->assertNotFalse($downloads);
        $this->assertNotFalse($news);
        $this->assertTrue($major < $downloads && $downloads < $news);
        $this->assertSame(1, substr_count($template, "@include('partials.home-downloads')"));
    }
}
