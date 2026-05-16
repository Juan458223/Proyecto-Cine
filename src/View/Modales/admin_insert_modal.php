<!-- Modal Administrativo: Rediseño Premium Dark (Versión Simplificada) -->
<div 
  id="admin-insert-modal"
  class="pointer-events-none fixed inset-0 z-[110] grid h-screen w-screen place-items-center bg-black/70 opacity-0 backdrop-blur-sm transition-opacity duration-300 hidden"
>
  <div
    id="admin-insert-content"
    class="pointer-events-auto relative mx-auto w-full max-w-[28rem] rounded-xl overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300"
  >
    <div class="relative flex flex-col bg-zinc-950 border border-zinc-800/50 shadow-2xl">
      
      <!-- Header Elevado -->
      <div class="relative m-3 items-center flex justify-between px-8 text-white h-24 rounded-lg bg-zinc-900 border border-zinc-800 shadow-md">
        <div class="flex flex-col">
            <h3 id="insert-modal-title" class="text-3xl font-black font-bebas tracking-widest uppercase italic leading-none">
              Nuevo Registro
            </h3>
            <span class="text-[9px] font-bold text-zinc-500 uppercase tracking-[0.4em] mt-2 block">Cine First Management</span>
        </div>
        <button onclick="closeInsertModal()" class="group text-zinc-600 hover:text-[#E50914] transition-all p-2 hover:bg-black/20 rounded-full">
            <svg class="w-5 h-5 transform group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
      </div>

      <!-- Formulario -->
      <form id="admin-insert-form" class="flex flex-col">
        <input type="hidden" name="id" id="form-id">
        
        <div id="insert-form-fields" class="flex flex-col gap-6 p-8 overflow-hidden">
            <!-- Los campos se inyectan mediante JS -->
        </div>

        <!-- Acciones Finales -->
        <div class="p-8 pt-0 flex flex-col gap-3">
          <button 
            type="submit"
            id="form-submit-btn"
            class="w-full rounded-md bg-[#E50914] py-3.5 px-4 border border-transparent text-center text-[11px] font-black text-white transition-all shadow-lg hover:shadow-red-900/20 hover:bg-[#b90710] active:scale-[0.98] uppercase tracking-[0.2em]"
          >
            Confirmar y Guardar
          </button>
          <button 
            type="button" 
            onclick="closeInsertModal()"
            class="w-full rounded-md bg-transparent py-3 px-4 text-center text-[10px] font-bold text-zinc-600 transition-all hover:text-zinc-400 uppercase tracking-widest"
          >
            Regresar al panel
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
    /* Estilos globales para inputs inyectados por JS */
    .admin-field-group { @apply flex flex-col w-full; }
    
    .admin-label { 
        @apply block mb-2 text-[10px] font-black text-zinc-500 uppercase tracking-widest ml-1; 
    }
    
    .admin-input { 
        @apply w-full bg-zinc-900/50 placeholder:text-zinc-700 text-white text-sm border border-zinc-800 rounded-lg px-4 py-3.5 transition duration-300 ease-in-out focus:outline-none focus:border-[#E50914] focus:bg-zinc-900 hover:border-zinc-700 shadow-sm; 
    }

    .admin-select {
        @apply w-full bg-zinc-900/50 text-white text-sm border border-zinc-800 rounded-lg px-4 py-3.5 transition duration-300 ease-in-out focus:outline-none focus:border-[#E50914] hover:border-zinc-700 appearance-none cursor-pointer;
    }

    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #27272a; border-radius: 10px; }
</style>
