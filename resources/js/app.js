import './bootstrap';

/*
 * Navbar publik.
 *
 * Sifatnya `fixed` sehingga selalu diam di atas layar. Lapisan latarnya
 * (warna navy + blur + garis bawah + bayangan) muncul SECARA BERTAHAP
 * mengikuti progress scroll, bukan melompat pada ambang tertentu.
 *
 *   scrollY = 0            -> opacity 0   (navbar transparan di atas hero)
 *   scrollY >= MAX_SCROLL  -> opacity 1   (navbar sepenuhnya opaque)
 *
 * Nilai di antaranya di-interpolasi sehingga transisinya halus dan tidak
 * terasa "lompat ke atas".
 */

const NAVBAR_FADE_RANGE = 140; // px scroll untuk berubah dari transparan ke opaque

function initStickyNavbar() {
    const navbar = document.querySelector('[data-sticky-navbar]');
    const background = document.querySelector('[data-navbar-bg]');

    if (!navbar) return;

    let ticking = false;

    const render = () => {
        const progress = Math.min(1, Math.max(0, window.scrollY / NAVBAR_FADE_RANGE));

        if (background) {
            background.style.opacity = progress.toFixed(3);
            // Garis bawah + bayangan muncul di tahap akhir,
            // jadi tidak terlihat "melompat".
            background.style.borderBottom = progress > 0.98 ? '1px solid rgba(255,255,255,0.12)' : '1px solid transparent';
            background.style.boxShadow = progress > 0.98
                ? '0 8px 24px rgba(0,0,0,0.30)'
                : 'none';
        }

        ticking = false;
    };

    const onScroll = () => {
        if (ticking) return;
        ticking = true;
        window.requestAnimationFrame(render);
    };

    render();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
}

/*
 * Pencarian Mobile (Dropdown di bawah topbar).
 */
function initMobileSearch() {
    const toggle = document.querySelector('[data-mobile-search-toggle]');
    const panel = document.querySelector('[data-mobile-search-bar]');
    const closeBtn = document.querySelector('[data-mobile-search-close]');
    const input = panel ? panel.querySelector('input[type="search"]') : null;

    if (!toggle || !panel) return;

    const setOpen = (open) => {
        panel.toggleAttribute('hidden', !open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open && input) {
            setTimeout(() => input.focus(), 80);
        }
    };

    toggle.addEventListener('click', (e) => {
        e.stopPropagation();
        setOpen(panel.hasAttribute('hidden'));
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', () => setOpen(false));
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !panel.hasAttribute('hidden')) {
            setOpen(false);
            toggle.focus();
        }
    });

    document.addEventListener('click', (event) => {
        if (panel.hasAttribute('hidden')) return;
        if (panel.contains(event.target) || toggle.contains(event.target)) return;
        setOpen(false);
    });

    window.matchMedia('(min-width: 1024px)').addEventListener('change', (event) => {
        if (event.matches) setOpen(false);
    });
}

/*
 * Bottom Sheet Drawer Mobile (Menu & Layanan Lainnya).
 */
function initMobileBottomSheet() {
    const toggle = document.querySelector('[data-bottom-sheet-toggle]');
    const sheet = document.querySelector('[data-bottom-sheet]');
    const backdrop = document.querySelector('[data-bottom-sheet-backdrop]');
    const closeBtn = document.querySelector('[data-bottom-sheet-close]');

    if (!toggle || !sheet) return;

    const setOpen = (open) => {
        sheet.toggleAttribute('hidden', !open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open) {
            document.body.classList.add('overflow-hidden');
        } else {
            document.body.classList.remove('overflow-hidden');
        }
    };

    toggle.addEventListener('click', (e) => {
        e.stopPropagation();
        setOpen(sheet.hasAttribute('hidden'));
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', () => setOpen(false));
    }

    if (backdrop) {
        backdrop.addEventListener('click', () => setOpen(false));
    }

    // Menutup sheet bila link di dalamnya diklik
    sheet.addEventListener('click', (event) => {
        if (event.target.closest('a')) setOpen(false);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !sheet.hasAttribute('hidden')) {
            setOpen(false);
            toggle.focus();
        }
    });

    // Otomatis tutup bila layar di-resize ke desktop
    window.matchMedia('(min-width: 1024px)').addEventListener('change', (event) => {
        if (event.matches) setOpen(false);
    });
}

/*
 * Reaksi berita: kirim lewat fetch lalu perbarui angka di layar.
 */
function initBeritaReaction() {
    const container = document.querySelector('[data-reaksi-container]');
    if (!container) return;

    const url = container.dataset.url;
    const token = container.dataset.csrf;

    container.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-tipe]');
        if (!button || button.disabled) return;

        const tipe = button.dataset.tipe;
        const buttons = container.querySelectorAll('[data-tipe]');
        buttons.forEach((b) => { b.disabled = true; });

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify({ tipe_reaksi: tipe }),
            });

            const result = await response.json();

            if (!result?.data) throw new Error('Respons tidak valid');

            Object.entries(result.data).forEach(([key, value]) => {
                container.querySelectorAll(`[data-kolom="${key}"]`).forEach((el) => {
                    el.textContent = new Intl.NumberFormat('id-ID').format(value);
                });
            });
        } catch (error) {
            window.alert('Reaksi gagal disimpan. Silakan coba lagi.');
        } finally {
            buttons.forEach((b) => { b.disabled = false; });
        }
    });
}

/*
 * Slider gambar (x-public.image-slider).
 *
 * Semua slide ditumpuk dengan position absolute; yang aktif diberi
 * opacity-100, sisanya disembunyikan. Mendukung tombol panah, titik
 * indikator, dan geser (swipe) di layar sentuh.
 */
function initImageSliders() {
    document.querySelectorAll('[data-slider]').forEach((slider) => {
        const slides = [...slider.querySelectorAll('[data-slider-slide]')];
        const dots = [...slider.querySelectorAll('[data-slider-dot]')];

        if (slides.length < 2) return;

        let index = 0;

        const show = (next) => {
            index = (next + slides.length) % slides.length;

            slides.forEach((slide, i) => {
                const active = i === index;
                slide.classList.toggle('opacity-100', active);
                slide.classList.toggle('opacity-0', !active);
                slide.classList.toggle('pointer-events-none', !active);
            });

            dots.forEach((dot, i) => {
                const active = i === index;
                dot.classList.toggle('w-6', active);
                dot.classList.toggle('bg-white', active);
                dot.classList.toggle('w-2', !active);
                dot.classList.toggle('bg-white/50', !active);
            });
        };

        slider.querySelector('[data-slider-prev]')?.addEventListener('click', () => show(index - 1));
        slider.querySelector('[data-slider-next]')?.addEventListener('click', () => show(index + 1));

        dots.forEach((dot, i) => dot.addEventListener('click', () => show(i)));

        // Geser (swipe) untuk layar sentuh.
        let startX = 0;
        let deltaX = 0;

        slider.addEventListener('touchstart', (event) => {
            startX = event.touches[0].clientX;
            deltaX = 0;
        }, { passive: true });

        slider.addEventListener('touchmove', (event) => {
            deltaX = event.touches[0].clientX - startX;
        }, { passive: true });

        slider.addEventListener('touchend', () => {
            if (Math.abs(deltaX) < 45) return;
            show(deltaX < 0 ? index + 1 : index - 1);
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initStickyNavbar();
    initMobileSearch();
    initMobileBottomSheet();
    initBeritaReaction();
    initImageSliders();
});
