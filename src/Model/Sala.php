<?php

require_once __DIR__ . '/Cine.php';

class Sala {
    private $id_sala;
    private $numero_sala;
    private $capacidad;
    private $cine;

    public function __construct($id_sala, $numero_sala, $capacidad, ?Cine $cine = null) {
        $this->id_sala = $id_sala;
        $this->numero_sala = $numero_sala;
        $this->capacidad = $capacidad;
        $this->cine = $cine;
    }

    public function getIdSala() { return $this->id_sala; }
    public function getNumeroSala() { return $this->numero_sala; }
    public function getCapacidad() { return $this->capacidad; }
    public function getCine() { return $this->cine; }

    public function setIdSala($id_sala) { $this->id_sala = $id_sala; }
    public function setNumeroSala($numero_sala) { $this->numero_sala = $numero_sala; }
    public function setCapacidad($capacidad) { $this->capacidad = $capacidad; }
    public function setCine(Cine $cine) { $this->cine = $cine; }
}
?>