<?php

class Cine {
    private $id_cine;
    private $nombre;
    private $calle;
    private $numero;
    private $telefono;

    public function __construct($id_cine, $nombre, $calle, $numero, $telefono) {
        $this->id_cine = $id_cine;
        $this->nombre = $nombre;
        $this->calle = $calle;
        $this->numero = $numero;
        $this->telefono = $telefono;
    }

    public function getIdCine() { return $this->id_cine; }
    public function getNombre() { return $this->nombre; }
    public function getCalle() { return $this->calle; }
    public function getNumero() { return $this->numero; }
    public function getTelefono() { return $this->telefono; }

    public function setIdCine($id_cine) { $this->id_cine = $id_cine; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setCalle($calle) { $this->calle = $calle; }
    public function setNumero($numero) { $this->numero = $numero; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }
}
?>