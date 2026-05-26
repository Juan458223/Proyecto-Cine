<?php

require_once __DIR__ . '/Cine.php';

class Tarifa {
    private $id_tarifa;
    private $cine; 
    private $nombre_dia;
    private $categoria;
    private $precio;

    public function __construct($id_tarifa, Cine $cine, $nombre_dia, $categoria, $precio) {
        $this->id_tarifa = $id_tarifa;
        $this->cine = $cine;
        $this->nombre_dia = $nombre_dia;
        $this->categoria = $categoria;
        $this->precio = $precio;
    }

    public function getIdTarifa() { return $this->id_tarifa; }
    public function getCine() { return $this->cine; }
    public function getNombreDia() { return $this->nombre_dia; }
    public function getCategoria() { return $this->categoria; }
    public function getPrecio() { return $this->precio; }

    public function setIdTarifa($id) { $this->id_tarifa = $id; }
    public function setCine(Cine $c) { $this->cine = $c; }
    public function setNombreDia($n) { $this->nombre_dia = $n; }
    public function setCategoria($c) { $this->categoria = $c; }
    public function setPrecio($p) { $this->precio = $p; }
}
?>