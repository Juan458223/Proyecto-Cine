<?php

class Pelicula {
    private $id_pelicula;
    private $titulo;
    private $director;
    private $clasificacion;
    private $url_image;
    private $genero_id;
    private $protagonistas;

    public function __construct($id_pelicula, $titulo, $director, $clasificacion, $url_image, $genero_id, $protagonistas = []) {
        $this->id_pelicula = $id_pelicula;
        $this->titulo = $titulo;
        $this->director = $director;
        $this->clasificacion = $clasificacion;
        $this->url_image = $url_image;
        $this->genero_id = $genero_id;
        $this->protagonistas = $protagonistas;
    }

    public function getIdPelicula() { return $this->id_pelicula; }
    public function getTitulo() { return $this->titulo; }
    public function getDirector() { return $this->director; }
    public function getClasificacion() { return $this->clasificacion; }
    public function getUrlImage() { return $this->url_image; }
    public function getGeneroId() { return $this->genero_id; }
    public function getProtagonistas() { return $this->protagonistas; }

    public function setIdPelicula($id) { $this->id_pelicula = $id; }
    public function setTitulo($t) { $this->titulo = $t; }
    public function setDirector($d) { $this->director = $d; }
    public function setClasificacion($c) { $this->clasificacion = $c; }
    public function setUrlImage($u) { $this->url_image = $u; }
    public function setGeneroId($g) { $this->genero_id = $g; }
    public function setProtagonistas($p) { $this->protagonistas = $p; }
}
?>