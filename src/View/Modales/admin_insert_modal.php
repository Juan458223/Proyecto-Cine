<div id="admin-insert-modal" class="hidden fixed inset-0 z-[120] flex items-center justify-center bg-black/10 backdrop-blur-md p-4 transition-all duration-500 opacity-0 pointer-events-none" onclick="if(event.target === this) closeInsertModal()">
    <div class="bg-zinc-950/60 backdrop-blur-3xl w-full max-w-lg rounded-[2.5rem] border border-white/10 shadow-2xl overflow-hidden transform transition-all duration-500 scale-95" id="admin-insert-content">
        
        <!-- Botón Cerrar: Estilo Rotativo Premium -->
        <button onclick="closeInsertModal()" class="btn-close-rot z-50 absolute top-6 right-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="p-12">
            <h3 class="text-2xl font-black text-white font-bebas tracking-wider uppercase mb-10">Gestionar Registro</h3>
            <form id="admin-insert-form" class="space-y-8">
                <input type="hidden" name="id" id="form-id">
                
                <div id="insert-form-fields" class="space-y-6">
                    <!-- Los campos se inyectan dinámicamente -->
                </div>

                <div class="pt-6">
                    <button type="submit" id="form-submit-btn" class="w-full bg-[#E50914] hover:bg-[#b90710] text-white text-[10px] font-black uppercase tracking-[0.4em] py-4 rounded-xl transition-all shadow-xl active:scale-95 font-montserrat">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
