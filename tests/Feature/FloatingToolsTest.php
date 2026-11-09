<?php

namespace Tests\Feature;

use Tests\TestCase;

class FloatingToolsTest extends TestCase
{
    public function test_floating_tools_are_flat_and_keep_their_actions(): void
    {
        $view = $this->view('partials.floating-tools', [
            'whatsappNumber' => '6285649011449',
            'whatsappMessage' => 'Halo sekolah',
        ]);

        $view->assertSee('fl-float-btn--ai')
            ->assertSee('fl-float-btn--whatsapp')
            ->assertSee("new CustomEvent('open-ai-chat')", false)
            ->assertSee('https://wa.me/6285649011449?text=Halo+sekolah', false)
            ->assertSee(route('public.traffic.index'))
            ->assertSee('box-shadow: none;', false)
            ->assertSee('filter: none;', false)
            ->assertSee(':focus-visible', false)
            ->assertDontSee('linear-gradient', false)
            ->assertDontSee('drop-shadow', false)
            ->assertDontSee('flHalo', false)
            ->assertDontSee('translateY', false);
    }
}
