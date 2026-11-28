path = r'resources/views/public/sejarah/show.blade.php'
with open(path, 'r', encoding='utf-8') as f:
    c = f.read()

old = """      {{-- Video Storytelling --}}
       @if ($videoStorytelling)
       <div>
         <div class="mb-4">
           <h3 class="font-display text-xl font-extrabold text-slate-900">Video Storytelling</h3>
           <p class="mt-2 text-[1rem] leading-relaxed text-slate-700">
             Saksikan kilas balik perjalanan historis dan suasana di gedung merdeka melalui video narasi sinematik berdurasi interaktif.
           </p>
         </div>
         <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_6px_30px_rgba(15,23,42,0.10)] transition-all duration-300 hover:shadow-[0_12px_40px_rgba(15,23,42,0.18)]">
           <div class="relative">
             <video controls playsinline preload="metadata" id="storytelling-video"
               class="aspect-video w-full bg-slate-900"
               @if ($posterVideo) poster="{{ $posterVideo }}" @endif>
               <source src="{{ $videoStorytelling }}">
               Browser Anda tidak mendukung pemutar video.
             </video>
             <button type="button" id="storytelling-play-btn" 
               class="absolute left-1/2 top-1/2 z-20 grid -translate-x-1/2 -translate-y-1/2 h-20 w-20 place-items-center rounded-full bg-white/95 text-navy-900 shadow-[0_8px_40px_rgba(0,0,0,0.4)] transition-all duration-300 hover:scale-110 hover:bg-white hover:shadow-[0_12px_50px_rgba(0,0,0,0.5)]">
               <i class="fas fa-play text-2xl ml-1"></i>
             </button>
             <div class="absolute left-4 top-4 inline-flex items-center gap-1.5 rounded-full bg-navy-900/95 px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-white shadow-[0_4px_16px_rgba(15,23,42,0.25)] backdrop-blur-sm">
               <i class="fas fa-play-circle text-[10px]"></i>
               Video Storytelling
             </div>
             <div class="absolute bottom-4 right-4 rounded-full bg-slate-900/85 px-2.5 py-1 text-xs font-semibold text-white">
               03:00
             </div>
           </div>
           <div class="flex items-center gap-2.5 border-t border-slate-100 px-4 py-3 transition-colors duration-300 group-hover:bg-slate-50/50">
             <span class="grid h-9 w-9 place-items-center rounded-lg bg-gradient-to-br from-navy-800 to-navy-900 text-white">
               <i class="fas fa-clapperboard text-xs"></i>
             </span>
             <div>
               <p class="font-display text-sm font-bold text-slate-900">Kilas Balik Sejarah Konferensi Asia Afrika (KAA) 1955</p>
               <p class="text-xs text-slate-600">Durasi 3 menit • Video Interaktif</p>
             </div>
           </div>
         </div>
       </div>
       <script>
         document.addEventListener('DOMContentLoaded', function() {
           const video = document.getElementById('storytelling-video');
           const playBtn = document.getElementById('storytelling-play-btn');
           if (video && playBtn) {
             playBtn.addEventListener('click', function() {
               video.play();
               playBtn.style.display = 'none';
             });
             video.addEventListener('play', function() {
               playBtn.style.display = 'none';
             });
             video.addEventListener('pause', function() {
               if (video.currentTime > 0 && !video.ended) {
                 playBtn.style.display = 'grid';
               }
             });
             video.addEventListener('ended', function() {
               playBtn.style.display = 'grid';
             });
           }
         });
       </script>
       @endif"""

new = """      {{-- Video Storytelling --}}
       @if ($videoStorytelling)
       <div>
         <div class="mb-4">
           <h3 class="font-display text-xl font-extrabold text-slate-900">Video Storytelling</h3>
           <p class="mt-2 text-[1rem] leading-relaxed text-slate-700">
             Saksikan kilas balik perjalanan historis dan suasana di gedung merdeka melalui video narasi sinematik berdurasi interaktif.
           </p>
         </div>
         <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_6px_30px_rgba(15,23,42,0.10)] transition-all duration-300 hover:shadow-[0_12px_40px_rgba(15,23,42,0.18)]">
           <div class="relative">
             <video controls playsinline preload="metadata" id="storytelling-video"
               class="aspect-video w-full bg-slate-900"
               @if ($posterVideo) poster="{{ $posterVideo }}" @endif>
               <source src="{{ $videoStorytelling }}">
               Browser Anda tidak mendukung pemutar video.
             </video>
             <button type="button" id="storytelling-play-btn" 
               class="absolute left-1/2 top-1/2 z-20 grid -translate-x-1/2 -translate-y-1/2 h-20 w-20 place-items-center rounded-full bg-white/95 text-navy-900 shadow-[0_8px_40px_rgba(0,0,0,0.4)] transition-all duration-300 hover:scale-110 hover:bg-white hover:shadow-[0_12px_50px_rgba(0,0,0,0.5)]">
               <i class="fas fa-play text-2xl ml-1"></i>
             </button>
             <div class="absolute left-4 top-4 inline-flex items-center gap-1.5 rounded-full bg-navy-900/95 px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-white shadow-[0_4px_16px_rgba(15,23,42,0.25)] backdrop-blur-sm">
               <i class="fas fa-play-circle text-[10px]"></i>
               Video Storytelling
             </div>
             <div class="absolute bottom-4 right-4 rounded-full bg-slate-900/85 px-2.5 py-1 text-xs font-semibold text-white">
               03:00
             </div>
           </div>
           <div class="flex items-center gap-2.5 border-t border-slate-100 px-4 py-3 transition-colors duration-300 group-hover:bg-slate-50/50">
             <span class="grid h-9 w-9 place-items-center rounded-lg bg-gradient-to-br from-navy-800 to-navy-900 text-white">
               <i class="fas fa-clapperboard text-xs"></i>
             </span>
             <div>
               <p class="font-display text-sm font-bold text-slate-900">Kilas Balik Sejarah Konferensi Asia Afrika (KAA) 1955</p>
               <p class="text-xs text-slate-600">Durasi 3 menit • Video Interaktif</p>
             </div>
           </div>
         </div>
         <a href="{{ $videoStorytelling }}" target="_blank" rel="noopener"
           class="mt-3 block overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_6px_30px_rgba(15,23,42,0.10)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_40px_rgba(15,23,42,0.18)]">
           <div class="flex items-center gap-4 p-4">
             <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-navy-800 to-navy-900 text-white shadow-sm">
               <i class="fas fa-film text-sm"></i>
             </span>
             <div class="min-w-0 flex-1">
               <p class="font-display text-sm font-bold text-slate-900">Video Storytelling - Kilas Balik KAA 1955</p>
               <p class="mt-0.5 text-xs text-slate-600">Saksikan narasi sinematik berdurasi 3 menit tentang Gedung Merdeka</p>
             </div>
             <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-3 py-1 text-xs font-bold text-brand-700">
               <i class="fas fa-play text-[9px]"></i>
               Putar Video
             </span>
           </div>
         </a>
       </div>
       <script>
         document.addEventListener('DOMContentLoaded', function() {
           const video = document.getElementById('storytelling-video');
           const playBtn = document.getElementById('storytelling-play-btn');
           if (video && playBtn) {
             playBtn.addEventListener('click', function() {
               video.play();
               playBtn.style.display = 'none';
             });
             video.addEventListener('play', function() {
               playBtn.style.display = 'none';
             });
             video.addEventListener('pause', function() {
               if (video.currentTime > 0 && !video.ended) {
                 playBtn.style.display = 'grid';
               }
             });
             video.addEventListener('ended', function() {
               playBtn.style.display = 'grid';
             });
           }
         });
       </script>
       @endif"""

if old not in c:
    print('not found')
else:
    c = c.replace(old, new)
    with open(path, 'w', encoding='utf-8') as f:
        f.write(c)
    print('done')
