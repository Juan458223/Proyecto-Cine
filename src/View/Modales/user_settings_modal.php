<div id="user-settings-modal" class="hidden fixed inset-0 z-[120] flex items-center justify-center bg-black/40 backdrop-blur-sm transition-all duration-500 opacity-0 pointer-events-none p-4" onclick="if(event.target === this) closeUserSettings()">
    <div class="relative bg-white/[0.03] backdrop-blur-3xl pt-24 pb-12 px-10 md:px-14 rounded-[3.5rem] border border-white/10 shadow-2xl max-w-md w-full transform transition-all duration-500 scale-95" id="settings-modal-content">
        
        <button onclick="closeUserSettings()" class="absolute top-10 right-10 btn-close-rot z-20">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <form id="user-settings-form" class="space-y-10">
            <div class="pt-4"></div>

            <div class="auth-input-group">
                <input type="text" name="nombre" id="settings-nombre" class="auth-input-modern" placeholder=" ">
                <label class="auth-label-modern">Nombre completo</label>
            </div>
            
            <div class="auth-input-group">
                <input type="email" name="email" id="settings-email" disabled class="auth-input-modern !text-zinc-600 opacity-50 cursor-not-allowed" placeholder=" ">
                <label class="auth-label-modern !text-zinc-700">Correo electrónico (no editable)</label>
            </div>

            <?php echo render_password_input('password_settings', 'password', 'Nueva contraseña'); ?>

            <?php echo render_password_input('confirm_settings', 'confirm_password', 'Confirmar contraseña'); ?>

            <button type="submit" class="btn-primary">
                Guardar cambios
            </button>
            <div id="settings-message" class="hidden text-sm font-bold text-center py-4 text-white"></div>

        </form>
    </div>
</div>