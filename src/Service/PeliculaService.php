<?php
require_once __DIR__ . '/../Dao/ProtagonistaDAO.php';
require_once __DIR__ . '/../Dao/PeliculaProtagonistaDAO.php';
require_once __DIR__ . '/../Dao/GeneroDAO.php';
require_once __DIR__ . '/../Dao/PeliculasDAO.php';
require_once __DIR__ . '/../Model/Pelicula.php';
require_once __DIR__ . '/../Model/Genero.php';

class PeliculaService {
    private $peliculaDAO;
    private $protagonistaDAO;
    private $generoDAO;
    private $pelihasprotaDAO;

    public function __construct(){
        $this->peliculaDAO = new peliculaDAO();
        $this->protagonistaDAO = new ProtagonistaDAO();
        $this->generoDAO = new GeneroDAO();
        $this->pelihasprotaDAO = new PeliculaProtagonistaDAO();
    }

    public function listarGeneros() {
        $generoDAO = $this->generoDAO->obtenerTodos();
        $generos = [];
        foreach ($generoDAO as $gen) {
            $generos[] = new Genero($gen['id_genero'], $gen['nombre_genero']);
        }
        return $generos;
    }

    public function listarPeliculas($genero_id = null){
        if ($genero_id) {
            $movieData = $this->peliculaDAO->capturarPelisPorGenero($genero_id);
        } else {
            $movieData = $this->peliculaDAO->capturarPelis();
        }
        $protaData = $this->protagonistaDAO->obtenerTodos();
        $pelihasprota = $this->pelihasprotaDAO->obtenerTodos();

        $peliculas = [];
        if($movieData){
            foreach ($movieData as $pelis) {
                $id_pelicula = $pelis['id_pelicula'];
                $titulo = $pelis['titulo'];
                $director = $pelis['director'];
                $clasificacion = $pelis['clasificacion'];
                if($pelis['clasificacion']==0){
                    $clasificacion = 'Todo público';
                }else{
                    $clasificacion = '+'.$pelis['clasificacion'];
                }
                $url_image = $pelis['url_image'];
                if($pelis['genero_id_genero'] == '1'){
                    $genero_id = 'Dibujos';
                }elseif($pelis['genero_id_genero'] == '2'){
                    $genero_id = 'Comedia';
                }elseif($pelis['genero_id_genero'] == '3'){
                    $genero_id = 'Drama';
                }else{
                    $genero_id = 'No especificado';
                }
                $protagonistas = [];
                foreach($pelihasprota as $php){
                    
                    if($php['pelicula_id_pelicula']==$id_pelicula){
                        
                        foreach($protaData as $protas){
                            if($protas['id_actor']==$php['protagonistas_id_protagonista']){
                                $protagonistas[] = $protas['nombre'];
                            }
                        }
                    }
                }
                $pelicula = new Pelicula(
                    $id_pelicula,
                    $titulo, 
                    $director, 
                    $clasificacion, 
                    $url_image, 
                    $genero_id, 
                    $protagonistas
                );
                $peliculas[] = $pelicula;
            }
        }
        return $peliculas;
    }

    public function obtenerPeliculasPaginadas($limit, $offset, $genero_id = null) {
        $movieData = $this->peliculaDAO->obtenerPeliculasPaginadas($limit, $offset, $genero_id);
        $protaData = $this->protagonistaDAO->obtenerTodos();
        $pelihasprota = $this->pelihasprotaDAO->obtenerTodos();

        $peliculas = [];
        if($movieData){
            foreach ($movieData as $pelis) {
                $id_pelicula = $pelis['id_pelicula'];
                $titulo = mb_convert_case($pelis['titulo'], MB_CASE_TITLE, "UTF-8");
                $director = mb_convert_case($pelis['director'], MB_CASE_TITLE, "UTF-8");
                $clasificacion = ($pelis['clasificacion'] == 0) ? 'Todo público' : '+'.$pelis['clasificacion'];
                $url_image = $pelis['url_image'];
                $genero_nombre = mb_convert_case($pelis['genero_nombre'] ?? 'No especificado', MB_CASE_TITLE, "UTF-8");

                $protagonistas = [];
                foreach($pelihasprota as $php){
                    if($php['pelicula_id_pelicula'] == $id_pelicula){
                        foreach($protaData as $protas){
                            if($protas['id_actor'] == $php['protagonistas_id_protagonista']){
                                $protagonistas[] = mb_convert_case($protas['nombre'], MB_CASE_TITLE, "UTF-8");
                            }
                        }
                    }
                }
                $pelicula = new Pelicula($id_pelicula, $titulo, $director, $clasificacion, $url_image, $genero_nombre, $protagonistas);
                $peliculas[] = $pelicula;
            }
        }
        return $peliculas;
    }

    public function contarPeliculas($genero_id = null) {
        return $this->peliculaDAO->contarPeliculas($genero_id);
    }

    public function obtenerTablas($genero_id = null, $page = 1, $limit = 12) {
        $offset = ($page - 1) * $limit;
        $peliculas = $this->obtenerPeliculasPaginadas($limit, $offset, $genero_id);

        if (empty($peliculas)) {
            echo "<p class='col-span-full text-center text-zinc-500 py-10'>No se encontraron películas.</p>";
            return;
        }

        foreach ($peliculas as $peli) {
            $json_data = htmlspecialchars(json_encode([
                'titulo' => $peli->getTitulo(),
                'director' => $peli->getDirector(),
                'clasificacion' => $peli->getClasificacion(),
                'imagen' => $peli->getUrlImage(),
                'genero' => $peli->getGenero(),
                'protagonistas' => implode(', ', $peli->getProtagonistas())
            ]), ENT_QUOTES, 'UTF-8');

            echo "
            <li class='group flex flex-col cursor-pointer' onclick='window.openMovieAdmin($json_data)'>
                <div class='relative aspect-[2/3] overflow-hidden rounded-md bg-zinc-900 shadow-lg shadow-black/50 transition-all duration-500 group-hover:shadow-[#E50914]/10 group-hover:shadow-2xl'>
                    <!-- Badge de Clasificación Mejorado -->
                    <div class='absolute top-3 left-3 z-20'>
                        <span class='bg-black/60 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-sm border border-white/10'>
                            {$peli->getClasificacion()}
                        </span>
                    </div>

                    <!-- Imagen con efecto Zoom -->
                    <img 
                        src='{$peli->getUrlImage()}' 
                        alt='{$peli->getTitulo()}' 
                        class='w-full h-full object-cover transition-transform duration-1000 ease-out group-hover:scale-110'
                        loading='lazy'
                    >

                    <!-- Overlay Inteligente (Sutil) -->
                    <div class='absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500'></div>
                    
                    <!-- Borde de acento inferior en hover -->
                    <div class='absolute bottom-0 left-0 w-0 h-1 bg-[#E50914] transition-all duration-500 group-hover:w-full'></div>
                </div>

                <!-- Información con jerarquía visual y Título en una sola línea -->
                <div class='mt-4 space-y-1 px-1'>
                    <h3 class='text-white font-bold text-sm md:text-base leading-tight truncate group-hover:text-[#E50914] transition-colors duration-300' title='{$peli->getTitulo()}'>
                        {$peli->getTitulo()}
                    </h3>
                    <div class='flex items-center gap-2'>
                        <span class='text-zinc-500 text-[10px] font-medium'>{$peli->getGenero()}</span>
                        <span class='w-1 h-1 rounded-full bg-zinc-800'></span>
                        <span class='text-zinc-600 text-[10px] font-medium italic truncate'>{$peli->getDirector()}</span>
                    </div>
                </div>
            </li>";
        }
    }

    public function obtenerPaginacion($genero_id = null, $page = 1, $limit = 12) {
        $totalPeliculas = $this->contarPeliculas($genero_id);
        $totalPages = ceil($totalPeliculas / $limit);
        
        if ($totalPages <= 1) return;

        echo "<div class='flex items-center justify-center gap-4 mt-16'>";
        
        // Botón Anterior
        $prevDisabled = ($page <= 1) ? 'opacity-30 pointer-events-none' : '';
        echo "<button onclick='changePage(".($page - 1).")' class='p-3 rounded-full bg-zinc-900 border border-zinc-800 text-white hover:bg-[#E50914] transition-all {$prevDisabled}'>
                <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 19l-7-7 7-7'/></svg>
              </button>";

        // Números de Página
        for ($i = 1; $i <= $totalPages; $i++) {
            $activeClass = ($i == $page) ? 'bg-[#E50914] text-white border-[#E50914]' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white';
            echo "<button onclick='changePage({$i})' class='w-10 h-10 rounded-xl border font-bold text-xs transition-all {$activeClass}'>{$i}</button>";
        }

        // Botón Siguiente
        $nextDisabled = ($page >= $totalPages) ? 'opacity-30 pointer-events-none' : '';
        echo "<button onclick='changePage(".($page + 1).")' class='p-3 rounded-full bg-zinc-900 border border-zinc-800 text-white hover:bg-[#E50914] transition-all {$nextDisabled}'>
                <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'/></svg>
              </button>";

        echo "</div>";
    }
}
?>