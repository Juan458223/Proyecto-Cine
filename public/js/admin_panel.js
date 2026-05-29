let currentTab = 'movies';
let currentPage = 1;
let selectedProtagonists = []; 

const TAB_LABELS = {
    'movies': 'Película',
    'users': 'Usuarios',
    'cines': 'Cines',
    'salas': 'Sala',
    'funciones': 'Función',
    'generos': 'Géneros',
    'tarifas': 'Tarifa',
    'protagonists': 'Reparto',
    'reports': 'Reportes'
};

const VALID_CLASSIFICATIONS = ['TP', '7', '12', '13', '15', '16', '18'];
const PUBLIC_CATEGORIES = ['General', 'Estudiante', 'Jubilado', 'Niño'];
const DAY_TYPES = ['Normal', 'Espectador', 'Festivo', 'Víspera'];

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

function switchAdminTab(tab) {
    if (currentTab === tab) return;
    currentTab = tab;
    currentPage = 1;

    document.querySelectorAll('.sidebar-btn').forEach(btn => btn.classList.remove('active', 'border-l-2', 'border-[#E50914]', 'bg-zinc-900/50'));
    const activeBtn = document.getElementById(`tab-${tab}`);
    if (activeBtn) activeBtn.classList.add('active', 'border-l-2', 'border-[#E50914]', 'bg-zinc-900/50');

    document.getElementById('current-tab-title').textContent = TAB_LABELS[tab].toUpperCase();

    const addBtn = document.querySelector('header button[onclick="openCreateTokenModal()"]');
    if (addBtn) {
        if (tab === 'users' || tab === 'reports') addBtn.classList.add('hidden');
        else addBtn.classList.remove('hidden');
    }

    if (tab === 'reports') renderReportsView();
    else loadAdminData();
}

function renderReportsView() {
    const mainView = document.getElementById('admin-main-view');
    mainView.innerHTML = `
        <div class="flex flex-col items-center justify-center h-full max-w-2xl mx-auto space-y-12 animate-in fade-in zoom-in duration-500 font-outfit">
            <div class="text-center space-y-4">
                <p class="text-zinc-500 text-sm font-medium leading-relaxed text-center">
                    Este módulo está diseñado para reportar el alcance de la aplicación. Seleccione el tipo de informe que desea generar para visualizar métricas de ocupación, ventas y actividad de usuarios.
                </p>
            </div>

            <div class="w-full bg-white/[0.03] backdrop-blur-3xl p-10 rounded-[2.5rem] border border-white/10 space-y-10">
                <div class="flex items-center gap-6">
                    <label class="text-[11px] font-bold text-zinc-400  tracking-widest whitespace-nowrap">Tipo de informe:</label>
                    <select id="report-type-select" class="w-full bg-zinc-900 border border-zinc-800 text-white text-xs font-bold px-4 py-4 rounded-xl focus:border-[#E50914] outline-none transition-all cursor-pointer appearance-none">
                        <option value="" hidden disabled selected>Seleccione un informe</option>
                        <option value="semanal">Informe semanal</option>
                        <option value="quincenal">Informe quincenal</option>
                        <option value="mensual">Informe mensual</option>
                    </select>
                </div>

                <button onclick="generateReport()" class="btn-primary w-full !py-5 tracking-[0.2em]">
                    Generar informe
                </button>
                <div id="report-error" class="mt-4 text-[10px] font-black tracking-widest text-white text-center hidden h-4"></div>
            </div>
        </div>
    `;
}

window.generateReport = () => {
    const select = document.getElementById('report-type-select');
    const err = document.getElementById('report-error');
    if (!select.value) {
        err.textContent = "Debe seleccionar un tipo de informe";
        err.classList.remove('hidden');
        return;
    }
    err.classList.add('hidden');
    alert(`Generando informe ${select.value}... (Lógica en construcción)`);
};

async function loadAdminData() {
    const tableBody = document.getElementById('token-table-body');
    if (tableBody) tableBody.style.opacity = '0.3';

    try {
        const response = await fetch(`../Controller/AdminController.php?action=list&type=${currentTab}&page=${currentPage}`);
        const text = await response.text();
        try {
            const result = JSON.parse(text);
            renderTable(result.data);
            renderPagination(result.pages, result.currentPage);
        } catch (parseError) {
        }
    } catch (e) { }
    finally { if (tableBody) tableBody.style.opacity = '1'; }
}

function renderTable(data) {
    const mainView = document.getElementById('admin-main-view');
    mainView.innerHTML = `
        <div class="rounded-2xl border border-white/5 overflow-hidden font-outfit">
            <table class="w-full text-left border-collapse">
                <thead class="bg-white/5 text-zinc-500 font-bold text-[9px] tracking-[0.2em]">
                    <tr id="admin-table-head"></tr>
                </thead>
                <tbody id="token-table-body" class="text-zinc-400 text-xs font-medium divide-y divide-white/5"></tbody>
            </table>
        </div>
        <div id="admin-pagination" class="flex items-center justify-center gap-4 pt-10 font-outfit"></div>
    `;

    const head = document.getElementById('admin-table-head');
    const body = document.getElementById('token-table-body');

    const config = {
        'movies': ['#', 'Título', 'Director', 'Clasificación', 'Género'],
        'users': ['#', 'Nombre', 'Correo', 'Estado', 'Permisos', 'Registro'],
        'cines': ['#', 'Nombre', 'Calle', 'Número', 'Teléfono'],
        'salas': ['#', 'Cine', 'Número sala', 'Capacidad'],
        'funciones': ['#', 'Película', 'Cine', 'Sala', 'Fecha y hora'],
        'generos': ['#', 'Nombre'],
        'tarifas': ['#', 'Cine', 'Día', 'Categoría', 'Precio'],
        'protagonists': ['#', 'Nombre']
    };

    const cols = config[currentTab];
    cols.forEach(c => head.innerHTML += `<th class="px-8 py-6 font-black text-[10px] tracking-widest uppercase">${c}</th>`);
    head.innerHTML += `<th class="px-8 py-6 font-black text-center text-[10px] tracking-widest uppercase">Acciones</th>`;

    if (!data || data.length === 0) {
        body.innerHTML = `<tr><td colspan="10" class="py-32 text-center text-zinc-700 tracking-[0.3em] font-black text-[10px] uppercase">Sin registros</td></tr>`;
        return;
    }

    data.forEach((item, idx) => {
        let row = `<tr class="hover:bg-white/5 transition-all group animate-in fade-in slide-in-from-bottom-1" style="animation-delay: ${idx * 20}ms">`;
        Object.values(item).forEach((val, i) => {
            row += `<td class="px-8 py-5 text-zinc-300 font-outfit text-[11px] ${i === 0 ? 'font-black text-[#E50914]' : ''}">${val}</td>`;
        });
        row += `
            <td class="px-8 py-5 text-center">
                <button onclick="openEditModal('${item.id}')" class="text-zinc-500 hover:text-white transition-colors text-[11px] font-medium font-outfit">Editar</button>
            </td>
        </tr>`;
        body.innerHTML += row;
    });
}

function renderPagination(total, current) {
    const container = document.getElementById('admin-pagination');
    if (!container || total <= 1) return;

    let html = `<button onclick="adminChangePage(${current - 1})" class="p-3 rounded-full bg-zinc-900 border border-zinc-800 text-white hover:bg-[#E50914] transition-all ${current <= 1 ? 'opacity-20 pointer-events-none' : ''} font-outfit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2"/></svg></button>`;
    
    for (let i = 1; i <= total; i++) {
        const active = (i === current) ? 'bg-[#E50914] text-white border-[#E50914]' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white';
        html += `<button onclick="adminChangePage(${i})" class="w-10 h-10 rounded-xl border font-black text-[10px] transition-all ${active} font-outfit">${i}</button>`;
    }

    html += `<button onclick="adminChangePage(${current + 1})" class="p-3 rounded-full bg-zinc-900 border border-zinc-800 text-white hover:bg-[#E50914] transition-all ${current >= total ? 'opacity-20 pointer-events-none' : ''} font-outfit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2"/></svg></button>`;
    container.innerHTML = html;
}

window.adminChangePage = (p) => { currentPage = p; loadAdminData(); };

async function getFieldsForTab(tab) {
    let html = '';
    switch(tab) {
        case 'movies':
            html = `
                <div class="space-y-10">
                    ${renderInput('titulo', 'Título de la película')}
                    ${renderInput('director', 'Director')}
                    ${renderStaticSelect('clasificacion', 'Clasificación', VALID_CLASSIFICATIONS, 'Seleccione clasificación')}
                    ${await renderSelect('genero_id', 'Género', '../Controller/GeneroController.php?action=list_all', '', 'Seleccione género')}
                    ${renderInput('url_image', 'URL de la imagen')}
                </div>
                <div class="space-y-6 flex flex-col h-full">
                    <label class="text-[10px] font-black text-[#E50914] tracking-[0.3em] font-outfit">Reparto (opcional)</label>
                    <div class="space-y-4">
                        ${await renderSelect('protagonista_selector', 'Añadir Actor', '../Controller/ProtagonistaController.php?action=list_all', 'addProtagonistToList(this)', 'Selecciona un actor')}
                    </div>
                    <div class="bg-zinc-950/40 rounded-[2rem] border border-white/5 overflow-hidden flex flex-col flex-1 min-h-[250px] max-h-[350px]">
                        <div class="flex-1 overflow-y-auto custom-scrollbar">
                            <table class="w-full text-left border-collapse">
                                <tbody id="selected-protagonists-list">
                                    <!-- Protagonistas seleccionados -->
                                </tbody>
                            </table>
                            <div id="no-protagonists-msg" class="h-full flex flex-col items-center justify-center text-zinc-600 text-[10px] font-black uppercase tracking-[0.3em] py-20 space-y-4">
                                <svg class="w-8 h-8 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2"/></svg>
                                <span class="normal-case">Sin reparto</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'cines':
            html += renderInput('nombre', 'Nombre del cine');
            html += renderInput('calle', 'Calle / Dirección');
            html += renderInput('numero', 'Número');
            html += renderInput('telefono', 'Teléfono');
            break;
        case 'salas':
            html += await renderSelect('cine_id', 'Cine', '../Controller/CineController.php?action=list_all', '', 'Seleccione un cine');
            html += renderInput('capacidad', 'Capacidad', 'number');
            break;
        case 'funciones':
            html += await renderSelect('pelicula_id', 'Película', '../Controller/PeliculaController.php?action=list_all', '', 'Seleccione película');
            html += await renderSelect('cine_id', 'Cine', '../Controller/CineController.php?action=list_all', 'onCineChange(this)', 'Seleccione cine');
            html += `<div id="sala-select-container">${renderStaticSelect('sala_id', 'Sala', [], 'Seleccione un cine primero')}</div>`;
            html += renderInput('fecha_hora', 'Fecha y hora', 'datetime-local');
            break;
        case 'generos':
            html += renderInput('nombre', 'Nombre del género');
            break;
        case 'tarifas':
            html += await renderSelect('cine_id', 'Cine', '../Controller/CineController.php?action=list_all', '', 'Seleccione un cine');
            html += renderStaticSelect('categoria', 'Categoría', PUBLIC_CATEGORIES, 'Seleccione categoría');
            html += renderStaticSelect('dia_id', 'Día', DAY_TYPES, 'Seleccione tipo de día');
            html += renderInput('precio', 'Precio', 'number');
            break;
        case 'protagonists':
            html += renderInput('nombre', 'Nombre completo');
            break;
        case 'users':
            html += `
                <div class="flex flex-col space-y-2 font-outfit">
                    <div class="flex items-center gap-4">
                        <label class="text-[10px] font-bold text-zinc-500 tracking-widest whitespace-nowrap min-w-[100px]">Estado:</label>
                        <select name="estado_id" class="w-full bg-zinc-900 border border-zinc-800 text-white text-xs font-bold px-4 py-3 rounded-xl focus:border-[#E50914] outline-none transition-all cursor-pointer appearance-none">
                            <option value="" disabled selected>Seleccione estado</option>
                            <option value="Activado">Activado</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Desactivado">Desactivado</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-col space-y-2 font-outfit">
                    <div class="flex items-center gap-4">
                        <label class="text-[10px] font-bold text-zinc-500 tracking-widest whitespace-nowrap min-w-[100px]">Permisos:</label>
                        <select name="permisos_id" class="w-full bg-zinc-900 border border-zinc-800 text-white text-xs font-bold px-4 py-3 rounded-xl focus:border-[#E50914] outline-none transition-all cursor-pointer appearance-none">
                            <option value="" disabled selected>Seleccione permisos</option>
                            <option value="Administrador">Administrador</option>
                            <option value="Usuario">Usuario</option>
                        </select>
                    </div>
                </div>
            `;
            break;
    }
    return html;
}

window.onCineChange = async (el) => {
    const container = document.getElementById('sala-select-container');
    container.innerHTML = '<div class="animate-pulse h-12 bg-white/5 rounded-xl font-outfit"></div>';
    const res = await fetch(`../Controller/SalaController.php?action=list_by_cine&cine_id=${el.value}`);
    const text = await res.text();
    try {
        const result = JSON.parse(text);
        const options = result.data.map(s => `<option value="${s.id_sala}">Sala ${s.numero_sala}</option>`).join('');
        container.innerHTML = `
            <div class="flex flex-col space-y-2 font-outfit">
                <div class="flex items-center gap-4">
                    <label class="text-[10px] font-bold text-zinc-500 tracking-widest whitespace-nowrap min-w-[100px]">Sala:</label>
                    <select name="sala_id" class="w-full bg-zinc-900 border border-zinc-800 text-white text-xs font-bold px-4 py-3 rounded-xl focus:border-[#E50914] outline-none transition-all cursor-pointer appearance-none font-outfit" required>
                        <option value="" hidden disabled selected>Seleccione una sala</option>
                        ${options}
                    </select>
                </div>
            </div>
        `;
    } catch(e) { }
};

function renderInput(name, label, type = 'text') {
    const extraClass = type === 'datetime-local' ? 'calendar-input' : '';
    return `<div class="auth-input-group font-outfit"><input type="${type}" name="${name}" oninput="clearError()" class="auth-input-modern ${extraClass}" placeholder=" " required><label class="auth-label-modern font-outfit">${label}</label></div>`;
}

function renderStaticSelect(name, label, options, placeholder = 'Seleccione una opción') {
    const optsHtml = options.map(opt => `<option value="${opt}">${opt}</option>`).join('');
    return `
        <div class="flex flex-col space-y-2 font-outfit">
            <div class="flex items-center gap-4">
                <label class="text-[10px] font-bold text-zinc-500 tracking-widest whitespace-nowrap min-w-[100px]">${label}:</label>
                <select name="${name}" onchange="clearError()" class="w-full bg-zinc-900 border border-zinc-800 text-white text-xs font-bold px-4 py-3 rounded-xl focus:border-[#E50914] outline-none transition-all cursor-pointer appearance-none">
                    <option value="" disabled selected>${placeholder}</option>
                    ${optsHtml}
                </select>
            </div>
        </div>
    `;
}

async function renderSelect(name, label, url, onchange = '', placeholder = 'Seleccione una opción') {
    try {
        const res = await fetch(url);
        const text = await res.text();
        let items = [];
        try {
            const result = JSON.parse(text);
            items = result.data || result;
        } catch(e) { 
            return `<div class="text-red-500 text-[10px]">Error cargando ${label}</div>`;
        }
        
        const options = items.map(opt => `<option value="${opt.id || opt.id_cine || opt.id_genero || opt.id_actor}">${opt.nombre || opt.Nombre || opt.titulo || ('Sala ' + opt.numero_sala)}</option>`).join('');
        return `
            <div class="flex flex-col space-y-2 font-outfit">
                <div class="flex items-center gap-4">
                    <label class="text-[10px] font-bold text-zinc-500 tracking-widest whitespace-nowrap min-w-[100px]">${label}:</label>
                    <select name="${name}" onchange="${onchange ? onchange + ';' : ''} clearError()" class="w-full bg-zinc-900 border border-zinc-800 text-white text-xs font-bold px-4 py-3 rounded-xl focus:border-[#E50914] outline-none transition-all cursor-pointer appearance-none font-outfit">
                        <option value="" disabled selected>${placeholder}</option>
                        ${options}
                    </select>
                </div>
            </div>
        `;
    } catch (e) { return ''; }
}

window.openCreateTokenModal = async () => {
    clearError();
    const modal = document.getElementById('admin-action-modal');
    const content = document.getElementById('admin-action-content');
    const form = document.getElementById('admin-action-form');
    
    form.querySelectorAll('input[type="hidden"]').forEach(input => {
        if (input.id !== 'form-id') input.remove();
    });

    if (currentTab === 'movies') {
        content.classList.remove('max-w-2xl');
        content.classList.add('max-w-5xl');
    } else {
        content.classList.remove('max-w-5xl');
        content.classList.add('max-w-2xl');
    }

    selectedProtagonists = [];
    const fields = document.getElementById('action-form-fields');
    document.getElementById('form-id').value = '';
    document.getElementById('form-submit-btn').textContent = 'Insertar';
    fields.innerHTML = '<div class="col-span-full py-20 text-center animate-pulse text-[#E50914] font-black tracking-widest text-[10px] font-outfit">Preparando formulario...</div>';
    fields.innerHTML = await getFieldsForTab(currentTab);
    
    modal.classList.remove('hidden', 'pointer-events-none');
    modal.classList.add('flex');
    requestAnimationFrame(() => {
        modal.classList.add('opacity-100');
        document.getElementById('admin-action-content').classList.remove('scale-95', 'opacity-0');
        document.getElementById('admin-action-content').classList.add('scale-100', 'opacity-100');
    });
};

function renderProtagonistList() {
    const container = document.getElementById('selected-protagonists-list');
    const noMsg = document.getElementById('no-protagonists-msg');
    if (!container) return;

    if (selectedProtagonists.length === 0) {
        container.innerHTML = '';
        noMsg.classList.remove('hidden');
        return;
    }

    noMsg.classList.add('hidden');
    container.innerHTML = selectedProtagonists.map(p => `
        <tr class="group hover:bg-white/5 transition-colors border-b border-white/5">
            <td class="py-3 px-4 w-10">
                <button type="button" onclick="removeProtagonistFromList('${p.id}')" class="w-6 h-6 flex items-center justify-center rounded-lg bg-zinc-900 border border-white/10 text-zinc-500 hover:text-[#E50914] hover:border-[#E50914] transition-all font-black text-[10px]">
                    ✕
                </button>
            </td>
            <td class="py-3 px-2 text-[11px] font-bold text-zinc-300 font-outfit uppercase tracking-wider">
                ${p.nombre}
            </td>
        </tr>
    `).join('');
}

window.addProtagonistToList = (select) => {
    const id = select.value;
    const nombre = select.options[select.selectedIndex].text;
    
    if (!id || id === "") return;
    
    if (selectedProtagonists.some(p => p.id == id)) {
        select.value = '';
        return;
    }

    selectedProtagonists.push({ id, nombre });
    renderProtagonistList();
    select.value = ''; 
};

window.removeProtagonistFromList = (id) => {
    selectedProtagonists = selectedProtagonists.filter(p => p.id != id);
    renderProtagonistList();
};

window.openEditModal = async (id) => {
    await window.openCreateTokenModal();
    document.getElementById('form-id').value = id;
    document.getElementById('form-submit-btn').textContent = 'Confirmar cambios';
    
    try {
        const res = await fetch(`../Controller/AdminController.php?action=get&type=${currentTab}&id=${id}`);
        const result = await res.json();
        const form = document.getElementById('admin-action-form');
        
        setTimeout(async () => {
            for (const key of Object.keys(result.data)) {
                const input = form.querySelector(`[name="${key}"]`);
                if (input) {
                    let value = result.data[key];

                    if (key === 'estado_id') {
                        const map = {1: 'Activado', 2: 'Pendiente', 3: 'Desactivado'};
                        value = map[value] || value;
                    } else if (key === 'permisos_id') {
                        const map = {1: 'Administrador', 2: 'Usuario'};
                        value = map[value] || value;
                    }

                    input.value = value;
                    
                    if (key === 'cine_id' && currentTab === 'funciones') {
                        await window.onCineChange(input);
                        if (result.data['sala_id']) {
                            const salaSelect = form.querySelector('[name="sala_id"]');
                            if (salaSelect) salaSelect.value = result.data['sala_id'];
                        }
                    }
                }
            }

            if (currentTab === 'movies' && result.data.protagonistas) {
                selectedProtagonists = result.data.protagonistas.map(p => ({
                    id: p.id || p.id_actor,
                    nombre: p.nombre
                }));
                renderProtagonistList();
            }

            if (currentTab === 'salas') {
                const cineSelect = form.querySelector('[name="cine_id"]');
                if (cineSelect && cineSelect.value) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'cine_id';
                    hiddenInput.id = 'hidden-cine-id';
                    hiddenInput.value = cineSelect.value;
                    form.appendChild(hiddenInput);
                    
                    cineSelect.disabled = true;
                    cineSelect.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }
        }, 180); 
    } catch (e) { }
};

window.closeInsertModal = () => {
    const modal = document.getElementById('admin-action-modal');
    modal.classList.remove('opacity-100');
    document.getElementById('admin-action-content').classList.add('scale-95', 'opacity-0');
    setTimeout(() => { modal.classList.add('hidden', 'pointer-events-none'); modal.classList.remove('flex'); }, 500);
};

window.clearError = () => {
    const err = document.getElementById('action-error-container');
    if (err) {
        err.classList.add('hidden');
        err.innerHTML = '';
    }
};

function showError(msg) {
    const err = document.getElementById('action-error-container');
    if (!err) return;
    
    const formattedMsg = msg.charAt(0).toUpperCase() + msg.slice(1).toLowerCase();
    
    err.textContent = formattedMsg;
    err.classList.remove('hidden');
    err.classList.remove('text-[#E50914]'); 
    err.classList.add('text-white'); 
    err.style.textTransform = 'none'; 
}

const actionForm = document.getElementById('admin-action-form');
if (actionForm) {
    actionForm.onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(actionForm);
        
        if (currentTab === 'movies') {
            selectedProtagonists.forEach(p => {
                formData.append('protagonistas[]', p.id);
            });
        }

        const data = Object.fromEntries(formData);
        const action = data.id ? 'update' : 'insert';
        const url = `../Controller/AdminController.php?action=${action}&type=${currentTab}`;

        try {
            const res = await fetch(url, { method: 'POST', body: formData });
            const text = await res.text(); 
            
            try {
                const result = JSON.parse(text);

                if (result.success) { 
                    closeInsertModal(); 
                    loadAdminData(); 
                } else {
                    showError(result.error || "Error desconocido en el servidor");
                }
            } catch (parseError) {
                showError("Respuesta corrupta del servidor. Revisa la consola.");
            }
        } catch (e) { 
            showError("No se pudo conectar con el servidor."); 
        }
    };
}
