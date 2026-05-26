// --- Cine Detail Modal Dashboard Logic ---

let currentCineData = null;
let currentCineFilterSala = "";
let cineTarifaPage = 1;
let cineFuncionPage = 1;

window.capitalize = function(str) {
    if (!str) return '';
    // Mapeo especial para Clasificación
    if (str.toUpperCase() === 'TP') return 'Todo público';
    const s = str.toString().trim();
    return s.charAt(0).toUpperCase() + s.slice(1).toLowerCase();
};

window.openCineDetail = function(cine) {
    currentCineData = cine;
    currentCineFilterSala = "";
    cineTarifaPage = 1;
    cineFuncionPage = 1;

    const modal = document.getElementById('cine-detail-modal');
    const content = document.getElementById('cine-detail-content');
    
    // Header Info
    document.getElementById('cine-detail-name').textContent = capitalize(cine.Nombre);
    document.getElementById('cine-detail-address').textContent = `${capitalize(cine.Calle || '')} ${cine.Numero || ''}`;
    document.getElementById('cine-detail-phone').textContent = cine.Telefono ? `Tel: ${cine.Telefono}` : 'Sin teléfono';

    // Populate Sala Filter
    const select = document.getElementById('cine-filter-sala');
    if (select) {
        select.innerHTML = '<option value="">Todas las salas</option>';
        if (cine.salas) {
            cine.salas.forEach(s => {
                select.innerHTML += `<option value="${s.id_sala}">Sala ${s.numero_sala || s.id_sala}</option>`;
            });
        }
    }

    window.renderCineTarifas(1);
    window.renderCineFunctions(1);

    modal.classList.remove('hidden', 'pointer-events-none');
    modal.classList.add('flex');
    setTimeout(() => {
        modal.classList.add('opacity-100');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }, 10);
    document.body.style.overflow = 'hidden';
};

window.renderCineTarifas = async function(page = 1) {
    const body = document.getElementById('cine-tarifa-table-body');
    const pagination = document.getElementById('cine-tarifa-pagination');
    if (!body) return;

    try {
        const response = await fetch(`../Controller/AdminController.php?action=list&type=tarifas_cine&cine_id=${currentCineData.id_cine}&page=${page}&limit=5`);
        const result = await response.json();
        
        body.innerHTML = '';
        if (result.data && result.data.length > 0) {
            result.data.forEach(t => {
                body.innerHTML += `
                    <tr class="hover:bg-white/5 transition-colors text-xs">
                        <td class="px-6 py-4 font-bold text-white">${capitalize(t.tipo_publico)}</td>
                        <td class="px-6 py-4 text-zinc-500 font-medium">${capitalize(t.tipo_dia)}</td>
                        <td class="px-6 py-4 text-right">
                            <span class="bg-[#E50914]/10 text-[#E50914] px-3 py-1 rounded-md font-black">$${parseFloat(t.precio).toLocaleString()}</span>
                        </td>
                    </tr>
                `;
            });
            renderInternalPagination(pagination, result.pages, page, 'renderCineTarifas');
        } else {
            body.innerHTML = '<tr><td colspan="3" class="px-6 py-10 text-center text-zinc-600 italic">No hay tarifas definidas</td></tr>';
            if (pagination) pagination.innerHTML = '';
        }
    } catch (e) {
        body.innerHTML = '<tr><td colspan="3" class="px-6 py-10 text-center text-red-500/50">Error cargando tarifas</td></tr>';
    }
};

window.renderCineFunctions = function(page = 1) {
    const body = document.getElementById('cine-funcion-table-body');
    const pagination = document.getElementById('cine-functions-pagination');
    if (!body) return;

    const filtered = currentCineFilterSala === "" 
        ? currentCineData.funciones || []
        : (currentCineData.funciones || []).filter(f => f.sala_id_sala.toString() === currentCineFilterSala);

    const limit = 5;
    const totalPages = Math.ceil(filtered.length / limit);
    const start = (page - 1) * limit;
    const paginated = filtered.slice(start, start + limit);

    body.innerHTML = '';
    if (paginated.length > 0) {
        paginated.forEach(f => {
            const date = new Date(f.fecha_hora.replace(' ', 'T'));
            const time = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            body.innerHTML += `
                <tr class="hover:bg-white/5 transition-colors text-xs">
                    <td class="px-6 py-4 font-bold text-white">${capitalize(f.pelicula_titulo)}</td>
                    <td class="px-6 py-4 text-zinc-500 font-medium">${time}</td>
                    <td class="px-6 py-4 text-right font-black text-[#E50914]">S${f.numero_sala || f.sala_id_sala}</td>
                </tr>
            `;
        });
        renderInternalPagination(pagination, totalPages, page, 'renderCineFunctions');
    } else {
        body.innerHTML = '<tr><td colspan="3" class="px-6 py-10 text-center text-zinc-600 italic">No hay funciones disponibles</td></tr>';
        if (pagination) pagination.innerHTML = '';
    }
};

function renderInternalPagination(container, total, current, callbackName) {
    if (!container) return;
    container.innerHTML = '';
    if (total <= 1) return;

    // Arrow Prev
    const prev = document.createElement('button');
    prev.className = `w-7 h-7 rounded-lg flex items-center justify-center transition-all ${current <= 1 ? 'opacity-20 pointer-events-none' : 'bg-zinc-900 text-zinc-500 hover:text-white'}`;
    prev.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>';
    prev.onclick = () => window[callbackName](current - 1);
    container.appendChild(prev);

    // Number
    const pageNum = document.createElement('span');
    pageNum.className = "text-[10px] font-black text-zinc-500 px-2 flex items-center";
    pageNum.textContent = `${current} / ${total}`;
    container.appendChild(pageNum);

    // Arrow Next
    const next = document.createElement('button');
    next.className = `w-7 h-7 rounded-lg flex items-center justify-center transition-all ${current >= total ? 'opacity-20 pointer-events-none' : 'bg-zinc-900 text-zinc-500 hover:text-white'}`;
    next.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>';
    next.onclick = () => window[callbackName](current + 1);
    container.appendChild(next);
}

function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    const icon = document.getElementById('hamburger-icon');
    
    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        setTimeout(() => {
            menu.classList.add('active', 'opacity-100');
            menu.style.maxHeight = '500px';
        }, 10);
        icon.setAttribute('d', 'M6 18L18 6M6 6l12 12'); // X icon
    } else {
        menu.classList.remove('active', 'opacity-100');
        menu.style.maxHeight = '0';
        setTimeout(() => {
            menu.classList.add('hidden');
        }, 300);
        icon.setAttribute('d', 'M4 6h16M4 12h16m-7 6h7'); // Hamburger icon
    }
}

function showSection(sectionId) {
    const sections = ['cartelera', 'cines'];
    const navItems = {
        'cartelera': document.getElementById('nav-cartelera'),
        'cines': document.getElementById('nav-cines')
    };
    
    sections.forEach(s => {
        const el = document.getElementById(`section-${s}`);
        if (!el) return;
        
        if (s === sectionId) {
            el.classList.remove('hidden');
            if (navItems[s]) {
                navItems[s].classList.add('text-white', 'relative', 'after:absolute', 'after:-bottom-2', 'after:left-0', 'after:w-full', 'after:h-0.5', 'after:bg-[#E50914]');
                navItems[s].classList.remove('text-zinc-400', 'hover:text-white');
            }
        } else {
            el.classList.add('hidden');
            if (navItems[s]) {
                navItems[s].classList.remove('text-white', 'relative', 'after:absolute', 'after:-bottom-2', 'after:left-0', 'after:w-full', 'after:h-0.5', 'after:bg-[#E50914]');
                navItems[s].classList.add('text-zinc-400', 'hover:text-white');
            }
        }
    });

    // Update Hero Content
    const heroTitle = document.getElementById('hero-title');
    const heroSubtitle = document.getElementById('hero-subtitle');
    const heroBg = document.getElementById('hero-bg');

    if (sectionId === 'cartelera') {
        if (heroTitle) heroTitle.innerHTML = 'CARTELERA <span class="text-[#E50914]">NACIONAL</span>';
        if (heroSubtitle) heroSubtitle.textContent = 'La mejor experiencia en la pantalla grande';
        if (heroBg) heroBg.src = "https://images.unsplash.com/photo-1478720568477-152d9b164e26?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80";
    } else if (sectionId === 'cines') {
        if (heroTitle) heroTitle.innerHTML = 'NUESTROS <span class="text-[#E50914]">CINES</span>';
        if (heroSubtitle) heroSubtitle.textContent = 'Encuentra tu sala más cercana';
        if (heroBg) heroBg.src = "https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80";
        loadCines(1);
    }
}

let currentCinePage = 1;

async function loadCines(page = 1) {
    const grid = document.getElementById('cines-grid');
    const pagination = document.getElementById('cines-pagination');
    if (!grid) return;

    grid.innerHTML = '<div class="col-span-full py-20 text-center"><div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-[#E50914]"></div></div>';
    
    try {
        const response = await fetch(`../Controller/AdminController.php?action=list&type=cines&page=${page}`);
        const result = await response.json();
        
        if (result.data && result.data.length > 0) {
            grid.innerHTML = '';
            result.data.forEach(cine => {
                const nombre = capitalize(cine.Nombre);
                const direccion = `${capitalize(cine.Calle || '')} ${cine.Numero || ''}`;
                
                grid.innerHTML += `
                    <div onclick='openCineDetail(${JSON.stringify(cine)})' class="group bg-zinc-950 border border-zinc-900 hover:border-[#E50914] transition-all p-8 rounded-sm font-outfit cursor-pointer">
                        <div class="flex items-start justify-between mb-6">
                            <div class="w-12 h-12 bg-zinc-900 rounded-lg flex items-center justify-center text-[#E50914] group-hover:bg-[#E50914] group-hover:text-white transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-black text-zinc-700 group-hover:text-zinc-500 tracking-[0.1em] uppercase font-bebas">CINE FIRST</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2 group-hover:text-[#E50914] transition-colors tracking-tight">${nombre}</h4>
                        <p class="text-zinc-500 text-xs font-medium mb-6 leading-relaxed">${direccion}</p>
                        <div class="flex items-center gap-4 border-t border-zinc-900 pt-6">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-zinc-600">${capitalize('Teléfono')}</span>
                                <span class="text-sm font-bold text-zinc-400 tracking-tight">${cine.Telefono || 'No disponible'}</span>
                            </div>
                            <button class="ml-auto bg-zinc-900 hover:bg-[#E50914] text-white p-3 rounded-sm transition-all shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7-7 7M5 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
            });

            if (pagination) {
                renderCinePagination(result.pages, result.currentPage);
            }
            currentCinePage = page;
        } else {
            grid.innerHTML = '<p class="col-span-full text-center text-zinc-600 font-medium py-20">No hay cines disponibles en este momento</p>';
        }
    } catch (error) {
        grid.innerHTML = '<p class="col-span-full text-center text-red-500/80 font-medium py-20">Error al cargar la lista de cines</p>';
    }
}

function renderCinePagination(totalPages, currentPage) {
    const container = document.getElementById('cines-pagination');
    if (!container || totalPages <= 1) {
        if (container) container.innerHTML = '';
        return;
    }
    let html = `
        <button onclick="loadCines(${currentPage - 1})" class="p-3 rounded-full bg-zinc-900 border border-zinc-800 text-white hover:bg-[#E50914] transition-all ${currentPage <= 1 ? 'opacity-30 pointer-events-none' : ''}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
    `;
    for (let i = 1; i <= totalPages; i++) {
        const active = i === currentPage ? 'bg-[#E50914] text-white border-[#E50914]' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white';
        html += `<button onclick="loadCines(${i})" class="w-10 h-10 rounded-xl border font-bold text-xs transition-all ${active}">${i}</button>`;
    }
    html += `
        <button onclick="loadCines(${currentPage + 1})" class="p-3 rounded-full bg-zinc-900 border border-zinc-800 text-white hover:bg-[#E50914] transition-all ${currentPage >= totalPages ? 'opacity-30 pointer-events-none' : ''}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
    `;
    container.innerHTML = html;
}

window.closeCineDetail = function() {
    const modal = document.getElementById('cine-detail-modal');
    const content = document.getElementById('cine-detail-content');
    modal.classList.remove('opacity-100');
    content.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden', 'pointer-events-none');
        document.body.style.overflow = 'auto';
    }, 500);
};

window.openModal = function(title_ignored, message, email, type) {
    const authModal = document.getElementById('auth-modal');
    const modalContainerInner = document.getElementById('auth-modal-content');
    const modalMessage = document.getElementById('auth-modal-message');
    if (!authModal) return;
    if (modalMessage) modalMessage.textContent = message;
    authModal.classList.remove("hidden", "pointer-events-none");
    authModal.classList.add("flex");
    requestAnimationFrame(() => {
        authModal.classList.add("opacity-100");
        if (modalContainerInner) {
            modalContainerInner.classList.remove("scale-95");
            modalContainerInner.classList.add("scale-100");
        }
    });
    document.body.style.overflow = "hidden";
};

window.openUserSettings = function() {
    const modal = document.getElementById('user-settings-modal');
    const content = document.getElementById('settings-modal-content');
    if (!modal || !content) return;
    modal.classList.remove('hidden', 'pointer-events-none');
    modal.classList.add('flex');
    setTimeout(() => {
        modal.classList.add('opacity-100');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }, 10);
    document.body.style.overflow = 'hidden';
};

window.closeUserSettings = function() {
    const modal = document.getElementById('user-settings-modal');
    const content = document.getElementById('settings-modal-content');
    if (!modal || !content) return;
    modal.classList.remove('opacity-100');
    content.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden', 'pointer-events-none');
        document.body.style.overflow = 'auto';
    }, 500);
};

document.addEventListener('DOMContentLoaded', () => {
    const filterGenre = document.getElementById('filter-genre');
    if (filterGenre) {
        filterGenre.addEventListener('change', (e) => {
            showSection('cartelera');
        });
    }

    const filterSala = document.getElementById('cine-filter-sala');
    if (filterSala) {
        filterSala.addEventListener('change', (e) => {
            currentCineFilterSala = e.target.value;
            window.renderCineFunctions(1);
        });
    }
});

window.showSettingsAlert = function(title, message, isError = false) {
    const modal = document.getElementById('admin-alert-modal');
    const content = document.getElementById('admin-alert-content');
    const messageEl = document.getElementById('admin-alert-message');
    if (!modal) return;
    if (messageEl) messageEl.textContent = message;
    modal.classList.remove('hidden', 'pointer-events-none');
    modal.classList.add('flex');
    setTimeout(() => {
        modal.classList.add('opacity-100');
        if (content) {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }
    }, 10);
};

window.closeAdminAlert = function() {
    const modal = document.getElementById('admin-alert-modal');
    const content = document.getElementById('admin-alert-content');
    modal.classList.remove('opacity-100');
    content.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden', 'pointer-events-none');
    }, 500);
};

const settingsForm = document.getElementById('user-settings-form');
if (settingsForm) {
    settingsForm.onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const nombre = formData.get('nombre');
        if (!nombre || nombre.trim().length === 0) {
            window.showSettingsAlert('Campo Requerido', 'Por favor, ingresa tu nombre completo', true);
            return;
        }
        try {
            const response = await fetch('../Controller/AuthController.php?action=update_profile', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                window.showSettingsAlert('¡Éxito!', 'Perfil actualizado correctamente');
                setTimeout(() => { window.location.reload(); }, 1500);
            } else {
                window.showSettingsAlert('Error', result.error || 'No se pudo actualizar el perfil', true);
            }
        } catch (error) {
            window.showSettingsAlert('Error de Sistema', 'Error de conexión con el servidor', true);
        }
    };
}

window.togglePasswordVisibility = function(inputId) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const btn = input.parentElement.querySelector(".password-toggle-btn");
    const type = input.getAttribute("type") === "password" ? "text" : "password";
    input.setAttribute("type", type);
    if (btn) {
        const slashPath = btn.querySelector(".eye-slash");
        if (type === "text") slashPath.classList.remove("hidden");
        else slashPath.classList.add("hidden");
    }
};
