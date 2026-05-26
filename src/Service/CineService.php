<?php
require_once __DIR__ . '/../Dao/CineDAO.php';
require_once __DIR__ . '/../Model/Cine.php';

class CineService {
    private $cineDAO;

    public function __construct() {
        $this->cineDAO = new CineDAO();
    }

    public function obtenerCinesPaginados($page = 1) {
        return $this->cineDAO->obtenerTodos($page);
    }

    public function contarCines() {
        return $this->cineDAO->contarTodos();
    }

    public function obtenerCinePorId($id) {
        return $this->cineDAO->obtenerPorId($id);
    }

    public function insertarCine($nombre, $calle, $numero, $telefono) {
        $cine = new Cine(null, $nombre, $calle, $numero, $telefono);
        return $this->cineDAO->insertar($cine);
    }

    public function actualizarCine($id, $nombre, $calle, $numero, $telefono) {
        $cine = new Cine($id, $nombre, $calle, $numero, $telefono);
        return $this->cineDAO->actualizar($cine);
    }

    public function eliminarCine($id) {
        return $this->cineDAO->eliminar($id);
    }
}
?>