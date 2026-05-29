<div id="admin-action-modal" class="hidden fixed inset-0 z-[120] flex items-center justify-center bg-black/40 backdrop-blur-sm p-4 transition-all duration-500 opacity-0 pointer-events-none" onclick="if(event.target === this) closeInsertModal()">
    <div class="relative bg-white/[0.03] backdrop-blur-3xl pt-24 pb-12 px-10 md:px-14 rounded-[3.5rem] border border-white/10 shadow-2xl max-w-2xl w-full transform transition-all duration-500 scale-95" id="admin-action-content">
        
        <!-- Botón Cerrar: Estilo Rotativo Premium -->
        <button onclick="closeInsertModal()" class="btn-close-rot z-50 absolute top-10 right-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <form id="admin-action-form" class="space-y-10" novalidate>
            <input type="hidden" name="id" id="form-id">
            
            <div id="action-form-fields" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
            </div>

            <div class="pt-4 flex flex-col items-center">
                <button type="submit" id="form-submit-btn" class="btn-primary px-12 !py-4 tracking-[0.2em] text-[10px] min-w-[200px]">
                    Agregar cambios
                </button>
                <div id="action-error-container" class="mt-4 text-[10px] font-black uppercase tracking-widest text-[#E50914] text-center hidden h-4"></div>
                <p id="action-message" class="text-center text-xs font-bold mt-2 hidden text-white"></p>
            </div>
        </form>
    </div>
</div>