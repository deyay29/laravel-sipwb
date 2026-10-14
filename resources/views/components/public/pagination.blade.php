{{--
    Pagination Tailwind untuk paginator Laravel.
    Diawali dengan angka halaman, bukan tautan markup bawaan Laravel.
--}}
@props(['paginator' => null])

@if ($paginator && $paginator->hasPages())
    <nav {{ $attributes->merge(['aria-label' => 'Navigasi halaman', 'class' => 'mt-8']) }}>
        <div class="flex flex-wrap items-center justify-center gap-1.5">
            @if ($paginator->onFirstPage())
                <span class="rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-400">
                    <i class="fas fa-chevron-left text-xs"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-brand-700 transition hover:border-brand-500 hover:bg-brand-50">
                    <i class="fas fa-chevron-left text-xs"></i>
                </a>
            @endif

            @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span aria-current="page" class="rounded-lg bg-brand-600 px-3.5 py-2 text-sm font-semibold text-white">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       class="rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-brand-700 transition hover:border-brand-500 hover:bg-brand-50">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-brand-700 transition hover:border-brand-500 hover:bg-brand-50">
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
            @else
                <span class="rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-400">
                    <i class="fas fa-chevron-right text-xs"></i>
                </span>
            @endif
        </div>

        <p class="mt-3 text-center text-xs text-slate-500">
            Menampilkan {{ $paginator->firstItem() }}&ndash;{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
        </p>
    </nav>
@endif
