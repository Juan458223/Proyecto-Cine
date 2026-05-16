<?php
class Usuario{
        private $id;
        private $nombre;
        private $correo;
        private $password;
        private $permisos;
        private $estado_id;

        public function __construct( $nombre, $correo, $password, $permisos = 0, $id = null) {
            $this->nombre = $nombre;
            $this->correo = $correo;
            // Solo hasheamos si no parece ser ya un hash de BCRYPT ($2y$)
            $this->password = (strpos($password, '$2y$') === 0) ? $password : password_hash($password, PASSWORD_DEFAULT);
            $this->permisos = $permisos;
            $this->id = $id;
            $this->estado_id = 1; // Default active
        }
        
        public function getId() {
            return $this->id;
        }
        
        public function setId($id) {
            $this->id = $id;
        }

        public function getEstadoId() {
            return $this->estado_id;
        }

        public function setEstadoId($estado_id) {
            $this->estado_id = $estado_id;
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
                // Solo hasheamos si no parece ser ya un hash de BCRYPT ($2y$)
                $this->password = (strpos($password, '$2y$') === 0) ? $password : password_hash($password, PASSWORD_DEFAULT);
        }
        public function setPermisos($permisos) {
            $this->permisos = $permisos;
        }

    }
?>