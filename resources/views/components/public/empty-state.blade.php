{{--
    Tampilan saat belum ada data pada halaman publik.

    @param string      $title
    @param string|null $description
    @param string      $icon
--}}
@props([
    'title' => 'Belum ada data.',
    'description' => null,
    'icon' => 'fa-inbox',
])

<div {{ $attributes->merge(['class' => 'col-span-full flex flex-col items-center justify-center gap-3 rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center']) }}>
    <span class="grid h-14 w-14 place-items-center rounded-full bg-slate-100 text-2xl text-slate-400">
        <i class="fas {{ $icon }}"></i>
    </span>
    <p class="font-display text-lg font-bold text-slate-800">{{ $title }}</p>

    @if ($description)
        <p class="max-w-md text-sm text-slate-500">{{ $description }}</p>
    @endif

    {{ $slot }}
</div>
