<!-- Modal de Autenticación - RECONSTRUCCIÓN FINAL SIN TÍTULOS Y TRANSPARENCIA NÍTIDA -->
<div id="auth-modal" class="hidden fixed inset-0 z-[200] flex items-center justify-center bg-black/20 backdrop-blur-sm transition-all duration-500 opacity-0 pointer-events-none p-4">
    
    <div class="relative bg-white/[0.03] backdrop-blur-3xl pt-24 pb-12 px-10 md:px-14 rounded-[3.5rem] border border-white/10 shadow-2xl max-w-md w-full transform transition-all duration-500 scale-95" id="modal-container-inner">
        
        <!-- Botón de Cerrar Giratorio (MÁS ESPACIO INFERIOR) -->
        <button type="button" id="close-auth-modal" class="absolute top-10 right-10 btn-close-rot z-20">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="text-center flex flex-col gap-10">
            <!-- Mensaje principal (Blanco nítido) -->
            <div id="modal-message-container" class="hidden animate-in fade-in duration-700">
                <p id="modal-message" class="text-white text-xl md:text-2xl font-bold leading-tight tracking-tight font-outfit"></p>
            </div>

            <!-- Cargando (Spinner Premium con rastro) -->
            <div id="modal-loading" class="hidden py-4 flex flex-col items-center justify-center">
                <div class="relative w-12 h-12">
                    <div class="absolute inset-0 rounded-full border-[2.5px] border-[#E50914]/10"></div>
                    <div class="absolute inset-0 rounded-full border-[2.5px] border-t-[#E50914] border-r-transparent border-b-transparent border-l-transparent animate-spin"></div>
                    <div class="absolute inset-0 rounded-full border-[2.5px] border-t-transparent border-r-[#E50914]/60 border-b-transparent border-l-transparent animate-spin [animation-delay:0.1s]"></div>
                </div>
            </div>

            <!-- Formulario del Token -->
            <form id="modal-form" class="hidden space-y-10 animate-in fade-in slide-in-from-bottom-4 duration-700">
                <div class="auth-input-group">
                    <input type="text" name="code" id="verification-code" maxlength="6"
                        class="auth-input-token" 
                        placeholder=" ">
                    <label for="verification-code" class="auth-label-modern !left-1/2 !-translate-x-1/2">Código de seguridad</label>
                </div>
                <button type="submit" id="btn-submit-modal" class="btn-primary" style="background-color: #E50914 !important; color: white !important;">
                    Validar código
                </button>
            </form>

            <!-- Nueva Contraseña -->
            <form id="change-password-form" class="hidden space-y-8 animate-in fade-in duration-700">
                <div class="auth-input-group">
                    <input type="password" name="password" id="new-password" class="auth-input-modern" placeholder=" ">
                    <label class="auth-label-modern">Nueva contraseña</label>
                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('new-password')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            <path class="eye-slash hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                <div class="auth-input-group">
                    <input type="password" name="confirm_password" id="confirm-new-password" class="auth-input-modern" placeholder=" ">
                    <label class="auth-label-modern">Confirmar contraseña</label>
                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('confirm-new-password')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            <path class="eye-slash hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                <button type="submit" class="btn-primary" style="background-color: #E50914 !important; color: white !important;">
                    Actualizar contraseña
                </button>
            </form>

            <!-- Errores y Soporte (Texto Blanco Nítido) -->
            <div id="modal-error-box" class="hidden animate-in fade-in duration-500">
                <p id="modal-error" class="text-white text-base md:text-lg font-medium leading-relaxed font-outfit"></p>
                
                <div id="contact-admin-container" class="hidden mt-6">
                    <a href="mailto:cine0first@gmail.com" class="inline-block text-[#E50914] text-xs font-bold border-b-2 border-[#E50914]/40 pb-2 hover:border-[#E50914] transition-all font-outfit">
                        Contactar al administrador
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>