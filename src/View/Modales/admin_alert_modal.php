<div id="admin-alert-modal" class="hidden fixed inset-0 z-[200] flex items-center justify-center bg-black/60 backdrop-blur-md p-4 transition-all duration-500 opacity-0 pointer-events-none" onclick="if(event.target === this) closeAdminAlert()">
    <div class="bg-zinc-950/40 backdrop-blur-2xl w-full max-w-sm rounded-[2.5rem] border border-white/5 shadow-2xl overflow-hidden transform transition-all duration-500 scale-95" id="admin-alert-content">
        <div class="p-10 text-center space-y-6">
            <div id="admin-alert-icon" class="w-16 h-16 bg-[#E50914]/10 rounded-full flex items-center justify-center mx-auto text-[#E50914]">
                <!-- Injected by JS -->
            </div>
            
            <div class="space-y-2">
                <h3 id="admin-alert-title" class="text-xl font-black text-white font-bebas tracking-wider uppercase"></h3>
                <p id="admin-alert-message" class="text-zinc-500 text-xs font-medium font-montserrat"></p>
            </div>

            <button onclick="closeAdminAlert()" class="w-full bg-white/5 hover:bg-white/10 text-white text-[10px] font-black uppercase tracking-[0.4em] py-4 rounded-xl transition-all font-montserrat">
                Entendido
            </button>
        </div>
    </div>
</div>
