<?php

class Pelicula {
    private $id_pelicula;
    private $titulo;
    private $director;
    private $clasificacion;
    private $url_image;
    private $genero;
    private $protagonistas;

    public function __construct($id_pelicula, $titulo, $director, $clasificacion, $url_image, $genero, $protagonistas = []) {
        $this->id_pelicula = $id_pelicula;
        $this->titulo = $titulo;
        $this->director = $director;
        $this->clasificacion = $clasificacion;
        $this->url_image = $url_image;
        $this->genero = $genero;
        $this->protagonistas = $protagonistas;
    }

    public function getIdPelicula() {
        return $this->id_pelicula;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getDirector() {
        return $this->director;
    }

    public function getClasificacion() {
        return $this->clasificacion;
    }

    public function getUrlImage() {
        return $this->url_image;
    }

    public function getGenero() {
        return $this->genero;
    }

    public function getProtagonistas() {
        return $this->protagonistas;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setDirector($director) {
        $this->director = $director;
    }

    public function setClasificacion($clasificacion) {
        $this->clasificacion = $clasificacion;
    }

    public function setUrlImage($url_image) {
        $this->url_image = $url_image;
    }

    public function setGenero($genero) {
        $this->genero = $genero;
    }

    public function setProtagonistas($protagonistas) {
        $this->protagonistas = $protagonistas;
    }

    public function addProtagonista($protagonista) {
        $this->protagonistas[] = $protagonista;
    }
}
?>
