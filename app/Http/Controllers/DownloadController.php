<?php

namespace App\Http\Controllers;

use App\Http\Requests\DownloadRequest;
use App\Models\Major;
use App\Models\SchoolDownload;
use App\Services\DownloadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadController extends Controller
{
    public function __construct(private DownloadService $files) {}

    public function index(Request $request): View
    {
        $majors = Major::orderBy('name')->get(['id', 'name']);
        $value = $request->query('jurusan');
        $majorFilter = is_string($value) && ($value === 'umum' || (ctype_digit($value) && $majors->contains('id', (int) $value)))
            ? ($value === 'umum' ? 'umum' : (string) (int) $value) : null;
        $downloads = SchoolDownload::with('major:id,name')->forMajor($majorFilter)->orderBy('sort_order')->orderByDesc('id')->get()
            ->each(fn (SchoolDownload $download) => $download->setAttribute('file_available', $this->files->exists($download)));

        return view('admin.downloads.index', compact('downloads', 'majors', 'majorFilter'));
    }

    public function create(): View
    {
        $majors = Major::orderBy('name')->get(['id', 'name']);

        return view('admin.downloads.create', compact('majors'));
    }

    public function store(DownloadRequest $request): RedirectResponse
    {
        $this->files->create($request->safe()->except('file'), $request->file('file'), $request->user()->id);

        return redirect()->route($this->indexRoute())->with('success', 'Berkas berhasil ditambahkan. File asli disimpan tanpa perubahan kualitas.');
    }

    public function edit(SchoolDownload $download): View
    {
        $fileAvailable = $this->files->exists($download);
        $majors = Major::orderBy('name')->get(['id', 'name']);

        return view('admin.downloads.edit', compact('download', 'fileAvailable', 'majors'));
    }

    public function update(DownloadRequest $request, SchoolDownload $download): RedirectResponse
    {
        $this->files->update($download, $request->safe()->except('file'), $request->file('file'));

        return redirect()->route($this->indexRoute())->with('success', 'Berkas berhasil diperbarui.');
    }

    public function destroy(SchoolDownload $download): RedirectResponse
    {
        $this->files->delete($download);

        return redirect()->route($this->indexRoute())->with('success', 'Berkas berhasil dihapus.');
    }

    public function preview(SchoolDownload $download): BinaryFileResponse
    {
        abort_unless($download->is_image, 404);

        return response()->file($this->files->path($download), [
            'Content-Type' => $download->mime_type, 'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    public function download(SchoolDownload $download): BinaryFileResponse
    {
        return response()->download($this->files->path($download), $download->original_name, [
            'Content-Type' => $download->mime_type, 'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    private function indexRoute(): string
    {
        return request()->routeIs('admin.logo-downloads.*') ? 'admin.logo-downloads.index' : 'admin.downloads.index';
    }
}
