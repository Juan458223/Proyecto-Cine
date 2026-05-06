<!-- Main Modal Container (Flowbite-inspired) -->
<div id="auth-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-full bg-black/70 flex">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm border-t-4 border-red-800">
            <div class="flex items-center justify-between p-4 border-b rounded-t border-gray-200">
                <h3 id="modal-title" class="text-xl font-semibold text-black">Verificación</h3>
                <button type="button" id="close-auth-modal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
            <div class="p-4 md:p-5">
                <p id="modal-message" class="text-sm text-gray-600 mb-4">Procesando...</p>
                <div id="modal-loading" class="flex flex-col items-center py-4">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-red-800"></div>
                    <p class="mt-2 text-sm text-red-800 font-medium">Enviando código...</p>
                </div>
                <form id="modal-form" class="hidden space-y-4">
                    <div>
                        <label for="verification-code" class="block mb-2 text-sm font-medium text-black uppercase tracking-wider text-center">Código de 6 dígitos</label>
                        <input type="text" name="code" id="verification-code" class="bg-gray-50 border border-gray-300 text-black text-2xl rounded-lg focus:ring-red-800 focus:border-red-800 block w-full p-2.5 text-center font-bold tracking-[0.5em]" placeholder="000000" maxlength="6">
                    </div>
                    <button type="submit" id="btn-submit-modal" class="w-full text-white bg-red-800 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Verificar</button>
                </form>

                <p id="modal-error" class="hidden text-xs text-red-600 font-medium italic text-center mt-4">Error detectado.</p>

                <!-- Formulario Nueva Contraseña -->
                <form id="change-password-form" class="hidden space-y-4">
                    <div>
                        <label for="new-password" class="block mb-2 text-sm font-medium text-black tracking-wider">Nueva Contraseña</label>
                        <input type="password" id="new-password" class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-red-800 focus:border-red-800 block w-full p-2.5" placeholder="••••••••">
                    </div>
                    <div>
                        <label for="confirm-new-password" class="block mb-2 text-sm font-medium text-black tracking-wider">Confirmar Contraseña</label>
                        <input type="password" id="confirm-new-password" class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-red-800 focus:border-red-800 block w-full p-2.5" placeholder="••••••••">
                    </div>
                    <button type="submit" class="w-full text-white bg-red-800 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Actualizar Contraseña</button>
                </form>
            </div>
        </div>
    </div>
</div>