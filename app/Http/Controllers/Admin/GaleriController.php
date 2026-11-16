<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GaleriController extends Controller
{
    public function index(): View
    {
        $galeris = Galeri::with('user')->latest()->paginate(12);

        return view('admin.galeri.index', compact('galeris'));
    }

    public function create(): View
    {
        return view('admin.galeri.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        unset($validated['file_gambar']);

        $validated['file_gambar'] = $this->simpanGambar($request);
        $validated['id_user'] = $request->user()->id;

        Galeri::create($validated);

        return redirect()
            ->route('admin.galeri.index')
            ->with('success', 'Foto galeri berhasil ditambahkan.');
    }

    public function edit(Galeri $galeri): View
    {
        return view('admin.galeri.form', compact('galeri'));
    }

    public function update(Request $request, Galeri $galeri): RedirectResponse
    {
        $validated = $request->validate($this->rules(isUpdate: true));

        unset($validated['file_gambar']);

        $validated['id_user'] = $request->user()->id;

        if ($request->hasFile('file_gambar')) {
            $this->hapusFileGambar($galeri->file_gambar);
            $validated['file_gambar'] = $this->simpanGambar($request);
        }

        $galeri->update($validated);

        return redirect()
            ->route('admin.galeri.index')
            ->with('success', 'Foto galeri berhasil diperbarui.');
    }

    public function destroy(Galeri $galeri): RedirectResponse
    {
        $this->hapusFileGambar($galeri->file_gambar);

        $galeri->delete();

        return redirect()
            ->route('admin.galeri.index')
            ->with('success', 'Foto galeri berhasil dihapus.');
    }

    /**
     * Simpan file gambar ke disk `public` pada folder galeri/.
     */
    protected function simpanGambar(Request $request): ?string
    {
        if (! $request->hasFile('file_gambar')) {
            return null;
        }

        return $request->file('file_gambar')->store('galeri', 'public');
    }

    /**
     * Hanya hapus file unggahan aplikasi ini (folder galeri/).
     * Foto bawaan seperti images/beranda.png tidak boleh ikut terhapus.
     */
    protected function hapusFileGambar(?string $path): void
    {
        if ($path && Str::startsWith($path, 'galeri/')) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function rules(bool $isUpdate = false): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'kategori_objek' => ['nullable', 'string', 'max:100'],
            'file_gambar' => [
                $isUpdate ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
