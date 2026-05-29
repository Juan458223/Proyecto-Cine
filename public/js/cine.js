/**
 * Cine First - Lógica de Cines (Versión Premium)
 * Gestión de Modal de Detalles: Tarifas y Funciones
 */

const cineState = {
    selectedCineId: null,
    // Paginación Tarifas
    tarifaPage: 1,
    tarifaLimit: 5,
    // Paginación Funciones
    funcionPage: 1,
    funcionLimit: 5,
    currentSalaFilter: ""
};

/**
 * Utilería: Formateo de Moneda
 */
const formatPrice = (value) => {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0
    }).format(value);
};

/**
 * Utilería: Formateo de Fecha/Hora para tablas
 */
const formatDateTime = (dateStr) => {
    const date = new Date(dateStr.replace(' ', 'T'));
    const day = date.toLocaleDateString([], { day: '2-digit', month: 'short' });
    const time = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    return `${day} - ${time}`;
};

/**
 * Abre el modal y dispara la carga de datos
 */
window.openCineDetail = function(cineData) {
    if (typeof cineData === 'string') {
        try { cineData = JSON.parse(cineData); } catch (e) { return; }
    }

    cineState.selectedCineId = cineData.id;
    cineState.tarifaPage = 1;
    cineState.funcionPage = 1;
    cineState.currentSalaFilter = "";

    const modal = document.getElementById('cine-detail-modal');
    const content = document.getElementById('cine-detail-content');
    if (!modal || !content) return;

    // Poblar Header
    document.getElementById('cine-detail-name').textContent = window.capitalize(cineData.Nombre);
    document.getElementById('cine-detail-address').textContent = `${window.capitalize(cineData.Calle || '')} ${cineData.Numero || ''}`;
    document.getElementById('cine-detail-phone').textContent = cineData.Telefono || 'No disponible';

    // Resetear Filtro de Salas
    const filterSala = document.getElementById('cine-filter-sala');
    if (filterSala) filterSala.value = "";

    // Cargar Datos de las Tablas (Ahora globales)
    window.loadCineTarifas();
    window.loadCineFunciones();
    window.loadSalasForFilter();

    // Animación de apertura
    modal.classList.remove('hidden', 'pointer-events-none');
    modal.classList.add('flex');
    
    requestAnimationFrame(() => {
        modal.classList.add('opacity-100');
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    });

    document.body.style.overflow = 'hidden';
};

/**
 * Carga las Tarifas del Cine
 */
window.loadCineTarifas = async function(page = 1) {
    cineState.tarifaPage = page;
    const tableBody = document.getElementById('cine-tarifa-table-body');
    const pagContainer = document.getElementById('cine-tarifa-pagination');
    if (!tableBody) return;

    tableBody.innerHTML = '<tr><td colspan="3" class="px-6 py-8 text-center animate-pulse text-zinc-500">Cargando tarifas...</td></tr>';

    try {
        const response = await fetch(`../Controller/TarifaController.php?action=list_by_cine&cine_id=${cineState.selectedCineId}&page=${page}`);
        const result = await response.json();

        if (result.data && result.data.length > 0) {
            tableBody.innerHTML = result.data.map(t => `
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 font-bold text-white">${window.capitalize(t.categoria)}</td>
                    <td class="px-6 py-4 text-zinc-400">${window.capitalize(t.tipo_dia)}</td>
                    <td class="px-6 py-4 text-right font-black text-white">${formatPrice(t.precio)}</td>
                </tr>
            `).join('');
            renderInternalPagination(pagContainer, result.pages, page, 'window.loadCineTarifas');
        } else {
            tableBody.innerHTML = '<tr><td colspan="3" class="px-6 py-8 text-center text-zinc-600 italic">No hay tarifas configuradas</td></tr>';
            if (pagContainer) pagContainer.innerHTML = '';
        }
    } catch (e) {
        tableBody.innerHTML = '<tr><td colspan="3" class="px-6 py-8 text-center text-red-500/50">Error al cargar datos</td></tr>';
    }
};

/**
 * Carga las Funciones (Cartelera) del Cine
 */
window.loadCineFunciones = async function(page = 1) {
    cineState.funcionPage = page;
    const tableBody = document.getElementById('cine-funcion-table-body');
    const pagContainer = document.getElementById('cine-functions-pagination');
    if (!tableBody) return;

    tableBody.innerHTML = '<tr><td colspan="3" class="px-6 py-8 text-center animate-pulse text-zinc-500">Cargando cartelera...</td></tr>';

    try {
        let url = `../Controller/FuncionController.php?action=list_by_cine&cine_id=${cineState.selectedCineId}&page=${page}`;
        if (cineState.currentSalaFilter) url += `&sala_id=${cineState.currentSalaFilter}`;

        const response = await fetch(url);
        const result = await response.json();

        if (result.data && result.data.length > 0) {
            tableBody.innerHTML = result.data.map(f => `
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-bold text-white">${window.capitalize(f.pelicula_titulo)}</span>
                            <span class="text-[9px] text-zinc-500 uppercase tracking-tighter">${f.pelicula_clasificacion}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-zinc-400 font-medium">${formatDateTime(f.fecha_hora)}</td>
                    <td class="px-6 py-4 text-right">
                        <span class="bg-zinc-900 border border-white/5 px-2 py-1 rounded text-white font-bold">Sala ${f.numero_sala}</span>
                    </td>
                </tr>
            `).join('');
            renderInternalPagination(pagContainer, result.pages, page, 'window.loadCineFunciones');
        } else {
            tableBody.innerHTML = '<tr><td colspan="3" class="px-6 py-8 text-center text-zinc-600 italic">Sin funciones programadas</td></tr>';
            if (pagContainer) pagContainer.innerHTML = '';
        }
    } catch (e) {
        tableBody.innerHTML = '<tr><td colspan="3" class="px-6 py-8 text-center text-red-500/50">Error al cargar funciones</td></tr>';
    }
};

/**
 * Carga las salas para el select de filtro
 */
window.loadSalasForFilter = async function() {
    const select = document.getElementById('cine-filter-sala');
    if (!select) return;

    // Aplicar estilo premium al select
    select.className = "bg-zinc-900 border border-zinc-800 text-white text-[10px] font-bold px-4 py-2 rounded-xl focus:border-[#E50914] outline-none transition-all cursor-pointer appearance-none min-w-[140px]";

    try {
        const response = await fetch(`../Controller/SalaController.php?action=list_by_cine&cine_id=${cineState.selectedCineId}`);
        const text = await response.text();
        try {
            const result = JSON.parse(text);
            select.innerHTML = '<option value="">Todas las salas</option>';
            if (result.success && result.data) {
                result.data.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.id_sala;
                    opt.textContent = `Sala ${s.numero_sala}`;
                    select.appendChild(opt);
                });
            }
        } catch (parseError) {
            console.error("Error parseando respuesta del servidor. Respuesta recibida:", text);
        }
    } catch (e) { console.error("Error al cargar salas para filtro", e); }
};

/**
 * Renderiza paginación pequeña para las tablas del modal
 */
function renderInternalPagination(container, totalPages, currentPage, callbackName) {
    if (!container || totalPages <= 1) {
        if (container) container.innerHTML = '';
        return;
    }

    let html = `
        <button onclick="${callbackName}(${currentPage - 1})" 
                class="w-7 h-7 rounded-lg flex items-center justify-center bg-zinc-900 border border-white/5 text-zinc-500 hover:text-white transition-all ${currentPage <= 1 ? 'opacity-20 pointer-events-none' : ''}">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    `;

    for (let i = 1; i <= totalPages; i++) {
        html += `
            <button onclick="${callbackName}(${i})" 
                    class="w-7 h-7 rounded-lg text-[9px] font-black transition-all ${i === currentPage ? 'bg-[#E50914] text-white shadow-lg' : 'bg-zinc-900 text-zinc-500 hover:text-white border border-white/5'}">
                ${i}
            </button>
        `;
    }

    html += `
        <button onclick="${callbackName}(${currentPage + 1})" 
                class="w-7 h-7 rounded-lg flex items-center justify-center bg-zinc-900 border border-white/5 text-zinc-500 hover:text-white transition-all ${currentPage >= totalPages ? 'opacity-20 pointer-events-none' : ''}">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    `;

    container.innerHTML = html;
}

/**
 * Cierra el modal de detalles
 */
window.closeCineDetail = function() {
    const modal = document.getElementById('cine-detail-modal');
    const content = document.getElementById('cine-detail-content');
    if (!modal) return;

    modal.classList.remove('opacity-100');
    if (content) content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden', 'pointer-events-none');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }, 500);
};

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    // Filtro de Sala
    const filterSala = document.getElementById('cine-filter-sala');
    if (filterSala) {
        filterSala.addEventListener('change', (e) => {
            cineState.currentSalaFilter = e.target.value;
            window.loadCineFunciones(1);
        });
    }

    // Cerrar modal al hacer clic fuera
    const modal = document.getElementById('cine-detail-modal');
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) window.closeCineDetail();
        });
    }
});
