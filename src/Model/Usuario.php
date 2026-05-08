<?php
class Usuario{
        private $id_usuario;
        private $nombre;
        private $correo;
        private $password;
        private $permisos;

        public function __construct( $nombre, $correo, $password, $permisos = 0) {
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->permisos = $permisos;
        }
         public function getIdUsuario() {
            return $this->id_usuario;
         }
        public function getNombre() {
                return $this->nombre;
        }
        public function getCorreo() {
                return $this->correo;
        }
        public function getPassword() {
                return $this->password;
        }
        public function getPermisos() {
            return $this->permisos;
        }
        public function setNombre($nombre) {
                $this->nombre = $nombre;
        }
        public function setCorreo($correo) {
                $this->correo = $correo;
        }
        public function setPassword($password) {
                $this->password = password_hash($password, PASSWORD_DEFAULT);
        }
        public function setPermisos($permisos) {
            $this->permisos = $permisos;
        }

    }
?>