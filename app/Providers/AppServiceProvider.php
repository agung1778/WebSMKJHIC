<?php

namespace App\Providers;

use App\Models\SpmbSetting;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::anonymousComponentNamespace('admin.components', 'admin-components');

        // Data untuk popup pengumuman PPDB di semua halaman publik.
        // Null = tidak aktif atau tidak ada data berstatus "Buka", jadi popup tidak dirender.
        View::composer('layouts.public-app', function ($view) {
            $setting = SpmbSetting::active();
            $show = $setting && $setting->status === 'Buka' && $setting->popup_enabled !== false;

            $view->with('ppdbSetting', $show ? $setting : null);
        });

        // Hosting di belakang proxy/cPanel sering terdeteksi sebagai HTTP,
        // sehingga route()/url() memakai http:// dan POST form diarahkan 301 -> GET -> 405.
        // Paksa HTTPS untuk domain asli; biarkan localhost/127.0.0.1 memakai http.
        $host = request()->getHost();
        if (! in_array($host, ['127.0.0.1', 'localhost'], true)) {
            URL::forceScheme('https');
        }
    }
}
