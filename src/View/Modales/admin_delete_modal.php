<div id="admin-delete-modal" class="hidden fixed inset-0 z-[150] flex items-center justify-center bg-black/60 backdrop-blur-md p-4 transition-all duration-500 opacity-0 pointer-events-none" onclick="if(event.target === this) closeDeleteModal()">
    <div class="bg-zinc-950/40 backdrop-blur-2xl w-full max-w-md rounded-[2.5rem] border border-white/5 shadow-2xl overflow-hidden transform transition-all duration-500 scale-95" id="admin-delete-content">
        <div class="p-10 md:p-14 text-center space-y-8">
            <div class="w-20 h-20 bg-red-500/10 rounded-full flex items-center justify-center mx-auto border border-red-500/20">
                <svg class="w-10 h-10 text-[#E50914]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            
            <div class="space-y-3">
                <h3 class="text-2xl font-black text-white font-bebas tracking-wider uppercase">¿Confirmar eliminación?</h3>
                <p class="text-zinc-500 text-sm font-medium font-montserrat">Esta acción es irreversible y eliminará permanentemente el registro de la base de datos.</p>
            </div>

            <div class="flex flex-col gap-3 pt-4">
                <button id="confirm-delete-btn" class="w-full bg-[#E50914] hover:bg-[#b90710] text-white text-[10px] font-black uppercase tracking-[0.4em] py-5 rounded-xl transition-all active:scale-95 shadow-xl shadow-red-900/20 font-montserrat">
                    Confirmar eliminación
                </button>
                <button onclick="closeDeleteModal()" class="w-full bg-transparent hover:bg-white/5 text-zinc-500 hover:text-white text-[10px] font-black uppercase tracking-[0.4em] py-5 rounded-xl transition-all font-montserrat">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
