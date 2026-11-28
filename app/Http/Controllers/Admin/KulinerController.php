<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kuliner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KulinerController extends Controller
{
    public function index(): View
    {
        $kuliners = Kuliner::with('user')
            ->withCount('fotos')
            ->latest()
            ->paginate(10);

        return view('admin.kuliner.index', compact('kuliners'));
    }

    public function create(): View
    {
        return view('admin.kuliner.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $kuliner = Kuliner::create($validated + ['id_user' => $request->user()->id]);

        $this->simpanFotoBaru($request, $kuliner);

        return redirect()
            ->route('admin.kuliner.index')
            ->with('success', 'Kuliner berhasil ditambahkan.');
    }

    public function edit(Kuliner $kuliner): View
    {
        $kuliner->load('fotos');

        return view('admin.kuliner.form', compact('kuliner'));
    }

    public function update(Request $request, Kuliner $kuliner): RedirectResponse
    {
        $validated = $this->validated($request);

        $validated['id_user'] = $request->user()->id;

        $kuliner->update($validated);

        $this->hapusFotoTerpilih($request, $kuliner);
        $this->simpanFotoBaru($request, $kuliner);

        return redirect()
            ->route('admin.kuliner.index')
            ->with('success', 'Kuliner berhasil diperbarui.');
    }

    public function destroy(Kuliner $kuliner): RedirectResponse
    {
        $this->hapusSemuaFileFoto($kuliner);

        $kuliner->delete();

        return redirect()
            ->route('admin.kuliner.index')
            ->with('success', 'Kuliner berhasil dihapus.');
    }

    /**
     * Validasi sekaligus membuang input foto dari data kolom tabel.
     *
     * File dan daftar foto yang dihapus ditangani terpisah karena bukan
     * kolom di tabel `kuliner`.
     *
     * @return array<string, mixed>
     */
    protected function validated(Request $request): array
    {
        $validated = $request->validate($this->rules());

        unset($validated['fotos'], $validated['hapus_foto']);

        // Checkbox tidak dikirim saat belum dicentang, jadi boolean-nya
        // ditentukan manual agar saat edit tidak kekal di nilai sebelumnya.
        $validated['ramah_anak'] = (bool) $request->input('ramah_anak');

        return $validated;
    }

    /**
     * Simpan semua file baru ke disk `public`, lalu jadikan foto pertama
     * sebagai sampul bila kuliner belum punya sampul.
     */
    protected function simpanFotoBaru(Request $request, Kuliner $kuliner): void
    {
        $files = $request->file('fotos', []);

        if (! is_array($files)) {
            $files = [$files];
        }

        if ($files === []) {
            return;
        }

        $urutan = (int) $kuliner->fotos()->max('urutan');

        foreach ($files as $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            $path = $file->store('kuliner', 'public');

            if (! $path) {
                continue;
            }

            $kuliner->fotos()->create([
                'path' => $path,
                'urutan' => ++$urutan,
            ]);
        }

        $this->sinkronkanSampul($kuliner);
    }

    /**
     * Hapus foto yang dicentang di form edit, termasuk filenya di disk.
     */
    protected function hapusFotoTerpilih(Request $request, Kuliner $kuliner): void
    {
        $ids = collect($request->input('hapus_foto', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return;
        }

        $fotos = $kuliner->fotos()->whereKey($ids)->get();

        foreach ($fotos as $foto) {
            $this->hapusFileFoto($foto->path);
            $foto->delete();
        }

        $this->sinkronkanSampul($kuliner);
    }

    /**
     * Samakan kolom `foto` (sampul) dengan foto album pertama.
     */
    protected function sinkronkanSampul(Kuliner $kuliner): void
    {
        $sampul = $kuliner->fotos()
            ->orderBy('urutan')
            ->orderBy('id_foto')
            ->value('path');

        $kuliner->forceFill(['foto' => $sampul])->save();
    }

    /**
     * Hapus seluruh file foto milik satu kuliner saat datanya dihapus.
     */
    protected function hapusSemuaFileFoto(Kuliner $kuliner): void
    {
        $paths = $kuliner->fotos()->pluck('path')
            ->push($kuliner->foto)
            ->filter()
            ->unique();

        foreach ($paths as $path) {
            $this->hapusFileFoto($path);
        }
    }

    /**
     * Hanya hapus file yang memang diunggah aplikasi ini.
     *
     * Foto bawaan (mis. `images/beranda.png`) tidak boleh ikut terhapus.
     */
    protected function hapusFileFoto(?string $path): void
    {
        if ($path && Str::startsWith($path, 'kuliner/')) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function rules(): array
    {
        return [
            'nama_kuliner' => ['required', 'string', 'max:255'],
            'kategori_suhu' => ['nullable', 'in:'.Kuliner::SUHU_MENGHANGATKAN.','.Kuliner::SUHU_MENYEGARKAN.','.Kuliner::SUHU_SEGALA_SUASANA],
            'harga' => ['nullable', 'integer', 'min:0'],
            'harga_minimum' => ['nullable', 'integer', 'min:0'],
            'harga_maksimum' => ['nullable', 'integer', 'min:0'],
            'jam_operasional' => ['nullable', 'string', 'max:100'],
            'kontak' => ['nullable', 'string', 'max:100'],
            'alamat_lengkap' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'radius_kilometer' => ['nullable', 'numeric', 'min:0', 'max:500'],
            'ramah_anak' => ['nullable', 'boolean'],
            'deskripsi' => ['required', 'string'],
            'status' => ['required', 'in:'.Kuliner::STATUS_AKTIF.','.Kuliner::STATUS_NONAKTIF],

            // Upload multiple: maksimal 8 file per sekali simpan, 2 MB per file.
            'fotos' => ['nullable', 'array', 'max:8'],
            'fotos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // Foto lama yang dicentang untuk dihapus di form edit.
            'hapus_foto' => ['nullable', 'array'],
            'hapus_foto.*' => ['integer'],
        ];
    }
}
