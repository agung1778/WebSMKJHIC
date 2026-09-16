<?php

namespace App\Http\Controllers;

use App\Models\SchoolLeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolLeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaders = SchoolLeader::orderBy('order_column')->orderBy('id')->get();
        return view('admin.tables.leader.index', compact('leaders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tables.leader.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateLeader($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('uploads/leaders', 'public');
        }

        SchoolLeader::create($validated);

        return redirect()->route('admin.leaders.index')->with('success', 'Data pemimpin sekolah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolLeader $leader)
    {
        return view('admin.tables.leader.show', compact('leader'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolLeader $leader)
    {
        return view('admin.tables.leader.edit', compact('leader'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolLeader $leader)
    {
        $validated = $this->validateLeader($request);

        if ($request->hasFile('image')) {
            if ($leader->image && !str_starts_with($leader->image, 'assets/')) {
                Storage::disk('public')->delete($leader->image);
            }
            $validated['image'] = $request->file('image')->store('uploads/leaders', 'public');
        }

        $leader->update($validated);

        return redirect()->route('admin.leaders.index')->with('success', 'Data pemimpin sekolah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolLeader $leader)
    {
        if ($leader->image && !str_starts_with($leader->image, 'assets/')) {
            Storage::disk('public')->delete($leader->image);
        }

        $leader->delete();

        return redirect()->route('admin.leaders.index')->with('success', 'Data pemimpin sekolah berhasil dihapus.');
    }

    /**
     * Validasi data pemimpin sekolah.
     */
    private function validateLeader(Request $request): array
    {
        return $request->validate([
            'school'        => ['required', 'in:Amaliah 1,Amaliah 2,Amaliah 1 & 2'],
            'name'          => ['required', 'string', 'max:255'],
            'position'      => ['required', 'string', 'max:255'],
            'quote'         => ['nullable', 'string', 'max:1000'],
            'facebook_url'  => ['nullable', 'url', 'max:500'],
            'instagram_url' => ['nullable', 'url', 'max:500'],
            'linkedin_url'  => ['nullable', 'url', 'max:500'],
            'image'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5048'],
            'order_column'  => ['nullable', 'integer', 'min:0'],
            'is_active'     => ['nullable', 'boolean'],
        ], [
            'school.required'    => 'Sekolah harus dipilih.',
            'school.in'          => 'Sekolah yang dipilih tidak valid.',
            'name.required'      => 'Nama lengkap wajib diisi.',
            'position.required'  => 'Jabatan wajib diisi.',
            'facebook_url.url'   => 'URL Facebook tidak valid.',
            'instagram_url.url'  => 'URL Instagram tidak valid.',
            'linkedin_url.url'   => 'URL LinkedIn tidak valid.',
            'image.image'        => 'File harus berupa gambar.',
            'image.mimes'        => 'Format yang diizinkan: jpeg, png, jpg, webp.',
            'image.max'          => 'Ukuran gambar maksimal 5MB.',
        ]);
    }
}