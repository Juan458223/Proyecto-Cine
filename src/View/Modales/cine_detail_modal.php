<!-- Modal de Detalles de Cine - Estilo Premium Minimalista -->
<div id="cine-detail-modal" class="hidden fixed inset-0 z-[110] flex items-center justify-center bg-black/10 backdrop-blur-md p-4 transition-all duration-500 opacity-0 pointer-events-none" onclick="if(event.target === this) closeCineDetail()">
    <div class="bg-zinc-950/60 backdrop-blur-3xl w-full max-w-4xl rounded-[2.5rem] border border-white/10 shadow-2xl custom-scrollbar transform transition-all duration-500 scale-95 opacity-0 flex flex-col" id="cine-detail-content">
        
        <!-- Header: Cine Info -->
        <div class="relative p-12 pb-8 border-b border-white/5 bg-zinc-950/20">
            <!-- Botón Cerrar: Estilo Rotativo Premium -->
            <button onclick="closeCineDetail()" class="btn-close-rot z-30">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
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

        <!-- Body: Two Columns Table Layout -->
        <div class="p-12 bg-[#09090b]/50">
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-12">

                <!-- Left Column: Tarifas -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between border-b border-white/5 pb-4">
                        <h4 class="text-white font-bold text-sm tracking-widest">Tabla de tarifas</h4>
                    </div>

                    <div class="rounded-xl border border-white/5 bg-zinc-900/20">
                        <table class="w-full text-left text-[11px]">
                            <thead class="bg-white/5 text-zinc-500 font-bold tracking-wider sticky top-0">
                                <tr>
                                    <th class="px-6 py-4">Público</th>
                                    <th class="px-6 py-4">Día</th>
                                    <th class="px-6 py-4 text-right">Precio</th>
                                </tr>
                            </thead>
                            <tbody id="cine-tarifa-table-body" class="divide-y divide-white/5 text-zinc-300">
                                <!-- Inyectado por JS -->
                            </tbody>
                        </table>
                    </div>
                    <!-- Paginación de Tarifas -->
                    <div id="cine-tarifa-pagination" class="flex justify-center gap-2 pt-4"></div>
                    <div id="cine-tarifa-pagination" class="flex justify-center gap-2 pt-2"></div>
                </div>

                <!-- Right Column: Funciones -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between border-b border-white/5 pb-4">
                        <h4 class="text-white font-bold text-sm tracking-widest">Cartelera del cine</h4>
                        <select id="cine-filter-sala" class="bg-zinc-900/50 border border-white/5 text-white text-[10px] px-4 py-1.5 rounded-lg focus:border-[#E50914] outline-none transition-all cursor-pointer">
                            <option value="">Todas las salas</option>
                        </select>
                    </div>

                    <div class="overflow-hidden rounded-xl border border-white/5 bg-zinc-900/20">
                        <table class="w-full text-left text-[11px]">
                            <thead class="bg-white/5 text-zinc-500 font-bold tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Película</th>
                                    <th class="px-6 py-4">Horario</th>
                                    <th class="px-6 py-4 text-right">Sala</th>
                                </tr>
                            </thead>
                            <tbody id="cine-funcion-table-body" class="divide-y divide-white/5 text-zinc-300">
                                <!-- Inyectado por JS -->
                            </tbody>
                        </table>
                    </div>
                    <div id="cine-functions-pagination" class="flex justify-center gap-2 pt-2"></div>
                </div>

            </div>
        </div>
    </div>
</div>
