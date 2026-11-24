{{--
    Widget Teh AI lokal.

    Satu-satunya widget chat di layout. Memanggil /api/teh-ai/chat, yang
    mengambil konteks langsung dari tabel publik kuliner/wisata/berita/sejarah.
--}}
<style>
    #teh-ai-panel[hidden] { display: none !important; }
    #teh-ai-messages { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
    #teh-ai-messages .teh-ai-teks { white-space: pre-wrap; word-break: break-word; }

    /* Mobile: naikkan widget di atas bottom navbar (z-40) supaya tidak bertumpuk. */
    @media (max-width: 1023px) {
        #teh-ai-toggle { bottom: 5.25rem; right: 1rem; }
        #teh-ai-panel { bottom: 8.75rem; right: 1rem; width: calc(100vw - 2rem); max-width: none; }
    }
</style>

{{-- Tombol pemicu: pojok kanan bawah, di atas seluruh konten --}}
<button
    type="button"
    id="teh-ai-toggle"
    aria-label="Buka percakapan dengan Teh AI"
    aria-expanded="false"
    aria-controls="teh-ai-panel"
    class="fixed bottom-6 right-6 z-50 flex h-14 w-14 items-center justify-center overflow-hidden rounded-full bg-brand-600 shadow-lg shadow-brand-800/30 transition hover:scale-105 focus:outline-none focus:ring-4 focus:ring-brand-200"
>
    <img src="{{ asset('images/teh-ai.png') }}" alt="Teh AI" class="h-full w-full rounded-full object-cover">
</button>

{{-- Pop-up chat: ringkas, melayang tepat di atas tombol --}}
<section
    id="teh-ai-panel"
    hidden
    aria-label="Percakapan Teh AI"
    class="fixed bottom-24 right-6 z-50 flex h-[500px] max-h-[75vh] w-[380px] max-w-[calc(100vw-3rem)] flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
>
    <header class="flex shrink-0 items-center justify-between bg-gradient-to-r from-brand-700 to-brand-500 px-3 py-2.5 text-white">
        <div class="flex min-w-0 items-center gap-2.5">
            <img src="{{ asset('images/teh-ai.png') }}" alt="" class="h-9 w-9 shrink-0 rounded-full border-2 border-white/70 object-cover">
            <div class="min-w-0">
                <p class="font-display text-sm font-bold leading-tight">Teh AI</p>
                <p class="truncate text-[10px] text-white/80">Informasi publik Bandung Info Center</p>
            </div>
        </div>
        <button type="button" id="teh-ai-close" aria-label="Tutup percakapan" class="shrink-0 rounded-full p-1.5 text-white/90 hover:bg-white/15 focus:outline-none focus:ring-2 focus:ring-white">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </header>

    <div id="teh-ai-messages" class="min-h-0 flex-1 space-y-2.5 overflow-y-auto bg-slate-50 px-3 py-3 text-[13px]" aria-live="polite">
        <div class="max-w-[88%] rounded-2xl rounded-tl-sm bg-white px-3 py-2 text-slate-700 shadow-sm ring-1 ring-slate-200">
            <p class="teh-ai-teks">Halo! Saya Teh AI. Saya bisa membantu informasi wisata, kuliner, berita, dan sejarah yang tampil di portal ini. Ada yang bisa saya bantu?</p>
        </div>
        <div id="teh-ai-suggestions" class="flex flex-wrap gap-2 pl-1"></div>
    </div>

    <form id="teh-ai-form" class="shrink-0 border-t border-slate-200 bg-white p-2.5">
        <div class="flex items-end gap-2">
            <label for="teh-ai-input" class="sr-only">Tulis pertanyaan untuk Teh AI</label>
            <textarea
                id="teh-ai-input"
                rows="1"
                maxlength="1000"
                placeholder="Contoh: kuliner ramah anak apa saja?"
                class="max-h-28 flex-1 resize-none rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100"
            ></textarea>
            <button type="submit" id="teh-ai-send" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-600 text-white transition hover:bg-brand-700 disabled:cursor-not-allowed disabled:opacity-60">
                <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                <span class="sr-only">Kirim</span>
            </button>
        </div>
    </form>
</section>

<script>
(function () {
    const toggle = document.getElementById('teh-ai-toggle');
    const panel = document.getElementById('teh-ai-panel');
    const close = document.getElementById('teh-ai-close');
    const form = document.getElementById('teh-ai-form');
    const input = document.getElementById('teh-ai-input');
    const send = document.getElementById('teh-ai-send');
    const messages = document.getElementById('teh-ai-messages');
    const suggestions = document.getElementById('teh-ai-suggestions');
    const endpoint = '{{ route('api.teh-ai.chat') }}';
    const topicsEndpoint = '{{ route('api.teh-ai.topics') }}';

    if (!toggle || !panel || !form || !input || !messages) return;

    let topicsLoaded = false;

    function scrollToBottom() {
        messages.scrollTop = messages.scrollHeight;
    }

    function setOpen(open) {
        panel.hidden = !open;
        toggle.setAttribute('aria-expanded', String(open));
        if (open) {
            scrollToBottom();
            input.focus();
            loadTopics();
        }
    }

    toggle.addEventListener('click', () => setOpen(panel.hidden));
    if (close) close.addEventListener('click', () => setOpen(false));

    function bubble(text, fromUser) {
        const wrapper = document.createElement('div');
        wrapper.className = 'max-w-[88%] rounded-2xl px-3 py-2 text-[13px] shadow-sm ring-1 ' +
            (fromUser
                ? 'ml-auto rounded-tr-sm bg-brand-600 text-white ring-brand-700'
                : 'rounded-tl-sm bg-white text-slate-700 ring-slate-200');

        const content = document.createElement('p');
        content.className = 'teh-ai-teks';
        content.textContent = text;
        wrapper.appendChild(content);
        messages.appendChild(wrapper);

        return wrapper;
    }

    function addSources(items) {
        if (!Array.isArray(items) || items.length === 0) return;

        const box = document.createElement('div');
        box.className = 'max-w-[88%] rounded-xl border border-brand-100 bg-brand-50 px-3 py-2 text-xs text-brand-800';
        const title = document.createElement('p');
        title.className = 'mb-1 font-semibold';
        title.textContent = 'Sumber:';
        box.appendChild(title);

        items.forEach((source) => {
            if (!source || !source.url || !source.judul) return;
            const link = document.createElement('a');
            link.href = source.url;
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            link.className = 'block truncate underline decoration-brand-300 underline-offset-2 hover:decoration-brand-700';
            link.textContent = source.judul;
            box.appendChild(link);
        });

        messages.appendChild(box);
    }

    async function loadTopics() {
        if (topicsLoaded) return;
        topicsLoaded = true;

        try {
            const response = await fetch(topicsEndpoint, { headers: { Accept: 'application/json' } });
            if (!response.ok) return;
            const payload = await response.json();
            (payload.topics || []).slice(0, 4).forEach((topic) => {
                const chip = document.createElement('button');
                chip.type = 'button';
                chip.className = 'rounded-full border border-brand-200 bg-white px-3 py-1 text-xs text-brand-700 hover:bg-brand-50';
                chip.textContent = topic;
                chip.addEventListener('click', () => {
                    input.value = topic;
                    form.requestSubmit();
                });
                suggestions.appendChild(chip);
            });
        } catch (error) {
            suggestions.innerHTML = '';
        }
    }

    function setLoading(active) {
        send.disabled = active;
        input.disabled = active;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const message = input.value.trim();
        if (!message) return;

        bubble(message, true);
        input.value = '';
        const loading = bubble('Mencari informasi di portal...', false);
        setLoading(true);
        scrollToBottom();

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ message: message }),
            });

            const payload = await response.json();
            loading.remove();

            if (!response.ok || payload.status !== 'success') {
                bubble('Maaf, terjadi gangguan saat mengambil data. Silakan coba lagi sebentar.', false);
                return;
            }

            bubble(payload.answer || 'Maaf, saya belum bisa menjawab pertanyaan ini.', false);
            addSources(payload.sources);
        } catch (error) {
            loading.remove();
            bubble('Maaf, koneksi ke portal sedang terganggu. Silakan coba lagi.', false);
        } finally {
            setLoading(false);
            scrollToBottom();
            input.focus();
        }
    });

    input.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            form.requestSubmit();
        }
    });
})();
</script>
