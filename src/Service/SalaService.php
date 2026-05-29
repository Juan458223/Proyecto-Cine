<?php
require_once __DIR__ . '/../Dao/SalaDAO.php';
require_once __DIR__ . '/../Model/Sala.php';
require_once __DIR__ . '/../Model/Cine.php';
require_once __DIR__ . '/CineService.php';

class SalaService {
    private $salaDAO;
    private $cineService;

    public function __construct() {
        $this->salaDAO = new SalaDAO();
        $this->cineService = new CineService();
    }

    public function obtenerSalasPaginadas($page = 1) {
        return $this->salaDAO->obtenerTodas($page);
    }

    public function contarSalas() {
        return $this->salaDAO->contarTodos();
    }

    public function obtenerSalaPorId($id) {
        return $this->salaDAO->obtenerPorId($id);
    }

    public function insertarSala($capacidad, $cine_id) {
        $cine = $this->cineService->obtenerCinePorId($cine_id);
        $sala = new Sala(null, 0, $capacidad, $cine);
        return $this->salaDAO->insertar($sala);
    }

    public function actualizarSala($id, $capacidad, $cine_id) {
        $cine = $this->cineService->obtenerCinePorId($cine_id);
        if (!$cine) return false;
        $sala = new Sala($id, 0, $capacidad, $cine); // El numero_sala se gestiona por trigger en BD
        return $this->salaDAO->actualizar($sala);
    }

    public function obtenerSalasPorCine($cine_id) {
        return $this->salaDAO->obtenerPorCine($cine_id);
    }
}
?>