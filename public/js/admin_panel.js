/**
 * Lógica del Panel Administrativo Premium (v2.0 Overhauled)
 * Maneja navegación lateral, tablas dinámicas y modales CRUD de alta fidelidad.
 */

let currentTab = 'movies';
let currentPage = 1;
let itemToDelete = null;

const TAB_LABELS = {
    'movies': 'Películas',
    'users': 'Usuarios',
    'cines': 'Cines',
    'salas': 'Salas',
    'funciones': 'Funciones',
    'generos': 'Géneros',
    'tarifas': 'Tarifas',
    'protagonists': 'Protagonistas',
    'tokens': 'Sistema de Tokens',
    'reports': 'Informes'
};

// --- Dashboard & Navigation ---

function openAdminDashboard() {
    const modal = document.getElementById('admin-dashboard-modal');
    const content = document.getElementById('admin-modal-content');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    requestAnimationFrame(() => {
        modal.classList.add('opacity-100');
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    });
    
    loadAdminData();
}

function closeAdminDashboard() {
    const modal = document.getElementById('admin-dashboard-modal');
    const content = document.getElementById('admin-modal-content');
    modal.classList.remove('opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 500);
}

// Reutilizamos el modal de alertas para notificaciones administrativas
function showAdminAlert(title, message, isError = false) {
    const modal = document.getElementById('admin-alert-modal');
    const content = document.getElementById('admin-alert-content');
    const messageEl = document.getElementById('admin-alert-message');

    if (!modal) return;

    if (messageEl) {
        messageEl.textContent = message;
    }

    modal.classList.remove('hidden', 'pointer-events-none');
    modal.classList.add('flex');
    
    setTimeout(() => {
        modal.classList.add('opacity-100');
        if (content) {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }
    }, 10);
}

function switchAdminTab(tab) {
    if (currentTab === tab) return;
    currentTab = tab;
    currentPage = 1;

    // UI Feedback
    document.querySelectorAll('.sidebar-btn').forEach(btn => btn.classList.remove('active', 'border-l-2', 'border-[#E50914]', 'bg-zinc-900/50'));
    const activeBtn = document.getElementById(`tab-${tab}`);
    if (activeBtn) activeBtn.classList.add('active', 'border-l-2', 'border-[#E50914]', 'bg-zinc-900/50');

    const titleEl = document.getElementById('current-tab-title');
    if (titleEl) {
        titleEl.textContent = TAB_LABELS[tab].toUpperCase();
    }
    
    loadAdminData();
}

// --- Data Loading & Rendering ---

async function loadAdminData(containerId = 'admin-pagination', type = currentTab) {
    const tableBody = document.getElementById('token-table-body');
    const loading = document.getElementById('admin-loading');
    
    if (tableBody && type === currentTab) tableBody.style.opacity = '0.3';
    if (loading) loading.classList.remove('hidden');

    let url = `../Controller/AdminController.php?action=list&type=${type}&page=${currentPage}`;
    if (type === 'movies') {
        const filterGenre = document.getElementById('filter-genre');
        if (filterGenre && filterGenre.value) {
            url += `&genero_id=${filterGenre.value}`;
        }
    }

    try {
        const response = await fetch(url);
        const result = await response.json();
        
        if (type === currentTab) {
            renderTable(result.data);
        }
        updatePagination(result, containerId);
    } catch (e) {
        console.error("Error cargando datos:", e);
    } finally {
        if (tableBody && type === currentTab) tableBody.style.opacity = '1';
        if (loading) loading.classList.add('hidden');
    }
}

function renderTable(data) {
    const head = document.querySelector('#admin-table-head');
    const body = document.getElementById('token-table-body');
    if (!head || !body) return;

    head.innerHTML = '';
    body.innerHTML = '';

    if (!data || data.length === 0) {
        body.innerHTML = `<tr><td colspan="10" class="py-32 text-center text-zinc-700 tracking-[0.3em] font-black uppercase text-[10px]">Sin registros disponibles</td></tr>`;
        return;
    }

    // Definir columnas esenciales según la pestaña
    const essentialKeys = {
        'movies': ['titulo', 'director', 'clasificacion', 'genero'],
        'users': ['nombre', 'correo', 'permisos'],
        'cines': ['Nombre', 'Calle', 'Teléfono'],
        'salas': ['Capacidad', 'Cine'],
        'funciones': ['Fecha', 'Pelicula', 'Sala'],
        'generos': ['Nombre'],
        'tarifas': ['Cine', 'Dia', 'Precio'],
        'protagonists': ['nombre']
    };

    const keys = essentialKeys[currentTab] || Object.keys(data[0]).filter(k => k !== 'id');
    
    keys.forEach(key => {
        head.innerHTML += `<th class="px-8 py-6 font-black text-[10px] tracking-widest text-zinc-500 uppercase">${key.replace(/_/g, ' ')}</th>`;
    });
    head.innerHTML += `<th class="px-8 py-6 font-black text-center text-[10px] tracking-widest text-zinc-500 uppercase">Acciones</th>`;

    data.forEach((item, index) => {
        let row = `<tr class="hover:bg-white/5 transition-all group border-b border-white/5 animate-in fade-in slide-in-from-bottom-2" style="animation-delay: ${index * 30}ms">`;
        keys.forEach(key => {
            let val = item[key] ?? '-';
            row += `<td class="px-8 py-5 text-zinc-300 transition-colors font-outfit text-[11px]">${val}</td>`;
        });
        
        row += `
            <td class="px-8 py-5 text-center">
                <div class="flex items-center justify-center gap-4">
                    <button onclick="openEditModal('${item.id}')" class="text-zinc-500 hover:text-white transition-colors text-[9px] font-black uppercase tracking-widest">EDITAR</button>
                    <button onclick="openDeleteConfirm('${item.id}')" class="text-zinc-500 hover:text-[#E50914] transition-colors text-[9px] font-black uppercase tracking-widest">BORRAR</button>
                </div>
            </td>
        </tr>`;
        body.innerHTML += row;
    });
}

function updatePagination(result, containerId = 'admin-pagination') {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    const currentPage = result.currentPage;
    const totalPages = result.pages;
    
    let html = `
        <button onclick="changePage(${currentPage - 1}, '${containerId}')" class="p-3 rounded-full bg-zinc-900 border border-zinc-800 text-white hover:bg-[#E50914] transition-all ${currentPage <= 1 ? 'opacity-30 pointer-events-none' : ''}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
    `;

    for (let i = 1; i <= totalPages; i++) {
        const activeClass = (i === currentPage) ? 'bg-[#E50914] text-white border-[#E50914]' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white';
        html += `<button onclick="changePage(${i}, '${containerId}')" class="w-10 h-10 rounded-xl border font-bold text-xs transition-all ${activeClass}">${i}</button>`;
    }

    html += `
        <button onclick="changePage(${currentPage + 1}, '${containerId}')" class="p-3 rounded-full bg-zinc-900 border border-zinc-800 text-white hover:bg-[#E50914] transition-all ${currentPage >= totalPages ? 'opacity-30 pointer-events-none' : ''}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
    `;

    container.innerHTML = html;
}

window.changePage = function(page, containerId = 'admin-pagination') {
    currentPage = page;
    loadAdminData();
};

// --- CRUD Modals (Insert/Edit/Delete) ---

window.openCreateTokenModal = function() {
    const modal = document.getElementById('admin-insert-modal');
    const content = document.getElementById('admin-insert-content');
    const fieldsContainer = document.getElementById('insert-form-fields');
    const submitBtn = document.getElementById('form-submit-btn');
    const idInput = document.getElementById('form-id');

    if (!modal || !fieldsContainer) return;

    idInput.value = ''; // Modo creación
    submitBtn.textContent = 'Crear Token';
    
    // Inyectar campos específicos para token si estamos en la pestaña de tokens
    fieldsContainer.innerHTML = `
        <div class="crud-input-group">
            <input type="text" name="usuario_email" class="crud-input" placeholder=" " required>
            <label class="crud-label">CORREO DEL USUARIO</label>
        </div>
        <div class="crud-input-group">
            <select name="tipo_token" class="crud-select">
                <option value="1">REGISTRO</option>
                <option value="2">RECUPERACIÓN</option>
            </select>
            <label class="crud-label">TIPO DE TOKEN</label>
        </div>
    `;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    requestAnimationFrame(() => {
        modal.classList.add('opacity-100');
        modal.classList.remove('pointer-events-none');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    });
};

window.openValidateTokenModal = function() {
    // Reutilizamos el modal de autenticación para validar tokens
    if (typeof window.openModal === 'function') {
        window.openModal("Validar", "Ingrese el código para validar", "", "validate_user");
        window.stopLoading();
    }
};

window.openEditModal = async function(id) {
    const modal = document.getElementById('admin-insert-modal');
    const content = document.getElementById('admin-insert-content');
    const fieldsContainer = document.getElementById('insert-form-fields');
    const submitBtn = document.getElementById('form-submit-btn');
    const idInput = document.getElementById('form-id');

    if (!modal || !fieldsContainer) return;

    idInput.value = id;
    submitBtn.textContent = 'Guardar Cambios';
    fieldsContainer.innerHTML = '<div class="py-10 text-center"><div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-[#E50914]"></div></div>';

    // Abrir modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    requestAnimationFrame(() => {
        modal.classList.add('opacity-100');
        modal.classList.remove('pointer-events-none');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    });

    try {
        const response = await fetch(`../Controller/AdminController.php?action=get&type=${currentTab}&id=${id}`);
        const data = await response.json();
        renderFormFields(data);
    } catch (e) {
        console.error("Error cargando registro:", e);
    }
};

window.openDeleteConfirm = function(id) {
    itemToDelete = id;
    const modal = document.getElementById('admin-delete-modal');
    const content = document.getElementById('admin-delete-content');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    requestAnimationFrame(() => {
        modal.classList.add('opacity-100');
        modal.classList.remove('pointer-events-none');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    });
};

window.closeInsertModal = function() {
    const modal = document.getElementById('admin-insert-modal');
    const content = document.getElementById('admin-insert-content');
    
    modal.classList.remove('opacity-100');
    content.classList.add('scale-95');
    content.classList.remove('scale-100');
    
    setTimeout(() => {
        modal.classList.add('hidden', 'pointer-events-none');
        modal.classList.remove('flex');
    }, 500);
};

window.closeDeleteModal = function() {
    const modal = document.getElementById('admin-delete-modal');
    const content = document.getElementById('admin-delete-content');
    
    modal.classList.remove('opacity-100');
    content.classList.add('scale-95');
    content.classList.remove('scale-100');
    
    setTimeout(() => {
        modal.classList.add('hidden', 'pointer-events-none');
        modal.classList.remove('flex');
    }, 500);
};

function renderFormFields(data) {
    const container = document.getElementById('insert-form-fields');
    container.innerHTML = '';
    
    Object.keys(data).forEach(key => {
        if (key === 'id' || key === 'id_usuario' || key === 'id_pelicula' || key === 'id_cine' || key === 'id_sala' || key === 'id_dia' || key === 'id_genero' || key === 'id_actor') return;
        
        const value = data[key] || '';
        const group = document.createElement('div');
        group.className = 'crud-input-group';
        
        group.innerHTML = `
            <input type="text" name="${key}" value="${value}" class="crud-input" placeholder=" " required>
            <label class="crud-label">${key.replace(/_/g, ' ').toUpperCase()}</label>
        `;
        container.appendChild(group);
    });
}

// Confirmar Eliminación
const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
if (confirmDeleteBtn) {
    confirmDeleteBtn.onclick = async () => {
        try {
            const formData = new FormData();
            formData.append('id', itemToDelete);
            const response = await fetch(`../Controller/AdminController.php?action=delete&type=${currentTab}`, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                closeDeleteModal();
                loadAdminData();
            }
        } catch (e) {
            console.error("Error eliminando:", e);
        }
    };
}

// Formulario de Inserción/Edición
const insertForm = document.getElementById('admin-insert-form');
if (insertForm) {
    insertForm.onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(insertForm);
        const action = formData.get('id') ? 'update' : 'insert';
        
        try {
            const response = await fetch(`../Controller/AdminController.php?action=${action}&type=${currentTab}`, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                closeInsertModal();
                loadAdminData();
            }
        } catch (e) {
            console.error("Error guardando:", e);
        }
    };
}

// Paginación Listeners (Uso de selectores de clase para contexto local)
document.addEventListener('click', (e) => {
    if (e.target.closest('.admin-next-page')) {
        currentPage++;
        loadAdminData();
    }
    if (e.target.closest('.admin-prev-page')) {
        if (currentPage > 1) {
            currentPage--;
            loadAdminData();
        }
    }
});
