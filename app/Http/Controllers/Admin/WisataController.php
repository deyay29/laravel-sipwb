<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wisata;

class WisataController extends Controller
{
    public function index()
    {
        $wisatas = Wisata::latest()->paginate(10);
        return view('admin.wisata.index', compact('wisatas'));
    }

    public function create()
    {
        return view('admin.wisata.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'image_url' => 'nullable|url',
            'location' => 'nullable|string'
        ]);

        Wisata::create($validated);
        return redirect()->route('admin.wisata.index')->with('success', 'Wisata created successfully.');
    }

    public function show(string $id) { }

    public function edit(string $id)
    {
        $wisata = Wisata::findOrFail($id);
        return view('admin.wisata.form', compact('wisata'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'image_url' => 'nullable|url',
            'location' => 'nullable|string'
        ]);

        $wisata = Wisata::findOrFail($id);
        $wisata->update($validated);
        return redirect()->route('admin.wisata.index')->with('success', 'Wisata updated successfully.');
    }

    public function destroy(string $id)
    {
        Wisata::findOrFail($id)->delete();
        return redirect()->route('admin.wisata.index')->with('success', 'Wisata deleted successfully.');
    }
}
