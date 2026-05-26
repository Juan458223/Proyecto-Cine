<?php
require_once __DIR__ . '/../Dao/PeliculasDAO.php';
require_once __DIR__ . '/../Dao/ProtagonistaDAO.php';
require_once __DIR__ . '/../Dao/PeliculaProtagonistaDAO.php';
require_once __DIR__ . '/../Model/Pelicula.php';

class PeliculaService {
    private $peliculaDAO;
    private $protagonistaDAO;
    private $pelihasprotaDAO;

    public function __construct(){
        $this->peliculaDAO = new PeliculaDAO();
        $this->protagonistaDAO = new ProtagonistaDAO();
        $this->pelihasprotaDAO = new PeliculaProtagonistaDAO();
    }

    public function listarGeneros() {
        require_once __DIR__ . '/../Dao/GeneroDAO.php';
        $generoDAO = new GeneroDAO();
        return $generoDAO->obtenerTodos();
    }

    public function obtenerPeliculasPaginadas($page = 1, $genero_id = null) {
        $peliculas = $this->peliculaDAO->obtenerPeliculasPaginadas($page, $genero_id);
        
        // Hidratar con protagonistas
        foreach ($peliculas as $peli) {
            $id = $peli->getIdPelicula();
            $protagonistas = $this->pelihasprotaDAO->obtenerProtagonistasPorPelicula($id);
            $peli->setProtagonistas($protagonistas);
        }
        
        return $peliculas;
    }

    public function contarPeliculas($genero_id = null) {
        return $this->peliculaDAO->contarPeliculas($genero_id);
    }

    public function obtenerPeliculaPorId($id) {
        // Implementar lógica de búsqueda si es necesario
        return null;
    }

    public function insertarPelicula($titulo, $director, $clasificacion, $url_image, $genero_id) {
        $peli = new Pelicula(null, $titulo, $director, $clasificacion, $url_image, $genero_id);
        return $this->peliculaDAO->insertarPelicula($peli);
    }

    public function actualizarPelicula($id, $titulo, $director, $clasificacion, $url_image, $genero_id) {
        $peli = new Pelicula($id, $titulo, $director, $clasificacion, $url_image, $genero_id);
        return $this->peliculaDAO->actualizarPelicula($peli);
    }

    public function eliminarPelicula($id) {
        return $this->peliculaDAO->eliminarPelicula($id);
    }
}
?>