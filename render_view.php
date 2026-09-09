<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$extracurriculars = App\Models\Extracurricular::latest()->get();
$mainImages = App\Models\Image::whereIn('title', ['ExtracurricularImage', 'main'])->get();
$html = view('PublicSide.extracurricular.index', [
    'extracurriculars' => $extracurriculars,
    'mainImages' => $mainImages,
    'totalWajib' => App\Models\Extracurricular::where('type','Wajib')->count(),
    'totalPilihan' => App\Models\Extracurricular::where('type','Pilihan')->count(),
])->render();
file_put_contents('/tmp/extracurricular_rendered.html', $html);
echo "written\n";
