<?php
require_once __DIR__ . '/../Dao/ProtagonistaDAO.php';
require_once __DIR__ . '/../Model/Protagonista.php';

class ProtagonistaService {
    private $protagonistaDAO;

    public function __construct() {
        $this->protagonistaDAO = new ProtagonistaDAO();
    }

    public function listarProtagonistasPaginados($limit, $offset) {
        $data = $this->protagonistaDAO->obtenerProtagonistasPaginados($limit, $offset);
        $protagonistas = [];
        foreach ($data as $row) {
            $protagonistas[] = new Protagonista($row['id_actor'], $row['nombre']);
        }
        return $protagonistas;
    }

    public function contarProtagonistas() {
        return $this->protagonistaDAO->contarProtagonistas();
    }

    public function insertarProtagonista($nombre) {
        return $this->protagonistaDAO->insertarProtagonista($nombre);
    }

    public function actualizarProtagonista($id, $nombre) {
        return $this->protagonistaDAO->actualizarProtagonista($id, $nombre);
    }

    public function eliminarProtagonista($id) {
        return $this->protagonistaDAO->eliminarProtagonista($id);
    }
}
?>