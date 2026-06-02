const userSettingsModal = document.getElementById('user-settings-modal');
const settingsModalContent = document.getElementById('settings-modal-content');
const userSettingsForm = document.getElementById('user-settings-form');
const settingsMessage = document.getElementById('settings-message');

const navCartelera = document.getElementById('nav-cartelera');
const navCines = document.getElementById('nav-cines');
const sectionCartelera = document.getElementById('section-cartelera');
const sectionCines = document.getElementById('section-cines');

const heroTitle = document.getElementById('hero-title');
const heroSubtitle = document.getElementById('hero-subtitle');
const heroBg = document.getElementById('hero-bg');

const movieGrid = document.getElementById('movie-grid');
const moviePagination = document.getElementById('movie-pagination');
const filterGenre = document.getElementById('filter-genre');

const cinesGrid = document.getElementById('cines-grid');
const cinesPagination = document.getElementById('cines-pagination');

const mobileMenu = document.getElementById('mobile-menu');
const hamburgerIcon = document.getElementById('hamburger-icon');

let currentCineData = null;
let currentCineFilterSala = "";
let currentCinePage = 1;

window.capitalize = function(str) {
    if (!str) return '';
    if (str.toUpperCase() === 'TP') return 'Todo público';
    const s = str.toString().trim();
    return s.charAt(0).toUpperCase() + s.slice(1).toLowerCase();
};


window.showSection = function(sectionId) {
    const sections = {
        'cartelera': { el: sectionCartelera, nav: navCartelera },
        'cines': { el: sectionCines, nav: navCines }
    };
    
    Object.keys(sections).forEach(key => {
        const item = sections[key];
        if (!item.el) return;
        
        if (key === sectionId) {
            item.el.classList.remove('hidden');
            if (item.nav) {
                item.nav.classList.add('text-white', 'relative', 'after:absolute', 'after:-bottom-2', 'after:left-0', 'after:w-full', 'after:h-0.5', 'after:bg-[#E50914]');
                item.nav.classList.remove('text-zinc-400', 'hover:text-white');
            }
        } else {
            item.el.classList.add('hidden');
            if (item.nav) {
                item.nav.classList.remove('text-white', 'relative', 'after:absolute', 'after:-bottom-2', 'after:left-0', 'after:w-full', 'after:h-0.5', 'after:bg-[#E50914]');
                item.nav.classList.add('text-zinc-400', 'hover:text-white');
            }
        }
    });

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

/**
 * Gestión del Modal de Ajustes
 */
window.openUserSettings = async function() {
    if (!userSettingsModal || !settingsModalContent || !userSettingsForm) return;

    if (settingsMessage) {
        settingsMessage.classList.add('hidden');
        settingsMessage.textContent = '';
    }
    userSettingsForm.reset();

    userSettingsForm.nombre.value = 'Cargando...';
    userSettingsForm.email.value = 'Cargando...';

    userSettingsModal.classList.remove('hidden', 'pointer-events-none');
    userSettingsModal.classList.add('flex');
    requestAnimationFrame(() => {
        userSettingsModal.classList.add('opacity-100');
        settingsModalContent.classList.remove('scale-95');
        settingsModalContent.classList.add('scale-100');
    });
    document.body.style.overflow = 'hidden';
    
    try {
        const response = await fetch(`../Controller/UsuarioController.php?action=get_profile`);
        const data = await response.json();
        
        if (data) {
            userSettingsForm.nombre.value = data.nombre || '';
            userSettingsForm.email.value = data.correo || '';
        }
    } catch(e) {
        showInlineError('No se pudieron cargar los datos del perfil.');
    }
};

window.closeUserSettings = function() {
    if (!userSettingsModal || !settingsModalContent) return;

    userSettingsModal.classList.remove('opacity-100');
    settingsModalContent.classList.add('scale-95');
    setTimeout(() => {
        userSettingsModal.classList.add('hidden', 'pointer-events-none');
        document.body.style.overflow = 'auto';
    }, 500);
};

/**
 * Validación y Envío de Ajustes
 */
function showInlineError(message, isSuccess = false) {
    if (!settingsMessage) return;

    settingsMessage.textContent = message;
    settingsMessage.classList.remove('hidden', 'text-red-500', 'text-green-500');
    settingsMessage.classList.add(isSuccess ? 'text-green-500' : 'text-red-500');
    
    settingsMessage.style.animation = 'none';
    settingsMessage.offsetHeight; 
    settingsMessage.style.animation = 'fadeInScale 0.3s ease-out forwards';
}

if (userSettingsForm) {
    userSettingsForm.onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(userSettingsForm);
        const pass = formData.get('password');
        const confirm = formData.get('confirm_password');
        const nombre = formData.get('nombre');
       

        if (!nombre || nombre.trim().length === 0) {
            showInlineError('El nombre es obligatorio');
            return;
        }

        if (pass || confirm) {
            if (pass !== confirm) {
                showInlineError('Las contraseñas no coinciden');
                return;
            }
            if (pass.length < 6) {
                showInlineError('La contraseña debe tener al menos 6 caracteres');
                return;
            }
        }

        try {
            const response = await fetch('../Controller/AuthController.php?action=update_profile', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                showInlineError('¡Perfil actualizado con éxito!', true);
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showInlineError(result.error || 'Error al actualizar');
            }
        } catch (error) {
            showInlineError('Error de conexión con el servidor');
        }
    };
}

/**
 * Películas (Carga y Paginación)
 */
window.loadGenres = async function() {
    if (!filterGenre) return;
    
    try {
        const response = await fetch('../Controller/GeneroController.php?action=list_all');
        const result = await response.json();
        
        if (result.data) {
            // Mantener la opción por defecto
            filterGenre.innerHTML = '<option value="">Todos los géneros</option>';
            result.data.forEach(g => {
                const opt = document.createElement('option');
                opt.value = g.id;
                opt.textContent = g.nombre;
                filterGenre.appendChild(opt);
            });
        }
    } catch (e) {
        console.error("Error cargando géneros:", e);
    }
}

window.changePage = async function(page) {
    if (!movieGrid) return;

    movieGrid.style.opacity = '0';
    const generoId = filterGenre ? filterGenre.value : "";
    
    try {
        let url = `../Controller/PeliculaController.php?action=list&page=${page}`;
        if (generoId && generoId !== "") {
            url += `&genero_id=${generoId}`;
        }
        
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.data && result.data.length > 0) {
            movieGrid.innerHTML = '';
            result.data.forEach(peli => {
                const json_data = JSON.stringify(peli).replace(/'/g, "\\'");
                movieGrid.innerHTML += `
                    <li class='group flex flex-col cursor-pointer' onclick='window.openMovieAdmin(${json_data})'>
                        <div class='relative aspect-[2/3] overflow-hidden rounded-md bg-zinc-900 shadow-lg shadow-black/50 transition-all duration-500 group-hover:shadow-[#E50914]/10 group-hover:shadow-2xl'>
                            <div class='absolute top-3 left-3 z-20'>
                                <span class='bg-black/60 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-sm border border-white/10'>
                                    ${peli.clasificacion}
                                </span>
                            </div>
                            <img src='${peli.url_image}' alt='${peli.titulo}' class='w-full h-full object-cover transition-transform duration-1000 ease-out group-hover:scale-110' loading='lazy' onerror="this.onerror=null; this.src='../../public/img/logo.svg'; this.classList.add('p-8', 'opacity-20');">
                            <div class='absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500'></div>
                            <div class='absolute bottom-0 left-0 w-0 h-1 bg-[#E50914] transition-all duration-500 group-hover:w-full'></div>
                        </div>
                        <div class='mt-4 space-y-1 px-1'>
                            <h3 class='text-white font-bold text-sm md:text-base leading-tight truncate group-hover:text-[#E50914] transition-colors duration-300' title='${peli.titulo}'>${peli.titulo}</h3>
                            <div class='flex items-center gap-2'>
                                <span class='w-1 h-1 rounded-full bg-zinc-800'></span>
                                <span class='text-zinc-600 text-[10px] font-medium italic truncate'>${peli.director}</span>
                            </div>
                        </div>
                    </li>
                `;
            });
            movieGrid.style.opacity = '1';
            
            if (moviePagination) {
                renderMoviePagination(result.pages, page);
            }
        } else {
            // Caso cuando no hay películas
            movieGrid.innerHTML = `
                <div class="col-span-full py-32 flex flex-col items-center justify-center text-center space-y-6">
                    <div class="w-20 h-20 bg-zinc-900/50 rounded-full flex items-center justify-center text-zinc-700">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <h4 class="text-white text-xl font-bold tracking-tight">No se encontraron películas</h4>
                        <p class="text-zinc-500 text-sm max-w-xs mx-auto">Lo sentimos, actualmente no hay películas disponibles para este género.</p>
                    </div>
                </div>
            `;
            movieGrid.style.opacity = '1';
            if (moviePagination) moviePagination.innerHTML = '';
        }
    } catch (error) {
        console.error('Error changing movie page:', error);
        movieGrid.style.opacity = '1';
    }
};

function renderMoviePagination(totalPages, currentPage) {
    if (!moviePagination) return;
    
    let html = "<div class='flex items-center justify-center gap-4 mt-16'>";
    
    const prevDisabled = (currentPage <= 1) ? 'opacity-30 pointer-events-none' : '';
    html += `<button onclick='changePage(${currentPage - 1})' class='p-3 rounded-full bg-zinc-900 border border-zinc-800 text-white hover:bg-[#E50914] transition-all ${prevDisabled}'>
                <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 19l-7-7 7-7'/></svg>
             </button>`;

    for (let i = 1; i <= totalPages; i++) {
        const activeClass = (i == currentPage) ? 'bg-[#E50914] text-white border-[#E50914]' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white';
        html += `<button onclick='changePage(${i})' class='w-10 h-10 rounded-xl border font-bold text-xs transition-all ${activeClass}'>${i}</button>`;
    }
    
    const nextDisabled = (currentPage >= totalPages) ? 'opacity-30 pointer-events-none' : '';
    html += `<button onclick='changePage(${currentPage + 1})' class='p-3 rounded-full bg-zinc-900 border border-zinc-800 text-white hover:bg-[#E50914] transition-all ${nextDisabled}'>
                <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'/></svg>
             </button>`;

    html += "</div>";
    moviePagination.innerHTML = html;
}

/**
 * Cines (Carga y Paginación)
 */
window.loadCines = async function(page = 1) {
    if (!cinesGrid) return;
    
    cinesGrid.style.opacity = '0';
    // Nota: Usamos el controlador de Cines (Corregido)
    const url = `../Controller/CineController.php?action=list&page=${page}`;
    
    try {
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.data && result.data.length > 0) {
            cinesGrid.innerHTML = '';
            result.data.forEach(cine => {
                // Aquí usamos la estructura premium de los cines
                cinesGrid.innerHTML += `
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
                        <h4 class="text-lg font-bold text-white mb-2 group-hover:text-[#E50914] transition-colors tracking-tight">${capitalize(cine.Nombre)}</h4>
                        <p class="text-zinc-500 text-xs font-medium mb-6 leading-relaxed">${capitalize(cine.Calle || '')} ${cine.Numero || ''}</p>
                        <div class="flex items-center gap-4 border-t border-zinc-900 pt-6">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-zinc-600">Teléfono</span>
                                <span class="text-sm font-bold text-zinc-400 tracking-tight">${cine.Telefono || 'No disponible'}</span>
                            </div>
                            <button class="ml-auto bg-zinc-900 hover:bg-[#E50914] text-white p-3 rounded-sm transition-all shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7-7 7M5 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>`;
            });
            cinesGrid.style.opacity = '1';
            
            if (cinesPagination) {
                renderCinePagination(result.pages, page);
            }
        } else {
            cinesGrid.innerHTML = `
                <div class="col-span-full py-32 flex flex-col items-center justify-center text-center space-y-6">
                    <div class="w-20 h-20 bg-zinc-900/50 rounded-full flex items-center justify-center text-zinc-700">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <h4 class="text-white text-xl font-bold tracking-tight">No hay cines disponibles</h4>
                        <p class="text-zinc-500 text-sm max-w-xs mx-auto">Lo sentimos, no pudimos encontrar salas de cine en este momento.</p>
                    </div>
                </div>`;
            cinesGrid.style.opacity = '1';
            if (cinesPagination) cinesPagination.innerHTML = '';
        }
    } catch (e) {
        console.error("Error cargando cines:", e);
        cinesGrid.style.opacity = '1';
    }
}

function renderCinePagination(totalPages, currentPage) {
    if (!cinesPagination) return;
    if (totalPages <= 1) {
        cinesPagination.innerHTML = '';
        return;
    }
    
    let html = "<div class='flex items-center justify-center gap-4 mt-16'>";
    
    const prevDisabled = (currentPage <= 1) ? 'opacity-30 pointer-events-none' : '';
    html += `<button onclick='loadCines(${currentPage - 1})' class='p-3 rounded-full bg-zinc-900 border border-zinc-800 text-white hover:bg-[#E50914] transition-all ${prevDisabled}'>
                <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 19l-7-7 7-7'/></svg>
             </button>`;

    for (let i = 1; i <= totalPages; i++) {
        const activeClass = (i == currentPage) ? 'bg-[#E50914] text-white border-[#E50914]' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white';
        html += `<button onclick='loadCines(${i})' class='w-10 h-10 rounded-xl border font-bold text-xs transition-all ${activeClass}'>${i}</button>`;
    }
    
    const nextDisabled = (currentPage >= totalPages) ? 'opacity-30 pointer-events-none' : '';
    html += `<button onclick='loadCines(${currentPage + 1})' class='p-3 rounded-full bg-zinc-900 border border-zinc-800 text-white hover:bg-[#E50914] transition-all ${nextDisabled}'>
                <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'/></svg>
             </button>`;

    html += "</div>";
    cinesPagination.innerHTML = html;
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

window.toggleMobileMenu = function() {
    if (!mobileMenu || !hamburgerIcon) return;
    
    if (mobileMenu.classList.contains('hidden')) {
        mobileMenu.classList.remove('hidden');
        setTimeout(() => {
            mobileMenu.classList.add('active', 'opacity-100');
            mobileMenu.style.maxHeight = '500px';
        }, 10);
        hamburgerIcon.setAttribute('d', 'M6 18L18 6M6 6l12 12');
    } else {
        mobileMenu.classList.remove('active', 'opacity-100');
        mobileMenu.style.maxHeight = '0';
        setTimeout(() => {
            mobileMenu.classList.add('hidden');
        }, 300);
        hamburgerIcon.setAttribute('d', 'M4 6h16M4 12h16m-7 6h7');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const inputsToWatch = [
        document.getElementById('settings-nombre'),
        document.getElementById('password_settings'),
        document.getElementById('confirm_settings')
    ];

    inputsToWatch.forEach(input => {
        if (input) {
            input.addEventListener('input', () => {
                if (settingsMessage) settingsMessage.classList.add('hidden');
            });
        }
    });

    if (filterGenre) {
        filterGenre.addEventListener('change', () => window.changePage(1));
    }

    // 3. Carga inicial de datos (Arquitectura Multi-capa vía AJAX)
    window.loadGenres();
    window.changePage(1);
});


