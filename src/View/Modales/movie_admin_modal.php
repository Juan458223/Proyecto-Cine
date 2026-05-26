<div id="movie-admin-modal" class="hidden fixed inset-0 z-[110] flex items-center justify-center bg-black/80 backdrop-blur-md p-4 transition-all duration-500 opacity-0 pointer-events-none" onclick="if(event.target === this) closeMovieAdmin()">
    <div class="bg-zinc-950/40 backdrop-blur-2xl w-full max-w-4xl max-h-[90vh] rounded-[2.5rem] border border-white/5 shadow-2xl overflow-y-auto custom-scrollbar transform transition-all duration-500 scale-95" id="movie-admin-content">
        <!-- Header: Banner & Poster -->
        <div class="relative h-[400px]">
            <img id="movie-admin-banner" src="" class="w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-t from-[#09090b] via-[#09090b]/60 to-transparent"></div>
            
            <!-- Botón Cerrar: Estilo Rotativo Premium -->
            <button onclick="closeMovieAdmin()" class="btn-close-rot z-30">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="absolute bottom-12 left-12 right-12 flex flex-col md:flex-row items-end gap-10">
                <img id="movie-admin-poster" src="" class="w-48 h-72 object-cover rounded-2xl shadow-2xl border border-white/10 transform -rotate-2 hover:rotate-0 transition-transform duration-500">
                <div class="flex-1 space-y-4">
                    <div id="movie-admin-genres" class="flex gap-2"></div>
                    <h2 id="movie-admin-title" class="text-5xl md:text-6xl font-extrabold text-white tracking-tight leading-tight"></h2>
                    <p id="movie-admin-director" class="text-[#E50914] text-base font-bold font-outfit"></p>
                </div>
            </div>
        </div>

        <!-- Body: Functions, Rating & Cast -->
        <div class="p-12 grid grid-cols-1 md:grid-cols-3 gap-12 bg-[#09090b]">
            <div class="md:col-span-2 space-y-10">
                <div class="space-y-6">
                    <h4 class="text-white font-bold text-xs tracking-widest font-montserrat">Funciones disponibles</h4>
                    <div id="movie-admin-functions" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Injected by JS -->
                    </div>
                    
                    <!-- Paginación de Funciones Centrada -->
                    <div id="movie-functions-pagination" class="flex items-center justify-center gap-2 mt-8">
                        <!-- Inyectado por JS -->
                    </div>
                </div>
            </div>

            <div class="space-y-10">
                <div class="space-y-6">
                    <h4 class="text-white font-bold text-xs tracking-widest font-montserrat">Clasificación</h4>
                    <div class="flex items-center gap-4">
                        <div id="movie-admin-rating" class="text-xl font-bold text-white font-outfit bg-zinc-900 px-6 py-3 rounded-xl border border-white/5"></div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h4 class="text-white font-bold text-xs tracking-widest font-montserrat">Reparto</h4>
                    <div id="movie-admin-cast" class="text-zinc-400 text-xs font-medium leading-relaxed"></div>
                </div>
            </div>
        </div>
    </div>
</div>
