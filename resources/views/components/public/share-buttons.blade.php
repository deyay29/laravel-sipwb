{{--
    Tombol bagikan untuk halaman publik.

    Default URL memakai halaman yang sedang dibuka. Tombol WhatsApp, Facebook,
    X, dan Telegram langsung membuka jendela share resmi masing-masing.
    Tombol "salin tautan" memakai Clipboard API dengan fallback untuk browser
    lama / koneksi non-HTTPS. Di perangkat yang mendukung Web Share API
    (umumnya browser mobile), tombol menu bagikan bawaan akan muncul.

    @param string|null $url    URL yang dibagikan (default: halaman saat ini)
    @param string      $title  judul konten yang dibagikan
    @param string      $label  teks label di depan ikon
--}}
@props([
    'url' => null,
    'title' => '',
    'label' => 'Bagikan',
])

@php
    $shareUrl = $url ?: url()->full();
    $shareTitle = $title !== '' ? $title : config('app.name', 'Bandung Info Center');

    $links = [
        [
            'label' => 'WhatsApp',
            'icon' => 'fab fa-whatsapp',
            'href' => 'https://wa.me/?text=' . rawurlencode($shareTitle.' '.$shareUrl),
            'hover' => 'hover:border-emerald-500 hover:bg-emerald-50 hover:text-emerald-600',
        ],
        [
            'label' => 'Facebook',
            'icon' => 'fab fa-facebook-f',
            'href' => 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode($shareUrl),
            'hover' => 'hover:border-blue-500 hover:bg-blue-50 hover:text-blue-600',
        ],
        [
            'label' => 'X',
            'icon' => 'fab fa-x-twitter',
            'href' => 'https://twitter.com/intent/tweet?text=' . rawurlencode($shareTitle) . '&url=' . rawurlencode($shareUrl),
            'hover' => 'hover:border-slate-800 hover:bg-slate-100 hover:text-slate-900',
        ],
        [
            'label' => 'Telegram',
            'icon' => 'fab fa-telegram',
            'href' => 'https://t.me/share/url?url=' . rawurlencode($shareUrl) . '&text=' . rawurlencode($shareTitle),
            'hover' => 'hover:border-sky-500 hover:bg-sky-50 hover:text-sky-600',
        ],
    ];
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-2']) }} data-share>
    <span class="mr-1 inline-flex items-center gap-1.5 font-display text-xs font-semibold text-slate-500">
        <i class="fas fa-share-nodes text-brand-500"></i>
        {{ $label }}
    </span>

    @foreach ($links as $link)
        <a href="{{ $link['href'] }}"
           target="_blank"
           rel="noopener noreferrer"
           title="Bagikan ke {{ $link['label'] }}"
           class="grid h-9 w-9 place-items-center rounded-full border border-slate-200 bg-white text-sm text-slate-500 shadow-sm transition hover:-translate-y-0.5 hover:shadow {{ $link['hover'] }}">
            <i class="{{ $link['icon'] }}"></i>
            <span class="sr-only">Bagikan ke {{ $link['label'] }}</span>
        </a>
    @endforeach

    <button type="button"
            data-share-copy
            data-url="{{ $shareUrl }}"
            title="Salin tautan"
            class="grid h-9 w-9 place-items-center rounded-full border border-slate-200 bg-white text-sm text-slate-500 shadow-sm transition hover:-translate-y-0.5 hover:border-brand-500 hover:bg-brand-50 hover:text-brand-700 hover:shadow">
        <i class="fas fa-link"></i>
        <span class="sr-only">Salin tautan</span>
    </button>

    <button type="button"
            data-share-native
            data-url="{{ $shareUrl }}"
            data-title="{{ $shareTitle }}"
            title="Menu bagikan lainnya"
            class="hidden h-9 w-9 place-items-center rounded-full border border-slate-200 bg-white text-sm text-slate-500 shadow-sm transition hover:-translate-y-0.5 hover:border-brand-500 hover:bg-brand-50 hover:text-brand-700 hover:shadow">
        <i class="fas fa-ellipsis-h"></i>
        <span class="sr-only">Menu bagikan lainnya</span>
    </button>

    <span data-share-status class="sr-only" role="status" aria-live="polite"></span>
</div>

@once
    @push('scripts')
        <script>
            (function () {
                const canNativeShare = typeof navigator !== 'undefined' && typeof navigator.share === 'function';

                if (canNativeShare) {
                    document.querySelectorAll('[data-share-native]').forEach(function (button) {
                        button.classList.remove('hidden');
                    });
                }

                function writeToClipboard(text) {
                    if (navigator.clipboard && window.isSecureContext) {
                        return navigator.clipboard.writeText(text);
                    }

                    return new Promise(function (resolve, reject) {
                        const textarea = document.createElement('textarea');
                        textarea.value = text;
                        textarea.setAttribute('readonly', '');
                        textarea.style.position = 'fixed';
                        textarea.style.opacity = '0';
                        document.body.appendChild(textarea);
                        textarea.select();

                        try {
                            document.execCommand('copy') ? resolve() : reject(new Error('copy-failed'));
                        } catch (error) {
                            reject(error);
                        } finally {
                            textarea.remove();
                        }
                    });
                }

                document.addEventListener('click', function (event) {
                    const copyButton = event.target.closest('[data-share-copy]');

                    if (copyButton) {
                        const wrapper = copyButton.closest('[data-share]');
                        const status = wrapper ? wrapper.querySelector('[data-share-status]') : null;
                        const icon = copyButton.querySelector('i');
                        const originalClass = icon ? icon.className : '';

                        writeToClipboard(copyButton.dataset.url)
                            .then(function () {
                                copyButton.classList.add('border-emerald-500', 'bg-emerald-600', 'text-white');
                                copyButton.classList.remove('bg-white', 'text-slate-500');
                                if (icon) {
                                    icon.className = 'fas fa-check';
                                }
                                if (status) {
                                    status.textContent = 'Tautan berhasil disalin.';
                                }

                                window.setTimeout(function () {
                                    copyButton.classList.remove('border-emerald-500', 'bg-emerald-600', 'text-white');
                                    copyButton.classList.add('bg-white', 'text-slate-500');
                                    if (icon) {
                                        icon.className = originalClass;
                                    }
                                }, 1800);
                            })
                            .catch(function () {
                                if (status) {
                                    status.textContent = 'Tautan tidak bisa disalin otomatis.';
                                }
                                window.prompt('Salin tautan ini:', copyButton.dataset.url);
                            });

                        return;
                    }

                    const nativeButton = event.target.closest('[data-share-native]');

                    if (nativeButton && canNativeShare) {
                        navigator.share({
                            title: nativeButton.dataset.title,
                            url: nativeButton.dataset.url,
                        }).catch(function () {
                            // Pengguna menutup dialog share: tidak perlu aksi.
                        });
                    }
                });
            })();
        </script>
    @endpush
@endonce
