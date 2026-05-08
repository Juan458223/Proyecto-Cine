<!-- Modal de Detalles de Película (Solo Lectura) -->
<div id="movie-admin-modal" tabindex="-1" aria-hidden="true" 
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[100] justify-center items-center w-full md:inset-0 h-full bg-black/80 backdrop-blur-sm flex transition-all duration-300 opacity-0">
    
    <div class="relative p-4 w-full max-w-3xl max-h-full transform scale-95 transition-transform duration-300" id="movie-modal-content">
        <!-- Contenido del Modal -->
        <div class="relative bg-zinc-900 rounded-2xl shadow-2xl border border-zinc-800 overflow-hidden">
            
            <!-- Header con Imagen de Fondo -->
            <div class="relative h-64 bg-zinc-800">
                <div id="modal-header-bg" class="absolute inset-0 bg-cover bg-center opacity-50"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-zinc-900 via-zinc-900/40 to-transparent"></div>
                
                <div class="absolute bottom-8 left-8 text-left right-8">
                    <div class="flex items-center gap-3 mb-3">
                        <span id="modal-info-clasif" class="bg-red-600 text-white text-[10px] px-2 py-1 rounded font-bold uppercase tracking-tighter shadow-lg shadow-red-900/40"></span>
                        <span id="modal-movie-subtitle" class="text-zinc-300 font-bold text-xs uppercase tracking-widest"></span>
                    </div>
                    <h2 id="modal-movie-title" class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter leading-none mb-2">Título</h2>
                </div>

                <button type="button" id="close-movie-admin-modal" class="absolute top-4 right-4 text-white/50 hover:text-white bg-black/20 hover:bg-black/40 p-2 rounded-full transition-all z-10">
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Cuerpo del Modal (Detalles) -->
            <div class="p-8 md:p-10 max-h-[50vh] overflow-y-auto custom-scrollbar text-left">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Columna Principal -->
                    <div class="md:col-span-2 space-y-8">
                        <div>
                            <h3 class="text-zinc-500 text-[10px] font-bold uppercase tracking-[0.2em] mb-4">Información General</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-zinc-800/40 p-5 rounded-2xl border border-zinc-800/50">
                                    <span class="text-zinc-500 text-[10px] font-bold uppercase block mb-2 tracking-widest">Director</span>
                                    <p id="modal-info-director" class="text-white font-semibold text-base"></p>
                                </div>
                                <div class="bg-zinc-800/40 p-5 rounded-2xl border border-zinc-800/50">
                                    <span class="text-zinc-500 text-[10px] font-bold uppercase block mb-2 tracking-widest">Género</span>
                                    <p id="modal-info-genero-ui" class="text-white font-semibold text-base"></p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-zinc-500 text-[10px] font-bold uppercase tracking-[0.2em] mb-4">Elenco Principal</h3>
                            <div class="bg-zinc-800/20 p-6 rounded-2xl border border-zinc-800/50">
                                <p id="modal-info-elenco" class="text-zinc-300 font-medium text-sm leading-relaxed"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Lateral / Call to Action -->
                    <div class="flex flex-col justify-start space-y-4">
                        <div class="bg-red-600/5 border border-red-600/20 p-6 rounded-2xl">
                            <h4 class="text-white font-bold text-sm mb-2">¿Quieres verla?</h4>
                            <p class="text-zinc-400 text-xs mb-4">Consulta los horarios disponibles en nuestras salas.</p>
                            <button class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition-all text-xs uppercase tracking-widest shadow-lg shadow-red-900/20">
                                Ver Horarios
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
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 9999px; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
