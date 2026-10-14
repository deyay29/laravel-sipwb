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

document.addEventListener('DOMContentLoaded', () => {
    initStickyNavbar();
    initBeritaReaction();
});
