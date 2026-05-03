
<?php 
    include('DatabaseConnection.php');
    class recordar{
        private $db;

        public function __construct() {
            $this->db = DatabaseConnection::getInstance()->getConnection();
        }
        

         public function verificar($correo) {
            // 3. Ejemplo de uso con PDO
            $sql = "SELECT * FROM usuario WHERE correo = :correo";
            $statement = $this->db->prepare($sql);
            $statement->execute(['correo' => $correo]);
            
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            if($user){
                header("Location: /proyectocine/view/preguntas.php?correo=$correo");
            }else{
                echo "ta mal
                Boorra la cuenta";
            }
        }
    }
?>