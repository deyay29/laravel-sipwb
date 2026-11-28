{{--
    Footer publik.

    Di layar kecil (mobile) disembunyikan agar halaman tidak dihiasai
    blok gelap di akhir konten. Muncul kembali mulai breakpoint `lg`
    ke atas. Footer hanya dipakai layouts/app (halaman publik), jadi
    halaman admin dan login tidak terpengaruh.

    CATATAN: id="kontak" di sini tidak bisa diandalkan sebagai target
    anchor di mobile, karena pada ukuran itu footer disembunyikan.
    Kartu kategori di beranda karena itu diarahkan ke route /kontak
    (lihat resources/views/public/home.blade.php).
--}}
<footer id="kontak" class="mt-16 hidden scroll-mt-20 bg-slate-900 py-10 text-slate-300 lg:block lg:scroll-mt-24">
    <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-4 text-center sm:px-6 md:flex-row md:text-left">
        <div>
            <p class="font-display text-lg font-bold text-white">Bandung Info Center</p>
            <p class="mt-1 text-sm">Sistem Layanan Informasi Publik Kota Bandung.</p>
        </div>

        <p class="text-sm">&copy; {{ date('Y') }} Seluruh hak cipta dilindungi.</p>
    </div>
</footer>