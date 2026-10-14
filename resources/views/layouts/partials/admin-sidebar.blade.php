<aside class="admin-sidebar fixed inset-y-0 left-0 z-50 hidden w-[220px] flex-col overflow-y-auto bg-navy-800 text-white md:flex">

    <div class="flex items-center gap-3 border-b border-white/10 px-[18px] py-5">
        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-[10px] bg-accent-500 text-lg text-white">
            <i class="fas fa-city"></i>
        </span>
        <div>
            <h3 class="text-[13px] font-bold leading-tight text-white">Bandung Info Center</h3>
            <span class="text-[10px] font-medium uppercase tracking-[1px] text-accent-500">Panel Admin</span>
        </div>
    </div>

    <p class="px-[18px] pb-1 pt-[18px] text-[10px] font-bold uppercase tracking-[1.5px] text-white/40">Menu Utama</p>
    <ul class="space-y-0.5 px-2.5">
        @foreach (config('nav.admin') as $item)
            <li>
                <a href="{{ route($item['route']) }}"
                   @class([
                       'group flex items-center gap-2.5 rounded-[10px] px-3 py-2 text-[13.5px] font-medium transition',
                       'bg-accent-500 font-semibold text-white' => request()->routeIs($item['pattern']),
                       'text-white/80 hover:bg-white/10 hover:text-white' => ! request()->routeIs($item['pattern']),
                   ])>
                    <span class="grid h-7 w-7 shrink-0 place-items-center rounded-lg text-sm">
                        <i class="fas {{ $item['icon'] }}"></i>
                    </span>
                    {{ $item['label'] }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="mx-[18px] my-2.5 h-px bg-white/10"></div>

    <p class="px-[18px] pb-1 text-[10px] font-bold uppercase tracking-[1.5px] text-white/40">Halaman Publik</p>
    <ul class="space-y-0.5 px-2.5">
        @foreach (config('nav.admin_public') as $item)
            <li>
                <a href="{{ route($item['route']) }}"
                   @class([
                       'flex items-center gap-2.5 rounded-[10px] px-3 py-2 text-[13.5px] font-medium transition',
                       'bg-accent-500 font-semibold text-white' => isset($item['pattern']) && request()->routeIs($item['pattern']),
                       'text-white/80 hover:bg-white/10 hover:text-white' => ! (isset($item['pattern']) && request()->routeIs($item['pattern'])),
                   ])
                   target="_blank"
                   rel="noopener">
                    <span class="grid h-7 w-7 shrink-0 place-items-center rounded-lg text-sm">
                        <i class="fas {{ $item['icon'] }}"></i>
                    </span>
                    {{ $item['label'] }}
                    <span class="ml-auto rounded-full bg-white/15 px-2 py-0.5 text-[10px] font-semibold tracking-[0.5px] text-white/70">
                        PUBLIK
                    </span>
                </a>
            </li>
        @endforeach
    </ul>

    <div class="mt-auto border-t border-white/10 px-[18px] py-4">
        <div class="flex items-center gap-2.5">
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-white/15 font-display text-sm font-bold text-white">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </span>
            <div class="min-w-0">
                <p class="truncate text-[13px] font-semibold text-white">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="truncate text-[11px] text-white/50">{{ auth()->user()->email ?? '' }}</p>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-[10px] bg-white/10 py-2 text-[13px] font-semibold text-white transition hover:bg-rose-500">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</aside>
