
<?php 
    include('DatabaseConnection.php');
    class respuestas{
        private $db;
        public function __construct() {
            $this->db = DatabaseConnection::getInstance()->getConnection();
        }

         public function verificar($correo, $pregunta1, $pregunta2, $pregunta3) {

            $correo = trim($correo);
            $p1 = trim($pregunta1);
            $p2 = trim($pregunta2);
            $p3 = trim($pregunta3);


            $sql = "SELECT * FROM usuario WHERE pregunta1 = :pregunta1 AND  pregunta2 = :pregunta2 AND  pregunta3 = :pregunta3 
            AND correo = :correo";
            $statement = $this->db->prepare($sql);
            $statement->execute(['pregunta1' => $p1, 'pregunta2' => $p2, 'pregunta3' => $p3, 'correo' => $correo]);
            
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            if($user){
                echo "paso";
            }else{
                echo "no";
            }
        }
    }
?>