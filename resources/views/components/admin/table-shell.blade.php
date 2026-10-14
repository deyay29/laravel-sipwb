{{--
    Pembungkus tabel admin: header + isi + pagination, dengan gaya Tailwind.

    Slot :head  = isi <thead>
    Slot :body  = isi <tbody>
--}}
@props(['paginator' => null])

<div {{ $attributes->merge(['class' => 'overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-[0_2px_16px_rgba(26,42,108,0.08)]']) }}>
    <table class="w-full min-w-[640px] border-collapse text-sm">
        {{ $head }}
        <tbody class="divide-y divide-slate-100">
            {{ $body }}
        </tbody>
    </table>
</div>

@if ($paginator)
    <x-public.pagination :paginator="$paginator" class="mt-6" />
@endif
