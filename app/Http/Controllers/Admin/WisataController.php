<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WisataController extends Controller
{
    public function index(): View
    {
        $wisatas = Wisata::with('user')
            ->withCount('fotos')
            ->latest()
            ->paginate(10);

        return view('admin.wisata.index', compact('wisatas'));
    }

    public function create(): View
    {
        return view('admin.wisata.form', [
            'kategoriBudgetOptions' => Wisata::BUDGET_TIERS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['kategori_budget'] ??= Wisata::BUDGET_HEMAT;

        $quizInput = $validated['quiz'] ?? [];
        unset($validated['quiz']);

        $wisata = Wisata::create($validated + ['id_user' => $request->user()->id]);

        $this->simpanFotoBaru($request, $wisata);
        $this->simpanQuiz($wisata, $quizInput);

        return redirect()
            ->route('admin.wisata.index')
            ->with('success', 'Wisata berhasil ditambahkan.');
    }

    public function edit(Wisata $wisata): View
    {
        $wisata->load('fotos', 'quiz');

        return view('admin.wisata.form', [
            'wisata' => $wisata,
            'kategoriBudgetOptions' => Wisata::BUDGET_TIERS,
        ]);
    }

    public function update(Request $request, Wisata $wisata): RedirectResponse
    {
        $validated = $this->validated($request);

        $quizInput = $validated['quiz'] ?? [];
        unset($validated['quiz']);

        $validated['id_user'] = $request->user()->id;

        $wisata->update($validated);

        $this->hapusFotoTerpilih($request, $wisata);
        $this->simpanFotoBaru($request, $wisata);
        $this->simpanQuiz($wisata, $quizInput);

        return redirect()
            ->route('admin.wisata.index')
            ->with('success', 'Wisata berhasil diperbarui.');
    }

    public function destroy(Wisata $wisata): RedirectResponse
    {
        $this->hapusSemuaFileFoto($wisata);

        $wisata->delete();

        return redirect()
            ->route('admin.wisata.index')
            ->with('success', 'Wisata berhasil dihapus.');
    }

    /**
     * Validasi sekaligus membuang input foto dari data kolom tabel.
     *
     * Kunci `quiz` (soal kuis) tetap disertakan pada hasil validasi dan
     * dipisahkan oleh pemanggilnya karena bukan kolom tabel wisata.
     *
     * @return array<string, mixed>
     */
    protected function validated(Request $request): array
    {
        $validated = $request->validate($this->rules($request), $this->messages(), $this->attributes());

        unset($validated['fotos'], $validated['hapus_foto']);

        return $validated;
    }

    /**
     * Simpan ulang seluruh soal kuis milik satu wisata.
     *
     * Strategi "ganti semua": soal lama dihapus lalu soal dari form dinamis
     * disimpan berurutan, sehingga isi kuis selalu sama dengan yang tampil
     * saat tombol Simpan ditekan. Bila kuis tidak aktif, soal lama
     * dibiarkan agar tidak hilang hanya karena centangnya dimatikan.
     *
     * @param  array<int, array<string, mixed>>  $quiz
     */
    protected function simpanQuiz(Wisata $wisata, array $quiz): void
    {
        if ($quiz === []) {
            return;
        }

        DB::transaction(function () use ($wisata, $quiz) {
            $wisata->quiz()->delete();

            foreach (array_values($quiz) as $urutan => $soal) {
                $wisata->quiz()->create([
                    'pertanyaan' => $soal['pertanyaan'],
                    'pilihan' => array_values($soal['pilihan'] ?? []),
                    'jawaban_benar' => (int) ($soal['jawaban_benar'] ?? 0),
                    'penjelasan' => trim((string) ($soal['penjelasan'] ?? '')) ?: null,
                    'urutan' => $urutan + 1,
                ]);
            }
        });
    }

    /**
     * Simpan semua file baru ke disk `public`, lalu jadikan foto pertama
     * sebagai sampul bila wisata belum punya sampul.
     */
    protected function simpanFotoBaru(Request $request, Wisata $wisata): void
    {
        $files = $request->file('fotos', []);

        if (! is_array($files)) {
            $files = [$files];
        }

        if ($files === []) {
            return;
        }

        $urutan = (int) $wisata->fotos()->max('urutan');

        foreach ($files as $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            $path = $file->store('wisata', 'public');

            if (! $path) {
                continue;
            }

            $wisata->fotos()->create([
                'path' => $path,
                'urutan' => ++$urutan,
            ]);
        }

        $this->sinkronkanSampul($wisata);
    }

    /**
     * Hapus foto yang dicentang di form edit, termasuk filenya di disk.
     */
    protected function hapusFotoTerpilih(Request $request, Wisata $wisata): void
    {
        $ids = collect($request->input('hapus_foto', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return;
        }

        $fotos = $wisata->fotos()->whereKey($ids)->get();

        foreach ($fotos as $foto) {
            $this->hapusFileFoto($foto->path);
            $foto->delete();
        }

        $this->sinkronkanSampul($wisata);
    }

    /**
     * Samakan kolom `gambar` (sampul) dengan foto album pertama.
     */
    protected function sinkronkanSampul(Wisata $wisata): void
    {
        $sampul = $wisata->fotos()
            ->orderBy('urutan')
            ->orderBy('id_foto')
            ->value('path');

        $wisata->forceFill(['gambar' => $sampul])->save();
    }

    /**
     * Hapus seluruh file foto milik satu wisata saat datanya dihapus.
     */
    protected function hapusSemuaFileFoto(Wisata $wisata): void
    {
        $paths = $wisata->fotos()->pluck('path')
            ->push($wisata->gambar)
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
        if ($path && Str::startsWith($path, 'wisata/')) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Aturan validasi, memakai nama kolom Indonesia.
     *
     * Aturan soal kuis hanya aktif bila checkbox "Aktifkan Quiz" dicentang,
     * supaya form yang disembunyikan tidak memblokir penyimpanan wisata.
     */
    protected function rules(Request $request): array
    {
        $rules = [
            'nama_wisata' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'kategori_budget' => ['nullable', 'in:'.implode(',', array_keys(Wisata::BUDGET_TIERS))],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'harga_tiket' => ['nullable', 'integer', 'min:0'],
            'jam_operasional' => ['nullable', 'string', 'max:100'],
            'quiz_tersedia' => ['nullable', 'boolean'],

            // Upload multiple: maksimal 8 file per sekali simpan, 4 MB per file.
            'fotos' => ['nullable', 'array', 'max:8'],
            'fotos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            // Foto lama yang dicentang untuk dihapus di form edit.
            'hapus_foto' => ['nullable', 'array'],
            'hapus_foto.*' => ['integer'],
        ];

        if ($request->boolean('quiz_tersedia')) {
            $rules += $this->quizRules();
        }

        return $rules;
    }

    /**
     * Aturan untuk form dinamis soal kuis (pertanyaan, pilihan A-D, kunci).
     *
     * @return array<string, array<int, string>>
     */
    protected function quizRules(): array
    {
        return [
            'quiz' => ['required', 'array', 'min:1', 'max:50'],
            'quiz.*.pertanyaan' => ['required', 'string', 'max:255'],
            'quiz.*.pilihan' => ['required', 'array', 'size:4'],
            'quiz.*.pilihan.*' => ['required', 'string', 'max:255'],
            'quiz.*.jawaban_benar' => ['required', 'integer', 'between:0,3'],
            'quiz.*.penjelasan' => ['nullable', 'string', 'max:10000'],
        ];
    }

    /**
     * Pesan validasi khusus untuk form soal kuis.
     *
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'quiz.required' => 'Kuis aktif memerlukan minimal satu soal. Klik "Tambah Pertanyaan" pada bagian kuis.',
            'quiz.min' => 'Kuis aktif memerlukan minimal satu soal.',
            'quiz.*.jawaban_benar.required' => 'Pilih kunci jawaban yang benar.',
        ];
    }

    /**
     * Nama ramah untuk field soal kuis pada pesan validasi.
     *
     * @return array<string, string>
     */
    protected function attributes(): array
    {
        return [
            'quiz' => 'soal kuis',
            'quiz.*.pertanyaan' => 'pertanyaan',
            'quiz.*.pilihan' => 'pilihan jawaban',
            'quiz.*.pilihan.*' => 'pilihan jawaban',
            'quiz.*.jawaban_benar' => 'kunci jawaban',
            'quiz.*.penjelasan' => 'penjelasan',
        ];
    }
}
