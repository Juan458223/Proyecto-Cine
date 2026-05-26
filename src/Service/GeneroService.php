<?php
require_once __DIR__ . '/../Dao/GeneroDAO.php';
require_once __DIR__ . '/../Model/Genero.php';

class GeneroService {
    private $generoDAO;

    public function __construct() {
        $this->generoDAO = new GeneroDAO();
    }

    public function listarGeneros($page = 1) {
        return $this->generoDAO->obtenerTodos($page);
    }

    public function contarGeneros() {
        return $this->generoDAO->contarTodos();
    }

    public function insertarGenero($nombre) {
        $genero = new Genero(null, $nombre);
        return $this->generoDAO->insertarGenero($genero);
    }

    public function actualizarGenero($id, $nombre) {
        $genero = new Genero($id, $nombre);
        return $this->generoDAO->actualizarGenero($genero);
    }

    public function eliminarGenero($id) {
        return $this->generoDAO->eliminarGenero($id);
    }
}
?>