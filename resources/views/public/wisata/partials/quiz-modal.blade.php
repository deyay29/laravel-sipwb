{{--
    Modal kuis wisata per-destinasi (halaman detail wisata).

    Soal BUKAN data statis: saat tombol "Mulai Kuis Wisata" diklik, sistem
    mem-fetch pertanyaan, pilihan jawaban (A-D), dan kunci jawaban khusus
    destinasi ini dari endpoint `public.wisata.kuis` (tabel quiz_wisata).

    Partial ini disertakan show.blade.php hanya bila kuis destinasi aktif
    dan memiliki soal tersimpan.
--}}
<div id="quiz-modal"
     class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-950/80 backdrop-blur-sm transition-opacity duration-300"
     role="dialog"
     aria-modal="true"
     aria-labelledby="quiz-modal-title"
     data-url="{{ $quizUrl }}"
     data-nama="{{ $wisata->nama_wisata }}"
     data-slug="{{ $wisata->slug }}">
    <div class="flex min-h-screen items-center justify-center p-4 sm:p-6">
        <div class="relative w-full max-w-2xl transform overflow-hidden rounded-3xl border border-slate-100 bg-white text-left shadow-2xl transition-all">

            {{-- Tombol Tutup --}}
            <button type="button"
                    id="quiz-modal-close"
                    class="absolute right-4 top-4 grid h-9 w-9 place-items-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-slate-200 hover:text-slate-800">
                <i class="fas fa-xmark"></i>
            </button>

            {{-- ================= STATE: MEMUAT SOAL ================= --}}
            <div id="quiz-modal-loading" class="hidden p-10 text-center sm:p-14">
                <i class="fas fa-circle-notch fa-spin text-3xl text-brand-600"></i>
                <p class="mt-4 font-display text-sm font-semibold text-slate-600">Memuat soal kuis…</p>
            </div>

            {{-- ================= STATE: SOAL ================= --}}
            <div id="quiz-modal-question" class="hidden p-6 sm:p-8">
                <div class="flex items-center gap-3 rounded-xl border border-brand-100 bg-brand-50/80 px-4 py-2.5">
                    <i class="fas fa-location-dot text-brand-600"></i>
                    <span id="quiz-modal-wisata" class="font-display text-sm font-bold text-brand-800"></span>
                </div>

                <div class="mt-5 flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <span id="quiz-modal-progress" class="font-display text-xs font-bold uppercase tracking-wider text-slate-400">Soal 1</span>
                        <p id="quiz-modal-poin-info" class="mt-0.5 text-xs font-semibold text-emerald-600">
                            <i class="fas fa-circle-check text-[11px]"></i> Jawab semua benar untuk 100 poin
                        </p>
                    </div>
                    <div class="flex items-center gap-2 rounded-full border border-brand-100 bg-brand-50 px-3.5 py-1.5">
                        <i class="fas fa-award text-xs text-brand-600"></i>
                        <span class="font-display text-xs font-bold text-brand-700">Target: 100 Poin</span>
                    </div>
                </div>

                <div class="mt-4 h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                    <div id="quiz-modal-bar" class="h-full rounded-full bg-gradient-to-r from-brand-600 to-blue-500 transition-all duration-300" style="width: 0%"></div>
                </div>

                <h3 id="quiz-modal-pertanyaan" class="mt-6 min-h-[56px] font-display text-lg font-extrabold leading-snug text-slate-900 sm:text-xl"></h3>

                <div id="quiz-modal-pilihan" class="mt-5 grid gap-3 sm:grid-cols-2"></div>

                {{-- Penjelasan singkat, tampil setelah jawaban dipilih --}}
                <div id="quiz-modal-penjelasan" class="mt-4 hidden rounded-xl border border-sky-100 bg-sky-50 px-4 py-3 text-xs leading-relaxed text-sky-800">
                    <i class="fas fa-circle-info mr-1 text-sky-500"></i>
                    <span id="quiz-modal-penjelasan-teks"></span>
                </div>

                <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                    <button type="button"
                            id="quiz-modal-prev"
                            class="hidden items-center gap-1.5 rounded-full border border-slate-200 px-5 py-2.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-100">
                        <i class="fas fa-chevron-left text-[10px]"></i> Sebelumnya
                    </button>
                    <span class="hidden text-xs italic text-slate-400 sm:inline">Pilih salah satu jawaban</span>

                    <button type="button"
                            id="quiz-modal-next"
                            disabled
                            class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-7 py-3 font-display text-xs font-bold text-white shadow-md shadow-brand-600/20 transition hover:bg-brand-700 disabled:cursor-not-allowed disabled:opacity-40">
                        <span id="quiz-modal-next-label">Lanjut</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </div>

            {{-- ================= STATE: HASIL ================= --}}
            <div id="quiz-modal-result" class="hidden p-8 text-center sm:p-12">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full border-2 border-brand-200 bg-blue-50 text-brand-600">
                    <i class="fas fa-trophy text-3xl"></i>
                </div>

                <p id="quiz-modal-result-wisata" class="mt-4 font-display text-sm font-bold text-brand-600"></p>
                <h3 id="quiz-modal-result-score" class="mt-2 font-display text-2xl font-extrabold text-slate-900 sm:text-3xl"></h3>
                <p id="quiz-modal-result-points" class="mt-2 text-sm font-medium text-slate-500"></p>

                {{-- Banner sertifikat untuk skor sempurna --}}
                <div id="quiz-modal-cert-banner" class="mt-7 hidden rounded-2xl border-2 border-amber-300 bg-gradient-to-r from-amber-50 via-amber-100/60 to-amber-50 p-6 text-center shadow-sm">
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-amber-500 text-white shadow-md">
                        <i class="fas fa-certificate text-xl"></i>
                    </div>
                    <h4 class="font-display text-lg font-extrabold text-amber-950">🎉 Luar Biasa! Nilai Sempurna 100 Poin!</h4>
                    <p class="mt-2 text-sm leading-relaxed text-amber-900/80">
                        Anda berhasil menjawab semua soal dengan benar dan berhak mendapatkan sertifikat penghargaan.
                    </p>
                    <button type="button"
                            id="quiz-modal-cert-btn"
                            class="mt-4 inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-amber-600 to-amber-700 px-6 py-3 font-display text-xs font-bold text-white shadow-lg shadow-amber-600/30 transition hover:from-amber-700 hover:to-amber-800 active:scale-95">
                        <i class="fas fa-award"></i> Klaim &amp; Unduh Sertifikat
                    </button>
                </div>

                {{-- Pesan bila belum sempurna --}}
                <p id="quiz-modal-try-again" class="mt-5 hidden text-xs text-slate-500">
                    <i class="fas fa-circle-info text-brand-500 mr-1"></i>
                    Jawab benar semua soal untuk meraih 100 poin dan sertifikat penghargaan!
                </p>

                <div class="mt-7 flex flex-col items-center justify-center gap-3 sm:flex-row">
                    <button type="button"
                            id="quiz-modal-restart"
                            class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-7 py-3 font-display text-sm font-bold text-white shadow-md shadow-brand-600/20 transition hover:bg-brand-700">
                        <i class="fas fa-rotate-right text-xs"></i> Ulangi Kuis Ini
                    </button>
                    <button type="button"
                            id="quiz-modal-done"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-7 py-3 font-display text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                        <i class="fas fa-check text-xs"></i> Selesai
                    </button>
                </div>
            </div>

            {{-- ================= STATE: GAGAL MEMUAT ================= --}}
            <div id="quiz-modal-error" class="hidden p-10 text-center sm:p-14">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-rose-50 text-rose-500">
                    <i class="fas fa-triangle-exclamation text-2xl"></i>
                </div>
                <p class="mt-4 font-display text-sm font-bold text-slate-800">Soal kuis gagal dimuat</p>
                <p class="mt-1 text-xs text-slate-500">Pastikan koneksi stabil, lalu coba lagi.</p>
                <button type="button"
                        id="quiz-modal-retry"
                        class="mt-5 inline-flex items-center gap-2 rounded-full bg-brand-600 px-6 py-2.5 font-display text-xs font-bold text-white transition hover:bg-brand-700">
                    <i class="fas fa-rotate-right text-[10px]"></i> Coba Lagi
                </button>
            </div>

        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const modal = document.getElementById('quiz-modal');
    const triggerBtn = document.getElementById('mulai-kuis-btn');

    if (!modal || !triggerBtn) {
        return;
    }

    const $ = (id) => document.getElementById(id);

    const elLoading = $('quiz-modal-loading');
    const elQuestion = $('quiz-modal-question');
    const elResult = $('quiz-modal-result');
    const elError = $('quiz-modal-error');

    const elWisata = $('quiz-modal-wisata');
    const elProgress = $('quiz-modal-progress');
    const elPoinInfo = $('quiz-modal-poin-info');
    const elBar = $('quiz-modal-bar');
    const elPertanyaan = $('quiz-modal-pertanyaan');
    const elPilihan = $('quiz-modal-pilihan');
    const elPenjelasan = $('quiz-modal-penjelasan');
    const elPenjelasanTeks = $('quiz-modal-penjelasan-teks');
    const elPrev = $('quiz-modal-prev');
    const elNext = $('quiz-modal-next');
    const elNextLabel = $('quiz-modal-next-label');

    // State kuis sesi ini.
    let soal = [];
    let jawaban = [];
    let indeks = 0;

    function tampilkan(panel) {
        [elLoading, elQuestion, elResult, elError].forEach((el) => el.classList.add('hidden'));
        panel.classList.remove('hidden');
    }

    function bukaModal() {
        modal.classList.remove('hidden');
    }

    function tutupModal() {
        modal.classList.add('hidden');
    }

    /** Ambil soal destinasi ini dari database lewat endpoint kuis. */
    async function muatSoal() {
        bukaModal();
        tampilkan(elLoading);

        try {
            const response = await fetch(modal.dataset.url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                throw new Error('Kuis tidak tersedia.');
            }

            const data = await response.json();

            if (!data.soal || data.soal.length === 0) {
                throw new Error('Kuis tidak memiliki soal.');
            }

            soal = data.soal;
            mulaiKuis();
        } catch (error) {
            console.error('Gagal memuat soal kuis:', error);
            tampilkan(elError);
        }
    }

    function mulaiKuis() {
        jawaban = new Array(soal.length).fill(null);
        indeks = 0;

        elWisata.textContent = modal.dataset.nama;
        elPoinInfo.textContent = '+100 poin bila semua ' + soal.length + ' soal dijawab benar';

        tampilkan(elQuestion);
        renderSoal();
    }

    /** Bangun tombol pilihan jawaban secara aman (tanpa innerHTML untuk teks). */
    function renderSoal() {
        const item = soal[indeks];
        const total = soal.length;
        const terpilih = jawaban[indeks];

        elProgress.textContent = 'Soal ' + (indeks + 1) + ' dari ' + total;
        elBar.style.width = (((indeks + 1) / total) * 100) + '%';

        elPertanyaan.textContent = item.pertanyaan;
        elPilihan.innerHTML = '';
        elPenjelasan.classList.add('hidden');

        item.pilihan.forEach(function (teks, urutan) {
            const huruf = String.fromCharCode(65 + urutan);
            const aktif = terpilih === urutan;

            const tombol = document.createElement('button');
            tombol.type = 'button';
            tombol.className = 'flex w-full items-center gap-3 rounded-2xl border p-4 text-left font-display text-sm transition-all '
                + (aktif
                    ? 'border-brand-600 bg-brand-50/90 shadow-sm ring-2 ring-brand-500/30'
                    : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50/80');

            const badge = document.createElement('span');
            badge.className = 'grid h-6 w-6 shrink-0 place-items-center rounded-full text-[11px] font-bold '
                + (aktif ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-500');
            badge.textContent = huruf;

            const label = document.createElement('span');
            label.className = 'flex-1 leading-relaxed';
            label.textContent = teks;

            tombol.appendChild(badge);
            tombol.appendChild(label);

            tombol.addEventListener('click', function () {
                pilihJawaban(urutan);
            });

            elPilihan.appendChild(tombol);
        });

        // Penjelasan hanya tampil setelah pengguna memilih jawaban.
        if (terpilih !== null) {
            tampilkanPenjelasan(item);
        }

        elPrev.classList.toggle('hidden', indeks === 0);
        if (indeks > 0) {
            elPrev.style.display = 'inline-flex';
        }

        elNext.disabled = terpilih === null;
        elNextLabel.textContent = indeks === total - 1 ? 'Selesaikan Kuis' : 'Lanjut';
    }

    function tampilkanPenjelasan(item) {
        if (item.penjelasan) {
            elPenjelasanTeks.textContent = item.penjelasan;
            elPenjelasan.classList.remove('hidden');
        } else {
            elPenjelasan.classList.add('hidden');
        }
    }

    function pilihJawaban(urutan) {
        jawaban[indeks] = urutan;
        renderSoal();
    }

    function lanjutSoal() {
        if (jawaban[indeks] === null) {
            return;
        }

        if (indeks < soal.length - 1) {
            indeks++;
            renderSoal();
        } else {
            selesaiKuis();
        }
    }

    function selesaiKuis() {
        const total = soal.length;
        let benar = 0;

        soal.forEach(function (item, i) {
            if (jawaban[i] === item.jawaban_benar) {
                benar++;
            }
        });

        // Skor proporsional: semua benar = 100 poin, apa pun jumlah soalnya.
        const poin = Math.round((benar / total) * 100);
        const sempurna = benar === total;

        // Simpan hasil sesi agar konsisten dengan sistem poin portal.
        localStorage.setItem('quiz_' + modal.dataset.slug + '_score', String(poin));
        localStorage.setItem('bandung_petualang_points', String(poin));
        document.querySelectorAll('.adventure-points-num').forEach(function (el) {
            el.textContent = poin;
        });

        if (sempurna) {
            localStorage.setItem('bandung_wisata_quiz_passed', 'true');
            localStorage.setItem('quiz_' + modal.dataset.slug + '_passed', 'true');
            localStorage.setItem('bandung_wisata_cert_wisata', modal.dataset.nama);
            localStorage.setItem('bandung_wisata_cert_date', new Date().toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
            }));
        }

        $('quiz-modal-result-wisata').textContent = 'Kuis: ' + modal.dataset.nama;
        $('quiz-modal-result-score').textContent = 'Skor Anda ' + benar + '/' + total;
        $('quiz-modal-result-points').textContent = 'Anda memperoleh ' + poin + ' poin dari kuis ini.';

        $('quiz-modal-cert-banner').classList.toggle('hidden', !sempurna);
        $('quiz-modal-try-again').classList.toggle('hidden', sempurna);

        tampilkan(elResult);
    }

    triggerBtn.addEventListener('click', muatSoal);
    $('quiz-modal-close').addEventListener('click', tutupModal);
    $('quiz-modal-done').addEventListener('click', tutupModal);
    $('quiz-modal-retry').addEventListener('click', muatSoal);
    $('quiz-modal-restart').addEventListener('click', mulaiKuis);
    elPrev.addEventListener('click', function () {
        if (indeks > 0) {
            indeks--;
            renderSoal();
        }
    });
    elNext.addEventListener('click', lanjutSoal);
    $('quiz-modal-cert-btn').addEventListener('click', function () {
        // Jumlah soal pada teks sertifikat disamakan dengan kuis yang
        // baru diselesaikan pengguna.
        if (typeof setCertificateQuizStats === 'function') {
            setCertificateQuizStats(soal.length);
        }

        if (typeof openCertificateModal === 'function') {
            openCertificateModal();
        }
    });
})();
</script>
