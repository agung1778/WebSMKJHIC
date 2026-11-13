<?php

namespace Tests\Feature;

use Tests\TestCase;

class SocialFeedLayoutTest extends TestCase
{
    public function test_homepage_places_instagram_and_tiktok_in_responsive_columns(): void
    {
        $template = file_get_contents(resource_path('views/welcome.blade.php'));

        $this->assertStringContainsString('grid grid-cols-1 lg:grid-cols-2 gap-10 items-start', $template);
        $this->assertStringContainsString('class="tiktok-head"', $template);
        $this->assertStringContainsString('class="tiktok-grid"', $template);
        $this->assertStringContainsString('class="tiktok-video-link"', $template);
        $this->assertStringContainsString("'assets/image/DroneView.jpg'", $template);
        $this->assertStringContainsString('Lihat semua video di TikTok', $template);
        $this->assertStringContainsString('.tiktok-card {', $template);
        $this->assertStringContainsString('grid-template-columns: repeat(3, minmax(0, 1fr));', $template);
        $this->assertStringNotContainsString('data-embed-type="creator"', $template);
        $this->assertStringNotContainsString('https://www.tiktok.com/embed.js', $template);
        $this->assertStringContainsString('TIKTOK_VIDEO_IDS', file_get_contents(base_path('config/services.php')));
        $this->assertStringContainsString("{{ '@' . config('services.tiktok.username') }}", $template);
    }
}