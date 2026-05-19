/**
 * Lógica del Panel Administrativo (Refactorizado Completo)
 * Maneja la carga dinámica, paginación y acciones CRUD para todas las tablas
 */

let currentTab = 'movies';
let currentPage = 1;

function openAdminDashboard() {
    const modal = document.getElementById('admin-dashboard-modal');
    const content = document.getElementById('admin-modal-content');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
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
    
    // Update tab UI
    document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
    document.getElementById(`tab-${tab}`).classList.add('active');
    
    // Update insert button text
    const insertBtnText = document.getElementById('insert-btn-text');
    const labels = {
        'movies': 'película', 'users': 'usuario', 'cines': 'cine', 
        'salas': 'sala', 'funciones': 'función', 'generos': 'género', 
        'tarifas': 'tarifa', 'estados': 'estado', 'protagonists': 'protagonista'
    };
    if (insertBtnText) insertBtnText.textContent = `Insertar ${labels[tab]}`;
    
    loadAdminData();
}

async function openInsertModal() {
    document.getElementById('form-id').value = '';
    await generateFormFields();
    showModal();
}

async function openEditModal(id) {
    document.getElementById('form-id').value = id;
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

async function generateFormFields(id = null) {
    const title = document.getElementById('insert-modal-title');
    const fieldsContainer = document.getElementById('insert-form-fields');
    const isEdit = id !== null;
    
    fieldsContainer.innerHTML = '<div class="py-10 text-center"><div class="inline-block animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-[#E50914]"></div></div>';
    
    let existingData = null;
    if (isEdit) {
        try {
            const resp = await fetch(`../Controller/AdminController.php?action=get&type=${currentTab}&id=${id}`);
            existingData = await resp.json();
        } catch(e) { console.error("Error loading data", e); }
    }

    const labels = {
        'movies': 'PELÍCULA', 'users': 'USUARIO', 'cines': 'CINE', 
        'salas': 'SALA', 'funciones': 'FUNCIÓN', 'generos': 'GÉNERO', 
        'tarifas': 'TARIFA', 'protagonists': 'PROTAGONISTA'
    };
    title.textContent = `${isEdit ? 'EDITAR' : 'NUEVO'} ${labels[currentTab]}`;

    let html = '';
    
    if (currentTab === 'movies') {
        let genresHtml = '';
        const gResp = await fetch('../Controller/AdminController.php?action=genres');
        const gResult = await gResp.json();
        gResult.data.forEach(g => {
            const selected = existingData && existingData.genero_id == g.id_genero ? 'selected' : '';
            genresHtml += `<option value="${g.id_genero}" ${selected}>${g.nombre_genero}</option>`;
        });

        html = `
            <div class="admin-field-group">
                <label class="admin-label">Título</label>
                <input type="text" name="titulo" value="${existingData?.titulo || ''}" required class="admin-input">
            </div>
            <div class="admin-field-group">
                <label class="admin-label">Director</label>
                <input type="text" name="director" value="${existingData?.director || ''}" required class="admin-input">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="admin-field-group">
                    <label class="admin-label">Clasificación</label>
                    <input type="number" name="clasificacion" value="${existingData?.clasificacion || '0'}" required class="admin-input">
                </div>
                <div class="admin-field-group">
                    <label class="admin-label">Género</label>
                    <select name="genero_id" required class="admin-select">${genresHtml}</select>
                </div>
            </div>
            <div class="admin-field-group">
                <label class="admin-label">URL Póster</label>
                <input type="url" name="url_image" value="${existingData?.url_image || ''}" class="admin-input">
            </div>
        `;
    } else if (currentTab === 'users') {
        html = `
            <div class="admin-field-group">
                <label class="admin-label">Nombre</label>
                <input type="text" name="nombre" value="${existingData?.nombre || ''}" required class="admin-input">
            </div>
            <div class="admin-field-group">
                <label class="admin-label">Email</label>
                <input type="email" name="email" value="${existingData?.correo || ''}" required class="admin-input">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="admin-field-group">
                    <label class="admin-label">Permisos</label>
                    <select name="permisos" class="admin-select">
                        <option value="0" ${existingData?.permisos == 0 ? 'selected' : ''}>Usuario</option>
                        <option value="1" ${existingData?.permisos == 1 ? 'selected' : ''}>Admin</option>
                    </select>
                </div>
                <div class="admin-field-group">
                    <label class="admin-label">Estado</label>
                    <select name="estado_id" class="admin-select">
                        <option value="1" ${existingData?.estado_id == 1 ? 'selected' : ''}>Activo</option>
                        <option value="2" ${existingData?.estado_id == 2 ? 'selected' : ''}>Inactivo</option>
                    </select>
                </div>
            </div>
        `;
    } else if (currentTab === 'cines') {
        html = `
            <div class="admin-field-group">
                <label class="admin-label">Nombre</label>
                <input type="text" name="nombre" value="${existingData?.nombre || ''}" required class="admin-input">
            </div>
            <div class="admin-field-group">
                <label class="admin-label">Dirección</label>
                <input type="text" name="direccion" value="${existingData?.direccion || ''}" required class="admin-input">
            </div>
            <div class="admin-field-group">
                <label class="admin-label">Teléfono</label>
                <input type="text" name="telefono" value="${existingData?.telefono || ''}" class="admin-input">
            </div>
        `;
    } else if (currentTab === 'salas') {
        let cinesHtml = '';
        const cResp = await fetch('../Controller/AdminController.php?action=cines_list');
        const cResult = await cResp.json();
        cResult.data.forEach(c => {
            const selected = existingData && existingData.cine_id_cine == c.id_cine ? 'selected' : '';
            cinesHtml += `<option value="${c.id_cine}" ${selected}>${c.nombre}</option>`;
        });

        html = `
            <div class="admin-field-group">
                <label class="admin-label">Capacidad</label>
                <input type="number" name="capacidad" value="${existingData?.capacidad || ''}" required class="admin-input">
            </div>
            <div class="admin-field-group">
                <label class="admin-label">Cine</label>
                <select name="cine_id" required class="admin-select">${cinesHtml}</select>
            </div>
        `;
    } else if (currentTab === 'funciones') {
        let pelisHtml = '', salasHtml = '', tarifasHtml = '';
        
        const pResp = await fetch('../Controller/AdminController.php?action=list&type=movies');
        const pResult = await pResp.json();
        pResult.data.forEach(p => {
            const selected = existingData && existingData.pelicula_id_pelicula == p.id ? 'selected' : '';
            pelisHtml += `<option value="${p.id}" ${selected}>${p.titulo}</option>`;
        });

        const sResp = await fetch('../Controller/AdminController.php?action=salas_list');
        const sResult = await sResp.json();
        sResult.data.forEach(s => {
            const selected = existingData && existingData.sala_id_sala == s.id_sala ? 'selected' : '';
            salasHtml += `<option value="${s.id_sala}" ${selected}>Sala ${s.id_sala} (${s.cine_nombre})</option>`;
        });

        const tResp = await fetch('../Controller/AdminController.php?action=tarifas_list');
        const tResult = await tResp.json();
        tResult.data.forEach(t => {
            const selected = existingData && existingData.tarifa_id_dia == t.id_dia ? 'selected' : '';
            tarifasHtml += `<option value="${t.id_dia}" ${selected}>${t.id_dia} ($${t.precio})</option>`;
        });

        html = `
            <div class="admin-field-group">
                <label class="admin-label">Fecha y Hora</label>
                <input type="datetime-local" name="fecha_hora" value="${existingData?.fecha_hora?.replace(' ', 'T') || ''}" required class="admin-input">
            </div>
            <div class="admin-field-group">
                <label class="admin-label">Película</label>
                <select name="pelicula_id" class="admin-select">${pelisHtml}</select>
            </div>
            <div class="admin-field-group">
                <label class="admin-label">Sala</label>
                <select name="sala_id" class="admin-select">${salasHtml}</select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="admin-field-group">
                    <label class="admin-label">Boletas Vendidas</label>
                    <input type="number" name="boletas_vendidas" value="${existingData?.boletas_vendidas || '0'}" class="admin-input">
                </div>
                <div class="admin-field-group">
                    <label class="admin-label">Tarifa</label>
                    <select name="tarifa_id" class="admin-select">${tarifasHtml}</select>
                </div>
            </div>
        `;
    } else if (currentTab === 'generos') {
        html = `
            <div class="admin-field-group">
                <label class="admin-label">Nombre del Género</label>
                <input type="text" name="nombre" value="${existingData?.nombre_genero || ''}" required class="admin-input">
            </div>
        `;
    } else if (currentTab === 'tarifas') {
        html = `
            <div class="admin-field-group">
                <label class="admin-label">Día / Tipo (ID)</label>
                <input type="text" name="id_dia" value="${existingData?.id_dia || ''}" ${isEdit ? 'readonly' : ''} required class="admin-input">
            </div>
            <div class="admin-field-group">
                <label class="admin-label">Precio</label>
                <input type="number" step="0.01" name="precio" value="${existingData?.precio || ''}" required class="admin-input">
            </div>
        `;
    } else if (currentTab === 'tipos_tokens') {
        html = `
            <div class="admin-field-group">
                <label class="admin-label">Nombre del Tipo</label>
                <input type="text" name="nombre" value="${existingData?.nombre || ''}" required class="admin-input">
            </div>
        `;
    } else if (currentTab === 'tokens') {
        html = `<p class="text-zinc-500 text-[10px] uppercase font-bold text-center py-4">Los tokens son de solo lectura / eliminación</p>`;
    } else if (currentTab === 'protagonists') {
        html = `
            <div class="admin-field-group">
                <label class="admin-label">Nombre del Protagonista</label>
                <input type="text" name="nombre" value="${existingData?.nombre || ''}" required class="admin-input">
            </div>
        `;
    }

    fieldsContainer.innerHTML = html;
}

document.getElementById('admin-insert-form').onsubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const id = formData.get('id');
    const action = id || currentTab === 'tarifas' ? 'update' : 'insert'; 
    // Trick: for tarifas, if id_dia is there, it might be update even if id hidden is empty if we use id_dia as the key
    
    try {
        const response = await fetch(`../Controller/AdminController.php?action=${id ? 'update' : 'insert'}&type=${currentTab}`, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            closeInsertModal();
            loadAdminData();
        } else alert(result.error || 'Error en la operación');
    } catch (e) { console.error(e); }
};

async function loadAdminData() {
    const tableBody = document.getElementById('admin-table-body');
    const loading = document.getElementById('admin-loading');
    tableBody.classList.add('opacity-30');
    loading.classList.remove('hidden');
    
    try {
        const response = await fetch(`../Controller/AdminController.php?action=list&type=${currentTab}&page=${currentPage}`);
        const result = await response.json();
        renderTable(result.data);
        updatePagination(result);
    } catch (e) { console.error(e); }
    finally {
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
        body.innerHTML = `<tr><td colspan="10" class="py-20 text-center text-zinc-600 tracking-widest uppercase text-[10px]">Sin registros</td></tr>`;
        return;
    }

    const keys = Object.keys(data[0]);
    keys.forEach(key => {
        if (key === 'id') return;
        head.innerHTML += `<th class="px-6 py-5 font-black text-[10px] tracking-widest uppercase">${key}</th>`;
    });
    head.innerHTML += `<th class="px-6 py-5 font-black text-center text-[10px] tracking-widest uppercase">Acciones</th>`;

    data.forEach(item => {
        let row = `<tr class="hover:bg-zinc-900/40 transition-all border-b border-zinc-900/30">`;
        keys.forEach(key => {
            if (key === 'id') return;
            row += `<td class="px-6 py-4 text-zinc-300">${item[key]}</td>`;
        });
        
        const id = item.id;
        row += `
            <td class="px-6 py-4">
                <div class="flex items-center justify-center gap-4">
                    <button onclick="openEditModal('${id}')" class="text-zinc-500 hover:text-blue-500 transition-colors uppercase text-[9px] font-black tracking-widest flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2.5"/></svg>
                        Editar
                    </button>
                    <button onclick="deleteItem('${id}')" class="text-zinc-500 hover:text-[#E50914] transition-colors uppercase text-[9px] font-black tracking-widest flex items-center gap-1">
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
    info.innerHTML = `<span class="text-[#E50914]">${result.currentPage}</span> de <span class="text-white">${result.pages}</span>`;
    document.getElementById('prev-page').disabled = result.currentPage <= 1;
    document.getElementById('next-page').disabled = result.currentPage >= result.pages;
}

document.getElementById('next-page').onclick = () => { currentPage++; loadAdminData(); };
document.getElementById('prev-page').onclick = () => { if (currentPage > 1) { currentPage--; loadAdminData(); } };

async function deleteItem(id) {
    if(!confirm('¿Eliminar este registro?')) return;
    const formData = new FormData();
    formData.append('id', id);
    try {
        const response = await fetch(`../Controller/AdminController.php?action=delete&type=${currentTab}`, {
            method: 'POST',
            body: formData
        });
        const res = await response.json();
        if(res.success) loadAdminData();
        else alert(res.error || "Error al eliminar");
    } catch (e) { console.error(e); }
}
Controller.php?action=delete&type=${currentTab}`, {
            method: 'POST',
            body: formData
        });
        const res = await response.json();
        if(res.success) loadAdminData();
        else alert(res.error || "Error al eliminar");
    } catch (e) { console.error(e); }
}
