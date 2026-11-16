path = r'resources/views/public/sejarah/show.blade.php'
with open(path, 'r', encoding='utf-8') as f:
    s = f.read()

old = "         </div>\n         <script>"
card = "         </div>\n         <a href=\"{{ $videoStorytelling }}\" target=\"_blank\" rel=\"noopener\"\n           class=\"mt-3 block overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_6px_30px_rgba(15,23,42,0.10)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_40px_rgba(15,23,42,0.18)]\">\n           <div class=\"flex items-center gap-4 p-4\">\n             <span class=\"grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-navy-800 to-navy-900 text-white shadow-sm\">\n               <i class=\"fas fa-film text-sm\"></i>\n             </span>\n             <div class=\"min-w-0 flex-1\">\n               <p class=\"font-display text-sm font-bold text-slate-900\">Video Storytelling - Kilas Balik KAA 1955</p>\n               <p class=\"mt-0.5 text-xs text-slate-600\">Saksikan narasi sinematik berdurasi 3 menit tentang Gedung Merdeka</p>\n             </div>\n             <span class=\"inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-3 py-1 text-xs font-bold text-brand-700\">\n               <i class=\"fas fa-play text-[9px]\"></i>\n               Putar Video\n             </span>\n           </div>\n         </a>\n         <script>"

if old in s:
    s2 = s.replace(old, card)
    with open(path, 'w', encoding='utf-8') as f:
        f.write(s2)
    print('ok')
else:
    print('no')
