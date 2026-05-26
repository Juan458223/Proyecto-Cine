<?php
class UsuarioDTO {
    public $id;
    public $nombre;
    public $correo;
    public $permisos; 
    public $estado;   

    public function __construct($id, $nombre, $correo, $permisos = 'Usuario', $estado = 'Activado') {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->permisos = $permisos;
        $this->estado = $estado;
    }
    
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getCorreo() { return $this->correo; }
    public function getPermisos() { return $this->permisos; }
    public function getEstado() { return $this->estado; }
}

?>
