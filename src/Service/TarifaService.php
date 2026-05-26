<?php
require_once __DIR__ . '/../Dao/TarifaDAO.php';
require_once __DIR__ . '/../Model/Tarifa.php';
require_once __DIR__ . '/CineService.php';

class TarifaService {
    private $tarifaDAO;
    private $cineService;

    public function __construct() {
        $this->tarifaDAO = new TarifaDAO();
        $this->cineService = new CineService();
    }

    public function obtenerTarifasPorCine($cine_id, $page = 1) {
        return $this->tarifaDAO->obtenerTarifasPorCinePaginadas($cine_id, $page);
    }

    public function contarTarifasPorCine($cine_id) {
        return $this->tarifaDAO->contarTarifasPorCine($cine_id);
    }
}
?>