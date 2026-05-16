/**
 * Lógica del Panel Administrativo (Refactorizado)
 * Maneja la carga dinámica, paginación y acciones CRUD
 */

let currentTab = 'movies';
let currentPage = 1;

function openAdminDashboard() {
    const modal = document.getElementById('admin-dashboard-modal');
    const content = document.getElementById('admin-modal-content');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Animación de entrada
    setTimeout(() => {
        modal.classList.add('opacity-100');
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
    
    loadAdminData();
}

function closeAdminDashboard() {
    const modal = document.getElementById('admin-dashboard-modal');
    const content = document.getElementById('admin-modal-content');
    
    modal.classList.remove('opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

function switchAdminTab(tab) {
    if (currentTab === tab) return;
    
    currentTab = tab;
    currentPage = 1;
    
    const mTab = document.getElementById('tab-movies');
    const uTab = document.getElementById('tab-users');
    const pTab = document.getElementById('tab-protagonists');
    const insertBtnText = document.getElementById('insert-btn-text'); // Referencia al texto del botón
    
    const activeClass = "px-8 py-3 text-[10px] font-black uppercase tracking-widest transition-all bg-[#E50914] text-white";
    const inactiveClass = "px-8 py-3 text-[10px] font-black uppercase tracking-widest transition-all text-zinc-500 hover:text-white";
    
    // Reset all tabs
    mTab.className = inactiveClass;
    uTab.className = inactiveClass;
    if (pTab) pTab.className = inactiveClass;

    if(tab === 'movies') {
        mTab.className = activeClass;
        if (insertBtnText) insertBtnText.textContent = 'Insertar Película';
    } else if (tab === 'users') {
        uTab.className = activeClass;
        if (insertBtnText) insertBtnText.textContent = 'Insertar Usuario';
    } else if (tab === 'protagonists') {
        if (pTab) pTab.className = activeClass;
        if (insertBtnText) insertBtnText.textContent = 'Insertar Protagonista';
    }
    
    loadAdminData();
}

/**
 * Abre el modal de inserción y genera los campos dinámicamente según la pestaña activa
 */
async function openInsertModal() {
    document.getElementById('form-id').value = ''; // Limpiar ID (modo inserción)
    await generateFormFields();
    showModal();
}

/**
 * Abre el modal de edición cargando los datos del recurso
 */
async function openEditModal(id) {
    document.getElementById('form-id').value = id; // Guardar ID (modo edición)
    await generateFormFields(id);
    showModal();
}

function showModal() {
    const modal = document.getElementById('admin-insert-modal');
    const content = document.getElementById('admin-insert-content');
    
    modal.classList.remove('hidden', 'pointer-events-none');
    modal.classList.add('grid', 'pointer-events-auto');
    
    setTimeout(() => {
        modal.classList.add('opacity-100');
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

/**
 * Cierra el modal de inserción con animación de salida
 */
function closeInsertModal() {
    const modal = document.getElementById('admin-insert-modal');
    const content = document.getElementById('admin-insert-content');
    
    modal.classList.remove('opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    content.classList.remove('scale-100', 'opacity-100');
    
    setTimeout(() => {
        modal.classList.add('hidden', 'pointer-events-none');
        modal.classList.remove('grid', 'pointer-events-auto');
    }, 300);
}

/**
 * Genera dinámicamente los campos del formulario (Insert/Edit)
 */
async function generateFormFields(id = null) {
    const title = document.getElementById('insert-modal-title');
    const fieldsContainer = document.getElementById('insert-form-fields');
    const isEdit = id !== null;
    
    fieldsContainer.innerHTML = '<div class="py-10 text-center"><div class="inline-block animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-[#E50914]"></div></div>';
    
    let existingData = null;
    if (isEdit) {
        try {
            const resp = await fetch(`../src/Controller/AdminController.php?action=get&type=${currentTab}&id=${id}`);
            existingData = await resp.json();
        } catch(e) { console.error("Error cargando datos existentes", e); }
    }

    if (currentTab === 'movies') {
        title.textContent = isEdit ? 'Editar Película' : 'Nueva Película';
        
        let genresHtml = '';
        try {
            // Cargar Géneros
            const gResp = await fetch('../src/Controller/AdminController.php?action=genres');
            const gResult = await gResp.json();
            gResult.data.forEach(g => {
                const selected = existingData && existingData.genero_id == g.id_genero ? 'selected' : '';
                genresHtml += `<option value="${g.id_genero}" ${selected}>${g.nombre_genero}</option>`;
            });
        } catch(e) { console.error("Error cargando géneros", e); }

        fieldsContainer.innerHTML = `
            <div class="admin-field-group">
                <label class="admin-label">Título Original</label>
                <input type="text" name="titulo" value="${existingData?.titulo || ''}" required class="admin-input" placeholder="Nombre de la película">
            </div>
            <div class="admin-field-group">
                <label class="admin-label">Director</label>
                <input type="text" name="director" value="${existingData?.director || ''}" required class="admin-input" placeholder="Cineasta a cargo">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="admin-field-group">
                    <label class="admin-label">Edad Mínima</label>
                    <input type="number" name="clasificacion" value="${existingData?.clasificacion || '0'}" required class="admin-input">
                </div>
                <div class="admin-field-group">
                    <label class="admin-label">Categoría</label>
                    <div class="relative">
                        <select name="genero_id" required class="admin-select">${genresHtml}</select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-zinc-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="admin-field-group">
                <label class="admin-label">URL del Póster</label>
                <input type="url" name="url_image" value="${existingData?.url_image || ''}" class="admin-input" placeholder="https://...">
            </div>
        `;
    } else if (currentTab === 'users') {
        title.textContent = isEdit ? 'Editar Administrador' : 'Nuevo Administrador';
        
        const permisosOptions = `
            <option value="0" ${existingData?.permisos == 0 ? 'selected' : ''}>Usuario Estándar</option>
            <option value="1" ${existingData?.permisos == 1 ? 'selected' : ''}>Administrador</option>
        `;

        const estadoOptions = `
            <option value="1" ${existingData?.estado_id == 1 ? 'selected' : ''}>Activo</option>
            <option value="2" ${existingData?.estado_id == 2 ? 'selected' : ''}>Inactivo</option>
        `;

        fieldsContainer.innerHTML = `
            <div class="admin-field-group">
                <label class="admin-label">Nombre Completo</label>
                <input type="text" name="nombre" value="${existingData?.nombre || ''}" required class="admin-input" placeholder="Nombre completo del usuario">
            </div>
            <div class="admin-field-group">
                <label class="admin-label">Correo Electrónico</label>
                <input type="email" name="email" value="${existingData?.correo || ''}" required class="admin-input" placeholder="usuario@cinefirst.com">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="admin-field-group">
                    <label class="admin-label">Nivel de Permisos</label>
                    <div class="relative">
                        <select name="permisos" required class="admin-select">${permisosOptions}</select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-zinc-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                    </div>
                </div>
                <div class="admin-field-group">
                    <label class="admin-label">Estado de Cuenta</label>
                    <div class="relative">
                        <select name="estado_id" required class="admin-select">${estadoOptions}</select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-zinc-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else if (currentTab === 'protagonists') {
        title.textContent = isEdit ? 'Editar Protagonista' : 'Nuevo Protagonista';
        fieldsContainer.innerHTML = `
            <div class="admin-field-group">
                <label class="admin-label">Nombre del Actor o Actriz</label>
                <input type="text" name="nombre" value="${existingData?.nombre || ''}" required class="admin-input" placeholder="Ej: Joaquin Phoenix">
            </div>
        `;
    }
}

// Manejo del envío del formulario (Insert/Update)
document.getElementById('admin-insert-form').onsubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const id = formData.get('id');
    const action = id ? 'update' : 'insert';
    
    try {
        const response = await fetch(`../src/Controller/AdminController.php?action=${action}&type=${currentTab}`, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            closeInsertModal();
            loadAdminData();
        } else {
            alert(result.error || 'Error al procesar la solicitud');
        }
    } catch (error) {
        console.error("Error en la operación:", error);
    }
};

async function loadAdminData() {
    const tableBody = document.getElementById('admin-table-body');
    const loading = document.getElementById('admin-loading');
    
    tableBody.classList.add('opacity-30');
    loading.classList.remove('hidden');
    
    try {
        const response = await fetch(`../src/Controller/AdminController.php?action=list&type=${currentTab}&page=${currentPage}`);
        const result = await response.json();
        
        if (result.error) {
            console.error(result.error);
            return;
        }
        
        renderTable(result.data);
        updatePagination(result);
    } catch (error) {
        console.error("Error cargando datos:", error);
    } finally {
        tableBody.classList.remove('opacity-30');
        loading.classList.add('hidden');
    }
}

function renderTable(data) {
    const head = document.getElementById('admin-table-head');
    const body = document.getElementById('admin-table-body');
    
    head.innerHTML = '';
    body.innerHTML = '';

    if (!data || data.length === 0) {
        body.innerHTML = `<tr><td colspan="10" class="py-20 text-center text-zinc-600 tracking-widest">No se encontraron registros</td></tr>`;
        return;
    }

    // Definición de headers según el tipo de datos
    const keys = Object.keys(data[0]);
    keys.forEach(key => {
        const label = key.replace('ID pelicula', 'ID').replace('ID actor', 'ID').replace('id', 'ID').toUpperCase();
        head.innerHTML += `<th class="px-6 py-5 font-black text-[10px] tracking-widest">${label}</th>`;
    });
    head.innerHTML += `<th class="px-6 py-5 font-black text-center text-[10px] tracking-widest">Acciones</th>`;

    // Renderizado de filas
    data.forEach(item => {
        let row = `<tr class="hover:bg-zinc-900/40 transition-all group border-b border-zinc-900/30">`;
        keys.forEach(key => {
            let val = item[key];
            // Estilo especial para IDs o Clasificaciones
            const cellClass = (key.includes('ID')) ? 'text-zinc-600 font-mono text-[9px]' : 'text-zinc-300';
            row += `<td class="px-6 py-4 ${cellClass}">${val}</td>`;
        });
        
        const id = item['ID pelicula'] || item['ID actor'] || item.id_usuario || item.id_pelicula || item.id;
        
        row += `
            <td class="px-6 py-4">
                <div class="flex items-center justify-center gap-4">
                    <button onclick="openEditModal('${id}')" class="flex items-center gap-2 text-zinc-500 hover:text-blue-500 transition-colors uppercase text-[9px] font-black tracking-widest">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2.5"/></svg>
                        Editar
                    </button>
                    <button onclick="deleteItem('${id}')" class="flex items-center gap-2 text-zinc-500 hover:text-[#E50914] transition-colors uppercase text-[9px] font-black tracking-widest">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2.5"/></svg>
                        Eliminar
                    </button>
                </div>
            </td>
        </tr>`;
        body.innerHTML += row;
    });
}

function updatePagination(result) {
    const info = document.getElementById('pagination-info');
    const prevBtn = document.getElementById('prev-page');
    const nextBtn = document.getElementById('next-page');
    
    info.innerHTML = `<span class="text-[#E50914]">${result.currentPage}</span> de <span class="text-white">${result.pages}</span>`;
    
    prevBtn.disabled = result.currentPage <= 1;
    nextBtn.disabled = result.currentPage >= result.pages;
}

document.getElementById('next-page').onclick = () => { 
    currentPage++; 
    loadAdminData(); 
};

document.getElementById('prev-page').onclick = () => { 
    if (currentPage > 1) {
        currentPage--; 
        loadAdminData(); 
    }
};

async function deleteItem(id) {
    if(!confirm('¿Estás seguro de eliminar este registro permanentemente?')) return;
    
    const formData = new FormData();
    formData.append('id', id);
    
    try {
        const response = await fetch(`../src/Controller/AdminController.php?action=delete&type=${currentTab}`, {
            method: 'POST',
            body: formData
        });
        
        const res = await response.json();
        if(res.success) {
            loadAdminData();
        } else {
            alert(res.error || "No se pudo eliminar el registro.");
        }
    } catch (error) {
        console.error("Error al eliminar:", error);
    }
}
