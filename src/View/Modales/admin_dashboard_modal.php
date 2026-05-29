<!-- 1. Layout Principal del Dashboard -->
<div id="admin-dashboard-modal" class="hidden fixed inset-0 z-[110] flex items-center justify-center bg-black/10 backdrop-blur-md p-4 transition-all duration-500 opacity-0 pointer-events-auto" onclick="if(event.target === this) closeAdminDashboard()">
    
    <div class="bg-[#09090b]/70 backdrop-blur-3xl w-full max-w-7xl h-[90vh] rounded-[2.5rem] border border-white/10 shadow-2xl overflow-hidden transform transition-all duration-500 scale-95 opacity-0 flex flex-col md:flex-row relative" id="admin-modal-content">

        <button onclick="closeAdminDashboard()" class="btn-close-rot z-[120] absolute top-6 right-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <aside class="w-72 bg-white/5 border-r border-white/5 flex flex-col shrink-0 pt-24">
            <nav class="flex-1 overflow-y-auto custom-scrollbar py-6 px-4 space-y-2">
                <button onclick="switchAdminTab('movies')" id="tab-movies" class="sidebar-btn group active">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" stroke-width="2"/></svg>
                    <span>Películas</span>
                </button>
                <button onclick="switchAdminTab('users')" id="tab-users" class="sidebar-btn group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197" stroke-width="2"/></svg>
                    <span>Usuarios</span>
                </button>
                <button onclick="switchAdminTab('cines')" id="tab-cines" class="sidebar-btn group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/></svg>
                    <span>Cines</span>
                </button>
                <button onclick="switchAdminTab('salas')" id="tab-salas" class="sidebar-btn group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16m-7 6h7" stroke-width="2"/></svg>
                    <span>Salas</span>
                </button>
                <button onclick="switchAdminTab('funciones')" id="tab-funciones" class="sidebar-btn group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"/></svg>
                    <span>Funciones</span>
                </button>
                <button onclick="switchAdminTab('generos')" id="tab-generos" class="sidebar-btn group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" stroke-width="2"/></svg>
                    <span>Géneros</span>
                </button>
                <button onclick="switchAdminTab('tarifas')" id="tab-tarifas" class="sidebar-btn group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"/></svg>
                    <span>Tarifas</span>
                </button>
                <button onclick="switchAdminTab('protagonists')" id="tab-protagonists" class="sidebar-btn group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2"/></svg>
                    <span>Reparto</span>
                </button>
                <div class="h-px bg-white/5 my-4"></div>
                <button onclick="switchAdminTab('reports')" id="tab-reports" class="sidebar-btn group text-[#E50914]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2"/></svg>
                    <span>Reportes</span>
                </button>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col min-w-0">
            <header class="h-24 p-10 flex items-center justify-between border-b border-white/5">
                <div class="flex items-center gap-6">
                    <h3 id="current-tab-title" class="text-2xl font-black text-white font-bebas tracking-wider leading-none uppercase">PELÍCULAS</h3>
                    <button onclick="openCreateTokenModal()" class="flex items-center gap-2 bg-[#E50914] hover:bg-[#b90710] text-white text-[9px] font-black uppercase tracking-widest px-6 py-2 rounded-lg transition-all shadow-xl active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    </button>
                </div>
            </header>

            <section id="admin-main-view" class="flex-1 overflow-y-auto custom-scrollbar p-10">
                <div class="rounded-2xl border border-white/5 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-white/5 text-zinc-500 font-bold uppercase text-[9px] tracking-[0.2em]">
                            <tr id="admin-table-head">
                            </tr>
                        </thead>
                        <tbody id="token-table-body" class="text-zinc-400 text-xs font-medium divide-y divide-white/5">
                        </tbody>
                    </table>
                </div>
                <div id="admin-pagination" class="flex items-center justify-center gap-4 pt-10">
                    <button class="admin-prev-page w-10 h-10 rounded-xl bg-zinc-900 border border-white/5 flex items-center justify-center hover:bg-[#E50914] transition-all text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <div id="pagination-info" class="text-xs font-bold text-white"></div>
                    <button class="admin-next-page w-10 h-10 rounded-xl bg-zinc-900 border border-white/5 flex items-center justify-center hover:bg-[#E50914] transition-all text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </section>
        </main>
    </div>
</div>