<?php

require_once __DIR__ . '/Pelicula.php';
require_once __DIR__ . '/Sala.php';

class Funcion {
    private $id_funcion;
    private $fecha_hora;
    private $pelicula; 
    private $sala;     

    public function __construct($id_funcion, $fecha_hora, Pelicula $pelicula, Sala $sala) {
        $this->id_funcion = $id_funcion;
        $this->fecha_hora = $fecha_hora;
        $this->pelicula = $pelicula;
        $this->sala = $sala;
    }

    public function getIdFuncion() { return $this->id_funcion; }
    public function getFechaHora() { return $this->fecha_hora; }
    public function getPelicula() { return $this->pelicula; }
    public function getSala() { return $this->sala; }

    public function setIdFuncion($id) { $this->id_funcion = $id; }
    public function setFechaHora($fecha) { $this->fecha_hora = $fecha; }
    public function setPelicula(Pelicula $p) { $this->pelicula = $p; }
    public function setSala(Sala $s) { $this->sala = $s; }
}
?>