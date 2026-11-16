<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sejarah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SejarahController extends Controller
{
    public function index(): View
    {
        $sejarahs = Sejarah::with('user')->withCount('babs')->latest()->paginate(10);

        return view('admin.sejarah.index', compact('sejarahs'));
    }

    public function create(): View
    {
        return view('admin.sejarah.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $babs = $validated['babs'] ?? [];
        $kelolaBabs = $request->boolean('babs_submitted');
        unset($validated['babs'], $validated['babs_submitted']);

        foreach ($this->mediaFields() as $field => $config) {
            unset($validated[$field], $validated[$config['remove']]);

            $validated[$field] = $this->simpanFile($request, $field, $config['folder']);
        }

        $validated['notebook'] = $this->flipbookUrl($validated['notebook'] ?? null);
        $validated['id_user'] = $request->user()->id;

        $sejarah = Sejarah::create($validated);

        if ($kelolaBabs) {
            $this->simpanBabs($sejarah, $babs);
        }

        return redirect()
            ->route('admin.sejarah.index')
            ->with('success', 'Topik sejarah berhasil ditambahkan.');
    }

    public function edit(Sejarah $sejarah): View
    {
        $sejarah->load('babs');

        return view('admin.sejarah.form', compact('sejarah'));
    }

    public function update(Request $request, Sejarah $sejarah): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $babs = $validated['babs'] ?? [];
        $kelolaBabs = $request->boolean('babs_submitted');
        unset($validated['babs'], $validated['babs_submitted']);

        foreach ($this->mediaFields() as $field => $config) {
            unset($validated[$field], $validated[$config['remove']]);

            if ($request->hasFile($field)) {
                $this->hapusFile($sejarah->{$field}, $config['prefix']);
                $validated[$field] = $this->simpanFile($request, $field, $config['folder']);
            } elseif ($request->boolean($config['remove'])) {
                $this->hapusFile($sejarah->{$field}, $config['prefix']);
                $validated[$field] = null;
            }
        }

        if (array_key_exists('notebook', $validated)) {
            $notebookBaru = $this->flipbookUrl($validated['notebook']);

            // Data lama bisa berupa file unggahan notebook; bersihkan filenya
            // saat tautan Flipbook Digital diganti atau dikosongkan.
            if ($notebookBaru !== $sejarah->notebook) {
                $this->hapusFile($sejarah->notebook, 'sejarah/notebook/');
            }

            $validated['notebook'] = $notebookBaru;
        }

        $validated['id_user'] = $request->user()->id;

        $sejarah->update($validated);

        if ($kelolaBabs) {
            $this->simpanBabs($sejarah, $babs);
        }

        return redirect()
            ->route('admin.sejarah.index')
            ->with('success', 'Topik sejarah berhasil diperbarui.');
    }

    public function destroy(Sejarah $sejarah): RedirectResponse
    {
        foreach ($this->mediaFields() as $field => $config) {
            $this->hapusFile($sejarah->{$field}, $config['prefix']);
        }

        $this->hapusFile($sejarah->notebook, 'sejarah/notebook/');

        // Bab ikut terhapus lewat cascade, jadi file gambarnya dibersihkan dulu.
        foreach ($sejarah->babs()->pluck('gambar')->filter() as $gambarBab) {
            $this->hapusFile($gambarBab, 'sejarah/bab/');
        }

        $sejarah->delete();

        return redirect()
            ->route('admin.sejarah.index')
            ->with('success', 'Topik sejarah berhasil dihapus.');
    }

    /**
     * Sinkronkan daftar bab: baris yang dihapus di form ikut dihapus di
     * database, baris baru ditambahkan, dan baris lama diperbarui.
     *
     * @param  array<int, array<string, mixed>>  $babs
     */
    protected function simpanBabs(Sejarah $sejarah, array $babs): void
    {
        $gambarLama = $sejarah->babs()->pluck('gambar')->filter()->all();

        DB::transaction(function () use ($sejarah, $babs) {
            $sejarah->babs()->delete();

            foreach (array_values($babs) as $urutan => $item) {
                $gambar = $item['gambar_lama'] ?? null;

                if (! empty($item['hapus_gambar'])) {
                    $gambar = null;
                }

                $file = $item['gambar'] ?? null;

                if ($file instanceof UploadedFile) {
                    $gambar = $file->store('sejarah/bab', 'public');
                }

                $sejarah->babs()->create([
                    'nomor_bab' => (int) ($item['nomor_bab'] ?? ($urutan + 1)),
                    'judul_bab' => trim((string) ($item['judul_bab'] ?? '')),
                    'isi_bab' => trim((string) ($item['isi_bab'] ?? '')) ?: null,
                    'gambar' => $gambar,
                ]);
            }
        });

        // Bersihkan file bab lama yang tidak lagi dipakai.
        $dipakai = $sejarah->babs()->pluck('gambar')->filter()->all();

        foreach (array_diff($gambarLama, $dipakai) as $path) {
            $this->hapusFile($path, 'sejarah/bab/');
        }
    }

    /**
     * Field media topik sejarah beserta folder penyimpanan dan prefix hapusnya.
     *
     * @return array<string, array{remove: string, folder: string, prefix: string}>
     */
    protected function mediaFields(): array
    {
        return [
            'gambar_utama' => [
                'remove' => 'hapus_gambar',
                'folder' => 'sejarah',
                'prefix' => 'sejarah/',
            ],
            'video_storytelling' => [
                'remove' => 'hapus_video',
                'folder' => 'sejarah/video',
                'prefix' => 'sejarah/video/',
            ],
            'gambar_flipbook' => [
                'remove' => 'hapus_gambar_flipbook',
                'folder' => 'sejarah/flipbook',
                'prefix' => 'sejarah/flipbook/',
            ],
        ];
    }

    /**
     * Simpan file ke disk `public` bila field-nya diisi.
     */
    protected function simpanFile(Request $request, string $field, string $folder): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        return $request->file($field)->store($folder, 'public');
    }

    /**
     * Hanya hapus file unggahan aplikasi ini, bukan path bawaan seperti
     * `images/beranda.png` yang mungkin masih dipakai data lama.
     */
    protected function hapusFile(?string $path, string $prefix): void
    {
        if ($path && Str::startsWith($path, $prefix)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Normalisasi tautan Flipbook Digital: buang spasi di ujungnya,
     * nilai kosong menjadi null.
     */
    protected function flipbookUrl(?string $url): ?string
    {
        $url = trim((string) $url);

        return $url === '' ? null : $url;
    }

    protected function rules(): array
    {
        return [
            'judul_topik' => ['required', 'string', 'max:255'],
            'ringkasan' => ['nullable', 'string'],
            'gambar_utama' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'video_storytelling' => ['nullable', 'file', 'mimes:mp4,webm,mov', 'max:51200'],
            'notebook' => ['nullable', 'url:http,https', 'max:255'],
            'gambar_flipbook' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'hapus_gambar' => ['nullable', 'boolean'],
            'hapus_video' => ['nullable', 'boolean'],
            'hapus_gambar_flipbook' => ['nullable', 'boolean'],

            // Daftar bab dinamis.
            'babs_submitted' => ['nullable', 'boolean'],
            'babs' => ['nullable', 'array', 'max:50'],
            'babs.*.nomor_bab' => ['required', 'integer', 'min:1', 'distinct'],
            'babs.*.judul_bab' => ['required', 'string', 'max:255'],
            'babs.*.isi_bab' => ['nullable', 'string'],
            'babs.*.gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'babs.*.gambar_lama' => ['nullable', 'string', 'max:255'],
            'babs.*.hapus_gambar' => ['nullable', 'boolean'],
        ];
    }
}
