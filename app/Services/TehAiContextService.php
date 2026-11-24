<?php

namespace App\Services;

use App\Models\Berita;
use App\Models\Kuliner;
use App\Models\Sejarah;
use App\Models\Wisata;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Service pengambilan konteks real-time untuk Teh AI.
 *
 * Semua data dibaca langsung dari tabel publik (kuliner, wisata, berita,
 * sejarah) setiap kali service dipanggil, tanpa cache statis dan tanpa
 * batasan tanggal. Data baru yang baru ditambahkan langsung berada di
 * urutan teratas (latest), sementara data lama tetap dapat dicari.
 *
 * Pencarian mendukung:
 *   1. Deteksi modul otomatis (berita, wisata, kuliner, sejarah) agar hasil
 *      relevan dan tidak mencampur modul yang tidak diminta.
 *   2. Retrieval SQL case-insensitive (LIKE) lintas kolom data publik.
 *   3. Ranking relevansi dengan tie-breaker kronologis (latest) sehingga
 *      entri baru selalu diprioritaskan saat skor relevansi seimbang.
 */
class TehAiContextService
{
    public const MODULES = ['berita', 'wisata', 'kuliner', 'sejarah'];

    /**
     * Kata indikator modul untuk mendeteksi modul yang diminta pengunjung.
     *
     * @var array<string, array<int, string>>
     */
    private const MODULE_INDICATORS = [
        'berita' => ['berita', 'kabar', 'publikasi', 'artikel', 'pengumuman', 'warta', 'liputan'],
        'wisata' => ['wisata', 'destinasi', 'objek', 'rekreasi', 'taman', 'curug', 'tempat wisata', 'tempat liburan', 'liburan', 'piknik'],
        'kuliner' => ['kuliner', 'makanan', 'makan', 'minuman', 'resto', 'restoran', 'kafe', 'cafe', 'warung', 'jajanan', 'menu', 'masakan', 'tempat makan', 'kedai'],
        'sejarah' => ['sejarah', 'historis', 'tokoh', 'peristiwa', 'cerita sejarah', 'kisah sejarah', 'bab sejarah'],
    ];

    /**
     * Kata yang terlalu umum untuk dijadikan kata kunci pencarian.
     *
     * @var array<int, string>
     */
    private const STOP_WORDS = [
        'yang', 'di', 'ke', 'dari', 'dan', 'atau', 'apa', 'apakah', 'bagaimana', 'kapan',
        'dimana', 'mana', 'siapa', 'mengapa', 'kenapa', 'saya', 'aku', 'kamu', 'anda',
        'tolong', 'bisa', 'boleh', 'mohon', 'ada', 'itu', 'ini', 'nya', 'untuk', 'pada',
        'tentang', 'lagi', 'informasi', 'info', 'berikan', 'carikan', 'tampilkan',
        'ceritakan', 'cerita', 'rekomendasi', 'rekomendasikan', 'daftar', 'kumpulan', 'sebutkan',
        'bandung', 'kota', 'saja', 'dong', 'ya', 'kah', 'tempat',
    ];

    /**
     * Kata-kata temporal/waktu umum.
     *
     * @var array<int, string>
     */
    private const TEMPORAL_WORDS = [
        'terbaru', 'terkini', 'terupdate', 'baru', 'hari', 'sekarang', 'saat',
    ];

    /**
     * Frasa yang harus dikenali sebagai satu konsep pencarian.
     *
     * @var array<string, array<int, string>>
     */
    private const PHRASES = [
        'ramah anak' => ['ramah', 'anak', 'keluarga', 'family', 'kids'],
        'ramah keluarga' => ['ramah', 'keluarga', 'anak', 'family', 'kids'],
        'jam buka' => ['jam', 'buka', 'operasional'],
        'jam operasional' => ['jam', 'operasional', 'buka', 'tutup'],
        'tiket masuk' => ['tiket', 'masuk', 'harga', 'tarif'],
        'harga tiket' => ['tiket', 'harga', 'tarif', 'masuk'],
        'kisaran harga' => ['harga', 'minimum', 'maksimum', 'range', 'rentang'],
    ];

    /**
     * Perluasan kata kunci satu kata menjadi konsep pencarian.
     *
     * @var array<string, array<int, string>>
     */
    private const SYNONYMS = [
        'anak' => ['anak', 'ramah', 'keluarga', 'family', 'kids'],
        'keluarga' => ['keluarga', 'anak', 'ramah', 'family', 'kids'],
        'ramah' => ['ramah', 'anak', 'keluarga', 'family', 'kids'],
        'family' => ['family', 'ramah', 'anak', 'keluarga', 'kids'],
        'kids' => ['kids', 'anak', 'ramah', 'keluarga', 'family'],
        'wisata' => ['wisata', 'destinasi', 'objek', 'taman', 'museum', 'liburan'],
        'destinasi' => ['destinasi', 'wisata', 'objek', 'taman', 'museum'],
        'kuliner' => ['kuliner', 'makanan', 'makan', 'minuman', 'warung', 'restoran', 'kafe', 'kopi'],
        'makanan' => ['makanan', 'makan', 'kuliner', 'warung', 'restoran'],
        'sejarah' => ['sejarah', 'bab', 'kisah', 'tokoh', 'peristiwa'],
        'berita' => ['berita', 'kabar', 'publikasi', 'artikel'],
        'murah' => ['murah', 'gratis', 'hemat', 'terjangkau'],
        'gratis' => ['gratis', 'murah', 'hemat', 'nol'],
        'hemat' => ['hemat', 'murah', 'gratis', 'terjangkau'],
        'mahal' => ['mahal', 'premium', 'nyaman', 'mewah'],
        'panas' => ['panas', 'gerah', 'menyegarkan', 'segar'],
        'gerah' => ['gerah', 'panas', 'menyegarkan', 'segar'],
        'dingin' => ['dingin', 'sejuk', 'hujan', 'menghangatkan', 'hangat'],
        'hangat' => ['hangat', 'menghangatkan', 'dingin', 'sejuk'],
        'segar' => ['segar', 'menyegarkan', 'panas', 'gerah'],
        'harga' => ['harga', 'biaya', 'tarif', 'tiket', 'rupiah'],
        'tarif' => ['tarif', 'harga', 'biaya', 'tiket'],
        'lokasi' => ['lokasi', 'alamat', 'tempat', 'dimana'],
        'dimana' => ['dimana', 'lokasi', 'alamat', 'tempat'],
        'alamat' => ['alamat', 'lokasi', 'tempat', 'dimana'],
    ];

    /**
     * Ambil konteks untuk sebuah pertanyaan pengguna.
     *
     * @return array{
     *     query: string|null,
     *     filters: array<string, bool>,
     *     counts: array<string, int>,
     *     has_results: bool,
     *     data: array<string, array<int, array<string, mixed>>>,
     *     suggestions: array<int, string>
     * }
     */
    public function contextFor(?string $query, ?int $limit = null): array
    {
        $query = $query === null ? null : trim($query);
        $limit = $this->normalizeLimit($limit);

        if ($query === null || $query === '') {
            $data = [
                'kuliner' => $this->kuliner([], $limit),
                'wisata' => $this->wisata([], $limit),
                'berita' => $this->berita([], $limit),
                'sejarah' => $this->sejarah([], $limit),
            ];

            $counts = array_map('count', $data);

            return [
                'query' => null,
                'filters' => $this->filters([]),
                'counts' => $counts,
                'has_results' => array_sum($counts) > 0,
                'data' => $data,
                'suggestions' => $this->suggestions(),
            ];
        }

        $normalized = $this->normalize($query);
        $targetedModules = $this->detectModules($normalized);
        $concepts = $this->concepts($query, $targetedModules);

        // Jika modul tertentu ditanyakan secara spesifik, ambil hanya data modul tersebut
        // agar jawaban tidak tercampur modul lain yang tidak diminta.
        $searchAll = empty($targetedModules);

        $data = [
            'kuliner' => ($searchAll || in_array('kuliner', $targetedModules, true))
                ? $this->kuliner($concepts, $limit)
                : [],
            'wisata' => ($searchAll || in_array('wisata', $targetedModules, true))
                ? $this->wisata($concepts, $limit)
                : [],
            'berita' => ($searchAll || in_array('berita', $targetedModules, true))
                ? $this->berita($concepts, $limit)
                : [],
            'sejarah' => ($searchAll || in_array('sejarah', $targetedModules, true))
                ? $this->sejarah($concepts, $limit)
                : [],
        ];

        $counts = array_map('count', $data);

        return [
            'query' => $query,
            'filters' => $this->filters($concepts),
            'counts' => $counts,
            'has_results' => array_sum($counts) > 0,
            'data' => $data,
            'suggestions' => $this->suggestions(),
        ];
    }

    /**
     * Deteksi modul yang disebutkan dalam pertanyaan pengunjung.
     *
     * @return array<int, string>
     */
    public function detectModules(string $normalizedQuery): array
    {
        $targeted = [];

        foreach (self::MODULE_INDICATORS as $module => $indicators) {
            foreach ($indicators as $indicator) {
                if (preg_match('/\b'.preg_quote($indicator, '/').'\b/u', $normalizedQuery) === 1) {
                    $targeted[] = $module;
                    break;
                }
            }
        }

        return array_values(array_unique($targeted));
    }

    /**
     * Daftar topik yang tersedia di portal, dipakai untuk saran saat data
     * tidak ditemukan dan oleh endpoint /api/teh-ai/topics.
     *
     * @return array<int, string>
     */
    public function suggestions(): array
    {
        $suggestions = collect();

        $kuliner = Kuliner::aktif()->latest('created_at')->latest('id_kuliner')->limit(2)->pluck('nama_kuliner');
        $wisata = Wisata::latest('created_at')->latest('id_wisata')->limit(2)->pluck('nama_wisata');
        $berita = Berita::latest('tanggal')->latest('id_berita')->limit(2)->pluck('judul');
        $sejarah = Sejarah::latest('created_at')->latest('id_sejarah')->limit(2)->pluck('judul_topik');

        foreach ($kuliner as $nama) {
            $suggestions->push('Kuliner: '.$nama);
        }
        foreach ($wisata as $nama) {
            $suggestions->push('Wisata: '.$nama);
        }
        foreach ($berita as $judul) {
            $suggestions->push('Berita: '.$judul);
        }
        foreach ($sejarah as $judul) {
            $suggestions->push('Sejarah: '.$judul);
        }

        if ($suggestions->isEmpty()) {
            return [
                'kuliner ramah anak',
                'wisata dengan tiket murah',
                'berita terbaru',
                'sejarah Kota Bandung',
            ];
        }

        return $suggestions->take(8)->values()->all();
    }

    /**
     * Ringkasan kategori yang bisa dibahas Teh AI (tanpa data internal).
     *
     * @return array<int, array{kategori: string, deskripsi: string}>
     */
    public function categories(): array
    {
        return [
            ['kategori' => 'kuliner', 'deskripsi' => 'Nama kuliner, kategori suhu, status ramah keluarga, harga, jam operasional, lokasi, dan deskripsi.'],
            ['kategori' => 'wisata', 'deskripsi' => 'Nama destinasi, kategori, harga tiket masuk, jam operasional, lokasi, dan deskripsi.'],
            ['kategori' => 'berita', 'deskripsi' => 'Judul, kategori, tanggal publikasi, dan ringkasan isi berita.'],
            ['kategori' => 'sejarah', 'deskripsi' => 'Judul topik, jumlah bab, tanggal, dan ringkasan cerita sejarah.'],
        ];
    }

    /**
     * @param  array<int, array{label: string, terms: array<int, string>}>  $concepts
     * @return array<int, array<string, mixed>>
     */
    private function kuliner(array $concepts, int $limit): array
    {
        $query = Kuliner::aktif();

        if ($concepts !== []) {
            $query->where(function (Builder $builder) use ($concepts): void {
                $this->applyConcepts(
                    $builder,
                    ['nama_kuliner', 'kategori_suhu', 'deskripsi', 'alamat_lengkap', 'jam_operasional', 'harga', 'harga_minimum', 'harga_maksimum'],
                    $concepts
                );

                if ($this->hasConcept($concepts, ['anak', 'keluarga', 'ramah', 'family', 'kids'])) {
                    $builder->orWhere('ramah_anak', true);
                }

                if ($this->hasConcept($concepts, ['panas', 'gerah', 'segar', 'menyegarkan'])) {
                    $builder->orWhere('kategori_suhu', Kuliner::SUHU_MENYEGARKAN);
                }

                if ($this->hasConcept($concepts, ['dingin', 'sejuk', 'hangat', 'menghangatkan'])) {
                    $builder->orWhere('kategori_suhu', Kuliner::SUHU_MENGHANGATKAN);
                }
            });
        }

        // Selalu prioritaskan data terbaru (latest) dari database
        $items = $query->latest('created_at')->latest('id_kuliner')->limit(100)->get();

        return $this->rank($items, $concepts, function (Kuliner $kuliner): array {
            return [
                'judul' => $kuliner->nama_kuliner,
                'teks' => implode(' ', array_filter([
                    $kuliner->nama_kuliner,
                    $kuliner->kategori_suhu,
                    $kuliner->deskripsi,
                    $kuliner->alamat_lengkap,
                    $kuliner->jam_operasional,
                    $kuliner->hargaTampilan(),
                    $kuliner->ramah_anak ? 'ramah anak keluarga family kids' : '',
                ])),
            ];
        })->take($limit)->map(fn (array $row): array => [
            'nama' => $row['model']->nama_kuliner,
            'kategori' => 'kuliner',
            'kategori_suhu' => $row['model']->kategori_suhu,
            'ramah_keluarga' => (bool) $row['model']->ramah_anak,
            'harga' => $row['model']->hargaTampilan(),
            'harga_tunggal' => $row['model']->harga,
            'harga_minimum' => $row['model']->harga_minimum,
            'harga_maksimum' => $row['model']->harga_maksimum,
            'jam_operasional' => $row['model']->jam_operasional ?: 'Jam operasional belum tercatat',
            'lokasi' => $row['model']->alamat_lengkap ?: 'Bandung',
            'alamat' => $row['model']->alamat_lengkap ?: 'Bandung',
            'deskripsi' => $this->teksPendek($row['model']->deskripsi, 300),
            'ringkasan' => $this->teksPendek($row['model']->deskripsi, 300),
            'url' => route('public.kuliner.show', $row['model']),
        ])->values()->all();
    }

    /**
     * @param  array<int, array{label: string, terms: array<int, string>}>  $concepts
     * @return array<int, array<string, mixed>>
     */
    private function wisata(array $concepts, int $limit): array
    {
        $query = Wisata::query();

        if ($concepts !== []) {
            $query->where(function (Builder $builder) use ($concepts): void {
                $this->applyConcepts(
                    $builder,
                    ['nama_wisata', 'kategori', 'kategori_budget', 'deskripsi', 'lokasi', 'jam_operasional', 'harga_tiket'],
                    $concepts
                );

                if ($this->hasConcept($concepts, ['murah', 'gratis', 'hemat', 'terjangkau'])) {
                    $builder->orWhere('kategori_budget', Wisata::BUDGET_HEMAT)
                        ->orWhere('harga_tiket', 0)
                        ->orWhere('harga_tiket', '<=', 20000);
                }
            });
        }

        // Selalu prioritaskan data terbaru (latest) dari database
        $items = $query->latest('created_at')->latest('id_wisata')->limit(100)->get();

        return $this->rank($items, $concepts, function (Wisata $wisata): array {
            return [
                'judul' => $wisata->nama_wisata,
                'teks' => implode(' ', array_filter([
                    $wisata->nama_wisata,
                    $wisata->kategori,
                    $wisata->deskripsi,
                    $wisata->lokasi,
                    $wisata->jam_operasional,
                    $this->labelHarga($wisata->harga_tiket),
                    $wisata->labelKategoriBudget(),
                ])),
            ];
        })->take($limit)->map(fn (array $row): array => [
            'nama' => $row['model']->nama_wisata,
            'kategori' => 'wisata',
            'jenis' => $row['model']->kategori,
            'kategori_wisata' => $row['model']->kategori,
            'kategori_anggaran' => $row['model']->labelKategoriBudget(),
            'harga_tiket' => $row['model']->harga_tiket,
            'harga_tiket_masuk' => $this->labelHarga($row['model']->harga_tiket),
            'jam_operasional' => $row['model']->jam_operasional ?: 'Jam operasional belum tercatat',
            'lokasi' => $row['model']->lokasi ?: 'Bandung',
            'alamat' => $row['model']->lokasi ?: 'Bandung',
            'deskripsi' => $this->teksPendek($row['model']->deskripsi, 300),
            'ringkasan' => $this->teksPendek($row['model']->deskripsi, 300),
            'url' => route('public.wisata.show', $row['model']),
        ])->values()->all();
    }

    /**
     * @param  array<int, array{label: string, terms: array<int, string>}>  $concepts
     * @return array<int, array<string, mixed>>
     */
    private function berita(array $concepts, int $limit): array
    {
        $query = Berita::query();

        if ($concepts !== []) {
            $query->where(function (Builder $builder) use ($concepts): void {
                $this->applyConcepts(
                    $builder,
                    ['judul', 'kategori', 'isi'],
                    $concepts
                );
            });
        }

        // Selalu prioritaskan data terbaru (latest) dari database
        $items = $query->latest('tanggal')->latest('id_berita')->limit(100)->get();

        return $this->rank($items, $concepts, function (Berita $berita): array {
            return [
                'judul' => $berita->judul,
                'teks' => implode(' ', array_filter([
                    $berita->judul,
                    $berita->kategori,
                    $this->teksPendek($berita->isi, 1200),
                ])),
            ];
        })->take($limit)->map(fn (array $row): array => [
            'judul' => $row['model']->judul,
            'kategori' => 'berita',
            'kategori_berita' => $row['model']->kategori,
            'tanggal' => $row['model']->tanggal?->toDateString(),
            'tanggal_label' => $row['model']->tanggal?->translatedFormat('d F Y') ?? ($row['model']->tanggal?->toDateString() ?? '-'),
            'ringkasan' => $this->teksPendek($row['model']->isi, 300),
            'isi' => $this->teksPendek($row['model']->isi, 600),
            'url' => route('public.berita.show', $row['model']),
        ])->values()->all();
    }

    /**
     * @param  array<int, array{label: string, terms: array<int, string>}>  $concepts
     * @return array<int, array<string, mixed>>
     */
    private function sejarah(array $concepts, int $limit): array
    {
        $query = Sejarah::withCount('babs')->with('babs');

        if ($concepts !== []) {
            $query->where(function (Builder $outer) use ($concepts): void {
                $this->applyConcepts($outer, ['judul_topik', 'ringkasan'], $concepts);
                $outer->orWhereHas('babs', function (Builder $bab) use ($concepts): void {
                    $this->applyConcepts($bab, ['judul_bab', 'isi_bab'], $concepts);
                });
            });
        }

        // Selalu prioritaskan data terbaru (latest) dari database
        $items = $query->latest('created_at')->latest('id_sejarah')->limit(100)->get();

        return $this->rank($items, $concepts, function (Sejarah $topik): array {
            $isiBab = $topik->babs->pluck('isi_bab')->implode(' ');

            return [
                'judul' => $topik->judul_topik,
                'teks' => implode(' ', array_filter([
                    $topik->judul_topik,
                    $topik->ringkasan,
                    $this->teksPendek($isiBab, 1200),
                ])),
            ];
        })->take($limit)->map(fn (array $row): array => [
            'judul' => $row['model']->judul_topik,
            'judul_topik' => $row['model']->judul_topik,
            'kategori' => 'sejarah',
            'jumlah_bab' => (int) $row['model']->babs_count,
            'tanggal' => $row['model']->created_at?->toDateString(),
            'tanggal_label' => $row['model']->created_at?->translatedFormat('d F Y') ?? ($row['model']->created_at?->toDateString() ?? '-'),
            'ringkasan' => $this->teksPendek(
                $row['model']->ringkasan ?: $row['model']->babs->pluck('isi_bab')->implode(' '),
                300
            ),
            'cerita' => $this->teksPendek(
                $row['model']->ringkasan ?: $row['model']->babs->pluck('isi_bab')->implode(' '),
                600
            ),
            'bab' => $row['model']->babs->take(5)->map(fn ($bab): array => [
                'nomor' => (int) $bab->nomor_bab,
                'judul' => $bab->judul_bab,
                'ringkasan' => $this->teksPendek($bab->isi_bab, 160),
            ])->values()->all(),
            'url' => route('public.sejarah.show', $row['model']),
        ])->values()->all();
    }

    /**
     * Terapkan pencarian kata kunci + sinonim ke sebuah query Eloquent (case-insensitive LIKE).
     *
     * @param  array<int, string>  $columns
     * @param  array<int, array{label: string, terms: array<int, string>}>  $concepts
     */
    private function applyConcepts(Builder $query, array $columns, array $concepts): void
    {
        if ($concepts === []) {
            return;
        }

        $query->where(function (Builder $outer) use ($columns, $concepts): void {
            foreach ($concepts as $concept) {
                $outer->orWhere(function (Builder $inner) use ($columns, $concept): void {
                    foreach ($concept['terms'] as $term) {
                        foreach ($columns as $column) {
                            $inner->orWhere($column, 'like', '%'.$term.'%');
                        }
                    }
                });
            }
        });
    }

    /**
     * Ranking relevansi: skor kecocokan tertinggi diutamakan.
     * Sebagai tie-breaker, selalu prioritaskan data terbaru (latest / tanggal / created_at / id).
     *
     * @template TModel of \Illuminate\Database\Eloquent\Model
     *
     * @param  Collection<int, TModel>  $items
     * @param  array<int, array{label: string, terms: array<int, string>}>  $concepts
     * @param  callable(TModel): array{judul: string, teks: string}  $searchable
     * @return Collection<int, array{model: TModel, score: int}>
     */
    private function rank(Collection $items, array $concepts, callable $searchable): Collection
    {
        $ranked = $items->map(function ($item) use ($concepts, $searchable): array {
            $fields = $searchable($item);
            $teks = $this->normalize($fields['teks']);
            $judul = $this->normalize($fields['judul']);

            return [
                'model' => $item,
                'score' => $this->score($concepts, $teks, $judul),
            ];
        });

        return $ranked->sort(function (array $a, array $b): int {
            if ($a['score'] !== $b['score']) {
                return $b['score'] <=> $a['score'];
            }

            // Tie-breaker: selalu utamakan data terbaru
            $dateA = $a['model']->tanggal ?? $a['model']->created_at;
            $dateB = $b['model']->tanggal ?? $b['model']->created_at;

            if ($dateA && $dateB && $dateA != $dateB) {
                return $dateB <=> $dateA;
            }

            return ($b['model']->getKey() ?? 0) <=> ($a['model']->getKey() ?? 0);
        })->values();
    }

    /**
     * @param  array<int, array{label: string, terms: array<int, string>}>  $concepts
     */
    private function score(array $concepts, string $teks, string $judul): int
    {
        $total = 0;

        foreach ($concepts as $concept) {
            $best = 0;

            foreach ($concept['terms'] as $term) {
                if ($term === '') {
                    continue;
                }

                if (str_contains($judul, $term)) {
                    $best = max($best, 12);
                }

                if (preg_match('/\b'.preg_quote($term, '/').'\b/u', $teks) === 1) {
                    $best = max($best, 8);
                } elseif (str_contains($teks, $term)) {
                    $best = max($best, 4);
                }
            }

            $total += $best;
        }

        return $total;
    }

    /**
     * Ubah pertanyaan menjadi konsep-konsep pencarian (kata kunci + sinonim),
     * lalu gabungkan konsep yang tumpang tindih.
     *
     * @param  array<int, string>  $targetedModules
     * @return array<int, array{label: string, terms: array<int, string>}>
     */
    private function concepts(string $query, array $targetedModules = []): array
    {
        $normalized = $this->normalize($query);
        $groups = [];

        // Kumpulkan indikator modul yang dipakai sebagai penentu modul
        $moduleTermsToFilter = [];
        if (! empty($targetedModules)) {
            foreach ($targetedModules as $mod) {
                if (isset(self::MODULE_INDICATORS[$mod])) {
                    $moduleTermsToFilter = array_merge($moduleTermsToFilter, self::MODULE_INDICATORS[$mod]);
                }
            }
        }

        foreach (self::PHRASES as $phrase => $terms) {
            if (str_contains($normalized, $phrase)) {
                $groups[$phrase] = $terms;
            }
        }

        $allTokens = preg_split('/\s+/', $normalized) ?: [];
        $hasOtherTokens = false;

        // Cek apakah ada token subjek selain stop words, temporal words, dan module indicator
        foreach ($allTokens as $token) {
            if (is_numeric($token)) {
                $hasOtherTokens = true;
                break;
            }
            if (strlen($token) >= 2
                && ! in_array($token, self::STOP_WORDS, true)
                && ! in_array($token, self::TEMPORAL_WORDS, true)
                && ! in_array($token, $moduleTermsToFilter, true)
            ) {
                $hasOtherTokens = true;
                break;
            }
        }

        foreach ($allTokens as $token) {
            if (is_numeric($token)) {
                $groups[$token] = [$token];
                continue;
            }

            if (strlen($token) < 2 || in_array($token, self::STOP_WORDS, true) || in_array($token, self::TEMPORAL_WORDS, true)) {
                continue;
            }

            // Jika ada kata subjek lain, jangan jadikan nama modul sebagai filter LIKE yang membatasi
            if ($hasOtherTokens && in_array($token, $moduleTermsToFilter, true)) {
                continue;
            }

            // Jika tidak ada kata subjek lain sama sekali dan token ini adalah nama modul, lewati
            // agar modul mengembalikan data terbaru (latest) tanpa filter LIKE yang membatasi.
            if (! $hasOtherTokens && in_array($token, $moduleTermsToFilter, true)) {
                continue;
            }

            $terms = self::SYNONYMS[$token] ?? [$token];
            $groups[$token] = array_values(array_unique(array_merge($groups[$token] ?? [], $terms)));
        }

        $merged = [];
        foreach ($groups as $label => $terms) {
            $target = null;

            foreach ($merged as $index => $group) {
                if (array_intersect($group['terms'], $terms) !== []) {
                    $target = $index;
                    break;
                }
            }

            if ($target === null) {
                $merged[] = ['label' => $label, 'terms' => $terms];
                continue;
            }

            $merged[$target]['terms'] = array_values(array_unique(array_merge($merged[$target]['terms'], $terms)));
        }

        return array_slice($merged, 0, 8);
    }

    /**
     * @param  array<int, array{label: string, terms: array<int, string>}>  $concepts
     * @param  array<int, string>  $needles
     */
    private function hasConcept(array $concepts, array $needles): bool
    {
        foreach ($concepts as $concept) {
            if (array_intersect($concept['terms'], $needles) !== []) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<int, array{label: string, terms: array<int, string>}>  $concepts
     * @return array<string, bool>
     */
    private function filters(array $concepts): array
    {
        return [
            'ramah_keluarga' => $this->hasConcept($concepts, ['anak', 'keluarga', 'ramah', 'family', 'kids']),
            'harga_murah' => $this->hasConcept($concepts, ['murah', 'gratis', 'hemat', 'terjangkau']),
            'suhu_panas' => $this->hasConcept($concepts, ['panas', 'gerah', 'segar', 'menyegarkan']),
            'suhu_dingin' => $this->hasConcept($concepts, ['dingin', 'sejuk', 'hangat', 'menghangatkan']),
        ];
    }

    private function normalize(string $text): string
    {
        $text = Str::lower(strip_tags($text));
        $text = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $text) ?? '';

        return trim(preg_replace('/\s+/', ' ', $text) ?? '');
    }

    private function teksPendek(?string $text, int $panjang): ?string
    {
        if ($text === null || trim($text) === '') {
            return null;
        }

        $text = trim(preg_replace('/\s+/', ' ', strip_tags($text)) ?? '');

        return Str::limit($text, $panjang, '…');
    }

    private function labelHarga(?int $harga): string
    {
        if ($harga === null) {
            return 'Harga belum diisi';
        }

        if ($harga === 0) {
            return 'Gratis';
        }

        return 'Rp '.number_format($harga, 0, ',', '.');
    }

    private function normalizeLimit(?int $limit): int
    {
        $default = (int) config('services.teh_ai.context_limit', 10);

        if ($limit === null) {
            return max(1, min($default, 15));
        }

        return max(1, min($limit, 15));
    }
}
