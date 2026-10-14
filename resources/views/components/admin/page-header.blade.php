{{--
 Judul halaman admin + tombol aksi utama.
--}}
@props([
 'title' => null,
 'description' => null,
 'action' => null,
 'actionLabel' => null,
 'actionIcon' => 'fa-plus',
])

<div {{ $attributes->merge(['class' => 'mb-6 flex flex-wrap items-end justify-between gap-4']) }}>
 <div>
 <h1 class="font-display text-2xl font-extrabold text-slate-800">{{ $title }}</h1>

 @if ($description)
 <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
 @endif
 </div>

 @if ($action)
 <a href="{{ $action }}"
 class="inline-flex items-center gap-2 rounded-[10px] bg-gradient-to-br from-navy-800 to-brand-600 px-5 py-2.5 font-display text-sm font-semibold text-white shadow-[0_4px_18px_rgba(26,42,108,0.3)] transition hover:shadow-[0_10px_28px_rgba(26,42,108,0.4)]">
 <i class="fas {{ $actionIcon }}"></i>
 {{ $actionLabel }}
 </a>
 @endif
</div>
