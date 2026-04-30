<?php
    session_start();
    include('DatabaseConnection.php');
    class Login {
        private $db;
        public function __construct() {
            $this->db = DatabaseConnection::getInstance()->getConnection();
        }

        public function verificarUsuario($correo, $password) {
            // 3. Ejemplo de uso con PDO
            $sql = "SELECT * FROM usuario WHERE correo = :correo AND password = :contra";
            $statement = $this->db->prepare($sql);
            $statement->execute(['correo' => $correo, 'contra' => $password]);
            
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            if($user){
                echo "ta bien
                Entro a la BD";
            }else{
                echo "ta mal
                Entro a la BD";
            }
        }
    }
?>