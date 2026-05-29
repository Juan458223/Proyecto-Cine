<?php
class UsuarioDTO {
    public $id;
    public $nombre;
    public $correo;
    public $permisos; 
    public $estado;
    public $estado_id;
    public $permisos_id;
    public $registro;

    public function __construct($id, $nombre, $correo, $permisos = 'Usuario', $estado = 'Activado', $registro = '', $estado_id = 0, $permisos_id = 0) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->permisos = $permisos;
        $this->estado = $estado;
        $this->registro = $registro;
        $this->estado_id = $estado_id;
        $this->permisos_id = $permisos_id;
    }
    
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getCorreo() { return $this->correo; }
    public function getPermisos() { return $this->permisos; }
    public function getEstado() { return $this->estado; }
    public function getEstadoId() { return $this->estado_id; }
    public function getPermisosId() { return $this->permisos_id; }
}

?>
