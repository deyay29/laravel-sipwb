$path = 'C:\Users\NXKS8\Downloads\laravel-sipwb\laravel-sipwb\resources\views\public\sejarah\show.blade.php'
$c = [System.IO.File]::ReadAllText($path)
$old = @"
  {{-- Cuplikan bab pertama --}}
  @if ($babs->isNotEmpty())
  <div class="mt-10 space-y-4">
  <h3 class="font-display text-xl font-extrabold text-slate-900">Baca per Bab</h3>

  @foreach ($babs->take(3) as $bab)
  <a href="{{ route('public.sejarah.bab', [$topik, $bab->nomor_bab]) }}"
  class="group flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-brand-500 hover:shadow-[0_10px_26px_rgba(15,23,42,0.10)]">
  <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-navy-800 font-display text-sm font-bold text-white">
  {{ $bab->nomor_bab }}
  </span>

  <div class="min-w-0">
  <p class="font-display text-base font-bold text-slate-900 transition group-hover:text-brand-700">
  {{ $bab->judul_bab }}
  </p>
  <p class="mt-1.5 text-sm leading-relaxed text-slate-600">
  {{ Str::limit($bab->isi_bab, 130) }}
  </p>
  </div>
  </a>
  @endforeach
  </div>
  @endif
"@
$new = @"
  {{-- Daftar Bab --}}
  @if ($babs->isNotEmpty())
  <div class="mt-10 space-y-4">
  <h3 class="font-display text-xl font-extrabold text-slate-900">
  <i class="fas fa-book-open mr-1.5 text-brand-600"></i>
  Daftar Bab
  </h3>

  <ul class="space-y-1.5">
  @foreach ($babs as $bab)
  <li>
  <a href="{{ route('public.sejarah.bab', [$topik, $bab->nomor_bab]) }}"
  class="group flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 transition hover:border-brand-500 hover:bg-brand-50">
  <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-brand-600 font-display text-xs font-bold text-white">
  {{ $bab->nomor_bab }}
  </span>
  <span class="text-sm text-slate-700 transition group-hover:text-brand-700">
  {{ Str::limit($bab->judul_bab, 60) }}
  </span>
  </a>
  </li>
  @endforeach
  </ul>
  </div>
  @endif
"@
if($c.Contains($old)) {
    $c2 = $c.Replace($old, $new)
    [System.IO.File]::WriteAllText($path, $c2)
    Write-Output 'ok'
} else {
    Write-Output 'not found'
}
