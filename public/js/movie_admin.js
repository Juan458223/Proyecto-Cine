// Variables de estado globales para sincronización
let currentMoviePage = 1;
let currentMovieGenre = "";

/**
 * Función Global para abrir el modal de detalles de película
 * @param {Object} data - Datos de la película (JSON)
 */
window.openMovieAdmin = function(data) {
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

    if (title) title.textContent = data.titulo;
    if (director) director.textContent = `Dirigida por ${data.director}`;
    if (banner) banner.src = data.imagen || '';
    if (poster) poster.src = data.imagen || '';
    if (rating) rating.textContent = data.clasificacion;

    // Géneros (Badge Moderno)
    if (genres) {
        genres.innerHTML = '';
        if (data.genero) {
            const span = document.createElement('span');
            span.className = "px-4 py-1.5 bg-[#E50914] text-white text-[10px] font-bold rounded-full shadow-lg shadow-red-900/20";
            span.textContent = data.genero;
            genres.appendChild(span);
        }
    }

    // Funciones
    if (functions) {
        functions.innerHTML = '<p class="text-zinc-600 text-xs font-medium italic">Próximamente más funciones...</p>';
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
