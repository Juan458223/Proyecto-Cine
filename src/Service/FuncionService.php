<?php
require_once __DIR__ . '/../Dao/FuncionDAO.php';
require_once __DIR__ . '/../Model/Funcion.php';
require_once __DIR__ . '/PeliculaService.php';
require_once __DIR__ . '/SalaService.php';

class FuncionService {
    private $funcionDAO;
    private $peliculaService;
    private $salaService;

    public function __construct() {
        $this->funcionDAO = new FuncionDAO();
        $this->peliculaService = new PeliculaService();
        $this->salaService = new SalaService();
    }

    public function obtenerFuncionesPaginadas($page = 1) {
        return $this->funcionDAO->obtenerTodas($page);
    }

    public function contarFunciones() {
        return $this->funcionDAO->contarTodas();
    }

    public function insertarFuncion($fecha_hora, $pelicula_id, $sala_id) {
        $pelicula = $this->peliculaService->obtenerPeliculaPorId($pelicula_id);
        $sala = $this->salaService->obtenerSalaPorId($sala_id);
        if (!$pelicula || !$sala) return false;
        
        $funcion = new Funcion(null, $fecha_hora, $pelicula, $sala);
        return $this->funcionDAO->insertar($funcion);
    }

    public function actualizarFuncion($id, $fecha_hora, $pelicula_id, $sala_id) {
        $pelicula = $this->peliculaService->obtenerPeliculaPorId($pelicula_id);
        $sala = $this->salaService->obtenerSalaPorId($sala_id);
        if (!$pelicula || !$sala) return false;
        
        $funcion = new Funcion($id, $fecha_hora, $pelicula, $sala);
        return $this->funcionDAO->actualizar($funcion);
    }

    public function eliminarFuncion($id) {
        return $this->funcionDAO->eliminar($id);
    }
}
?>