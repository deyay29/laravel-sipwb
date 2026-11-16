<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(): View
    {
        $beritas = Berita::with(['user', 'reaksi'])->latest('tanggal')->paginate(10);

        return view('admin.berita.index', compact('beritas'));
    }

    public function create(): View
    {
        return view('admin.berita.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        unset($validated['gambar'], $validated['audio'], $validated['hapus_gambar'], $validated['hapus_audio']);

        $validated['id_user'] = $request->user()->id;
        $validated['tanggal'] = $this->resolveTanggal($request);
        $validated['gambar'] = $this->simpanGambar($request);
        $validated['audio_url'] = $this->resolveAudio($request);

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

        unset($validated['gambar'], $validated['audio'], $validated['hapus_gambar'], $validated['hapus_audio'], $validated['audio_url']);

        $validated['id_user'] = $request->user()->id;
        $validated['tanggal'] = $this->resolveTanggal($request, $berita);

        if ($request->hasFile('gambar')) {
            $this->hapusFileBerita($berita->gambar);
            $validated['gambar'] = $this->simpanGambar($request);
        } elseif ($request->boolean('hapus_gambar')) {
            $this->hapusFileBerita($berita->gambar);
            $validated['gambar'] = null;
        }

        if ($request->hasFile('audio')) {
            $this->hapusFileBerita($berita->audio_url);
            $validated['audio_url'] = $this->resolveAudio($request);
        } elseif ($request->boolean('hapus_audio')) {
            $this->hapusFileBerita($berita->audio_url);
            $validated['audio_url'] = null;
        } elseif ($request->filled('audio_url')) {
            if ($berita->audio_url !== $request->input('audio_url')) {
                $this->hapusFileBerita($berita->audio_url);
            }

            $validated['audio_url'] = $request->input('audio_url');
        }

        $berita->update($validated);

        return redirect()
            ->route('admin.berita.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita): RedirectResponse
    {
        $this->hapusFileBerita($berita->gambar);
        $this->hapusFileBerita($berita->audio_url);

        $berita->delete();

        return redirect()
            ->route('admin.berita.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    /**
     * Simpan file gambar berita ke disk `public`.
     */
    protected function simpanGambar(Request $request): ?string
    {
        if (! $request->hasFile('gambar')) {
            return null;
        }

        return $request->file('gambar')->store('berita', 'public');
    }

    /**
     * Audio bisa berupa file upload atau URL eksternal.
     */
    protected function resolveAudio(Request $request): ?string
    {
        if ($request->hasFile('audio')) {
            return $request->file('audio')->store('berita/audio', 'public');
        }

        return $request->filled('audio_url') ? $request->input('audio_url') : null;
    }

    /**
     * Hanya hapus file yang diunggah lewat aplikasi ini (folder berita/).
     *
     * Path bawaan seperti `images/beranda.png` atau URL audio eksternal
     * tidak boleh ikut terhapus.
     */
    protected function hapusFileBerita(?string $path): void
    {
        if ($path && Str::startsWith($path, 'berita/')) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'isi' => ['required', 'string'],
            'tanggal' => ['nullable', 'date'],

            // Upload gambar sampul berita.
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'hapus_gambar' => ['nullable', 'boolean'],

            // Audio boleh upload file sendiri atau memakai URL eksternal.
            'audio' => ['nullable', 'file', 'mimes:mp3,wav,ogg,m4a,aac', 'max:10240'],
            'audio_url' => ['nullable', 'string', 'max:255'],
            'hapus_audio' => ['nullable', 'boolean'],
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
