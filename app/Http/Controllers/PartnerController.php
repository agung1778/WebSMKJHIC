<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partners = Partner::all();
        return view('admin.tables.partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tables.partners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'string',
            'logo' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
            'sector' => 'string|max:255',
            'city' => 'string|max:255',
            'company_contact' => 'nullable|string|max:255',
            'partnership_date' => 'required|date',
        ], [
            'name.required' => 'Nama mitra harus diisi.',
            'description.required' => 'Deskripsi harus diisi.',
            'logo.required' => 'Logo harus diunggah.',
            'logo.image' => 'File harus berupa gambar.',
            'logo.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, jpg, atau svg.',
            'logo.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            'sector.required' => 'Sektor harus diisi.',
            'city.required' => 'Kota harus diisi.',
            'partnership_date.required' => 'Tanggal kerja sama harus diisi.',
            'partnership_date.date' => 'Tanggal kerja sama harus dalam format tanggal yang valid.',
        ]);

        $validatedData['publisher'] = Auth::user()->name;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('partner_logos', 'public');
            $validatedData['logo'] = $logoPath;
        }

        Partner::create($validatedData);

        return redirect()->route('admin.partners.index')->with('success', 'Mitra industri berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Partner $partner)
    {
        return view('admin.tables.partners.show', compact('partner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partner $partner)
    {
        return view('admin.tables.partners.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partner $partner)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'sector' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'company_contact' => 'nullable|string|max:255',
            'partnership_date' => 'required|date',
        ], [
            'name.required' => 'Nama mitra harus diisi.',
            'description.required' => 'Deskripsi harus diisi.',
            'logo.image' => 'File harus berupa gambar.',
            'logo.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, jpg, atau svg.',
            'logo.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            'sector.required' => 'Sektor harus diisi.',
            'city.required' => 'Kota harus diisi.',
            'partnership_date.required' => 'Tanggal kerja sama harus diisi.',
            'partnership_date.date' => 'Tanggal kerja sama harus dalam format tanggal yang valid.',
        ]);

        $validatedData['publisher'] = Auth::user()->name;

        if ($request->hasFile('logo')) {
            if ($partner->logo) {
                Storage::disk('public')->delete($partner->logo);
            }
            $logoPath = $request->file('logo')->store('partner_logos', 'public');
            $validatedData['logo'] = $logoPath;
        }

        $partner->update($validatedData);

        return redirect()->route('admin.partners.index')->with('success', 'Mitra industri berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partner $partner)
    {
        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Mitra industri berhasil dihapus!');
    }
}