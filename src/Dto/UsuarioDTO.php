<?php
class UsuarioDTO {
    public $id;
    public $nombre;
    public $correo;

    public function __construct( $id,$nombre, $correo) {
        $this->nombre = $nombre;
         $this->id = $id;
        $this->correo = $correo;
    }
    public function getNombre() {
        return $this->nombre;
    }
    public function getCorreo() {
        return $this->correo;
    }
    public function getId() {
        return $this->id;
    }

}

?>
