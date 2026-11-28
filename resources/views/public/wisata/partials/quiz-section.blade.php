{{--
    Kategori Quiz Wisata Bandung — Per-Wisata
    - Setiap wisata memiliki 10 pertanyaan masing-masing
    - 1 pertanyaan benar = 10 poin
    - Jika benar semua = 100 poin & langsung tampil unduh sertifikat
--}}
<div id="quiz-tab-content" class="w-full">
    <div class="w-full space-y-6">

        {{-- ==================== 0. PILIHAN WISATA (SELECTOR) ==================== --}}
        <div id="quiz-wisata-selector" class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-[0_4px_24px_rgba(15,23,42,0.06)]">
            <div class="text-center mb-6">
                <h3 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900">
                    <i class="fas fa-circle-question text-brand-600 mr-1"></i> Pilih Kuis Wisata
                </h3>
                <p class="mt-1.5 text-sm text-slate-500">
                    Setiap wisata memiliki 10 soal unik. Jawab semua benar untuk meraih 100 poin & sertifikat!
                </p>
            </div>

            <div id="quiz-wisata-grid" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                {{-- Dirender oleh JS berdasarkan data QUIZ_PER_WISATA --}}
            </div>
        </div>

        {{-- ==================== 1. TAMPILAN PERTANYAAN (SEDANG KUIS) ==================== --}}
        <div id="quiz-question-card" class="hidden rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 lg:p-10 shadow-[0_4px_24px_rgba(15,23,42,0.06)] transition-all">
            
            <div class="grid lg:grid-cols-12 gap-8 items-start">
                
                {{-- Kolom Kiri: Soal, Progress, Pilihan Jawaban --}}
                <div class="lg:col-span-8 space-y-6">
                    
                    {{-- Tombol Kembali ke Daftar Wisata --}}
                    <button type="button" onclick="backToWisataSelector()" class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-600 hover:text-brand-800 transition">
                        <i class="fas fa-arrow-left text-[10px]"></i> Kembali ke daftar kuis
                    </button>

                    {{-- Nama Wisata yang Sedang Dikuis --}}
                    <div class="flex items-center gap-3 rounded-xl bg-brand-50/80 border border-brand-100 px-4 py-2.5">
                        <i class="fas fa-location-dot text-brand-600"></i>
                        <span id="quiz-wisata-name" class="font-display text-sm font-bold text-brand-800"></span>
                    </div>

                    {{-- Header Kuis --}}
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <span id="quiz-progress-text" class="font-display text-xs font-bold uppercase tracking-wider text-slate-400">
                                Soal 1 dari 10
                            </span>
                            <h4 class="text-xs font-semibold text-emerald-600 mt-0.5">
                                <i class="fas fa-circle-check text-[11px]"></i> +10 Poin per jawaban benar
                            </h4>
                        </div>

                        {{-- Target Poin --}}
                        <div class="flex items-center gap-2 rounded-full bg-brand-50 px-3.5 py-1.5 border border-brand-100">
                            <i class="fas fa-award text-brand-600 text-xs"></i>
                            <span class="font-display text-xs font-bold text-brand-700">
                                Target: 100 Poin
                            </span>
                        </div>
                    </div>

                    {{-- Bar Progress --}}
                    <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                        <div id="quiz-progress-bar"
                             class="h-full rounded-full bg-gradient-to-r from-brand-600 to-blue-500 transition-all duration-300"
                             style="width: 10%;"></div>
                    </div>

                    {{-- Teks Pertanyaan --}}
                    <div class="min-h-[64px]">
                        <h3 id="quiz-question-title" class="font-display text-xl sm:text-2xl font-extrabold leading-snug text-slate-900"></h3>
                    </div>

                    {{-- Pilihan Jawaban --}}
                    <div id="quiz-options-container" class="grid gap-3.5 sm:grid-cols-2"></div>

                    {{-- Navigasi --}}
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <button type="button"
                                id="quiz-prev-btn"
                                onclick="prevQuizQuestion()"
                                class="hidden items-center gap-1.5 rounded-full px-5 py-2.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-100 border border-slate-200">
                            <i class="fas fa-chevron-left text-[10px]"></i> Sebelumnya
                        </button>

                        <div class="ml-auto flex items-center gap-3">
                            <span id="quiz-hint-text" class="text-xs text-slate-400 italic hidden sm:inline">
                                Pilih salah satu jawaban
                            </span>
                            <button type="button"
                                    id="quiz-next-btn"
                                    onclick="nextQuizQuestion()"
                                    disabled
                                    class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-7 py-3 font-display text-xs font-bold text-white shadow-md shadow-brand-600/20 transition hover:bg-brand-700 disabled:opacity-40 disabled:cursor-not-allowed">
                                <span>Lanjut</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </button>
                        </div>
                    </div>

                </div>

                {{-- Kolom Kanan: Info Kuis --}}
                <div class="lg:col-span-4 rounded-2xl bg-slate-50 border border-slate-200/80 p-6 space-y-4">
                    <div class="flex items-center gap-3 border-b border-slate-200 pb-3">
                        <div class="grid h-10 w-10 place-items-center rounded-xl bg-amber-500 text-white shadow-sm">
                            <i class="fas fa-certificate text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-display text-sm font-extrabold text-slate-900">Sertifikat Petualang</h4>
                            <p class="text-[11px] text-slate-500">Penghargaan Resmi Pemkot Bandung</p>
                        </div>
                    </div>

                    <ul class="space-y-3 text-xs text-slate-600">
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-emerald-600 mt-0.5 shrink-0"></i>
                            <span>Terdapat <strong>10 soal</strong> khusus untuk wisata ini.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-emerald-600 mt-0.5 shrink-0"></i>
                            <span>Tiap 1 jawaban benar memberikan <strong>+10 poin</strong> petualang.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-emerald-600 mt-0.5 shrink-0"></i>
                            <span>Jika benar semua (<strong>100 Poin</strong>), langsung dapat <strong>Sertifikat Digital Resmi</strong> yang bisa diunduh!</span>
                        </li>
                    </ul>

                    {{-- Poin Sesi Aktif per Destinasi --}}
                    <div class="rounded-xl bg-white border border-brand-200 p-4 flex items-center justify-between shadow-sm">
                        <div class="text-xs">
                            <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Poin Sesi Aktif</span>
                            <span class="font-display text-lg font-extrabold text-brand-700">
                                <span id="quiz-active-session-points">0</span> / 100 Poin
                            </span>
                            <span class="block text-[10px] text-slate-400 mt-0.5">
                                Khusus kuis <span id="quiz-active-session-name" class="font-semibold text-slate-500">-</span>
                            </span>
                        </div>
                        <span class="rounded-full bg-brand-50 px-2.5 py-1 text-[11px] font-bold text-brand-700 border border-brand-200">
                            Sesi Kuis
                        </span>
                    </div>

                    <div class="rounded-xl bg-white border border-slate-200 p-4 flex items-center justify-between">
                        <div class="text-xs">
                            <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Poin Petualang Anda</span>
                            <span class="font-display text-lg font-extrabold text-brand-700">
                                <span class="adventure-points-num">30</span> Poin
                            </span>
                        </div>
                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700 border border-emerald-200">
                            Aktif
                        </span>
                    </div>
                </div>

            </div>

        </div>

        {{-- ==================== 2. TAMPILAN SELESAI (SKOR & HASIL) ==================== --}}
        <div id="quiz-result-card" class="hidden rounded-3xl border border-slate-200 bg-white p-8 sm:p-12 shadow-[0_4px_24px_rgba(15,23,42,0.06)] text-center transition-all w-full">
            
            {{-- Icon Piala --}}
            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-blue-50 border-2 border-brand-200 text-brand-600">
                <i class="fas fa-trophy text-4xl"></i>
            </div>

            {{-- Nama Wisata --}}
            <p id="quiz-result-wisata-name" class="mt-4 font-display text-sm font-bold text-brand-600"></p>

            {{-- Judul Skor --}}
            <h3 id="quiz-score-display" class="mt-2 font-display text-2xl sm:text-3xl font-extrabold text-slate-900">
                Skor Anda 1/10
            </h3>

            {{-- Poin --}}
            <p id="quiz-points-added-display" class="mt-2 text-sm font-medium text-slate-500">
                +10 poin ditambahkan.
            </p>

            {{-- BANNER SERTIFIKAT (JIKA 100 POIN) --}}
            <div id="quiz-certificate-banner" class="mt-8 hidden rounded-2xl border-2 border-amber-300 bg-gradient-to-r from-amber-50 via-amber-100/60 to-amber-50 p-6 sm:p-8 text-center shadow-sm max-w-2xl mx-auto">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-amber-500 text-white shadow-md mb-3">
                    <i class="fas fa-certificate text-2xl"></i>
                </div>
                <h4 class="font-display text-xl font-extrabold text-amber-950">
                    🎉 Luar Biasa! Nilai Sempurna 100 Poin!
                </h4>
                <p class="mt-2 text-sm text-amber-900/80 max-w-lg mx-auto leading-relaxed">
                    Selamat! Anda berhasil menjawab semua 10 pertanyaan dengan benar dan berhak mendapatkan <strong>Sertifikat Resmi Ahli Wisata Kota Bandung</strong>.
                </p>

                <div class="mt-5">
                    <button type="button"
                            onclick="openCertificateModal()"
                            class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-amber-600 to-amber-700 px-7 py-3.5 font-display text-xs font-bold text-white shadow-lg shadow-amber-600/30 transition hover:from-amber-700 hover:to-amber-800 active:scale-95">
                        <i class="fas fa-award"></i>
                        <span>Klaim &amp; Unduh Sertifikat</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </div>

            {{-- Pesan jika belum 100 poin --}}
            <div id="quiz-try-again-banner" class="mt-4 text-xs text-slate-500 max-w-md mx-auto">
                <p>
                    <i class="fas fa-circle-info text-brand-500 mr-1"></i>
                    Jawab benar semua 10 pertanyaan untuk meraih 100 poin dan sertifikat petualang!
                </p>
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                <button type="button"
                        onclick="restartQuiz()"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-full bg-brand-600 px-8 py-3.5 font-display text-sm font-bold text-white shadow-md shadow-brand-600/20 transition hover:bg-brand-700 active:scale-95">
                    <i class="fas fa-rotate-right text-xs"></i>
                    <span>Ulangi Quiz Ini</span>
                </button>

                <button type="button"
                        onclick="backToWisataSelector()"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-full border border-slate-300 bg-white px-7 py-3.5 font-display text-sm font-bold text-slate-700 transition hover:bg-slate-50 active:scale-95">
                    <i class="fas fa-list text-xs"></i>
                    <span>Pilih Kuis Wisata Lain</span>
                </button>

                <button type="button"
                        id="view-saved-cert-btn"
                        onclick="openCertificateModal()"
                        class="hidden w-full sm:w-auto items-center justify-center gap-2 rounded-full border border-amber-400 bg-amber-50 px-7 py-3.5 font-display text-sm font-bold text-amber-900 transition hover:bg-amber-100">
                    <i class="fas fa-award text-amber-600"></i>
                    <span>Buka Sertifikat Tersimpan</span>
                </button>
            </div>

        </div>

    </div>
</div>

<script>
/**
 * BANK SOAL PER WISATA
 * Setiap wisata punya 10 soal, 10 poin per soal, total 100 poin.
 */
const QUIZ_PER_WISATA = {
    "Gedung Sate": {
        icon: "fa-landmark",
        color: "from-blue-600 to-brand-700",
        questions: [
            { question: "Gedung Sate mulai dibangun pada tahun?", options: ["1920", "1945", "1955", "1901"], correctIndex: 0, explanation: "Pembangunan Gedung Sate dimulai pada 27 Juli 1920 dan rampung pada September 1924." },
            { question: "Berapa jumlah ornamen tusuk sate di puncak menara Gedung Sate?", options: ["6 buah", "4 buah", "8 buah", "10 buah"], correctIndex: 0, explanation: "Terdapat 6 ornamen berbentuk bulat menyerupai tusuk sate." },
            { question: "Ornamen tusuk sate di Gedung Sate melambangkan?", options: ["Biaya 6 juta gulden", "6 bupati pertama", "6 pahlawan gugur", "6 pintu gerbang"], correctIndex: 0, explanation: "Enam ornamen menandakan biaya pembangunan sebesar 6 juta Gulden." },
            { question: "Siapa arsitek yang merancang Gedung Sate?", options: ["J. Gerber", "Thomas Karsten", "A.F. Aalbers", "H.P. Berlage"], correctIndex: 0, explanation: "Ir. J. Gerber adalah arsitek yang merancang Gedung Sate." },
            { question: "Gedung Sate selesai dibangun pada tahun?", options: ["1924", "1920", "1930", "1935"], correctIndex: 0, explanation: "Gedung Sate selesai dibangun pada September 1924." },
            { question: "Pada masa kolonial, Gedung Sate berfungsi sebagai?", options: ["Kantor pusat Departement van Verkeer en Waterstaat", "Istana Gubernur Jenderal", "Museum Seni", "Rumah Sakit Militer"], correctIndex: 0, explanation: "Gedung ini awalnya sebagai kantor pusat Departemen Lalu Lintas dan Pengairan." },
            { question: "Peristiwa heroik di Gedung Sate tahun 1945 melibatkan berapa pemuda yang gugur?", options: ["7 pemuda", "5 pemuda", "10 pemuda", "3 pemuda"], correctIndex: 0, explanation: "Tujuh pemuda gugur saat mempertahankan Gedung Sate dari pasukan Jepang/Sekutu." },
            { question: "Gedung Sate terletak di jalan?", options: ["Jl. Diponegoro", "Jl. Asia Afrika", "Jl. Braga", "Jl. Merdeka"], correctIndex: 0, explanation: "Gedung Sate beralamat di Jl. Diponegoro No.22, Bandung." },
            { question: "Gaya arsitektur Gedung Sate merupakan perpaduan antara?", options: ["Eropa dan lokal Indonesia", "Jepang dan Belanda", "Spanyol dan Portugis", "Cina dan Belanda"], correctIndex: 0, explanation: "Gedung Sate memadukan arsitektur Eropa dengan ornamen lokal nusantara." },
            { question: "Saat ini, Gedung Sate difungsikan sebagai?", options: ["Kantor Gubernur Jawa Barat", "Museum Nasional", "Kantor Walikota Bandung", "Gedung DPRD"], correctIndex: 0, explanation: "Gedung Sate merupakan kantor Gubernur Jawa Barat sejak kemerdekaan." }
        ]
    },
    "Tangkuban Perahu": {
        icon: "fa-mountain",
        color: "from-emerald-600 to-teal-700",
        questions: [
            { question: "Tangkuban Perahu terletak di kawasan?", options: ["Lembang", "Dago", "Ciwidey", "Pangalengan"], correctIndex: 0, explanation: "Tangkuban Perahu berada di kawasan Lembang, Bandung Utara." },
            { question: "Legenda yang berkaitan dengan Tangkuban Perahu adalah?", options: ["Sangkuriang", "Lutung Kasarung", "Malin Kundang", "Timun Mas"], correctIndex: 0, explanation: "Legenda Sangkuriang dan Dayang Sumbi erat kaitannya dengan Tangkuban Perahu." },
            { question: "Tangkuban Perahu merupakan jenis gunung?", options: ["Stratovolcano aktif", "Gunung mati", "Gunung berapi bawah laut", "Gunung es"], correctIndex: 0, explanation: "Tangkuban Perahu adalah gunung api aktif bertipe stratovolcano." },
            { question: "Kawah utama Tangkuban Perahu bernama?", options: ["Kawah Ratu", "Kawah Putih", "Kawah Domas", "Kawah Upas"], correctIndex: 0, explanation: "Kawah Ratu adalah kawah utama dan terbesar di Tangkuban Perahu." },
            { question: "Ketinggian puncak Tangkuban Perahu sekitar?", options: ["2.084 mdpl", "1.500 mdpl", "3.000 mdpl", "2.500 mdpl"], correctIndex: 0, explanation: "Puncak Tangkuban Perahu berada di ketinggian sekitar 2.084 meter di atas permukaan laut." },
            { question: "Nama 'Tangkuban Perahu' berarti?", options: ["Perahu terbalik", "Gunung tinggi", "Lembah hijau", "Danau indah"], correctIndex: 0, explanation: "Tangkuban Perahu berarti perahu terbalik, karena bentuk gunungnya menyerupai perahu terbalik." },
            { question: "Kawah yang bisa digunakan untuk berendam air panas di Tangkuban Perahu adalah?", options: ["Kawah Domas", "Kawah Ratu", "Kawah Upas", "Kawah Baru"], correctIndex: 0, explanation: "Kawah Domas memiliki sumber air panas yang bisa dikunjungi pengunjung." },
            { question: "Letusan terakhir Tangkuban Perahu terjadi pada tahun?", options: ["2019", "2010", "2005", "2000"], correctIndex: 0, explanation: "Tangkuban Perahu mengalami erupsi freatik pada Juli 2019." },
            { question: "Tangkuban Perahu berada di kabupaten?", options: ["Bandung Barat", "Bandung", "Cimahi", "Sumedang"], correctIndex: 0, explanation: "Secara administratif, Tangkuban Perahu terletak di Kabupaten Bandung Barat." },
            { question: "Flora khas yang banyak ditemukan di kawasan Tangkuban Perahu adalah?", options: ["Cantigi dan Edelweiss Jawa", "Raflesia Arnoldii", "Anggrek Bulan", "Kelapa Sawit"], correctIndex: 0, explanation: "Cantigi dan Edelweiss Jawa tumbuh subur di ketinggian Tangkuban Perahu." }
        ]
    },
    "Museum Geologi Bandung": {
        icon: "fa-building-columns",
        color: "from-violet-600 to-purple-700",
        questions: [
            { question: "Museum Geologi Bandung didirikan pada tahun?", options: ["1929", "1945", "1950", "1920"], correctIndex: 0, explanation: "Museum Geologi diresmikan pada 16 Mei 1929 bertepatan dengan pembukaan De Vierde Pacific Science Congress." },
            { question: "Museum Geologi berlokasi di?", options: ["Jl. Diponegoro No.57", "Jl. Asia Afrika No.1", "Jl. Braga No.10", "Jl. Merdeka No.5"], correctIndex: 0, explanation: "Museum Geologi beralamat di Jl. Diponegoro No.57, Bandung." },
            { question: "Koleksi utama Museum Geologi meliputi?", options: ["Fosil, mineral, dan batuan", "Lukisan dan patung", "Senjata tradisional", "Kain dan tekstil"], correctIndex: 0, explanation: "Museum Geologi menyimpan fosil, mineral, batuan vulkanik, dan artefak geologi lainnya." },
            { question: "Fosil replika hewan purba apa yang menjadi ikon Museum Geologi?", options: ["Stegodon (Gajah purba)", "Dinosaurus T-Rex", "Mammoth", "Pterodactyl"], correctIndex: 0, explanation: "Replika fosil Stegodon (gajah purba) menjadi salah satu koleksi paling terkenal." },
            { question: "Museum Geologi berada di bawah naungan?", options: ["Badan Geologi, Kementerian ESDM", "Kementerian Pendidikan", "Pemerintah Kota Bandung", "TNI"], correctIndex: 0, explanation: "Museum ini dikelola oleh Badan Geologi, Kementerian Energi dan Sumber Daya Mineral." },
            { question: "Berapa jumlah perkiraan koleksi yang dimiliki Museum Geologi?", options: ["Lebih dari 250.000 koleksi", "10.000 koleksi", "50.000 koleksi", "1.000 koleksi"], correctIndex: 0, explanation: "Museum Geologi memiliki lebih dari 250.000 koleksi geologi dari seluruh Indonesia." },
            { question: "Gaya arsitektur bangunan Museum Geologi adalah?", options: ["Art Deco", "Gothic", "Modern Minimalis", "Tradisional Sunda"], correctIndex: 0, explanation: "Bangunan Museum Geologi dibangun dengan gaya arsitektur Art Deco khas era kolonial." },
            { question: "Museum Geologi pernah direnovasi besar-besaran dengan bantuan dari negara?", options: ["Jepang (JICA)", "Belanda", "Amerika Serikat", "Australia"], correctIndex: 0, explanation: "Renovasi besar dilakukan dengan bantuan JICA (Japan International Cooperation Agency) pada tahun 2000." },
            { question: "Apa nama peristiwa ilmiah yang bertepatan dengan peresmian Museum Geologi?", options: ["Pacific Science Congress ke-4", "APEC Summit", "Konferensi Asia Afrika", "Sidang PBB"], correctIndex: 0, explanation: "Museum diresmikan bertepatan dengan Pacific Science Congress ke-4 di Bandung tahun 1929." },
            { question: "Di lantai berapa pengunjung bisa melihat koleksi mineral dan batuan?", options: ["Lantai 2", "Lantai 1", "Lantai 3", "Basement"], correctIndex: 0, explanation: "Koleksi mineral dan batuan ditampilkan di lantai 2 Museum Geologi." }
        ]
    },
    "Taman Hutan Raya Bandung": {
        icon: "fa-tree",
        color: "from-green-600 to-emerald-700",
        questions: [
            { question: "Nama resmi Taman Hutan Raya Bandung adalah?", options: ["Tahura Ir. H. Djuanda", "Tahura Gunung Halimun", "Tahura Raden Saleh", "Tahura Ciamis"], correctIndex: 0, explanation: "Nama resminya adalah Taman Hutan Raya Ir. H. Djuanda, dinamai menteri Indonesia." },
            { question: "Tahura Bandung membentang dari kawasan Dago hingga?", options: ["Maribaya", "Ciwidey", "Pangalengan", "Cimahi"], correctIndex: 0, explanation: "Tahura Bandung membentang dari kawasan Dago hingga Maribaya, Lembang." },
            { question: "Luas kawasan Tahura Bandung sekitar?", options: ["590 hektar", "100 hektar", "1.000 hektar", "50 hektar"], correctIndex: 0, explanation: "Luas Tahura Ir. H. Djuanda sekitar 590 hektar." },
            { question: "Gua peninggalan Jepang yang terkenal di Tahura Bandung bernama?", options: ["Gua Jepang", "Gua Belanda", "Gua Pawon", "Gua Kiskendo"], correctIndex: 0, explanation: "Gua Jepang dibangun oleh tentara Jepang saat Perang Dunia II sebagai bunker pertahanan." },
            { question: "Tahura Bandung diresmikan menjadi Taman Hutan Raya pada tahun?", options: ["1985", "1970", "1945", "2000"], correctIndex: 0, explanation: "Tahura Ir. H. Djuanda diresmikan pada tahun 1985 sebagai taman hutan raya pertama di Indonesia." },
            { question: "Air terjun terkenal yang berada di kawasan Tahura Bandung adalah?", options: ["Curug Dago", "Curug Cimahi", "Curug Malela", "Curug Lawe"], correctIndex: 0, explanation: "Curug Dago merupakan air terjun yang menjadi salah satu daya tarik di kawasan Tahura." },
            { question: "Tahura Bandung merupakan taman hutan raya pertama di?", options: ["Indonesia", "Jawa Barat", "Asia Tenggara", "Dunia"], correctIndex: 0, explanation: "Tahura Ir. H. Djuanda adalah taman hutan raya pertama yang diresmikan di Indonesia." },
            { question: "Prasasti di Curug Dago dikaitkan dengan raja dari kerajaan?", options: ["Thailand (Siam)", "Majapahit", "Mataram", "Pajajaran"], correctIndex: 0, explanation: "Prasasti di Curug Dago diduga terkait kunjungan Raja Siam (Thailand) ke Bandung." },
            { question: "Fungsi utama Tahura Bandung selain wisata adalah?", options: ["Konservasi alam dan paru-paru kota", "Pertambangan", "Perkebunan teh", "Peternakan"], correctIndex: 0, explanation: "Tahura berfungsi sebagai kawasan konservasi alam dan paru-paru hijau Kota Bandung." },
            { question: "Jenis vegetasi dominan di Tahura Bandung adalah?", options: ["Hutan hujan tropis pegunungan", "Hutan mangrove", "Padang rumput savana", "Hutan bakau"], correctIndex: 0, explanation: "Tahura didominasi hutan hujan tropis pegunungan dengan keanekaragaman flora tinggi." }
        ]
    },
    "Kampung Naga": {
        icon: "fa-house-chimney",
        color: "from-amber-600 to-orange-700",
        questions: [
            { question: "Kampung Naga terletak di kabupaten?", options: ["Tasikmalaya", "Sumedang", "Garut", "Bandung"], correctIndex: 0, explanation: "Kampung Naga berada di Desa Neglasari, Kecamatan Salawu, Kabupaten Tasikmalaya." },
            { question: "Jumlah rumah di Kampung Naga yang tetap dipertahankan sekitar?", options: ["113 rumah", "50 rumah", "200 rumah", "500 rumah"], correctIndex: 0, explanation: "Kampung Naga mempertahankan sekitar 113 bangunan rumah sesuai adat turun-temurun." },
            { question: "Bahan utama atap rumah di Kampung Naga adalah?", options: ["Daun nipah/ijuk", "Genteng tanah liat", "Seng", "Asbes"], correctIndex: 0, explanation: "Atap rumah tradisional Kampung Naga menggunakan daun nipah atau ijuk." },
            { question: "Masyarakat Kampung Naga tidak memperbolehkan?", options: ["Membangun rumah permanen (beton)", "Bercocok tanam", "Memelihara hewan", "Makan nasi"], correctIndex: 0, explanation: "Adat Kampung Naga melarang pembangunan rumah permanen dari beton untuk menjaga tradisi." },
            { question: "Letak Kampung Naga berada di?", options: ["Lembah di tepi Sungai Ciwulan", "Puncak gunung", "Tepi pantai", "Dataran tinggi terbuka"], correctIndex: 0, explanation: "Kampung Naga terletak di lembah yang dikelilingi bukit hijau di tepi Sungai Ciwulan." },
            { question: "Berapa jumlah anak tangga yang harus dituruni untuk mencapai Kampung Naga?", options: ["Sekitar 439 anak tangga", "100 anak tangga", "50 anak tangga", "1.000 anak tangga"], correctIndex: 0, explanation: "Pengunjung harus menuruni sekitar 439 anak tangga untuk sampai ke Kampung Naga." },
            { question: "Arah hadap seluruh rumah di Kampung Naga adalah?", options: ["Menghadap utara-selatan", "Menghadap timur", "Menghadap barat", "Bebas ke segala arah"], correctIndex: 0, explanation: "Semua rumah di Kampung Naga menghadap arah utara-selatan sesuai adat." },
            { question: "Upacara adat terbesar di Kampung Naga dilaksanakan setiap?", options: ["6 bulan sekali (Hajat Sasih)", "Setiap hari", "Setahun sekali", "5 tahun sekali"], correctIndex: 0, explanation: "Hajat Sasih adalah upacara adat utama yang diselenggarakan setiap 6 bulan sekali." },
            { question: "Listrik dan televisi di Kampung Naga?", options: ["Tidak diperbolehkan di dalam kampung", "Tersedia di setiap rumah", "Hanya di rumah ketua adat", "Menggunakan panel surya"], correctIndex: 0, explanation: "Kampung Naga melarang penggunaan listrik dan televisi di dalam area kampung adat." },
            { question: "Sumber mata pencaharian utama masyarakat Kampung Naga adalah?", options: ["Bertani dan menganyam", "Berdagang di kota", "Menambang", "Nelayan"], correctIndex: 0, explanation: "Bertani padi dan menganyam bambu merupakan mata pencaharian utama warga Kampung Naga." }
        ]
    }
};

let currentWisataKey = null;
let currentQuestionIndex = 0;
let userAnswers = [];

// Poin sesi aktif per destinasi wisata
let activeQuizSession = {
    wisataKey: null,
    points: 0
};

/**
 * Render kartu pemilih wisata
 */
function renderWisataSelector() {
    const grid = document.getElementById('quiz-wisata-grid');
    if (!grid) return;
    grid.innerHTML = '';

    Object.entries(QUIZ_PER_WISATA).forEach(([name, data]) => {
        const storageKey = `quiz_${name.replace(/\s+/g, '_')}_score`;
        const savedScore = localStorage.getItem(storageKey);
        const passed = savedScore === '100';

        const card = document.createElement('button');
        card.type = 'button';
        card.className = `group relative flex flex-col items-center gap-3 rounded-2xl border p-6 text-center transition-all hover:shadow-lg active:scale-[0.98] ${
            passed 
                ? 'border-amber-300 bg-gradient-to-br from-amber-50 to-amber-100/60 shadow-md' 
                : 'border-slate-200 bg-white hover:border-brand-300 hover:bg-brand-50/30'
        }`;
        card.onclick = () => startWisataQuiz(name);

        card.innerHTML = `
            <div class="grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br ${data.color} text-white shadow-lg transition group-hover:scale-110">
                <i class="fas ${data.icon} text-xl"></i>
            </div>
            <h4 class="font-display text-sm font-extrabold text-slate-900">${name}</h4>
            <p class="text-[11px] text-slate-500">10 Soal · 100 Poin</p>
            ${passed
                ? `<span class="inline-flex items-center gap-1 rounded-full bg-amber-500 px-3 py-1 text-[10px] font-bold text-white shadow-sm">
                       <i class="fas fa-trophy text-[9px]"></i> 100 Poin ✓
                   </span>`
                : (savedScore
                    ? `<span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-[10px] font-bold text-slate-600 border border-slate-200">
                           Skor: ${savedScore} Poin
                       </span>`
                    : `<span class="inline-flex items-center gap-1 rounded-full bg-brand-50 px-3 py-1 text-[10px] font-bold text-brand-700 border border-brand-100">
                           <i class="fas fa-play text-[8px]"></i> Mulai Kuis
                       </span>`
                )
            }
        `;
        grid.appendChild(card);
    });
}

/**
 * Update tampilan poin sesi aktif
 */
function updateActiveSessionPointsUI() {
    const pointsEl = document.getElementById('quiz-active-session-points');
    const nameEl = document.getElementById('quiz-active-session-name');
    if (pointsEl) {
        pointsEl.textContent = activeQuizSession.points || 0;
    }
    if (nameEl) {
        nameEl.textContent = activeQuizSession.wisataKey || '-';
    }
}

/**
 * Mulai kuis untuk wisata tertentu
 * Reset poin sesi aktif dan poin petualangan jika berpindah ke destinasi wisata yang berbeda
 */
function startWisataQuiz(wisataName) {
    if (!QUIZ_PER_WISATA[wisataName]) return;

    // Reset poin sesi aktif dan poin petualangan jika pindah ke kuis destinasi yang berbeda
    if (activeQuizSession.wisataKey !== wisataName) {
        activeQuizSession.wisataKey = wisataName;
        activeQuizSession.points = 0;
        // Reset poin petualangan ke 0 untuk sesi kuis destinasi baru
        localStorage.setItem('bandung_petualang_points', '0');
        document.querySelectorAll('.adventure-points-num').forEach(el => {
            el.textContent = '0';
        });
    }

    currentWisataKey = wisataName;
    currentQuestionIndex = 0;
    userAnswers = new Array(QUIZ_PER_WISATA[wisataName].questions.length).fill(null);

    document.getElementById('quiz-wisata-selector').classList.add('hidden');
    document.getElementById('quiz-question-card').classList.remove('hidden');
    document.getElementById('quiz-result-card').classList.add('hidden');
    document.getElementById('quiz-wisata-name').textContent = wisataName;

    updateActiveSessionPointsUI();
    renderQuizQuestion();
}

/**
 * Kembali ke daftar pemilih wisata
 * Reset poin sesi aktif dan poin petualangan jika keluar dari kuis
 */
function backToWisataSelector() {
    // Reset poin sesi aktif dan poin petualangan saat keluar dari kuis
    if (currentWisataKey) {
        activeQuizSession.wisataKey = currentWisataKey;
        activeQuizSession.points = 0;
        localStorage.setItem('bandung_petualang_points', '0');
        document.querySelectorAll('.adventure-points-num').forEach(el => {
            el.textContent = '0';
        });
        updateActiveSessionPointsUI();
    }

    document.getElementById('quiz-wisata-selector').classList.remove('hidden');
    document.getElementById('quiz-question-card').classList.add('hidden');
    document.getElementById('quiz-result-card').classList.add('hidden');
    currentWisataKey = null;
    renderWisataSelector();
}

function renderQuizQuestion() {
    if (!currentWisataKey) return;
    const questions = QUIZ_PER_WISATA[currentWisataKey].questions;
    const q = questions[currentQuestionIndex];
    const totalQ = questions.length;

    const progressText = document.getElementById('quiz-progress-text');
    const progressBar = document.getElementById('quiz-progress-bar');
    const questionTitle = document.getElementById('quiz-question-title');
    const optionsContainer = document.getElementById('quiz-options-container');
    const prevBtn = document.getElementById('quiz-prev-btn');
    const nextBtn = document.getElementById('quiz-next-btn');

    if (!progressText || !progressBar || !questionTitle || !optionsContainer) return;

    progressText.textContent = `Soal ${currentQuestionIndex + 1} dari ${totalQ}`;
    const progressPercent = ((currentQuestionIndex + 1) / totalQ) * 100;
    progressBar.style.width = `${progressPercent}%`;

    questionTitle.textContent = q.question;
    optionsContainer.innerHTML = '';

    const selectedIdx = userAnswers[currentQuestionIndex];

    q.options.forEach((optText, idx) => {
        const isSelected = selectedIdx === idx;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = `w-full text-left rounded-2xl border p-4 sm:p-5 font-display text-sm transition-all flex items-center justify-between ${
            isSelected
                ? 'border-brand-600 bg-brand-50/90 text-brand-900 font-bold shadow-sm ring-2 ring-brand-500/30'
                : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50/80'
        }`;

        btn.innerHTML = `
            <span class="text-slate-900 ${isSelected ? 'font-bold text-brand-900' : ''}">${optText}</span>
            <span class="grid h-6 w-6 place-items-center rounded-full border shrink-0 ${
                isSelected
                    ? 'border-brand-600 bg-brand-600 text-white'
                    : 'border-slate-300 text-transparent'
            }">
                <i class="fas fa-check text-[11px]"></i>
            </span>
        `;

        btn.onclick = () => selectQuizOption(idx);
        optionsContainer.appendChild(btn);
    });

    if (prevBtn) {
        prevBtn.classList.toggle('hidden', currentQuestionIndex === 0);
        if (currentQuestionIndex > 0) prevBtn.style.display = 'inline-flex';
    }

    if (nextBtn) {
        nextBtn.disabled = selectedIdx === null;
        const isLast = currentQuestionIndex === totalQ - 1;
        nextBtn.innerHTML = `
            <span>${isLast ? 'Selesaikan Kuis' : 'Lanjut'}</span>
            <i class="fas ${isLast ? 'fa-circle-check' : 'fa-arrow-right'} text-[10px]"></i>
        `;
    }
}

function selectQuizOption(idx) {
    userAnswers[currentQuestionIndex] = idx;
    renderQuizQuestion();

    // Akumulasi poin sesi aktif jika jawaban benar
    if (currentWisataKey && QUIZ_PER_WISATA[currentWisataKey]) {
        const questions = QUIZ_PER_WISATA[currentWisataKey].questions;
        const q = questions[currentQuestionIndex];
        // Pastikan ini sesi yang aktif
        if (activeQuizSession.wisataKey !== currentWisataKey) {
            activeQuizSession.wisataKey = currentWisataKey;
            activeQuizSession.points = 0;
            // Reset poin petualangan juga
            localStorage.setItem('bandung_petualang_points', '0');
            document.querySelectorAll('.adventure-points-num').forEach(el => {
                el.textContent = '0';
            });
        }
        // Jika jawaban benar
        if (idx === q.correctIndex) {
            // Hitung berapa sudah benar sejauh ini untuk akumulasi bersih
            let correctCountSoFar = 0;
            questions.forEach((quest, i) => {
                if (i <= currentQuestionIndex && userAnswers[i] === quest.correctIndex) {
                    correctCountSoFar++;
                }
            });
            // Set poin sesi aktif = jumlah benar * 10, maksimal 100
            activeQuizSession.points = Math.min(correctCountSoFar * 10, 100);
            updateActiveSessionPointsUI();
            // Update poin petualangan sesuai poin sesi aktif
            localStorage.setItem('bandung_petualang_points', activeQuizSession.points.toString());
            document.querySelectorAll('.adventure-points-num').forEach(el => {
                el.textContent = activeQuizSession.points.toString();
            });
        }
    }

    const questions = QUIZ_PER_WISATA[currentWisataKey].questions;
    if (currentQuestionIndex < questions.length - 1) {
        setTimeout(() => {
            if (userAnswers[currentQuestionIndex] !== null) {
                currentQuestionIndex++;
                renderQuizQuestion();
            }
        }, 320);
    }
}

function nextQuizQuestion() {
    const questions = QUIZ_PER_WISATA[currentWisataKey].questions;
    if (currentQuestionIndex < questions.length - 1) {
        currentQuestionIndex++;
        renderQuizQuestion();
    } else {
        finishQuiz();
    }
}

function prevQuizQuestion() {
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        renderQuizQuestion();
    }
}

function finishQuiz() {
    const questions = QUIZ_PER_WISATA[currentWisataKey].questions;
    let correctCount = 0;
    questions.forEach((q, idx) => {
        if (userAnswers[idx] === q.correctIndex) {
            correctCount++;
        }
    });

    const pointsEarned = Math.min(correctCount * 10, 100);
    const isPerfect = correctCount === questions.length;

    // Pastikan poin sesi aktif sesuai hasil akhir
    if (activeQuizSession.wisataKey !== currentWisataKey) {
        activeQuizSession.wisataKey = currentWisataKey;
    }
    activeQuizSession.points = pointsEarned;
    updateActiveSessionPointsUI();

    // Set poin petualangan sesuai poin kuis untuk destinasi ini
    localStorage.setItem('bandung_petualang_points', pointsEarned.toString());
    document.querySelectorAll('.adventure-points-num').forEach(el => {
        el.textContent = pointsEarned.toString();
    });

    // Simpan skor per wisata
    const storageKey = `quiz_${currentWisataKey.replace(/\s+/g, '_')}_score`;
    const prevScore = parseInt(localStorage.getItem(storageKey) || '0', 10);

    // Update skor jika lebih tinggi atau sama untuk sesi saat ini
    localStorage.setItem(storageKey, pointsEarned.toString());

    if (isPerfect) {
        localStorage.setItem('bandung_wisata_quiz_passed', 'true');
        localStorage.setItem(`quiz_${currentWisataKey.replace(/\s+/g, '_')}_passed`, 'true');
        localStorage.setItem('bandung_wisata_cert_date', new Date().toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        }));
        localStorage.setItem('bandung_wisata_cert_wisata', currentWisataKey);
    }

    // Tampilkan hasil
    document.getElementById('quiz-question-card').classList.add('hidden');
    const resultCard = document.getElementById('quiz-result-card');
    resultCard.classList.remove('hidden');

    document.getElementById('quiz-result-wisata-name').textContent = `Kuis: ${currentWisataKey}`;
    document.getElementById('quiz-score-display').textContent = `Skor Anda ${correctCount}/${questions.length}`;
    document.getElementById('quiz-points-added-display').textContent = `Anda memperoleh ${pointsEarned} poin untuk kuis ${currentWisataKey}.`;

    const certBanner = document.getElementById('quiz-certificate-banner');
    const tryAgainBanner = document.getElementById('quiz-try-again-banner');
    const viewSavedBtn = document.getElementById('view-saved-cert-btn');

    if (isPerfect) {
        certBanner.classList.remove('hidden');
        tryAgainBanner.classList.add('hidden');
        if (viewSavedBtn) viewSavedBtn.classList.remove('hidden');
        triggerQuizConfetti();
    } else {
        certBanner.classList.add('hidden');
        tryAgainBanner.classList.remove('hidden');
    }
}

function restartQuiz() {
    if (!currentWisataKey) return;
    currentQuestionIndex = 0;
    userAnswers = new Array(QUIZ_PER_WISATA[currentWisataKey].questions.length).fill(null);
    // Reset poin sesi aktif dan poin petualangan saat mengulangi kuis
    activeQuizSession.wisataKey = currentWisataKey;
    activeQuizSession.points = 0;
    localStorage.setItem('bandung_petualang_points', '0');
    document.querySelectorAll('.adventure-points-num').forEach(el => {
        el.textContent = '0';
    });
    updateActiveSessionPointsUI();
    document.getElementById('quiz-result-card').classList.add('hidden');
    document.getElementById('quiz-question-card').classList.remove('hidden');
    renderQuizQuestion();
}

function triggerQuizConfetti() {
    try {
        const colors = ['#2541a0', '#fbbf24', '#10b981', '#f59e0b', '#3b82f6'];
        for (let i = 0; i < 30; i++) {
            const conf = document.createElement('div');
            conf.className = 'fixed pointer-events-none z-50 rounded-sm';
            conf.style.width = `${Math.random() * 8 + 6}px`;
            conf.style.height = `${Math.random() * 8 + 6}px`;
            conf.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            conf.style.left = `${Math.random() * 80 + 10}vw`;
            conf.style.top = '-10px';
            conf.style.opacity = '1';
            conf.style.transform = `rotate(${Math.random() * 360}deg)`;
            conf.style.transition = `all ${Math.random() * 2 + 1.5}s cubic-bezier(0.25, 0.46, 0.45, 0.94)`;
            document.body.appendChild(conf);

            setTimeout(() => {
                conf.style.top = `${window.innerHeight + 20}px`;
                conf.style.opacity = '0';
                conf.style.transform = `rotate(${Math.random() * 720}deg) scale(0.6)`;
            }, 50);

            setTimeout(() => conf.remove(), 3500);
        }
    } catch (e) {}
}

document.addEventListener('DOMContentLoaded', () => {
    renderWisataSelector();
    renderQuizQuestion();
    updateActiveSessionPointsUI();
    // Pastikan poin petualangan ditampilkan
    const savedPoints = localStorage.getItem('bandung_petualang_points') || '0';
    document.querySelectorAll('.adventure-points-num').forEach(el => {
        el.textContent = savedPoints;
    });

    if (localStorage.getItem('bandung_wisata_quiz_passed') === 'true') {
        const viewSavedBtn = document.getElementById('view-saved-cert-btn');
        if (viewSavedBtn) viewSavedBtn.classList.remove('hidden');
    }
});
</script>
