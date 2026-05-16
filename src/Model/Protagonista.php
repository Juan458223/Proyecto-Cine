<?php
class Protagonista {
    private $id_actor;
    private $nombre;

    public function __construct($id_actor, $nombre) {
        $this->id_actor = $id_actor;
        $this->nombre = $nombre;
    }

    public function getIdActor() {
        return $this->id_actor;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setIdActor($id_actor) {
        $this->id_actor = $id_actor;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
}
?>