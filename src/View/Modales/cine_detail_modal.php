<!-- Modal de Detalles de Cine - Estilo Premium Minimalista -->
<div id="cine-detail-modal" class="hidden fixed inset-0 z-[110] flex items-center justify-center bg-black/80 backdrop-blur-md p-4 transition-all duration-500 opacity-0 pointer-events-none" onclick="if(event.target === this) closeCineDetail()">
    <div class="bg-zinc-950/40 backdrop-blur-2xl w-full max-w-4xl max-h-[90vh] rounded-[2.5rem] border border-white/5 shadow-2xl overflow-y-auto custom-scrollbar transform transition-all duration-500 scale-95" id="cine-detail-content">
        
        <!-- Header: Cine Info -->
        <div class="relative p-12 pb-8">
            <button onclick="closeCineDetail()" class="absolute top-8 right-8 btn-close-rot z-20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="space-y-4">
                <h2 id="cine-detail-name" class="text-5xl md:text-6xl font-extrabold text-white tracking-tight leading-tight"></h2>
                <div class="flex flex-col md:flex-row md:items-center gap-6 text-zinc-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#E50914]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/></svg>
                        <span id="cine-detail-address" class="text-xs font-medium"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#E50914]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-width="2"/></svg>
                        <span id="cine-detail-phone" class="text-xs font-medium tracking-tighter"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Body: Filters & Content -->
        <div class="p-12 pt-0 bg-[#09090b]/50">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 border-b border-white/5 pb-6">
                <h4 class="text-white font-bold text-xs tracking-widest font-montserrat mb-4 md:mb-0">Películas en cartelera</h4>
                
                <div class="flex items-center gap-4">
                    <span class="text-[10px] font-bold text-zinc-500 tracking-widest">Filtrar por sala:</span>
                    <select id="cine-filter-sala" class="bg-zinc-900 border border-white/5 text-white text-[10px] font-bold px-4 py-2 rounded-lg focus:border-[#E50914] outline-none transition-all cursor-pointer">
                        <option value="">Todas las salas</option>
                    </select>
                </div>
            </div>

            <!-- Listado de Peliculas/Funciones -->
            <div id="cine-functions-list" class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[400px] overflow-y-auto custom-scrollbar pr-2">
                <!-- Inyectado por JS -->
            </div>
        </div>
    </div>
</div>
