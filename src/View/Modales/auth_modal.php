<!-- Main Modal Container (CINE FIRST Style) -->
<div id="auth-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-full bg-black/40 backdrop-blur-md flex transition-all duration-500">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-zinc-950 rounded-sm shadow-2xl border border-zinc-800">
            <!-- Header (Solo la X a la derecha) -->
            <div class="flex items-center p-6 border-b border-zinc-900" id="modal-header">
                <h3 id="modal-title" class="hidden text-2xl font-black text-white uppercase tracking-widest font-bebas mr-auto"></h3>
                <button type="button" id="close-auth-modal" class="ml-auto text-zinc-600 hover:text-[#E50914] transition-all transform hover:rotate-90">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-10">
                <!-- Contenedor de Mensajes Informativos (Blanco/Zinc) -->
                <div id="modal-message-container" class="mb-8 text-center hidden">
                    <p id="modal-message" class="text-sm font-bold text-zinc-200 tracking-wide leading-relaxed"></p>
                </div>

                <!-- Loading State -->
                <div id="modal-loading" class="hidden flex flex-col items-center py-6">
                    <div class="w-10 h-10 border-2 border-zinc-800 border-t-[#E50914] rounded-full animate-spin"></div>
                </div>

                <!-- Formulario Código (Borde Completo Redondeado) -->
                <form id="modal-form" class="hidden space-y-8">
                    <div class="space-y-4">
                        <label for="verification-code" class="block text-[9px] font-black text-zinc-500 uppercase tracking-[0.4em] text-center">Ingrese el código</label>
                        <input type="text" name="code" id="verification-code" 
                            class="bg-transparent border-2 border-zinc-200 text-white text-4xl block w-full p-4 text-center font-black tracking-[0.5em] outline-none rounded-md focus:border-[#E50914] transition-all" 
                            placeholder="------" maxlength="6">
                    </div>
                    <button type="submit" id="btn-submit-modal" 
                        class="w-full bg-[#E50914] hover:bg-[#b90710] text-white text-[10px] font-black uppercase tracking-[0.3em] py-4 rounded-sm shadow-xl transition-all active:scale-95">
                        Validar
                    </button>
                </form>

                <!-- Error Box -->
                <div id="modal-error-box" class="hidden mt-6 space-y-6">
                    <div class="p-6">
                        <p id="modal-error" class="text-sm text-white-500 font-bold tracking-normal text-center leading-relaxed"></p>
                    </div>
                    <!-- Botón Soporte -->
                    <div id="contact-admin-container" class="hidden flex justify-center">
                        <a href="mailto:cine0first@gmail.com?subject=Soporte" class="inline-flex items-center gap-2 text-[#E50914] hover:text-white transition-colors">
                            <span class="text-[9px] font-black uppercase tracking-widest border-b border-[#E50914]/40 pb-1 text-center">Contactar al administrador</span>
                        </a>
                    </div>
                </div>

                <!-- Formulario Nueva Contraseña (Minimalista: Solo línea inferior blanca a roja) -->
                <form id="change-password-form" class="hidden space-y-8">
                    <div class="space-y-6">
                        <div class="flex flex-col">
                            <input type="password" id="new-password" 
                                class="bg-transparent border-0 border-b-2 border-zinc-200 text-white text-sm py-3 outline-none focus:border-b-[#E50914] transition-all" 
                                placeholder="Escriba su nueva contraseña">
                        </div>
                        <div class="flex flex-col">
                            <input type="password" id="confirm-new-password" 
                                class="bg-transparent border-0 border-b-2 border-zinc-200 text-white text-sm py-3 outline-none focus:border-b-[#E50914] transition-all" 
                                placeholder="Confirme su nueva contraseña">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-[#E50914] hover:bg-[#b90710] text-white text-[10px] font-black uppercase tracking-[0.3em] py-4 rounded-sm shadow-xl transition-all active:scale-95">
                        Restablecer Contraseña
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
