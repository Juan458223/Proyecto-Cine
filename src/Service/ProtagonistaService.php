<?php
require_once __DIR__ . '/../Dao/ProtagonistaDAO.php';
require_once __DIR__ . '/../Model/Protagonista.php';

class ProtagonistaService {
    private $protagonistaDAO;

    public function __construct() {
        $this->protagonistaDAO = new ProtagonistaDAO();
    }

    public function listarProtagonistasPaginados($page = 1) {
        return $this->protagonistaDAO->obtenerProtagonistasPaginados($page);
    }

    public function contarProtagonistas() {
        return $this->protagonistaDAO->contarProtagonistas();
    }

    public function insertarProtagonista($nombre) {
        $prota = new Protagonista(null, $nombre);
        return $this->protagonistaDAO->insertarProtagonista($prota);
    }

    public function actualizarProtagonista($id, $nombre) {
        $prota = new Protagonista($id, $nombre);
        return $this->protagonistaDAO->actualizarProtagonista($prota);
    }

    public function eliminarProtagonista($id) {
        return $this->protagonistaDAO->eliminarProtagonista($id);
    }
}
?>