<!-- Modal Dashboard Administrativo -->
<div id="admin-dashboard-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/90 backdrop-blur-sm p-4 overflow-hidden">
    <div class="bg-zinc-950 w-full max-w-6xl h-full max-h-[85vh] rounded-sm border border-zinc-800 flex flex-col shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="admin-modal-content">
        
        <!-- Header & Tabs -->
        <div class="p-8 border-b border-zinc-900 flex flex-col xl:flex-row xl:items-center justify-between gap-6">
            <div>
                <h2 class="text-4xl font-black text-[#E50914] font-bebas tracking-[0.2em] uppercase leading-none">PANEL DE CONTROL</h2>
                <p class="text-zinc-600 text-[9px] font-bold uppercase tracking-[0.3em] mt-2">Gestión centralizada de recursos</p>
            </div>
            
            <div class="flex flex-wrap bg-zinc-900 p-1 rounded-sm border border-zinc-800 gap-1">
                <button onclick="switchAdminTab('movies')" id="tab-movies" class="admin-tab active">Películas</button>
                <button onclick="switchAdminTab('users')" id="tab-users" class="admin-tab">Usuarios</button>
                <button onclick="switchAdminTab('cines')" id="tab-cines" class="admin-tab">Cines</button>
                <button onclick="switchAdminTab('salas')" id="tab-salas" class="admin-tab">Salas</button>
                <button onclick="switchAdminTab('funciones')" id="tab-funciones" class="admin-tab">Funciones</button>
                <button onclick="switchAdminTab('generos')" id="tab-generos" class="admin-tab">Géneros</button>
                <button onclick="switchAdminTab('tarifas')" id="tab-tarifas" class="admin-tab">Tarifas</button>
                <button onclick="switchAdminTab('estados')" id="tab-estados" class="admin-tab">Estados</button>
                <button onclick="switchAdminTab('tipos_tokens')" id="tab-tipos_tokens" class="admin-tab">Tipos Tokens</button>
                <button onclick="switchAdminTab('tokens')" id="tab-tokens" class="admin-tab">Tokens</button>
                <button onclick="switchAdminTab('protagonists')" id="tab-protagonists" class="admin-tab">Protagonistas</button>
            </div>

            <button onclick="closeAdminDashboard()" class="text-zinc-500 hover:text-[#E50914] transition-all bg-zinc-900 p-2 rounded-full border border-zinc-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Botón de Inserción -->
        <div class="px-8 py-4 bg-zinc-900/30 border-b border-zinc-900 flex justify-end">
            <button onclick="openInsertModal()" class="flex items-center gap-2 bg-[#E50914] hover:bg-[#b90710] text-white text-[10px] font-black uppercase tracking-widest px-6 py-2 rounded-sm transition-all shadow-lg shadow-red-900/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                </svg>
                <span id="insert-btn-text">Insertar película</span>
            </button>
        </div>

        <!-- Body / Table Area -->
        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar bg-black/20">
            <div class="relative overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr id="admin-table-head" class="text-zinc-600 text-[10px] font-black uppercase tracking-[0.2em] border-b border-zinc-800">
                            <!-- Headers inyectados por JS -->
                        </tr>
                    </thead>
                    <tbody id="admin-table-body" class="text-zinc-400 text-[11px] font-bold divide-y divide-zinc-900/50">
                        <!-- Filas inyectadas por JS -->
                    </tbody>
                </table>
                
                <div id="admin-loading" class="hidden py-20 text-center">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-[#E50914]"></div>
                </div>
            </div>
        </div>

        <!-- Footer / Pagination -->
        <div class="p-8 border-t border-zinc-900 bg-zinc-950 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="h-2 w-2 rounded-full bg-[#E50914] animate-pulse"></div>
                <span id="pagination-info" class="text-[10px] text-zinc-500 font-black uppercase tracking-[0.2em]">Página 1 de 1</span>
            </div>
            
            <div class="flex gap-2">
                <button id="prev-page" class="group bg-zinc-900 hover:bg-[#E50914] border border-zinc-800 text-white p-3 rounded-sm disabled:opacity-20 disabled:hover:bg-zinc-900 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button id="next-page" class="group bg-zinc-900 hover:bg-[#E50914] border border-zinc-800 text-white p-3 rounded-sm disabled:opacity-20 disabled:hover:bg-zinc-900 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .admin-tab {
        @apply px-4 py-2 text-[9px] font-black uppercase tracking-widest transition-all text-zinc-500 hover:text-white rounded-sm;
    }
    .admin-tab.active {
        @apply bg-[#E50914] text-white;
    }
</style>
