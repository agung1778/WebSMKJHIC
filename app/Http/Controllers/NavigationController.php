<?php

namespace App\Http\Controllers;

use App\Models\Navigation;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function index()
    {
        // Mengurutkan berdasarkan posisi lalu berdasarkan urutan angka
        $navigations = Navigation::orderBy('position')->orderBy('order')->get();
        return view('admin.navigations.index', compact('navigations'));
    }

    public function create()
    {
        return view('admin.navigations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'position' => 'required|string',
            'type' => 'required|string',
            'target' => 'required|string',
            'order' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

        Navigation::create($validated);
        return redirect()->route('admin.navigations.index')->with('success', 'Menu navigasi berhasil ditambahkan!');
    }

    public function edit(Navigation $navigation)
    {
        return view('admin.navigations.edit', compact('navigation'));
    }

    public function update(Request $request, Navigation $navigation)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'position' => 'required|string',
            'type' => 'required|string',
            'target' => 'required|string',
            'order' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

        $navigation->update($validated);
        return redirect()->route('admin.navigations.index')->with('success', 'Menu navigasi berhasil diperbarui!');
    }

    public function destroy(Navigation $navigation)
    {
        $navigation->delete();
        return redirect()->route('admin.navigations.index')->with('success', 'Menu navigasi berhasil dihapus!');
    }
}
