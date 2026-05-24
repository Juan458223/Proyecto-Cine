<!-- 2. Modales de Creación y Validación de Tokens -->

<!-- Modal: Crear Token -->
<div id="token-create-modal" class="hidden fixed inset-0 z-[150] flex items-center justify-center bg-black/60 backdrop-blur-md p-4 transition-all duration-500 opacity-0 pointer-events-none" onclick="if(event.target === this) closeCreateTokenModal()">
    <div class="bg-zinc-950/40 backdrop-blur-2xl w-full max-w-lg rounded-[2.5rem] border border-white/5 shadow-2xl overflow-hidden transform transition-all duration-500 scale-95" id="token-create-content">
        <div class="p-10 md:p-14 space-y-10">
            <div class="flex justify-between items-center">
                <h3 class="text-3xl font-black text-white font-bebas tracking-wider uppercase leading-none">Generar Nuevo Token</h3>
                <button onclick="closeCreateTokenModal()" class="btn-close-rot">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="token-create-form" class="space-y-8">
                <div class="auth-input-group">
                    <input type="text" name="token_name" required class="auth-input-modern" placeholder=" ">
                    <label class="auth-label-modern">Nombre del Beneficio (Ej: Entrada VIP)</label>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="auth-input-group">
                        <input type="number" name="discount" required class="auth-input-modern" placeholder=" ">
                        <label class="auth-label-modern">% Descuento</label>
                    </div>
                    <div class="auth-input-group">
                        <input type="date" name="expiry" required class="auth-input-modern" placeholder=" ">
                        <label class="auth-label-modern">Fecha Expiración</label>
                    </div>
                </div>

                <div class="pt-6 flex gap-4">
                    <button type="submit" class="flex-1 bg-[#E50914] hover:bg-[#b90710] text-white text-[10px] font-black uppercase tracking-[0.4em] py-5 rounded-xl transition-all active:scale-95 shadow-xl shadow-red-900/20 font-montserrat">
                        Generar Código
                    </button>
                    <button type="button" onclick="closeCreateTokenModal()" class="flex-1 bg-zinc-900 hover:bg-zinc-800 text-zinc-400 text-[10px] font-black uppercase tracking-[0.4em] py-5 rounded-xl transition-all font-montserrat">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Validar Token -->
<div id="token-validate-modal" class="hidden fixed inset-0 z-[160] flex items-center justify-center bg-black/60 backdrop-blur-md p-4 transition-all duration-500 opacity-0 pointer-events-none" onclick="if(event.target === this) closeValidateTokenModal()">
    <div class="bg-zinc-950/40 backdrop-blur-2xl w-full max-w-md rounded-[3rem] border border-white/10 shadow-2xl overflow-hidden transform transition-all duration-500 scale-95" id="token-validate-content">
        <div class="p-12 text-center space-y-10">
            <button onclick="closeValidateTokenModal()" class="absolute top-8 right-8 text-zinc-500 hover:text-white transition-all">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="space-y-4">
                <div class="w-16 h-16 bg-[#E50914]/10 rounded-full flex items-center justify-center mx-auto text-[#E50914]">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-white text-lg font-bold tracking-tight uppercase font-bebas">Validar Token de Cliente</p>
            </div>

            <form id="token-validate-form" class="space-y-8">
                <div class="auth-input-group !pt-2">
                    <input type="text" id="validate-code-input" maxlength="12" class="auth-input-modern text-center !text-4xl tracking-[0.5em] font-black" placeholder=" ">
                    <label class="auth-label-modern !left-1/2 !-translate-x-1/2">Ingresar Código</label>
                </div>

                <div id="validate-status" class="hidden py-4 flex flex-col items-center justify-center">
                    <div class="relative w-10 h-10 mb-4">
                        <div class="absolute inset-0 rounded-full border-[2.5px] border-[#E50914]/10"></div>
                        <div class="absolute inset-0 rounded-full border-[2.5px] border-t-[#E50914] border-r-transparent border-b-transparent border-l-transparent animate-spin"></div>
                    </div>
                    <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-[0.3em] animate-pulse">Verificando en base de datos...</p>
                </div>

                <button type="submit" class="w-full bg-[#E50914] hover:bg-[#b90710] text-white text-[11px] font-black uppercase tracking-[0.4em] py-5 rounded-2xl shadow-xl active:scale-95 transition-all font-montserrat">
                    Validar Ahora
                </button>
            </form>
        </div>
    </div>
</div>
