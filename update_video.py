path = r'resources/views/public/sejarah/show.blade.php'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

idx = content.find('   {{-- Media Terkait --}}')
if idx == -1:
    raise SystemExit('no start')

end = content.find('{{-- Notebook pendukung --}}')
if end == -1:
    raise SystemExit('no end')

new_section = """   {{-- Media Terkait --}}
   <section class="mt-12">
     <div class="grid gap-6 md:grid-cols-2">
       {{-- Video Storytelling --}}
       @if ($videoStorytelling)
       <div>
         <div class="mb-4">
           <h3 class="font-display text-xl font-extrabold text-slate-900">Video Storytelling</h3>
           <p class="mt-2 text-[1rem] leading-relaxed text-slate-700">
             Saksikan kilas balik perjalanan historis dan suasana di gedung merdeka melalui video narasi sinematik berdurasi interaktif.
           </p>
         </div>
         <div class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_6px_30px_rgba(15,23,42,0.10)] transition-all duration-300 hover:shadow-[0_12px_40px_rgba(15,23,42,0.18)]">
           <div class="relative">
             <video playsinline preload="metadata" id="storytelling-video"
               class="aspect-video w-full bg-slate-900"
               @if ($posterVideo) poster="{{ $posterVideo }}" @endif>
               <source src="{{ $videoStorytelling }}">
               Browser Anda tidak mendukung pemutar video.
             </video>
             <button type="button" id="storytelling-play-btn" 
               class="absolute left-1/2 top-1/2 z-10 grid -translate-x-1/2 -translate-y-1/2 h-20 w-20 place-items-center rounded-full bg-white/95 text-navy-900 shadow-[0_8px_40px_rgba(0,0,0,0.4)] transition-all duration-300 hover:scale-110 hover:bg-white hover:shadow-[0_12px_50px_rgba(0,0,0,0.5)] group-hover:scale-105">
               <i class="fas fa-play text-2xl ml-1"></i>
             </button>
             <div class="absolute left-4 top-4 inline-flex items-center gap-1.5 rounded-full bg-navy-900/95 px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-white shadow-[0_4px_16px_rgba(15,23,42,0.25)] backdrop-blur-sm transition-opacity duration-300 group-hover:opacity-90">
               <i class="fas fa-play-circle text-[10px]"></i>
               Video Storytelling
             </div>
             <div class="absolute bottom-4 right-4 rounded-full bg-slate-900/85 px-2.5 py-1 text-xs font-semibold text-white transition-opacity duration-300 group-hover:opacity-90">
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
       @endif

       {{-- History Flip Book Digital --}}
       <div>
         <div class="mb-4">
           <h3 class="font-display text-xl font-extrabold text-slate-900">History Flip Book Digital</h3>
           <p class="mt-2 text-[1rem] leading-relaxed text-slate-700">
             Klip buku digital untuk menelusuri kisah di balik layar konferensi asia afrika melalui lembaran halaman digital.
           </p>
         </div>
         <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_4px_20px_rgba(15,23,42,0.08)]">
           <div class="relative aspect-video w-full bg-slate-900">
             <div class="flex h-full w-full flex-col items-center justify-center gap-3 text-center text-white">
               <span class="grid h-14 w-14 place-items-center rounded-full bg-white/10 backdrop-blur-sm">
                 <i class="fas fa-book-open text-lg"></i>
               </span>
               <p class="font-display text-base font-bold">History Flip Book Digital</p>
               <p class="text-sm text-white/70">Pratinjau interaktif flip book</p>
             </div>
             <div class="absolute left-4 top-4 inline-flex items-center gap-1.5 rounded-full bg-navy-900/95 px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-white shadow-sm">
               <i class="fas fa-book text-[10px]"></i>
               Flip Book
             </div>
           </div>
           <div class="flex items-center gap-2.5 border-t border-slate-100 px-4 py-3">
             <span class="grid h-9 w-9 place-items-center rounded-lg bg-gradient-to-br from-navy-800 to-navy-900 text-white">
               <i class="fas fa-book text-xs"></i>
             </span>
             <div>
               <p class="font-display text-sm font-bold text-slate-900">History Flip Book Digital - {{ Str::limit($topik->judul_topik, 40) }}</p>
               <p class="text-xs text-slate-600">Versi Digital Materi Sejarah</p>
             </div>
           </div>
         </div>
       </div>
     </div>
   </section>
"""

content = content[:idx] + new_section + '\n' + content[end:]
with open(path, 'w', encoding='utf-8') as f:
    f.write(content)

print('done')
