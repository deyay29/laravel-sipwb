@php
    $flash = array_filter([
        'success' => session('success'),
        'error' => session('error'),
        'warning' => session('warning'),
        'info' => session('status'),
    ])
@endphp

@if ($flash)
    <div class="mx-auto w-full max-w-7xl px-4 pt-5 sm:px-6">
        @foreach ($flash as $type => $message)
            <div @class([
                'mb-3 flex items-start gap-2.5 rounded-xl border px-4 py-3 text-sm font-medium',
                'border-emerald-200 bg-emerald-50 text-emerald-800' => $type === 'success',
                'border-rose-200 bg-rose-50 text-rose-800' => $type === 'error',
                'border-amber-200 bg-amber-50 text-amber-800' => $type === 'warning',
                'border-sky-200 bg-sky-50 text-sky-800' => $type === 'info',
            ])>
                <i class="fas fa-circle-info mt-0.5 shrink-0"></i>
                <span>{{ $message }}</span>
            </div>
        @endforeach
    </div>
@endif

@if ($errors->any() && ! $errors->has('email'))
    <div class="mx-auto w-full max-w-7xl px-4 pt-5 sm:px-6">
        <div class="mb-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            <p class="font-semibold">Periksa kembali isian Anda:</p>
            <ul class="mt-1 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
