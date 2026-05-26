// Variables de estado globales para sincronización
let currentMoviePage = 1;
let currentMovieGenre = "";
let currentModalFunctions = []; // Almacén para paginación interna

/**
 * Función Global para abrir el modal de detalles de película
 */
window.openMovieAdmin = function(data) {
    console.log("Abriendo modal con datos:", data);
    
    if (typeof data === 'string') {
        try { data = JSON.parse(data); } catch (e) { console.error("Error parseando JSON:", e); return; }
    }

    const modal = document.getElementById('movie-admin-modal');
    const content = document.getElementById('movie-admin-content');
    if (!modal || !content) {
        console.error("No se encontró el modal o el contenido");
        return;
    }

    // Helper Capitalize local
    const cap = (val) => {
        if (!val || typeof val !== 'string') return '';
        const low = val.toLowerCase().trim();
        return low.charAt(0).toUpperCase() + low.slice(1);
    };

    // Inyectar datos básicos
    const titleEl = document.getElementById('movie-admin-title');
    const directorEl = document.getElementById('movie-admin-director');
    const bannerEl = document.getElementById('movie-admin-banner');
    const posterEl = document.getElementById('movie-admin-poster');
    const ratingEl = document.getElementById('movie-admin-rating');
    const castEl = document.getElementById('movie-admin-cast');

    if (titleEl) titleEl.textContent = cap(data.titulo);
    if (directorEl) directorEl.textContent = `Dirigida por ${cap(data.director)}`;
    if (bannerEl) bannerEl.src = data.imagen || '';
    if (posterEl) posterEl.src = data.imagen || '';
    if (ratingEl) ratingEl.textContent = cap(data.clasificacion);
    if (castEl) castEl.textContent = data.protagonistas || 'Sin información de reparto';

    const genres = document.getElementById('movie-admin-genres');
    if (genres) {
        genres.innerHTML = '';
        if (data.genero) {
            const span = document.createElement('span');
            span.className = "px-4 py-1.5 bg-[#E50914] text-white text-[10px] font-bold rounded-full shadow-lg";
            span.textContent = cap(data.genero);
            genres.appendChild(span);
        }
    }

    // Preparar Funciones
    currentModalFunctions = [];
    if (Array.isArray(data.funciones)) {
        currentModalFunctions = data.funciones;
    } else if (data.funciones && typeof data.funciones === 'object') {
        currentModalFunctions = Object.values(data.funciones);
    }

    renderModalFunctions(1);

    // Mostrar Modal
    modal.classList.remove('hidden', 'pointer-events-none');
    modal.classList.add('flex');
    
    // Forzar reflow para animación
    void modal.offsetWidth;
    
    modal.classList.add('opacity-100');
    content.classList.remove('scale-95', 'opacity-0');
    content.classList.add('scale-100', 'opacity-100');
    
    document.body.style.overflow = 'hidden';
};

function renderModalFunctions(page) {
    const container = document.getElementById('movie-admin-functions');
    const pagContainer = document.getElementById('movie-functions-pagination');
    if (!container) return;

    const limit = 8;
    const start = (page - 1) * limit;
    const paginated = currentModalFunctions.slice(start, start + limit);
    const totalPages = Math.ceil(currentModalFunctions.length / limit);

    const cap = (val) => {
        if (!val || typeof val !== 'string') return '';
        const low = val.toLowerCase().trim();
        return low.charAt(0).toUpperCase() + low.slice(1);
    };

    container.innerHTML = '';
    
    if (paginated.length > 0) {
        paginated.forEach(f => {
            const dateStr = f.fecha_hora.replace(' ', 'T');
            const date = new Date(dateStr);
            const time = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const day = cap(date.toLocaleDateString([], { weekday: 'short', day: 'numeric', month: 'short' }));

            const div = document.createElement('div');
            div.className = "bg-zinc-900/50 border border-white/5 p-4 rounded-xl space-y-2 group/func hover:border-[#E50914] transition-all";
            div.innerHTML = `
                <div class="flex justify-between items-start">
                    <span class="text-[#E50914] text-[10px] font-black tracking-widest">${day}</span>
                    <span class="text-white font-black text-lg">${time}</span>
                </div>
                <div class="space-y-1">
                    <p class="text-white text-xs font-bold tracking-tight">${cap(f.cine_nombre)}</p>
                    <p class="text-zinc-500 text-[10px] font-medium">${cap(f.cine_direccion)}</p>
                    <p class="text-zinc-600 text-[9px] font-bold">Sala ${f.numero_sala || f.sala_id_sala}</p>
                </div>
            `;
            container.appendChild(div);
        });
    } else {
        container.innerHTML = '<p class="text-zinc-600 text-[10px] font-bold italic py-4">Sin funciones programadas</p>';
    }

    // Render Paginación
    if (pagContainer) {
        pagContainer.innerHTML = '';
        if (totalPages > 1) {
            // Botón Anterior
            const prevBtn = document.createElement('button');
            prevBtn.className = `w-8 h-8 rounded-lg flex items-center justify-center transition-all ${page <= 1 ? 'opacity-30 pointer-events-none' : 'bg-zinc-900 text-zinc-500 hover:text-white hover:bg-zinc-800'}`;
            prevBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>';
            prevBtn.onclick = () => renderModalFunctions(page - 1);
            pagContainer.appendChild(prevBtn);

            // Números
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.className = `w-8 h-8 rounded-lg text-[10px] font-black transition-all ${i === page ? 'bg-[#E50914] text-white shadow-lg shadow-red-900/20' : 'bg-zinc-900 text-zinc-500 hover:text-white hover:bg-zinc-800'}`;
                btn.textContent = i;
                btn.onclick = () => renderModalFunctions(i);
                pagContainer.appendChild(btn);
            }

            // Botón Siguiente
            const nextBtn = document.createElement('button');
            nextBtn.className = `w-8 h-8 rounded-lg flex items-center justify-center transition-all ${page >= totalPages ? 'opacity-30 pointer-events-none' : 'bg-zinc-900 text-zinc-500 hover:text-white hover:bg-zinc-800'}`;
            nextBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>';
            nextBtn.onclick = () => renderModalFunctions(page + 1);
            pagContainer.appendChild(nextBtn);
        }
    }
}

/**
 * Cierra el modal de detalles
 */
window.closeMovieAdmin = function() {
    const modal = document.getElementById('movie-admin-modal');
    const content = document.getElementById('movie-admin-content');
    if (!modal) return;

    modal.classList.remove('opacity-100');
    if (content) content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden', 'pointer-events-none');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }, 500);
};

/**
 * Carga las películas sincronizando género y página
 */
async function loadMovies(page = 1, genre = "") {
    const movieGrid = document.getElementById('movie-grid');
    const paginationContainer = document.getElementById('movie-pagination');
    const section = document.getElementById('section-cartelera');
    
    if (!movieGrid) return;
    
    movieGrid.style.opacity = '0.5';
    movieGrid.style.pointerEvents = 'none';
    
    try {
        const gridResponse = await fetch(`../Controller/PelisController.php?action=renderGrid&genero_id=${genre}&page=${page}`);
        movieGrid.innerHTML = await gridResponse.text();
        
        const pagResponse = await fetch(`../Controller/PelisController.php?action=renderPagination&genero_id=${genre}&page=${page}`);
        if (paginationContainer) paginationContainer.innerHTML = await pagResponse.text();
        
        currentMoviePage = page;
        currentMovieGenre = genre;
    } catch (error) {
        console.error("Error al sincronizar la cartelera:", error);
    } finally {
        movieGrid.style.opacity = '1';
        movieGrid.style.pointerEvents = 'auto';
        if (section) window.scrollTo({ top: section.offsetTop - 100, behavior: 'smooth' });
    }
}

window.changePage = function(page) {
    loadMovies(page, currentMovieGenre);
};

document.addEventListener('DOMContentLoaded', () => {
    const filterGenre = document.getElementById('filter-genre');
    if (filterGenre) {
        filterGenre.addEventListener('change', (e) => {
            loadMovies(1, e.target.value);
        });
    }

    const modal = document.getElementById('movie-admin-modal');
    if (modal) {
        modal.onclick = (e) => {
            if (e.target === modal) window.closeMovieAdmin();
        };
    }
});
