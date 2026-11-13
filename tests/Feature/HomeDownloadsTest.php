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
            ->assertSee(route('public.downloads.index', ['kategori' => 'company_logo']), false)
            ->assertSee(route('public.downloads.index', ['kategori' => 'pkl']), false)
            ->assertSee('aria-labelledby="home-downloads-title"', false);
    }

    public function test_homepage_does_not_render_the_downloads_section(): void
    {
        $template = file_get_contents(resource_path('views/welcome.blade.php'));

        $this->assertStringNotContainsString("@include('partials.home-downloads')", $template);
    }

    public function test_home_leader_tabs_rotate_automatically_and_pause_on_hover(): void
    {
        $template = file_get_contents(resource_path('views/welcome.blade.php'));

        $this->assertStringContainsString('setInterval(() => { if (autoRotate) activeTab = activeTab ===', $template);
        $this->assertStringContainsString('@mouseenter="autoRotate = false"', $template);
        $this->assertStringContainsString('@mouseleave="autoRotate = true"', $template);
    }
}
