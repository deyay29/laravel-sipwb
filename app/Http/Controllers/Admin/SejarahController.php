<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sejarah;

class SejarahController extends Controller
{
    public function index()
    {
        $sejarahs = Sejarah::latest()->paginate(10);
        return view('admin.sejarah.index', compact('sejarahs'));
    }

    public function create()
    {
        return view('admin.sejarah.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'image_url' => 'nullable|url'
        ]);

        Sejarah::create($validated);
        return redirect()->route('admin.sejarah.index')->with('success', 'Sejarah created successfully.');
    }

    public function show(string $id) { }

    public function edit(string $id)
    {
        $sejarah = Sejarah::findOrFail($id);
        return view('admin.sejarah.form', compact('sejarah'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'image_url' => 'nullable|url'
        ]);

        $sejarah = Sejarah::findOrFail($id);
        $sejarah->update($validated);
        return redirect()->route('admin.sejarah.index')->with('success', 'Sejarah updated successfully.');
    }

    public function destroy(string $id)
    {
        Sejarah::findOrFail($id)->delete();
        return redirect()->route('admin.sejarah.index')->with('success', 'Sejarah deleted successfully.');
    }
}
