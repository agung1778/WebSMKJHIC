<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Navigation;

class NavigationSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'title' => 'Info SPMB',
                'url' => '/info-spmb', 
                'position' => 'top_bar',
                'type' => 'link',
                'target' => '_self',
                'order' => 1,
            ],
            [
                'title' => 'Info BKK',
                'url' => 'https://www.instagram.com/bkksmkamaliah/',
                'position' => 'top_bar',
                'type' => 'link',
                'target' => '_blank',
                'order' => 2,
            ],
            [
                'title' => 'E-Learning',
                'url' => 'https://lms.smkamaliah.sch.id',
                'position' => 'top_bar',
                'type' => 'link',
                'target' => '_blank',
                'order' => 3,
            ],
            [
                'title' => 'AM Movie',
                'url' => 'https://nonton.smkamaliah.sch.id/',
                'position' => 'top_bar',
                'type' => 'link',
                'target' => '_blank',
                'order' => 4,
            ],
            [
                'title' => 'Hubungi Kami',
                'url' => 'https://wa.me/6285649011449',
                'position' => 'top_bar',
                'type' => 'button', // Ini akan kita render jadi tombol hitam
                'target' => '_blank',
                'order' => 5,
            ],
        ];

        foreach ($menus as $menu) {
            Navigation::create($menu);
        }
    }
}
