<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WisataController extends Controller
{
    public function index(): View
    {
        $wisatas = Wisata::with('user')->latest()->paginate(10);

        return view('admin.wisata.index', compact('wisatas'));
    }

    public function create(): View
    {
        return view('admin.wisata.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        Wisata::create($validated + ['id_user' => $request->user()->id]);

        return redirect()
            ->route('admin.wisata.index')
            ->with('success', 'Wisata berhasil ditambahkan.');
    }

    public function edit(Wisata $wisata): View
    {
        return view('admin.wisata.form', compact('wisata'));
    }

    public function update(Request $request, Wisata $wisata): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $validated['id_user'] = $request->user()->id;

        $wisata->update($validated);

        return redirect()
            ->route('admin.wisata.index')
            ->with('success', 'Wisata berhasil diperbarui.');
    }

    public function destroy(Wisata $wisata): RedirectResponse
    {
        $wisata->delete();

        return redirect()
            ->route('admin.wisata.index')
            ->with('success', 'Wisata berhasil dihapus.');
    }

    /**
     * Aturan validasi, memakai nama kolom Indonesia.
     */
    protected function rules(): array
    {
        return [
            'nama_wisata' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'harga_tiket' => ['nullable', 'integer', 'min:0'],
            'jam_operasional' => ['nullable', 'string', 'max:100'],
            'gambar' => ['nullable', 'string', 'max:255'],
            'quiz_tersedia' => ['nullable', 'boolean'],
        ];
    }
}
