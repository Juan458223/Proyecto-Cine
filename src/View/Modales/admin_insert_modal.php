<div id="admin-insert-modal" class="hidden fixed inset-0 z-[120] flex items-center justify-center bg-black/80 backdrop-blur-md p-4 transition-all duration-500 opacity-0 pointer-events-none" onclick="if(event.target === this) closeInsertModal()">
    <div class="bg-zinc-950/40 backdrop-blur-2xl w-full max-w-lg rounded-[2.5rem] border border-white/5 shadow-2xl overflow-hidden transform transition-all duration-500 scale-95" id="admin-insert-content">
        <div class="modal-content-wrapper">
            <!-- Botón Cerrar Animado -->
            <div class="flex justify-end -mb-6">
                <button onclick="closeInsertModal()" class="btn-close-rot">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="admin-insert-form" class="crud-form-modern">
                <input type="hidden" name="id" id="form-id">
                
                <div id="insert-form-fields" class="space-y-10 modal-scroll-container">
                    <!-- Los campos se inyectan dinámicamente por JS -->
                </div>

                <div class="pt-8 flex flex-col gap-4">
                    <button type="submit" id="form-submit-btn" class="w-full bg-[#E50914] hover:bg-[#b90710] text-white text-[10px] font-black uppercase tracking-[0.4em] py-5 rounded-xl transition-all active:scale-95 shadow-xl shadow-red-900/20 font-montserrat">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
