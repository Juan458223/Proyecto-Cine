<?php
class Usuario{
        private $id;
        private $nombre;
        private $correo;
        private $password;
        private $permisos; 
        private $estado;

        public function __construct( $nombre, $correo, $password, $permisos = 'Usuario', $id = null, $estado = 'Activado') {
            $this->nombre = $nombre;
            $this->correo = $correo;
            $this->password = (strpos($password, '$2y$') === 0) ? $password : password_hash($password, PASSWORD_DEFAULT);
            $this->permisos = $permisos;
            $this->id = $id;
            $this->estado = $estado;
        }
        
        public function getId() {
            return $this->id;
        }
        
        public function setId($id) {
            $this->id = $id;
        }

        public function getEstado() {
            return $this->estado;
        }

        public function setEstado($estado) {
            $this->estado = $estado;
        }

        public function getPermisos() {
            return $this->permisos;
        }

        public function setPermisos($permisos) {
            $this->permisos = $permisos;
        }

         public function getIdUsuario() {
            return $this->id;
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
        public function setNombre($nombre) {
                $this->nombre = $nombre;
        }
        public function setCorreo($correo) {
                $this->correo = $correo;
        }
        public function setPassword($password) {
                $this->password = (strpos($password, '$2y$') === 0) ? $password : password_hash($password, PASSWORD_DEFAULT);
        }

    }
?>