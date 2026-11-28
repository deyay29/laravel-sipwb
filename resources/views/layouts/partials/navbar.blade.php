{{--
    Navbar Publik - Adaptif untuk Desktop dan Mobile.

    Desktop:
    - Navbar fixed di atas layar dengan logo, menu pill lengkap, pencarian, dan tombol login/admin.
    - Latar navy + blur muncul bertahap saat halaman di-scroll (resources/js/app.js).

    Mobile:
    - Navbar Atas: Ramping & minimalis (Logo di kiri, tombol toggle pencarian & login/admin di kanan).
    - Navbar Bawah: Bottom bar modern ala aplikasi native (Beranda, Wisata, Kuliner, Berita, Lainnya).
    - Drawer Bawah: Bottom sheet meluncur untuk mengakses Sejarah, Galeri, Smart Budget, Kontak, & akun.
--}}

{{-- ============================================================== --}}
{{-- 1. NAVBAR ATAS (DESKTOP & MOBILE TOPBAR)                       --}}
{{-- ============================================================== --}}
<nav data-sticky-navbar class="fixed inset-x-0 top-0 z-40 w-full transition-colors">
    {{-- Latar navbar: opacity-nya diatur JS mengikuti progress scroll --}}
    <div data-navbar-bg
         aria-hidden="true"
         class="pointer-events-none absolute inset-0 -z-10 bg-navy-900/95 backdrop-blur-md"
         style="opacity: 0"></div>

    <div class="mx-auto flex max-w-[1400px] items-center justify-between gap-3 px-4 py-3 sm:px-6 lg:gap-4 lg:px-8 lg:py-4">
        {{-- Brand Logo --}}
        <a href="{{ route('home') }}" class="flex shrink-0 items-center transition hover:opacity-90">
            <img src="{{ asset('images/logo.png') }}"
                 alt="Bandung Info Center"
                 class="h-10 w-auto sm:h-11 lg:h-12">
        </a>

        {{-- Menu Utama (Desktop: lg+) --}}
        <ul class="hidden flex-1 items-center justify-center gap-1.5 lg:flex">
            @foreach (config('nav.public') as $item)
                @php $active = request()->routeIs($item['pattern']); @endphp

                <li>
                    <a href="{{ route($item['route']) }}"
                       @class([
                           'inline-block rounded-full px-5 py-2.5 font-display text-sm font-semibold transition',
                           'bg-white text-navy-800 shadow-lg' => $active,
                           'border border-white/30 bg-white/10 text-white backdrop-blur hover:bg-white/20' => ! $active,
                       ])>
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- Pencarian & Autentikasi (Desktop: lg+) --}}
        <div class="hidden shrink-0 items-center gap-3 lg:flex">
            <form action="{{ route('home') }}" method="GET" role="search" class="relative">
                <label for="nav-search-desktop" class="sr-only">Cari informasi</label>
                <i class="fas fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 z-10 -translate-y-1/2 text-sm text-white/80"></i>
                <input id="nav-search-desktop"
                       type="search"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Search Here"
                       class="w-52 rounded-full border border-white/25 bg-white/10 py-2.5 pl-11 pr-4 text-sm text-white placeholder-white/60 outline-none transition focus:w-64 focus:bg-white/20 focus:ring-2 focus:ring-white/30">
            </form>

            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       class="rounded-full bg-white px-5 py-2.5 font-display text-sm font-semibold text-navy-800 shadow-lg transition hover:bg-brand-600 hover:text-white">
                        Dashboard
                    </a>
                @endif

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="rounded-full border border-white/30 bg-white/10 px-5 py-2.5 font-display text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="rounded-full bg-white px-5 py-2.5 font-display text-sm font-semibold text-navy-800 shadow-lg transition hover:bg-brand-600 hover:text-white">
                    Login
                </a>
            @endauth
        </div>

        {{-- Aksi Cepat Mobile (Hanya muncul di mobile < lg) --}}
        <div class="flex items-center gap-2 lg:hidden">
            {{-- Tombol Toggle Pencarian Mobile --}}
            <button type="button"
                    data-mobile-search-toggle
                    aria-controls="mobile-search-panel"
                    aria-expanded="false"
                    aria-label="Cari informasi"
                    class="grid h-10 w-10 shrink-0 place-items-center rounded-full border border-white/25 bg-white/10 text-white backdrop-blur transition active:scale-95 hover:bg-white/20">
                <i class="fas fa-magnifying-glass text-sm"></i>
            </button>

            {{-- Tombol Auth Mobile --}}
            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       class="inline-flex items-center gap-1.5 rounded-full bg-white px-3.5 py-1.5 font-display text-xs font-semibold text-navy-800 shadow transition active:scale-95 hover:bg-brand-600 hover:text-white">
                        <i class="fas fa-gauge text-xs"></i>
                        <span>Admin</span>
                    </a>
                @else
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-1 rounded-full border border-white/30 bg-white/10 px-3 py-1.5 font-display text-xs font-semibold text-white backdrop-blur transition active:scale-95">
                            <i class="fas fa-arrow-right-from-bracket text-xs"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-1.5 rounded-full bg-white px-3.5 py-1.5 font-display text-xs font-semibold text-navy-800 shadow transition active:scale-95 hover:bg-brand-600 hover:text-white">
                    <i class="fas fa-arrow-right-to-bracket text-xs"></i>
                    <span>Masuk</span>
                </a>
            @endauth
        </div>
    </div>

    {{-- Panel Pencarian Mobile (Dropdown mulus di bawah topbar) --}}
    <div id="mobile-search-panel"
         data-mobile-search-bar
         hidden
         class="border-t border-white/15 bg-navy-900/98 px-4 py-3 backdrop-blur-xl lg:hidden">
        <div class="mx-auto max-w-md">
            <form action="{{ route('home') }}" method="GET" role="search" class="relative flex items-center">
                <label for="nav-search-mobile-input" class="sr-only">Cari informasi</label>
                <i class="fas fa-magnifying-glass pointer-events-none absolute left-4 text-sm text-white/70"></i>
                <input id="nav-search-mobile-input"
                       type="search"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Cari wisata, berita, kuliner..."
                       class="w-full rounded-full border border-white/25 bg-white/10 py-2 pl-11 pr-10 text-sm text-white placeholder-white/60 outline-none transition focus:border-white/50 focus:bg-white/20 focus:ring-2 focus:ring-white/30">
                <button type="button"
                        data-mobile-search-close
                        aria-label="Tutup pencarian"
                        class="absolute right-3 grid h-7 w-7 place-items-center rounded-full text-white/60 transition hover:bg-white/10 hover:text-white">
                    <i class="fas fa-xmark text-sm"></i>
                </button>
            </form>
        </div>
    </div>
</nav>

{{-- ============================================================== --}}
{{-- 2. BOTTOM NAVBAR (MOBILE ONLY)                                 --}}
{{-- ============================================================== --}}
<nav data-bottom-navbar
     aria-label="Navigasi Bawah Mobile"
     class="fixed inset-x-0 bottom-0 z-40 border-t border-white/10 bg-navy-900/95 backdrop-blur-xl shadow-[0_-8px_30px_rgba(0,0,0,0.35)] lg:hidden"
     style="padding-bottom: max(0.375rem, env(safe-area-inset-bottom, 6px));">
    <div class="mx-auto flex max-w-md items-center justify-around px-2 py-1.5">
        {{-- 1. Beranda --}}
        @php $isHome = request()->routeIs('home'); @endphp
        <a href="{{ route('home') }}"
           class="group flex flex-1 flex-col items-center justify-center py-1 transition-transform active:scale-95">
            <span @class([
                'flex h-7 w-12 items-center justify-center rounded-full transition-all duration-200',
                'bg-white text-navy-900 shadow-md ring-2 ring-white/40' => $isHome,
                'text-white/70 group-hover:text-white' => ! $isHome,
            ])>
                <i class="fas fa-house text-sm"></i>
            </span>
            <span @class([
                'mt-1 font-display text-[11px] leading-tight transition-colors',
                'font-bold text-white' => $isHome,
                'font-medium text-white/70 group-hover:text-white' => ! $isHome,
            ])>
                Beranda
            </span>
        </a>

        {{-- 2. Wisata --}}
        @php $isWisata = request()->routeIs('public.wisata*'); @endphp
        <a href="{{ route('public.wisata') }}"
           class="group flex flex-1 flex-col items-center justify-center py-1 transition-transform active:scale-95">
            <span @class([
                'flex h-7 w-12 items-center justify-center rounded-full transition-all duration-200',
                'bg-white text-navy-900 shadow-md ring-2 ring-white/40' => $isWisata,
                'text-white/70 group-hover:text-white' => ! $isWisata,
            ])>
                <i class="fas fa-mountain-sun text-sm"></i>
            </span>
            <span @class([
                'mt-1 font-display text-[11px] leading-tight transition-colors',
                'font-bold text-white' => $isWisata,
                'font-medium text-white/70 group-hover:text-white' => ! $isWisata,
            ])>
                Wisata
            </span>
        </a>

        {{-- 3. Kuliner --}}
        @php $isKuliner = request()->routeIs('public.kuliner*'); @endphp
        <a href="{{ route('public.kuliner') }}"
           class="group flex flex-1 flex-col items-center justify-center py-1 transition-transform active:scale-95">
            <span @class([
                'flex h-7 w-12 items-center justify-center rounded-full transition-all duration-200',
                'bg-white text-navy-900 shadow-md ring-2 ring-white/40' => $isKuliner,
                'text-white/70 group-hover:text-white' => ! $isKuliner,
            ])>
                <i class="fas fa-utensils text-sm"></i>
            </span>
            <span @class([
                'mt-1 font-display text-[11px] leading-tight transition-colors',
                'font-bold text-white' => $isKuliner,
                'font-medium text-white/70 group-hover:text-white' => ! $isKuliner,
            ])>
                Kuliner
            </span>
        </a>

        {{-- 4. Berita --}}
        @php $isBerita = request()->routeIs('public.berita*'); @endphp
        <a href="{{ route('public.berita') }}"
           class="group flex flex-1 flex-col items-center justify-center py-1 transition-transform active:scale-95">
            <span @class([
                'flex h-7 w-12 items-center justify-center rounded-full transition-all duration-200',
                'bg-white text-navy-900 shadow-md ring-2 ring-white/40' => $isBerita,
                'text-white/70 group-hover:text-white' => ! $isBerita,
            ])>
                <i class="fas fa-newspaper text-sm"></i>
            </span>
            <span @class([
                'mt-1 font-display text-[11px] leading-tight transition-colors',
                'font-bold text-white' => $isBerita,
                'font-medium text-white/70 group-hover:text-white' => ! $isBerita,
            ])>
                Berita
            </span>
        </a>

        {{-- 5. Lainnya (Menu Drawer) --}}
        @php
            $isMoreActive = request()->routeIs('public.sejarah*') ||
                            request()->routeIs('public.galeri*') ||
                            request()->routeIs('public.kontak*');
        @endphp
        <button type="button"
                data-bottom-sheet-toggle
                aria-expanded="false"
                aria-controls="mobile-more-sheet"
                class="group flex flex-1 flex-col items-center justify-center py-1 transition-transform active:scale-95">
            <span @class([
                'relative flex h-7 w-12 items-center justify-center rounded-full transition-all duration-200',
                'bg-white text-navy-900 shadow-md ring-2 ring-white/40' => $isMoreActive,
                'text-white/70 group-hover:text-white' => ! $isMoreActive,
            ])>
                <i class="fas fa-grip text-sm"></i>
                @if ($isMoreActive)
                    <span class="absolute -right-0.5 -top-0.5 h-2 w-2 rounded-full bg-accent-400 ring-2 ring-navy-900"></span>
                @endif
            </span>
            <span @class([
                'mt-1 font-display text-[11px] leading-tight transition-colors',
                'font-bold text-white' => $isMoreActive,
                'font-medium text-white/70 group-hover:text-white' => ! $isMoreActive,
            ])>
                Lainnya
            </span>
        </button>
    </div>
</nav>

{{-- ============================================================== --}}
{{-- 3. MOBILE BOTTOM SHEET (DRAWER LAYANAN LAINNYA)                --}}
{{-- ============================================================== --}}
<div id="mobile-more-sheet"
     data-bottom-sheet
     hidden
     class="fixed inset-0 z-50 flex flex-col justify-end lg:hidden"
     role="dialog"
     aria-modal="true"
     aria-label="Menu Layanan Lainnya">
    {{-- Backdrop gelap transparan (tanpa blur) --}}
    <div data-bottom-sheet-backdrop
         class="fixed inset-0 bg-slate-950/70 transition-opacity"></div>

    {{-- Sheet Panel meluncur dari bawah (solid tanpa blur) --}}
    <div class="relative z-10 w-full max-h-[85vh] overflow-y-auto rounded-t-3xl border-t border-white/20 bg-navy-900 px-5 pb-8 pt-4 text-white shadow-2xl">
        {{-- Handle bar di tengah atas --}}
        <div class="mx-auto mb-3 h-1.5 w-12 rounded-full bg-white/25"></div>

        {{-- Header Drawer --}}
        <div class="flex items-center justify-between border-b border-white/10 pb-3">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                <div>
                    <h3 class="font-display text-sm font-bold text-white">Menu &amp; Layanan</h3>
                    <p class="text-[11px] text-white/60">Bandung Info Center</p>
                </div>
            </div>
            <button type="button"
                    data-bottom-sheet-close
                    aria-label="Tutup menu"
                    class="grid h-8 w-8 place-items-center rounded-full bg-white/10 text-white/70 transition hover:bg-white/20 hover:text-white">
                <i class="fas fa-xmark text-sm"></i>
            </button>
        </div>

        {{-- Grid Menu Tambahan --}}
        <div class="mt-4 grid grid-cols-2 gap-2.5">
            {{-- Sejarah --}}
            <a href="{{ route('public.sejarah') }}"
               @class([
                   'flex items-center gap-3 rounded-2xl border p-3 transition active:scale-95',
                   'border-white/40 bg-white/20 text-white shadow-sm' => request()->routeIs('public.sejarah*'),
                   'border-white/10 bg-white/5 text-white/80 hover:bg-white/10 hover:text-white' => ! request()->routeIs('public.sejarah*'),
               ])>
                <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-amber-500/20 text-amber-400">
                    <i class="fas fa-landmark text-base"></i>
                </div>
                <div class="min-w-0">
                    <div class="font-display text-xs font-bold text-white">Sejarah</div>
                    <div class="truncate text-[10px] text-white/60">Arsip &amp; museum</div>
                </div>
            </a>

            {{-- Galeri --}}
            <a href="{{ route('public.galeri') }}"
               @class([
                   'flex items-center gap-3 rounded-2xl border p-3 transition active:scale-95',
                   'border-white/40 bg-white/20 text-white shadow-sm' => request()->routeIs('public.galeri*'),
                   'border-white/10 bg-white/5 text-white/80 hover:bg-white/10 hover:text-white' => ! request()->routeIs('public.galeri*'),
               ])>
                <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-sky-500/20 text-sky-400">
                    <i class="fas fa-images text-base"></i>
                </div>
                <div class="min-w-0">
                    <div class="font-display text-xs font-bold text-white">Galeri</div>
                    <div class="truncate text-[10px] text-white/60">Pesona kota</div>
                </div>
            </a>

            </a>

            {{-- Kontak --}}
            <a href="{{ route('public.kontak') }}"
               @class([
                   'flex items-center gap-3 rounded-2xl border p-3 transition active:scale-95',
                   'border-white/40 bg-white/20 text-white shadow-sm' => request()->routeIs('public.kontak*'),
                   'border-white/10 bg-white/5 text-white/80 hover:bg-white/10 hover:text-white' => ! request()->routeIs('public.kontak*'),
               ])>
                <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-purple-500/20 text-purple-400">
                    <i class="fas fa-envelope text-base"></i>
                </div>
                <div class="min-w-0">
                    <div class="font-display text-xs font-bold text-white">Kontak</div>
                    <div class="truncate text-[10px] text-white/60">Bantuan &amp; saran</div>
                </div>
            </a>
        </div>

        {{-- Kartu Akun / Pengelola di Drawer --}}
        <div class="mt-4 rounded-2xl border border-white/10 bg-white/5 p-3.5">
            @auth
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="grid h-9 w-9 place-items-center rounded-full bg-brand-500 text-xs font-bold text-white shadow">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-display text-xs font-bold text-white">{{ auth()->user()->name ?? 'Administrator' }}</p>
                            <p class="text-[10px] text-white/60">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1 font-display text-xs font-semibold text-navy-800 shadow hover:bg-white/90">
                            <i class="fas fa-gauge text-[11px]"></i>
                            <span>Dashboard</span>
                        </a>
                    @endif
                </div>

                <div class="mt-3 border-t border-white/10 pt-3">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-xl border border-rose-400/30 bg-rose-500/10 py-2 text-xs font-semibold text-rose-300 transition hover:bg-rose-500/20">
                            <i class="fas fa-arrow-right-from-bracket text-xs"></i>
                            <span>Keluar dari Akun</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="font-display text-xs font-bold text-white">Pengelola Portal?</p>
                        <p class="text-[10px] text-white/60">Masuk ke panel admin Bandung Info Center</p>
                    </div>
                    <a href="{{ route('login') }}"
                       class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-white px-3.5 py-2 font-display text-xs font-semibold text-navy-900 shadow transition active:scale-95 hover:bg-white/90">
                        <i class="fas fa-arrow-right-to-bracket text-xs"></i>
                        <span>Login Admin</span>
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>
