<!-- Modal de Detalles de Película (Solo Lectura) -->
<div id="movie-admin-modal" tabindex="-1" aria-hidden="true" 
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[100] justify-center items-center w-full md:inset-0 h-full bg-black/40 backdrop-blur-xl flex transition-all duration-300 opacity-0">
    
    <div class="relative p-4 w-full max-w-3xl max-h-full transform scale-95 transition-transform duration-300" id="movie-modal-content">
        <!-- Contenido del Modal -->
        <div class="relative bg-zinc-950 rounded-sm shadow-2xl border border-zinc-800 overflow-hidden">
            
            <!-- Header con Imagen de Fondo -->
            <div class="relative h-72 bg-zinc-900">
                <div id="modal-header-bg" class="absolute inset-0 bg-cover bg-center opacity-40"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/20 to-transparent"></div>
                
                <div class="absolute bottom-8 left-8 text-left right-8">
                    <div class="flex items-center gap-3 mb-3">
                        <span id="modal-info-clasif" class="bg-[#E50914] text-white text-[10px] px-2 py-1 rounded-sm font-black uppercase tracking-widest shadow-xl"></span>
                        <span id="modal-movie-subtitle" class="text-zinc-400 font-bold text-xs uppercase tracking-[0.2em]"></span>
                    </div>
                    <h2 id="modal-movie-title" class="text-5xl md:text-6xl font-black text-white uppercase tracking-tighter leading-none mb-2 font-bebas">Título</h2>
                </div>

                <!-- Botón Cerrar con Animación -->
                <button type="button" id="close-movie-admin-modal" class="absolute top-6 right-6 text-white/40 hover:text-[#E50914] transition-all transform hover:rotate-90 z-20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Cuerpo del Modal (Detalles) -->
            <div class="p-10 md:p-12 max-h-[50vh] overflow-y-auto custom-scrollbar text-left bg-zinc-950">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <!-- Columna Principal -->
                    <div class="md:col-span-2 space-y-10">
                        <div>
                            <h3 class="text-zinc-600 text-[10px] font-black uppercase tracking-[0.3em] mb-6">FICHA TÉCNICA</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="bg-zinc-900/50 p-6 rounded-sm border border-zinc-800/50">
                                    <span class="text-zinc-500 text-[9px] font-black uppercase block mb-2 tracking-[0.2em]">Director</span>
                                    <p id="modal-info-director" class="text-white font-bold text-base uppercase tracking-tight font-bebas"></p>
                                </div>
                                <div class="bg-zinc-900/50 p-6 rounded-sm border border-zinc-800/50">
                                    <span class="text-zinc-500 text-[9px] font-black uppercase block mb-2 tracking-[0.2em]">Género</span>
                                    <p id="modal-info-genero-ui" class="text-white font-bold text-base uppercase tracking-tight font-bebas"></p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-zinc-600 text-[10px] font-black uppercase tracking-[0.3em] mb-6">REPARTO</h3>
                            <div class="bg-zinc-900/30 p-8 rounded-sm border border-zinc-800/30">
                                <p id="modal-info-elenco" class="text-zinc-400 font-medium text-sm leading-relaxed uppercase tracking-tighter italic"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Lateral / Call to Action -->
                    <div class="flex flex-col justify-start">
                        <div class="bg-[#E50914]/5 border border-[#E50914]/20 p-8 rounded-sm space-y-6">
                            <div class="space-y-2">
                                <h4 class="text-white font-black text-sm uppercase tracking-widest font-bebas">EXPERIENCIA CINE</h4>
                                <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-tight leading-relaxed">Disfruta esta producción con la mejor tecnología de proyección.</p>
                            </div>
                            <button class="w-full bg-[#E50914] hover:bg-[#b90710] text-white font-black py-4 rounded-sm transition-all text-[10px] uppercase tracking-[0.3em] shadow-2xl active:scale-95">
                                Ver horarios
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #27272a; border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #E50914; }
</style>
