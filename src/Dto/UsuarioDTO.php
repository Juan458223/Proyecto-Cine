<?php
class UsuarioDTO {
    public $id;
    public $nombre;
    public $correo;
    public $permisos;

    public function __construct( $id,$nombre, $correo, $permisos = 0) {
        $this->nombre = $nombre;
         $this->id = $id;
        $this->correo = $correo;
        $this->permisos = $permisos;
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
    public function getPermisos() {
        return $this->permisos;
    }
}

?>
