{{--
    Navbar publik - satu-satunya navbar di seluruh halaman publik.

    Sifatnya fixed: diam di atas layar, tidak ikut ter-scroll.
    Latar (warna + blur + garis bawah) muncul bertahap mengikuti progress
    scroll, ditangani resources/js/app.js lewat atribut [data-navbar-bg].

    Daftar menu diambil dari config/nav.php supaya tidak ada duplikasi
    antara desktop dan mobile.
--}}
<nav data-sticky-navbar class="fixed inset-x-0 top-0 z-50 w-full">
    {{-- Latar navbar: opacity-nya diatur JS mengikuti progress scroll --}}
    <div data-navbar-bg
         aria-hidden="true"
         class="pointer-events-none absolute inset-0 -z-10 bg-navy-900/95 backdrop-blur-md"
         style="opacity: 0"></div>

    <div class="mx-auto flex max-w-[1400px] items-center gap-4 px-5 py-4 lg:px-8">
        {{-- Brand --}}
        <a href="{{ route('home') }}" class="flex shrink-0 items-center">
            <img src="{{ asset('images/logo.png') }}"
                 alt="Bandung Info Center"
                 class="h-12 w-auto">
        </a>

        {{-- Menu utama (desktop) --}}
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

        {{-- Pencarian + autentikasi (desktop) --}}
        <div class="hidden shrink-0 items-center gap-3 lg:flex">
            <form action="{{ route('home') }}" method="GET" role="search" class="relative">
                <label for="nav-search" class="sr-only">Cari informasi</label>
                <i class="fas fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 z-10 -translate-y-1/2 text-sm text-white/80"></i>
                <input id="nav-search"
                       type="search"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Search Here"
                       class="w-52 rounded-full border border-white/25 bg-white/10 py-2.5 pl-11 pr-4 text-sm text-white placeholder-white/60 outline-none transition focus:w-64 focus:bg-white/20 focus:ring-2 focus:ring-white/30">
            </form>

            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       class="rounded-full bg-white px-5 py-2.5 font-display text-sm font-semibold text-navy-800 shadow-lg transition hover:bg-white/90">
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
                   class="rounded-full bg-white px-5 py-2.5 font-display text-sm font-semibold text-navy-800 shadow-lg transition hover:bg-white/90">
                    Login Admin
                </a>
            @endauth
        </div>

        {{-- Tombol menu (mobile) --}}
        <button type="button"
                class="ml-auto grid h-11 w-11 shrink-0 place-items-center rounded-full border border-white/30 bg-white/10 text-white lg:hidden"
                aria-label="Buka menu navigasi"
                aria-expanded="false"
                onclick="document.getElementById('navbar-mobile').classList.toggle('hidden')">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    {{-- Menu mobile --}}
    <div id="navbar-mobile" class="hidden border-t border-white/15 bg-navy-900/95 backdrop-blur lg:hidden">
        <div class="mx-auto max-w-[1400px] px-5 py-4">
            <form action="{{ route('home') }}" method="GET" role="search" class="relative mb-4">
                <label for="nav-search-mobile" class="sr-only">Cari informasi</label>
                <i class="fas fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 z-10 -translate-y-1/2 text-sm text-white/80"></i>
                <input id="nav-search-mobile"
                       type="search"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Search Here"
                       class="w-full rounded-full border border-white/25 bg-white/10 py-2.5 pl-11 pr-4 text-sm text-white placeholder-white/60 outline-none focus:ring-2 focus:ring-white/30">
            </form>

            <ul class="flex flex-wrap gap-2">
                @foreach (config('nav.public') as $item)
                    <li>
                        <a href="{{ route($item['route']) }}"
                           @class([
                               'inline-block rounded-full px-4 py-2 font-display text-sm font-semibold transition',
                               'bg-white text-navy-800' => request()->routeIs($item['pattern']),
                               'border border-white/25 bg-white/10 text-white hover:bg-white/20' => ! request()->routeIs($item['pattern']),
                           ])>
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="mt-4 flex flex-wrap gap-2 border-t border-white/15 pt-4">
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-2 font-display text-sm font-semibold text-navy-800">
                            <i class="fas fa-gauge text-xs"></i>
                            Dashboard
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="rounded-full border border-white/30 bg-white/10 px-5 py-2 font-display text-sm font-semibold text-white">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="rounded-full bg-white px-5 py-2 font-display text-sm font-semibold text-navy-800">
                        Login Admin
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
