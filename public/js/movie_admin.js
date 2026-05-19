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
        // Inyectar datos
        document.getElementById('modal-movie-title').textContent = data.titulo;
        document.getElementById('modal-movie-subtitle').textContent = data.genero;
        document.getElementById('modal-header-bg').style.backgroundImage = `url('${data.imagen}')`;
        
        document.getElementById('modal-info-director').textContent = data.director;
        document.getElementById('modal-info-elenco').textContent = data.protagonistas;
        document.getElementById('modal-info-clasif').textContent = data.clasificacion;
        document.getElementById('modal-info-genero-ui').textContent = data.genero;

        // Animación de entrada
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
        
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
