/**
 * Cine First - Lógica de Películas (Nueva Versión)
 * Implementación optimizada para Arquitectura Multicapa
 */

// Estado global del módulo de películas
const movieState = {
    // Estado de la galería
    currentPage: 1,
    currentGenre: null,
    movies: [],
    totalPages: 1,
    
    // Estado del modal de detalles
    selectedMovie: null,
    currentFunctionPage: 1,
    functionsPerPage: 6
};

/**
 * Utilería: Capitalización de textos
 */
const cap = (str) => {
    if (!str || typeof str !== 'string') return '';
    const low = str.toLowerCase().trim();
    return low.charAt(0).toUpperCase() + low.slice(1);
};

/**
 * Carga las películas desde el controlador
 */
async function loadMovies(page = 1, genreId = null) {
    movieState.currentPage = page;
    movieState.currentGenre = genreId;

    const movieGrid = document.getElementById('movie-grid');
    if (!movieGrid) return;

    movieGrid.style.opacity = '0.5';

    try {
        let url = `../Controller/PeliculaController.php?action=list&page=${page}`;
        if (genreId) url += `&genero_id=${genreId}`;

        const response = await fetch(url);
        const result = await response.json();

        movieState.movies = result.data || [];
        movieState.totalPages = result.pages || 1;

        renderMovieGrid();
        renderPagination();
    } catch (error) {
        console.error("Error al cargar películas:", error);
    } finally {
        movieGrid.style.opacity = '1';
    }
}

/**
 * Renderiza la cuadrícula de películas
 */
function renderMovieGrid() {
    const movieGrid = document.getElementById('movie-grid');
    if (!movieGrid) return;

    if (movieState.movies.length === 0) {
        movieGrid.innerHTML = `
            <div class="col-span-full py-20 text-center">
                <p class="text-zinc-500 font-medium">No se encontraron películas.</p>
            </div>
        `;
        return;
    }

    movieGrid.innerHTML = movieState.movies.map(movie => {
        // Preparamos los datos para pasarlos al modal (Escapamos comillas para el onclick)
        const movieData = JSON.stringify(movie).replace(/'/g, "\\'");
        
        return `
            <li class="group flex flex-col cursor-pointer" onclick='window.openMovieAdmin(${movieData})'>
                <div class="relative aspect-[2/3] overflow-hidden rounded-xl bg-zinc-900 shadow-2xl transition-all duration-500 group-hover:shadow-[#E50914]/20">
                    <img src="${movie.url_image}" alt="${movie.titulo}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy" onerror="this.onerror=null; this.src='../../public/img/logo.svg'; this.classList.add('p-8', 'opacity-20');">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="absolute top-4 left-4">
                        <span class="bg-black/60 backdrop-blur-md text-white text-[10px] font-bold px-2 py-1 rounded-md border border-white/10">
                            ${movie.clasificacion}
                        </span>
                    </div>
                </div>
                <div class="mt-4 px-1">
                    <h3 class="text-white font-bold text-sm group-hover:text-[#E50914] transition-colors truncate">${movie.titulo}</h3>
                    <p class="text-zinc-500 text-[10px] mt-1">${movie.director}</p>
                </div>
            </li>
        `;
    }).join('');
}

/**
 * Renderiza los controles de paginación de la galería
 */
function renderPagination() {
    const paginationContainer = document.getElementById('movie-pagination');
    if (!paginationContainer) return;

    let html = '<div class="flex items-center justify-center gap-2 mt-12">';
    
    html += `
        <button onclick="loadMovies(${movieState.currentPage - 1}, ${movieState.currentGenre})" 
                class="p-2 rounded-lg bg-zinc-900 border border-zinc-800 text-white hover:bg-[#E50914] transition-all ${movieState.currentPage <= 1 ? 'opacity-20 pointer-events-none' : ''}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    `;

    for (let i = 1; i <= movieState.totalPages; i++) {
        html += `
            <button onclick="loadMovies(${i}, ${movieState.currentGenre})" 
                    class="w-10 h-10 rounded-lg border font-bold text-xs transition-all ${i === movieState.currentPage ? 'bg-[#E50914] text-white border-[#E50914]' : 'bg-zinc-900 text-zinc-500 border-zinc-800 hover:text-white'}">
                ${i}
            </button>
        `;
    }

    html += `
        <button onclick="loadMovies(${movieState.currentPage + 1}, ${movieState.currentGenre})" 
                class="p-2 rounded-lg bg-zinc-900 border border-zinc-800 text-white hover:bg-[#E50914] transition-all ${movieState.currentPage >= movieState.totalPages ? 'opacity-20 pointer-events-none' : ''}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    `;

    html += '</div>';
    paginationContainer.innerHTML = html;
}

/**
 * Abre el modal de detalles de la película
 */
window.openMovieAdmin = function(data) {
    if (typeof data === 'string') {
        try { data = JSON.parse(data); } catch (e) { return; }
    }

    movieState.selectedMovie = data;
    movieState.currentFunctionPage = 1;

    const modal = document.getElementById('movie-admin-modal');
    const content = document.getElementById('movie-admin-content');
    if (!modal || !content) return;

    // Poblar datos estáticos
    document.getElementById('movie-admin-title').textContent = cap(data.titulo);
    document.getElementById('movie-admin-director').textContent = `Dirigida por ${cap(data.director)}`;
    
    const banner = document.getElementById('movie-admin-banner');
    const poster = document.getElementById('movie-admin-poster');
    
    banner.src = data.url_image || '';
    banner.onerror = function() { this.onerror=null; this.src='../../public/img/logo.svg'; this.classList.add('p-20', 'opacity-10'); };
    
    poster.src = data.url_image || '';
    poster.onerror = function() { this.onerror=null; this.src='../../public/img/logo.svg'; this.classList.add('p-8', 'opacity-20'); };

    document.getElementById('movie-admin-rating').textContent = cap(data.clasificacion);
    document.getElementById('movie-admin-cast').textContent = data.protagonistas || 'Sin información de reparto';

    // Géneros
    const genresContainer = document.getElementById('movie-admin-genres');
    genresContainer.innerHTML = '';
    if (data.genero) {
        genresContainer.innerHTML = `<span class="px-4 py-1.5 bg-[#E50914] text-white text-[10px] font-bold rounded-full shadow-lg">${cap(data.genero)}</span>`;
    }

    // Funciones
    renderModalFunctions(1);

    // Animación de apertura
    modal.classList.remove('hidden', 'pointer-events-none');
    modal.classList.add('flex');
    
    requestAnimationFrame(() => {
        modal.classList.add('opacity-100');
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    });

    document.body.style.overflow = 'hidden';
};

/**
 * Renderiza las funciones dentro del modal con paginación
 */
function renderModalFunctions(page) {
    movieState.currentFunctionPage = page;
    const container = document.getElementById('movie-admin-functions');
    const paginationContainer = document.getElementById('movie-functions-pagination');
    if (!container) return;

    const functions = movieState.selectedMovie.funciones || [];
    const totalPages = Math.ceil(functions.length / movieState.functionsPerPage);
    const start = (page - 1) * movieState.functionsPerPage;
    const paginated = functions.slice(start, start + movieState.functionsPerPage);

    container.innerHTML = paginated.length > 0 
        ? paginated.map(f => {
            const date = new Date(f.fecha_hora.replace(' ', 'T'));
            const time = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const day = cap(date.toLocaleDateString([], { weekday: 'short', day: 'numeric', month: 'short' }));

            return `
                <div class="bg-zinc-900/50 border border-white/5 p-4 rounded-xl space-y-2 group/func hover:border-[#E50914] transition-all">
                    <div class="flex justify-between items-start">
                        <span class="text-[#E50914] text-[10px] font-black tracking-widest">${day}</span>
                        <span class="text-white font-black text-lg">${time}</span>
                    </div>
                    <div class="space-y-1">
                        <p class="text-white text-xs font-bold tracking-tight">${cap(f.cine_nombre || '')}</p>
                        <p class="text-zinc-500 text-[10px] font-medium">${cap(f.cine_calle || '')} ${f.cine_numero || ''}</p>
                        <p class="text-zinc-600 text-[9px] font-bold">Sala ${f.numero_sala || f.sala_id_sala || ''}</p>
                    </div>
                </div>
            `;
        }).join('')
        : '<p class="text-zinc-600 text-[10px] font-bold italic py-4">Sin funciones programadas</p>';

    // Paginación de funciones
    if (paginationContainer) {
        paginationContainer.innerHTML = '';
        if (totalPages > 1) {
            let html = `
                <button onclick="renderModalFunctions(${page - 1})" 
                        class="w-8 h-8 rounded-lg flex items-center justify-center transition-all ${page <= 1 ? 'opacity-30 pointer-events-none' : 'bg-zinc-900 text-zinc-500 hover:text-white hover:bg-zinc-800'}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            `;

            for (let i = 1; i <= totalPages; i++) {
                html += `
                    <button onclick="renderModalFunctions(${i})" 
                            class="w-8 h-8 rounded-lg text-[10px] font-black transition-all ${i === page ? 'bg-[#E50914] text-white shadow-lg' : 'bg-zinc-900 text-zinc-500 hover:text-white hover:bg-zinc-800'}">
                        ${i}
                    </button>
                `;
            }

            html += `
                <button onclick="renderModalFunctions(${page + 1})" 
                        class="w-8 h-8 rounded-lg flex items-center justify-center transition-all ${page >= totalPages ? 'opacity-30 pointer-events-none' : 'bg-zinc-900 text-zinc-500 hover:text-white hover:bg-zinc-800'}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            `;
            paginationContainer.innerHTML = html;
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

// Event Listeners iniciales
document.addEventListener('DOMContentLoaded', () => {
    loadMovies();
    
    // Cerrar modal al hacer clic fuera
    const modal = document.getElementById('movie-admin-modal');
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) window.closeMovieAdmin();
        });
    }
});
