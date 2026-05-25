<!-- Modal de Alertas - ESTILO AUTH MODAL (TRANSPARENTE Y NÍTIDO) -->
<div id="admin-alert-modal" class="hidden fixed inset-0 z-[200] flex items-center justify-center bg-black/20 backdrop-blur-sm transition-all duration-500 opacity-0 pointer-events-none p-4" onclick="if(event.target === this) window.closeAdminAlert()">
    
    <div class="relative bg-white/[0.03] backdrop-blur-3xl pt-24 pb-12 px-10 md:px-14 rounded-[3.5rem] border border-white/10 shadow-2xl max-w-md w-full transform transition-all duration-500 scale-95" id="admin-alert-content">
        
        <!-- Botón de Cerrar Giratorio -->
        <button type="button" onclick="window.closeAdminAlert()" class="absolute top-10 right-10 btn-close-rot z-20">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="text-center">
            <!-- Mensaje principal (Blanco nítido como el index) -->
            <div id="admin-alert-message-container">
                <p id="admin-alert-message" class="text-white text-xl md:text-2xl font-bold leading-tight tracking-tight font-outfit"></p>
            </div>
        </div>
    </div>
</div>
