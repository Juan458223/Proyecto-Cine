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

    public function listarPeliculas(){
        $movieData = $this->peliculaDAO->capturarPelis();
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
                    $clasificacion = 'Todo Publico';
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

    public function obtenerPeliculasPaginadas($limit, $offset) {
        $data = $this->peliculaDAO->obtenerPeliculasPaginadas($limit, $offset);
        $peliculas = [];
        foreach ($data as $row) {
            $peliculas[] = new Pelicula(
                $row['id_pelicula'],
                $row['titulo'],
                $row['director'],
                $row['clasificacion'],
                $row['url_image'],
                $row['genero']
            );
        }
        return $peliculas;
    }

    public function actualizarPelicula($pelicula){
        return $this->peliculaDAO->actualizarPelicula($pelicula);
    }

    public function contarPeliculas() {
        return $this->peliculaDAO->contarPeliculas();
    }

    public function eliminarPelicula($id) {
        // Eliminamos la relación con protagonistas antes de borrar la película
        $this->pelihasprotaDAO->eliminarTodosPorPelicula($id);
        return $this->peliculaDAO->eliminarPelicula($id);
    }

    public function insertarPelicula($titulo, $director, $clasificacion, $url_image, $genero_id) {
        $pelicula = new Pelicula(
            0, //De valor temporal', al insertarlo al SQL no se pasara este atributo.
            $titulo, 
            $director, 
            $clasificacion, 
            $url_image, 
            $genero_id
        );
        return $this->peliculaDAO->insertarPelicula($pelicula);
    }

    public function obtenerTablas() {
        $peliculas = $this->listarPeliculas();
        $genderData = $this->generoDAO->obtenerTodos();

        foreach ($peliculas as $peli) {
            // Formateo para la UI
            $clasifUI = ($peli->getClasificacion() == 0) ? 'Todo Publico' : '+'.$peli->getClasificacion();
            

            $json_data = htmlspecialchars(json_encode([
                'titulo' => $peli->getTitulo(),
                'director' => $peli->getDirector(),
                'clasificacion' => $clasifUI,
                'imagen' => $peli->getUrlImage(),
                'genero' => $peli->getGenero(),
                'protagonistas' => implode(', ', $peli->getProtagonistas())
            ]), ENT_QUOTES, 'UTF-8');

            echo "
            <li class='group flex flex-col cursor-pointer' onclick='window.openMovieAdmin($json_data)'>
                <div class='relative aspect-[2/3] overflow-hidden rounded-sm bg-zinc-900 shadow-md transition-all duration-300'>
                    <!-- Badge de Clasificación (Estilo Cine Colombia) -->
                    <div class='absolute top-2 left-2 z-10'>
                        <span class='bg-black/60 backdrop-blur-md text-white text-[9px] font-bold px-2 py-0.5 rounded-sm border border-white/20 uppercase'>
                            {$clasifUI}
                        </span>
                    </div>

                    <!-- Imagen -->
                    <img 
                        src='{$peli->getUrlImage()}' 
                        alt='{$peli->getTitulo()}' 
                        class='w-full h-full object-cover transition-transform duration-700 group-hover:scale-105'
                        loading='lazy'
                    >

                    <!-- Overlay Hover (Cine Colombia style: Botón central) -->
                    <div class='absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center p-4'>
                        <button class='bg-[#E50914] text-white text-[10px] font-bold uppercase tracking-[0.2em] px-4 py-2 rounded-sm shadow-xl transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300'>
                            Ver Detalles
                        </button>
                    </div>
                </div>

                <!-- Info debajo (Limpia y tipográfica) -->
                <div class='mt-4 space-y-1'>
                    <h3 class='text-white font-black text-sm md:text-base leading-tight uppercase tracking-tight font-bebas group-hover:text-[#E50914] transition-colors'>
                        {$peli->getTitulo()}
                    </h3>
                    <div class='flex items-center gap-2'>
                        <span class='text-zinc-500 text-[10px] font-bold uppercase tracking-widest'>{$peli->getGenero()}</span>
                    </div>
                </div>
            </li>";
        }
    }
}
?>