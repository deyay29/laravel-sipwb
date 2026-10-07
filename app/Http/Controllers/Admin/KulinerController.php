<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kuliner;

class KulinerController extends Controller
{
    public function index()
    {
        $kuliners = Kuliner::latest()->paginate(10);
        return view('admin.kuliner.index', compact('kuliners'));
    }

    public function create()
    {
        return view('admin.kuliner.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'image_url' => 'nullable|url',
            'location' => 'nullable|string'
        ]);

        Kuliner::create($validated);
        return redirect()->route('admin.kuliner.index')->with('success', 'Kuliner created successfully.');
    }

    public function show(string $id) { }

    public function edit(string $id)
    {
        $kuliner = Kuliner::findOrFail($id);
        return view('admin.kuliner.form', compact('kuliner'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'image_url' => 'nullable|url',
            'location' => 'nullable|string'
        ]);

        $kuliner = Kuliner::findOrFail($id);
        $kuliner->update($validated);
        return redirect()->route('admin.kuliner.index')->with('success', 'Kuliner updated successfully.');
    }

    public function destroy(string $id)
    {
        Kuliner::findOrFail($id)->delete();
        return redirect()->route('admin.kuliner.index')->with('success', 'Kuliner deleted successfully.');
    }
}
