<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(): View
    {
        $beritas = Berita::with('user')->latest('tanggal')->paginate(10);

        return view('admin.berita.index', compact('beritas'));
    }

    public function create(): View
    {
        return view('admin.berita.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $validated['id_user'] = $request->user()->id;
        $validated['tanggal'] = $this->resolveTanggal($request);

        Berita::create($validated);

        return redirect()
            ->route('admin.berita.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(Berita $berita): View
    {
        return view('admin.berita.form', compact('berita'));
    }

    public function update(Request $request, Berita $berita): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $validated['id_user'] = $request->user()->id;
        $validated['tanggal'] = $this->resolveTanggal($request, $berita);

        $berita->update($validated);

        return redirect()
            ->route('admin.berita.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita): RedirectResponse
    {
        $berita->delete();

        return redirect()
            ->route('admin.berita.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    protected function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'isi' => ['required', 'string'],
            'gambar' => ['nullable', 'string', 'max:255'],
            'audio_url' => ['nullable', 'string', 'max:255'],
            'tanggal' => ['nullable', 'date'],
        ];
    }

    /**
     * Berita tanpa tanggal diisi dengan waktu sekarang.
     */
    protected function resolveTanggal(Request $request, ?Berita $berita = null): ?Carbon
    {
        if ($request->filled('tanggal')) {
            return Carbon::parse($request->input('tanggal'));
        }

        return $berita?->tanggal ?? Carbon::now();
    }
}
