document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('movie-admin-modal');
    const content = document.getElementById('movie-modal-content');
    const closeBtn = document.getElementById('close-movie-admin-modal');
    const filterGenre = document.getElementById('filter-genre');
    const movieGrid = document.querySelector('main ul');
    
    if (filterGenre && movieGrid) {
        filterGenre.addEventListener('change', async (e) => {
            const generoId = e.target.value;
            movieGrid.style.opacity = '0.5';
            movieGrid.style.pointerEvents = 'none';
            
            try {
                const response = await fetch(`../Controller/PelisController.php?action=renderGrid&genero_id=${generoId}`);
                const html = await response.text();
                movieGrid.innerHTML = html;
            } catch (error) {
                console.error("Error filtrando películas:", error);
            } finally {
                movieGrid.style.opacity = '1';
                movieGrid.style.pointerEvents = 'auto';
            }
        });
    }

    if (!modal || !content) return;

    // Función Global para abrir el modal
    window.openMovieAdmin = function(data) {
        // Inyectar datos (Alineado con los IDs de movie_admin_modal.php)
        const banner = document.getElementById('movie-admin-banner');
        const poster = document.getElementById('movie-admin-poster');
        const title = document.getElementById('movie-admin-title');
        const director = document.getElementById('movie-admin-director');
        const rating = document.getElementById('movie-admin-rating');
        const genres = document.getElementById('movie-admin-genres');
        const functions = document.getElementById('movie-admin-functions');

        if (title) title.textContent = data.titulo;
        if (director) director.textContent = data.director;
        if (banner) banner.src = data.imagen || '';
        if (poster) poster.src = data.imagen || '';
        if (rating) rating.textContent = data.clasificacion;

        // Limpiar y cargar géneros
        if (genres) {
            genres.innerHTML = '';
            if (data.genero) {
                const span = document.createElement('span');
                span.className = "px-3 py-1 bg-[#E50914] text-white text-[9px] font-black uppercase tracking-widest rounded-full";
                span.textContent = data.genero;
                genres.appendChild(span);
            }
        }

        // Limpiar y cargar funciones (Simulado o desde data si existe)
        if (functions) {
            functions.innerHTML = '<p class="text-zinc-600 text-[10px] font-bold uppercase tracking-widest">No hay funciones programadas</p>';
        }

        // Animación de entrada
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        requestAnimationFrame(() => {
            modal.classList.add('opacity-100');
            modal.classList.remove('pointer-events-none');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        });
        
        document.body.style.overflow = 'hidden';
    };

    window.closeMovieModal = function() {
        modal.classList.add('opacity-0');
        modal.classList.remove('opacity-100');
        content.classList.add('scale-95');
        content.classList.remove('scale-100');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    };

    if (closeBtn) closeBtn.addEventListener('click', closeMovieModal);

    // Cerrar al click afuera
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeMovieModal();
    });

    // Cerrar con ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === "Escape" && !modal.classList.contains('hidden')) {
            closeMovieModal();
        }
    });
});
