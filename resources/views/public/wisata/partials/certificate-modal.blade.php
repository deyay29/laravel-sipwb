{{--
    Modal Sertifikat Kelulusan Kuis Wisata Bandung (100 Poin)
    Desain resmi, elegan, berstandar visual tinggi dengan fitur cetak & unduh PNG.
--}}
<div id="certificate-modal"
     class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-950/80 backdrop-blur-sm transition-opacity duration-300"
     role="dialog"
     aria-modal="true"
     aria-labelledby="cert-modal-title">
    
    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
        <div class="relative w-full max-w-4xl transform overflow-hidden rounded-3xl bg-white p-6 sm:p-8 text-left shadow-2xl transition-all border border-slate-100">
            
            {{-- Tombol Tutup --}}
            <button type="button"
                    onclick="closeCertificateModal()"
                    class="absolute right-5 top-5 grid h-10 w-10 place-items-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-800 transition">
                <i class="fas fa-xmark text-lg"></i>
            </button>

            {{-- Header Modal --}}
            <div id="cert-modal-header" class="text-center pb-5 border-b border-slate-100">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">
                    <i class="fas fa-award text-amber-600"></i> Sertifikat Resmi Petualang Wisata
                </span>
                <h3 id="cert-modal-title" class="mt-2 font-display text-2xl font-extrabold text-slate-900 sm:text-3xl">
                    Selamat Atas Prestasi Sempurna Anda! 🏆
                </h3>
                <p class="mt-1 text-sm text-slate-600">
                    Anda berhasil menjawab semua 10 pertanyaan dengan benar dan meraih nilai 100/100 Poin.
                </p>
                
                {{-- Form Ganti Nama Penerima --}}
                <div class="mt-4 mx-auto max-w-md flex items-center gap-2 rounded-xl border border-slate-300 bg-slate-50 px-3 py-2 focus-within:border-brand-500 focus-within:ring-2 focus-within:ring-brand-500/20">
                    <i class="fas fa-user-pen text-slate-400 text-sm"></i>
                    <input type="text"
                           id="cert-input-name"
                           value="{{ auth()->check() ? auth()->user()->name : 'Petualang Sejati Bandung' }}"
                           placeholder="Ketik nama lengkap Anda di sini..."
                           class="w-full bg-transparent text-sm font-semibold text-slate-800 placeholder-slate-400 outline-none"
                           oninput="updateCertificateRecipient(this.value)">
                    <span class="text-[11px] font-medium text-slate-400 shrink-0">Nama Anda</span>
                </div>
            </div>

            {{-- KONTEN SERTIFIKAT (Untuk Tampilan Layar & Cetak) --}}
            <div class="my-6 overflow-x-auto p-1">
                <div id="certificate-print-area"
                     class="mx-auto min-w-[700px] max-w-[820px] rounded-2xl border-8 border-double border-amber-600/40 bg-gradient-to-br from-amber-50/60 via-white to-slate-50 p-8 shadow-inner relative text-slate-900">
                    
                    {{-- Hiasan Sudut Emas --}}
                    <div class="absolute left-3 top-3 h-8 w-8 border-l-2 border-t-2 border-amber-600"></div>
                    <div class="absolute right-3 top-3 h-8 w-8 border-r-2 border-t-2 border-amber-600"></div>
                    <div class="absolute bottom-3 left-3 h-8 w-8 border-l-2 border-b-2 border-amber-600"></div>
                    <div class="absolute bottom-3 right-3 h-8 w-8 border-r-2 border-b-2 border-amber-600"></div>

                    {{-- Kop Sertifikat --}}
                    <div class="flex items-center justify-between border-b-2 border-amber-500/30 pb-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/logo-biru.png') }}" alt="Logo Bandung Info Center"
                                 class="h-12 w-auto object-contain drop-shadow-sm">
                            <div class="text-left">
                                <h4 class="font-display text-sm font-extrabold tracking-wider text-navy-900 uppercase">
                                    Pemerintah Kota Bandung
                                </h4>
                                <p class="text-[11px] font-medium tracking-wide text-slate-500 uppercase">
                                    Dinas Pariwisata &amp; Kebudayaan &bull; Bandung Info Center
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-block rounded-md bg-amber-500/10 px-2.5 py-1 font-mono text-[11px] font-bold text-amber-900 border border-amber-400/30">
                                NO: <span id="cert-serial-number">BDG-WISATA-100-2026</span>
                            </span>
                        </div>
                    </div>

                    {{-- Judul Utama Sertifikat --}}
                    <div class="my-6 text-center">
                        <p class="font-serif italic text-amber-700 text-sm tracking-widest uppercase">
                            Certificate of Excellence &bull; Pariwisata Kota Bandung
                        </p>
                        <h2 class="mt-1 font-display text-3xl sm:text-4xl font-extrabold text-navy-900 tracking-tight">
                            SERTIFIKAT PENGHARGAAN
                        </h2>
                        <div class="mx-auto mt-2 h-1 w-24 rounded-full bg-gradient-to-r from-amber-400 via-amber-600 to-amber-400"></div>
                        
                        <p class="mt-4 text-xs uppercase tracking-widest text-slate-500">
                            Diberikan dengan hormat dan apresiasi setinggi-tingginya kepada:
                        </p>

                        {{-- Nama Penerima --}}
                        <div class="my-4">
                            <span id="cert-display-name" class="font-display text-2xl sm:text-3xl font-extrabold text-brand-700 underline decoration-amber-500/50 decoration-2 underline-offset-8">
                                {{ auth()->check() ? auth()->user()->name : 'Petualang Sejati Bandung' }}
                            </span>
                        </div>

                        {{-- Deskripsi Kelulusan --}}
                        <p class="mx-auto max-w-2xl text-xs sm:text-sm leading-relaxed text-slate-600">
                            Atas keberhasilan luar biasa menyelesaikan seluruh <strong class="text-slate-900">10 Pertanyaan Kuis Wisata Kota Bandung</strong>
                            dengan hasil sempurna <strong class="text-emerald-700 font-extrabold">100 / 100 Poin (10 dari 10 Benar)</strong>.
                        </p>
                    </div>

                    {{-- Medali Nilai & Verifikasi --}}
                    <div class="mt-8 grid grid-cols-3 items-end border-t border-slate-200/80 pt-6">
                        {{-- Tanggal Terbit --}}
                        <div class="text-left text-xs text-slate-500">
                            <p>Diterbitkan pada:</p>
                            <p id="cert-display-date" class="font-semibold text-slate-800">
                                {{ now()->translatedFormat('d F Y') }}
                            </p>
                        </div>

                        {{-- Badge Stempel Emas 100 Poin --}}
                        <div class="flex flex-col items-center">
                            <div class="relative grid h-20 w-20 place-items-center rounded-full bg-gradient-to-tr from-amber-600 via-amber-400 to-amber-200 p-1 shadow-lg ring-4 ring-amber-400/30">
                                <div class="grid h-full w-full place-items-center rounded-full bg-navy-900 text-center text-white">
                                    <div>
                                        <p class="text-[9px] font-bold uppercase tracking-wider text-amber-300">Skor</p>
                                        <p class="font-display text-xl font-extrabold leading-none text-white">100</p>
                                        <p class="text-[8px] text-amber-200 uppercase font-semibold">Poin</p>
                                    </div>
                                </div>
                            </div>
                            <span class="mt-1 text-[10px] font-extrabold tracking-wider text-amber-800 uppercase">
                                Nilai Sempurna
                            </span>
                        </div>

                        {{-- Status Verifikasi --}}
                        <div class="text-right">
                            <div class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600">
                                <i class="fas fa-circle-check"></i> Terverifikasi Digital SIPWB
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Tombol Tindakan (Download, Cetak, Tutup) --}}
            <div id="cert-modal-actions" class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5">
                <p class="text-xs text-slate-500">
                    <i class="fas fa-shield-halved text-emerald-500"></i> Sertifikat ini resmi dan dapat disimpan atau dicetak.
                </p>

                <div class="flex flex-wrap items-center gap-2">
                    <button type="button"
                            onclick="downloadCertificatePNG()"
                            class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-5 py-2.5 font-display text-xs font-bold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700 active:scale-95">
                        <i class="fas fa-download"></i> Unduh Sertifikat (PNG)
                    </button>

                    <button type="button"
                            onclick="printCertificate()"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 font-display text-xs font-semibold text-slate-700 transition hover:bg-slate-50 hover:text-slate-900">
                        <i class="fas fa-print"></i> Cetak / Simpan PDF
                    </button>

                    <button type="button"
                            onclick="closeCertificateModal()"
                            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 px-4 py-2.5 font-display text-xs font-medium text-slate-500 hover:bg-slate-100">
                        Tutup
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Canvas tersembunyi untuk merender sertifikat ke PNG resolusi tinggi (1600x1100 px) --}}
<canvas id="cert-canvas" width="1600" height="1100" class="hidden"></canvas>

{{-- Print CSS khusus agar ketika user klik Cetak, hanya sertifikat yang tercetak dalam landscape --}}
<style>
@media print {
    body * {
        visibility: hidden !important;
    }
    #certificate-modal,
    #certificate-modal * {
        visibility: visible !important;
    }
    #certificate-modal {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background: transparent !important;
        padding: 0 !important;
    }
    #certificate-print-area {
        position: absolute !important;
        left: 50% !important;
        top: 50% !important;
        transform: translate(-50%, -50%) !important;
        width: 100% !important;
        max-width: 95% !important;
        box-shadow: none !important;
        border-width: 4px !important;
    }
    button, input, #cert-modal-header, #cert-modal-actions {
        display: none !important;
    }
}
</style>
