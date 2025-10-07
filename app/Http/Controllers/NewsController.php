<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua berita dari database, diurutkan dari yang terbaru
        $news = News::latest()->get();
        // Mengubah path view dari 'news.index' menjadi 'admin.tables.news.index'
        return view("admin.tables.news.index", compact("news"));

        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mengubah path view dari 'news.create' menjadi 'admin.tables.news.create'
        return view("admin.tables.news.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ... (Logika validasi dan penyimpanan tidak berubah)
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'description' => 'required|string',
            'date_published' => 'required|date',
        ]);

        $validatedData['publisher'] = Auth::user()->name;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public');
            $validatedData['image'] = $imagePath;
        }

        News::create($validatedData);

        // Mengubah redirect ke rute dengan nama yang benar (admin.news.index)
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $newsItem = News::findOrFail($id);
        // Mengubah path view dari 'news.show' menjadi 'admin.tables.news.show'
        return view('admin.tables.news.show', compact('newsItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $newsItem = News::findOrFail($id);
        // Mengubah path view dari 'news.edit' menjadi 'admin.tables.news.edit'
        return view('admin.tables.news.edit', compact('newsItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // ... (Logika validasi dan update tidak berubah)
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'description' => 'required|string',
            'date_published' => 'required|date',
        ]);
        
        $newsItem = News::findOrFail($id);
        $validatedData['publisher'] = Auth::user()->name;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public');
            $validatedData['image'] = $imagePath;
        }

        $newsItem->update($validatedData);

        // Mengubah redirect ke rute dengan nama yang benar (admin.news.index)
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $newsItem = News::findOrFail($id);
        $newsItem->delete();
        // Mengubah redirect ke rute dengan nama yang benar (admin.news.index)
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }
}