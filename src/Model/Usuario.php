<?php
class Usuario{
        private $id_usuario;
        private $nombre;
        private $correo;
        private $password;

        public function __construct( $nombre, $correo, $password) {
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
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
        public function setNombre($nombre) {
                $this->nombre = $nombre;
        }
        public function setCorreo($correo) {
                $this->correo = $correo;
        }
        public function setPassword($password) {
                $this->password = password_hash($password, PASSWORD_DEFAULT);
        }

    }
?>