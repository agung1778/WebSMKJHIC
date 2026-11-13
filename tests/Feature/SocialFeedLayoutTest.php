<?php

namespace Tests\Feature;

use Tests\TestCase;

class SocialFeedLayoutTest extends TestCase
{
    public function test_homepage_places_instagram_and_tiktok_in_responsive_columns(): void
    {
        $template = file_get_contents(resource_path('views/welcome.blade.php'));

        $this->assertStringContainsString('grid grid-cols-1 lg:grid-cols-2 gap-10 items-start', $template);
        $this->assertStringContainsString('data-video-id="{{ $videoId }}"', $template);
        $this->assertStringContainsString('.tiktok-card {', $template);
        $this->assertStringContainsString('flex-direction: column;', $template);
        $this->assertStringNotContainsString('data-embed-type="creator"', $template);
        $this->assertStringContainsString('TIKTOK_VIDEO_IDS', file_get_contents(base_path('config/services.php')));
        $this->assertStringContainsString('https://www.tiktok.com/embed.js', $template);
        $this->assertStringContainsString("{{ '@' . config('services.tiktok.username') }}", $template);
    }
}