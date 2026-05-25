// Variables de estado globales para sincronización
let currentMoviePage = 1;
let currentMovieGenre = "";

/**
 * Función Global para abrir el modal de detalles de película
 * @param {Object|string} data - Datos de la película (JSON)
 */
window.openMovieAdmin = function(data) {
    // Depuración (puedes ver esto en la consola del navegador F12)
    console.log("Datos recibidos en el modal:", data);

    // Si recibimos una cadena, intentamos parsearla
    if (typeof data === 'string') {
        try {
            data = JSON.parse(data);
        } catch (e) {
            console.error("Error al parsear datos de la película:", e);
            return;
        }
    }

    const modal = document.getElementById('movie-admin-modal');
    const content = document.getElementById('movie-admin-content');
    
    if (!modal || !content) {
        console.error("No se encontró el modal o su contenido. Revisa movie_admin_modal.php");
        return;
    }

    // Inyectar datos con Capitalización y Limpieza
    const banner = document.getElementById('movie-admin-banner');
    const poster = document.getElementById('movie-admin-poster');
    const title = document.getElementById('movie-admin-title');
    const director = document.getElementById('movie-admin-director');
    const rating = document.getElementById('movie-admin-rating');
    const genres = document.getElementById('movie-admin-genres');
    const functions = document.getElementById('movie-admin-functions');
    const cast = document.getElementById('movie-admin-cast');

    // Helper para Capitalización (Primera Mayúscula, resto minúscula)
    const capitalize = (val) => {
        if (!val || typeof val !== 'string') return '';
        const low = val.toLowerCase().trim();
        if (low.length === 0) return '';
        return low.charAt(0).toUpperCase() + low.slice(1);
    };

    if (title) title.textContent = capitalize(data.titulo);
    if (director) director.textContent = `Dirigida por ${capitalize(data.director)}`;
    if (banner) banner.src = data.imagen || '';
    if (poster) poster.src = data.imagen || '';
    if (rating) rating.textContent = capitalize(data.clasificacion);
    if (cast) cast.textContent = data.protagonistas || 'Información de reparto no disponible';

    // Géneros (Badge Moderno)
    if (genres) {
        genres.innerHTML = '';
        if (data.genero) {
            const span = document.createElement('span');
            span.className = "px-4 py-1.5 bg-[#E50914] text-white text-[10px] font-bold rounded-full shadow-lg shadow-red-900/20";
            span.textContent = capitalize(data.genero);
            genres.appendChild(span);
        }
    }

    // Funciones
    if (functions) {
        functions.innerHTML = '';
        // Normalizar funciones a Array
        let funcs = [];
        if (Array.isArray(data.funciones)) {
            funcs = data.funciones;
        } else if (data.funciones && typeof data.funciones === 'object') {
            funcs = Object.values(data.funciones);
        }
        
        if (funcs.length > 0) {
            funcs.forEach(f => {
                // Formatear fecha para compatibilidad (Sustituir espacio por T)
                const dateStr = f.fecha_hora.replace(' ', 'T');
                const date = new Date(dateStr);
                
                const time = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                const dayRaw = date.toLocaleDateString([], { weekday: 'short', day: 'numeric', month: 'short' });
                const day = capitalize(dayRaw);
                
                const div = document.createElement('div');
                div.className = "bg-zinc-900/50 border border-white/5 p-4 rounded-xl space-y-2 group/func hover:border-[#E50914] transition-all";
                div.innerHTML = `
                    <div class="flex justify-between items-start">
                        <span class="text-[#E50914] text-[10px] font-black tracking-widest">${day}</span>
                        <span class="text-white font-black text-lg">${time}</span>
                    </div>
                    <div class="space-y-1">
                        <p class="text-white text-xs font-bold tracking-tight">${capitalize(f.cine_nombre)}</p>
                        <p class="text-zinc-500 text-[10px] font-medium">${capitalize(f.cine_direccion)}</p>
                        ${f.cine_telefono ? `<p class="text-zinc-600 text-[9px] font-bold tracking-tighter">TEL: ${f.cine_telefono}</p>` : ''}
                    </div>
                `;
                functions.appendChild(div);
            });
        } else {
            functions.innerHTML = '<p class="text-zinc-600 text-[10px] font-bold tracking-widest italic opacity-50">Próximamente más funciones...</p>';
        }
    }

    // Mostrar Modal con Animación
    modal.classList.remove('hidden', 'pointer-events-none');
    modal.classList.add('flex');
    
    setTimeout(() => {
        modal.classList.add('opacity-100');
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
    
    document.body.style.overflow = 'hidden';
};

/**
 * Cierra el modal de detalles
 */
window.closeMovieAdmin = function() {
    const modal = document.getElementById('movie-admin-modal');
    const content = document.getElementById('movie-admin-content');
    
    if (!modal) return;

    modal.classList.remove('opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    content.classList.remove('scale-100', 'opacity-100');
    
    setTimeout(() => {
        modal.classList.add('hidden', 'pointer-events-none');
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
        // 1. Cargar el Grid de Películas
        const gridResponse = await fetch(`../Controller/PelisController.php?action=renderGrid&genero_id=${genre}&page=${page}`);
        movieGrid.innerHTML = await gridResponse.text();
        
        // 2. Cargar la Paginación
        const pagResponse = await fetch(`../Controller/PelisController.php?action=renderPagination&genero_id=${genre}&page=${page}`);
        if (paginationContainer) paginationContainer.innerHTML = await pagResponse.text();
        
        // Actualizar estado global
        currentMoviePage = page;
        currentMovieGenre = genre;

    } catch (error) {
        console.error("Error al sincronizar la cartelera:", error);
    } finally {
        movieGrid.style.opacity = '1';
        movieGrid.style.pointerEvents = 'auto';
        
        // Scroll suave al inicio de la sección para mejorar UX
        if (section) window.scrollTo({ top: section.offsetTop - 100, behavior: 'smooth' });
    }
}

// Función global para los botones de la paginación
window.changePage = function(page) {
    loadMovies(page, currentMovieGenre);
};

document.addEventListener('DOMContentLoaded', () => {
    const filterGenre = document.getElementById('filter-genre');
    const modal = document.getElementById('movie-admin-modal');

    // Listener para el filtro por género
    if (filterGenre) {
        filterGenre.addEventListener('change', (e) => {
            loadMovies(1, e.target.value);
        });
    }

    // Cerrar al hacer clic fuera del contenido
    if (modal) {
        modal.onclick = (e) => {
            if (e.target === modal) window.closeMovieAdmin();
        };
    }
});
