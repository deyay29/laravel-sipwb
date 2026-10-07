<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::latest()->paginate(10);
        return view('admin.berita.index', compact('beritas'));
    }

    public function create()
    {
        return view('admin.berita.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image_url' => 'nullable|url',
            'published_at' => 'nullable|date'
        ]);

        Berita::create($validated);
        return redirect()->route('admin.berita.index')->with('success', 'Berita created successfully.');
    }

    public function show(string $id) { }

    public function edit(string $id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.form', compact('berita'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image_url' => 'nullable|url',
            'published_at' => 'nullable|date'
        ]);

        $berita = Berita::findOrFail($id);
        $berita->update($validated);
        return redirect()->route('admin.berita.index')->with('success', 'Berita updated successfully.');
    }

    public function destroy(string $id)
    {
        Berita::findOrFail($id)->delete();
        return redirect()->route('admin.berita.index')->with('success', 'Berita deleted successfully.');
    }
}
