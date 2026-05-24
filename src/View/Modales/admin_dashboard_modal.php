<!-- 1. Layout Principal del Dashboard -->
<div id="admin-dashboard-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm p-0 md:p-6 transition-all duration-500 opacity-0" onclick="if(event.target === this) closeAdminDashboard()">
    
    <div class="bg-zinc-950 border border-zinc-800/50 w-full max-w-7xl h-full md:h-[90vh] md:rounded-3xl flex flex-col md:flex-row shadow-2xl overflow-hidden transform transition-all duration-500 scale-95 opacity-0" id="admin-modal-content">

        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-72 bg-zinc-950 border-b md:border-b-0 md:border-r border-zinc-900 flex flex-col shrink-0">
            <div class="p-8 border-b border-zinc-900 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-black text-[#E50914] font-bebas tracking-tighter leading-none">CINE ADMIN</h2>
                    <p class="text-zinc-600 text-[8px] font-bold tracking-[0.3em] mt-2 font-montserrat uppercase">Sistema de Gestión</p>
                </div>
            </div>
            
            <nav class="flex-1 overflow-y-auto custom-scrollbar py-6 px-4 space-y-2">
                <button onclick="switchAdminTab('movies')" id="tab-movies" class="sidebar-btn group active border-l-2 border-[#E50914] bg-zinc-900/50">
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
                    <span>Protagonistas</span>
                </button>
                
                <div class="pt-4 pb-2 px-4 text-[10px] font-bold text-zinc-700 uppercase tracking-[0.2em]">Marketing</div>
                <button onclick="switchAdminTab('tokens')" id="tab-tokens" class="sidebar-btn group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" stroke-width="2"/></svg>
                    <span>Sistema de Tokens</span>
                </button>
            </nav>

            <div class="p-6 border-t border-zinc-900">
                <button onclick="closeAdminDashboard()" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-zinc-900 hover:bg-zinc-800 text-zinc-400 hover:text-white transition-all text-xs font-bold uppercase tracking-widest">
                    <span>Salir</span>
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0 bg-[#09090b] relative">
            <header class="h-24 p-8 flex items-center justify-between border-b border-zinc-900 bg-zinc-950/20 backdrop-blur-md">
                <div class="flex flex-col">
                    <h3 id="current-tab-title" class="text-3xl font-black text-white font-bebas tracking-wider leading-none uppercase">Tokens Activos</h3>
                    <p class="text-zinc-500 text-[9px] font-bold mt-2 tracking-[0.2em] font-montserrat uppercase">Gestión de beneficios y entradas</p>
                </div>
                <div class="flex items-center gap-4">
                    <button onclick="openValidateTokenModal()" class="flex items-center gap-2 bg-zinc-900 hover:bg-zinc-800 text-zinc-300 text-[10px] font-black uppercase tracking-widest px-6 py-3 rounded-lg transition-all border border-zinc-800">
                        Validar Código
                    </button>
                    <button onclick="openCreateTokenModal()" class="flex items-center gap-2 bg-[#E50914] hover:bg-[#b90710] text-white text-[10px] font-black uppercase tracking-widest px-6 py-3 rounded-lg transition-all shadow-xl shadow-red-900/10 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                        <span>Crear Token</span>
                    </button>
                </div>
            </header>

            <section id="admin-main-view" class="flex-1 overflow-auto p-8 custom-scrollbar">
                <!-- Table View de Tokens -->
                <div class="bg-zinc-900/10 rounded-2xl border border-white/5 overflow-hidden shadow-2xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-zinc-500 text-[10px] font-black uppercase tracking-[0.2em] bg-zinc-950/50 border-b border-zinc-900 font-montserrat">
                                <th class="px-8 py-6">Código</th>
                                <th class="px-8 py-6">Beneficio</th>
                                <th class="px-8 py-6">Expiración</th>
                                <th class="px-8 py-6">Estado</th>
                                <th class="px-8 py-6 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="token-table-body" class="text-zinc-400 text-[11px] font-bold divide-y divide-zinc-900/50 font-outfit">
                            <!-- Injected via JS -->
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</div>
