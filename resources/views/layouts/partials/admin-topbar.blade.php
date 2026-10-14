<header class="sticky top-0 z-40 flex h-[60px] items-center justify-between border-b border-slate-200 bg-white px-5 lg:px-7">
    <div class="flex items-center gap-3">
        <button type="button"
                class="grid h-9 w-9 place-items-center rounded-lg border border-slate-200 text-slate-600 md:hidden"
                onclick="document.getElementById('admin-sidebar-mobile').classList.toggle('hidden')"
                aria-label="Buka menu">
            <i class="fas fa-bars"></i>
        </button>

        <h1 class="font-display text-base font-bold text-slate-800">
            @yield('topbar-title', 'Dashboard')
        </h1>
    </div>

    <a href="{{ route('home') }}"
       target="_blank"
       rel="noopener"
       class="inline-flex items-center gap-2 rounded-[10px] bg-navy-800 px-4 py-2 text-[13px] font-semibold text-white transition hover:bg-navy-900">
        Lihat Situs Publik
        <i class="fas fa-arrow-up-right-from-square text-xs"></i>
    </a>
</header>

{{-- Sidebar versi mobile (muncul saat tombol menu ditekan) --}}
<div id="admin-sidebar-mobile" class="hidden md:hidden">
    <div class="fixed inset-0 bg-slate-900/50" onclick="this.parentElement.classList.add('hidden')"></div>
    <nav class="fixed inset-y-0 left-0 flex w-[260px] flex-col bg-navy-800 p-4 text-white">
        <div class="mb-4 flex items-center gap-3">
            <span class="grid h-10 w-10 place-items-center rounded-[10px] bg-accent-500"><i class="fas fa-city"></i></span>
            <div>
                <p class="text-sm font-bold">Bandung Info Center</p>
                <p class="text-[10px] uppercase tracking-[1px] text-accent-500">Panel Admin</p>
            </div>
        </div>

        <ul class="flex-1 space-y-1 overflow-y-auto">
            @foreach (config('nav.admin') as $item)
                <li>
                    <a href="{{ route($item['route']) }}"
                       @class([
                           'flex items-center gap-2.5 rounded-[10px] px-3 py-2 text-sm transition',
                           'bg-accent-500 font-semibold' => request()->routeIs($item['pattern']),
                           'text-white/80 hover:bg-white/10' => ! request()->routeIs($item['pattern']),
                       ])>
                        <i class="fas w-5 text-center {{ $item['icon'] }}"></i>
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach

            <li class="pt-2">
                <a href="{{ route('admin.contact.index') }}" class="flex items-center gap-2.5 rounded-[10px] px-3 py-2 text-sm text-white/80 transition hover:bg-white/10">
                    <i class="fas fa-address-card w-5 text-center"></i> Data Kontak
                </a>
            </li>
        </ul>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-[10px] bg-white/10 py-2 text-sm font-semibold transition hover:bg-rose-500">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </nav>
</div>
