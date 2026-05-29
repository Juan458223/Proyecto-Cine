<?php
require_once __DIR__ . '/../Dao/PeliculasDAO.php';
require_once __DIR__ . '/../Dao/ProtagonistaDAO.php';
require_once __DIR__ . '/../Dao/PeliculaProtagonistaDAO.php';
require_once __DIR__ . '/../Model/Pelicula.php';

class PeliculaService {
    private $peliculaDAO;
    private $protagonistaDAO;
    private $pelihasprotaDAO;
    private $funcionDAO;

    public function __construct(){
        $this->peliculaDAO = new PeliculaDAO();
        $this->protagonistaDAO = new ProtagonistaDAO();
        $this->pelihasprotaDAO = new PeliculaProtagonistaDAO();
        require_once __DIR__ . '/../Dao/FuncionDAO.php';
        $this->funcionDAO = new FuncionDAO();
    }

    public function listarGeneros() {
        require_once __DIR__ . '/../Dao/GeneroDAO.php';
        $generoDAO = new GeneroDAO();
        return $generoDAO->obtenerTodos();
    }

    public function obtenerPeliculasPaginadas($page = 1, $genero_id = null) {
        $peliculas = $this->peliculaDAO->obtenerPeliculasPaginadas($page, $genero_id);
        
        // Hidratar con protagonistas y funciones
        foreach ($peliculas as $peli) {
            $id = $peli->getIdPelicula();
            
            // Protagonistas
            $protagonistas = $this->pelihasprotaDAO->obtenerProtagonistasPorPelicula($id);
            $peli->setProtagonistas($protagonistas);
            
            // Funciones (NUEVO)
            $funciones = $this->funcionDAO->obtenerFuncionesPorPelicula($id);
            $peli->setFunciones($funciones);
        }
        
        return $peliculas;
    }

    public function contarPeliculas($genero_id = null) {
        return $this->peliculaDAO->contarPeliculas($genero_id);
    }

    public function obtenerPeliculaPorId($id) {
        $peli = $this->peliculaDAO->obtenerPorId($id);
        if ($peli) {
            $protagonistas = $this->pelihasprotaDAO->obtenerProtagonistasFullPorPelicula($id);
            $peli->setProtagonistas($protagonistas);
        }
        return $peli;
    }

    public function insertarPelicula($titulo, $director, $clasificacion, $url_image, $genero_id) {
        $peli = new Pelicula(null, $titulo, $director, $clasificacion, $url_image, $genero_id);
        return $this->peliculaDAO->insertarPelicula($peli);
    }

    public function actualizarPelicula($id, $titulo, $director, $clasificacion, $url_image, $genero_id) {
        $peli = new Pelicula($id, $titulo, $director, $clasificacion, $url_image, $genero_id);
        return $this->peliculaDAO->actualizarPelicula($peli);
    }

    public function actualizarReparto($id_pelicula, $id_protagonistas) {
        // Primero limpiar reparto actual
        $this->pelihasprotaDAO->desvincularPorPelicula($id_pelicula);
        
        // Vincular los nuevos
        $success = true;
        foreach ($id_protagonistas as $id_prota) {
            if (!$this->pelihasprotaDAO->vincular($id_pelicula, $id_prota)) {
                $success = false;
            }
        }
        return $success;
    }
}
?>