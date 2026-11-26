<?php

namespace App\Http\Controllers\PublicPage;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\Partner;
use App\Models\SchoolDownload;
use App\Services\DownloadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PublicDownloadController extends Controller
{
    public function __construct(private DownloadService $files) {}

    public function index(Request $request): View
    {
        $categories = SchoolDownload::categories();
        $category = $request->query('kategori');
        $category = is_string($category) && isset($categories[$category]) ? $category : null;
        $majors = Major::orderBy('name')->get(['id', 'name']);
        $value = $request->query('jurusan');
        $majorFilter = is_string($value) && ($value === 'umum' || (ctype_digit($value) && $majors->contains('id', (int) $value)))
            ? ($value === 'umum' ? 'umum' : (string) (int) $value) : null;
        if ($majorFilter !== null) {
            $category = 'pkl';
        }
        $downloads = SchoolDownload::with('major:id,name')->published()->forMajor($majorFilter)
            ->when($category, fn ($query) => $query->where('category', $category))
            ->orderBy('sort_order')->orderBy('id')->get()
            ->filter(fn (SchoolDownload $logo) => $this->files->exists($logo))
            ->map(fn (SchoolDownload $logo) => [
                'name' => $logo->name, 'description' => $logo->description,
                'category_label' => $logo->category_label, 'document_icon' => $logo->document_icon,
                'major_label' => $logo->major_label,
                'dark_preview' => $logo->dark_preview,
                'preview' => $logo->is_image ? route('public.downloads.preview', $logo) : null,
                'formats' => [[
                    'label' => strtoupper($logo->extension),
                    'url' => route('public.downloads.file', $logo),
                    'size' => $logo->formatted_size, 'dimensions' => $logo->dimensions,
                ]],
            ])->values()->all();

        // Integrasikan logo Mitra Industri secara otomatis saat melihat Semua Berkas atau kategori Logo Perusahaan
        if (Schema::hasTable('partners') && $majorFilter === null && ($category === null || $category === 'company_logo')) {
            $partnerDownloads = Partner::whereNotNull('logo')
                ->where('logo', '!=', '')
                ->orderBy('name')
                ->get()
                ->filter(fn (Partner $partner) => $partner->hasValidLogo())
                ->map(fn (Partner $partner) => [
                    'name' => 'Logo ' . $partner->name,
                    'description' => $partner->description
                        ? \Illuminate\Support\Str::limit(strip_tags($partner->description), 160)
                        : ('Logo resmi mitra industri ' . $partner->name . ($partner->sector ? ' (' . $partner->sector . ')' : '')),
                    'category_label' => 'Logo Perusahaan',
                    'document_icon' => 'fa-file-image',
                    'major_label' => null,
                    'dark_preview' => false,
                    'preview' => img_url($partner->logo, 400, 225),
                    'formats' => [[
                        'label' => strtoupper($partner->logo_extension),
                        'url' => route('public.partners.download-logo', $partner),
                        'size' => $partner->formatted_logo_size,
                        'dimensions' => $partner->logo_dimensions,
                    ]],
                ])->values()->all();

            $downloads = array_merge($downloads, $partnerDownloads);
        }

        return view('PublicSide.downloads.index', compact('downloads', 'categories', 'category', 'majors', 'majorFilter'));
    }

    public function download(SchoolDownload $download): BinaryFileResponse
    {
        abort_unless($download->is_active, 404);

        return response()->download($this->files->path($download), $download->original_name, [
            'Content-Type' => $download->mime_type, 'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    public function preview(SchoolDownload $download): BinaryFileResponse
    {
        abort_unless($download->is_active && $download->is_image, 404);

        return response()->file($this->files->path($download), [
            'Content-Type' => $download->mime_type, 'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
