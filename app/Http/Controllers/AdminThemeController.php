<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class AdminThemeController extends Controller
{
    public function __invoke(): Response
    {
        $stylesheet = file_get_contents(public_path('admin/admin-theme.css'));
        abort_if($stylesheet === false, 404);

        return response($stylesheet, 200, [
            'Content-Type' => 'text/css; charset=UTF-8',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
