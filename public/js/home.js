/**
 * Lógica de la página de inicio (Home)
 * Maneja el menú móvil, cambio de secciones y ajustes de usuario
 */

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
        heroTitle.innerHTML = 'CARTELERA <span class="text-[#E50914]">NACIONAL</span>';
        heroSubtitle.textContent = 'La mejor experiencia en la pantalla grande';
        heroBg.src = "https://images.unsplash.com/photo-1478720568477-152d9b164e26?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80";
    } else if (sectionId === 'cines') {
        heroTitle.innerHTML = 'NUESTROS <span class="text-[#E50914]">CINES</span>';
        heroSubtitle.textContent = 'Encuentra tu sala más cercana';
        heroBg.src = "https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80";
        loadCines();
    }
}

async function loadCines() {
    const grid = document.getElementById('cines-grid');
    grid.innerHTML = '<div class="col-span-full py-20 text-center"><div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-[#E50914]"></div></div>';
    
    try {
        const response = await fetch('../Controller/AdminController.php?action=list&type=cines');
        const result = await response.json();
        
        if (result.data && result.data.length > 0) {
            grid.innerHTML = '';
            result.data.forEach(cine => {
                grid.innerHTML += `
                    <div class="group bg-zinc-950 border border-zinc-900 hover:border-[#E50914] transition-all p-8 rounded-sm">
                        <div class="flex items-start justify-between mb-6">
                            <div class="w-12 h-12 bg-zinc-900 rounded-lg flex items-center justify-center text-[#E50914] group-hover:bg-[#E50914] group-hover:text-white transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <span class="text-[9px] font-black text-zinc-800 group-hover:text-zinc-600 uppercase tracking-[0.3em]">Cine First</span>
                        </div>
                        <h4 class="text-2xl font-black font-bebas tracking-wider text-white mb-2 group-hover:text-[#E50914] transition-colors">${cine.Nombre}</h4>
                        <p class="text-zinc-500 text-xs font-medium mb-6 leading-relaxed">${cine.Direccion}</p>
                        <div class="flex items-center gap-4 border-t border-zinc-900 pt-6">
                            <div class="flex flex-col">
                                <span class="text-[8px] font-black text-zinc-600 uppercase tracking-widest">Teléfono</span>
                                <span class="text-[10px] font-bold text-zinc-400">${cine.Telefono || 'N/A'}</span>
                            </div>
                            <button class="ml-auto bg-zinc-900 hover:bg-[#E50914] text-white p-3 rounded-sm transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7-7 7M5 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
            });
        } else {
            grid.innerHTML = '<p class="col-span-full text-center text-zinc-600 font-bold uppercase tracking-widest py-20">No hay cines disponibles en este momento</p>';
        }
    } catch (error) {
        grid.innerHTML = '<p class="col-span-full text-center text-red-500 font-bold uppercase tracking-widest py-20">Error al cargar los cines</p>';
    }
}

// User Settings Logic
function openUserSettings() {
    const modal = document.getElementById('user-settings-modal');
    const content = document.getElementById('settings-modal-content');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    requestAnimationFrame(() => {
        modal.classList.add('opacity-100');
        modal.classList.remove('pointer-events-none');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    });
    document.body.style.overflow = 'hidden';
}

function closeUserSettings() {
    const modal = document.getElementById('user-settings-modal');
    const content = document.getElementById('settings-modal-content');
    const message = document.getElementById('settings-message');
    
    modal.classList.remove('opacity-100');
    modal.classList.add('pointer-events-none');
    content.classList.add('scale-95');
    content.classList.remove('scale-100');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        message.classList.add('hidden');
        document.getElementById('user-settings-form').reset();
        document.body.style.overflow = 'auto';
    }, 500);
}

document.getElementById('user-settings-form').onsubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const message = document.getElementById('settings-message');
    
    if (formData.get('password') !== formData.get('confirm_password')) {
        message.textContent = 'Las contraseñas no coinciden';
        message.className = 'text-red-500 bg-red-500/10 py-2 px-4 rounded-sm text-[10px] font-bold uppercase tracking-widest text-center';
        message.classList.remove('hidden');
        return;
    }

    try {
        const response = await fetch('../Controller/AuthController.php?action=update_profile', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        
        if (result.success) {
            message.textContent = 'Perfil actualizado con éxito';
            message.className = 'text-green-500 bg-green-500/10 py-2 px-4 rounded-sm text-[10px] font-bold uppercase tracking-widest text-center';
            message.classList.remove('hidden');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            message.textContent = result.error || 'Error al actualizar';
            message.className = 'text-red-500 bg-red-500/10 py-2 px-4 rounded-sm text-[10px] font-bold uppercase tracking-widest text-center';
            message.classList.remove('hidden');
        }
    } catch (error) {
        message.textContent = 'Error de conexión';
        message.className = 'text-red-500 bg-red-500/10 py-2 px-4 rounded-sm text-[10px] font-bold uppercase tracking-widest text-center';
        message.classList.remove('hidden');
    }
};

window.togglePasswordVisibility = function(inputId) {
    const input = document.getElementById(inputId);
    if (!input) return;
    
    const btn = input.parentElement.querySelector(".password-toggle-btn");
    const type = input.getAttribute("type") === "password" ? "text" : "password";
    input.setAttribute("type", type);
    
    // Cambiar icono (Añadir/Quitar tachado)
    if (btn) {
        const slashPath = btn.querySelector(".eye-slash");
        if (type === "text") {
            slashPath.classList.remove("hidden");
        } else {
            slashPath.classList.add("hidden");
        }
    }
};
