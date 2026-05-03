<?php
    session_start();
    include('DatabaseConnection.php');
    class Usuario {
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
                $_SESSION['id']=$user['identificacion'];
                $_SESSION['name']=$user['nombre'];
                $_SESSION['password']=$user['password'];
                $_SESSION['correo']=$user['correo'];
                $_SESSION['permisos']=$user['permisos'];
                $_SESSION['preg1']=$user['pregunta1'];
                $_SESSION['preg2']=$user['pregunta2'];
                $_SESSION['preg3']=$user['pregunta3'];
                header("Location: /proyectocine/view/home.php");
            }else{
                echo "Contraseña mal";
            }
        }

        public function insertarUsuario($nom, $pass, $correo, $permisos, $pregunta1, $pregunta2, $pregunta3){
            try {
                $sql = "INSERT INTO usuario (nombre, password, correo, permisos, pregunta1, pregunta2, pregunta3)
                VALUES (:nom , :pass , :correo , :permisos , :pregunta1 , :pregunta2 , :pregunta3)";
                $statement = $this->db->prepare($sql);
                $statement->execute(['nom' => $nom, 'pass' => $pass, 'correo' => $correo, 'permisos' => $permisos, 'pregunta1' => $pregunta1, 
                'pregunta2' => $pregunta2, 'pregunta3' => $pregunta3]);
                echo "REGISTRADO SATISFACTORIAMENTE.....";
                header("Refresh: 5; url=/proyectocine/index.php");
            } catch (PDOException $e) {
                error_log("Error en BD: ".$e->getMessage());
                echo "Error: ".$e->getMessage();
            }
        }

        public function verUsuario(){
            $sql = "SELECT * FROM usuario";
            $statement = $this->db->prepare($sql);
            $statement->execute();
            $usuarios = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach($usuarios as $users){
                echo "<tr>
                <td>".$users['identificacion']."</td>
                <td>".$users['nombre']."</td>
                <td>".$users['password']."</td>
                <td>".$users['correo']."</td>";
                if($users['permisos']==1){
                    echo "<td>Administrador</td>";
                }else{
                    echo "<td>Usuario</td>";
                }
                echo "
                <td>".$users['pregunta1']."</td>
                <td>".$users['pregunta2']."</td>
                <td>".$users['pregunta3']."</td>";
                if($users['identificacion']!=$_SESSION['id']){
                    echo "<td><a href='/proyectocine/controller/eliminar_usuario.php?id=".$users['identificacion']."'>Eliminar</a></td>";
                }
                
            }
        }

        public function elminarUsuario($id){
            try {
                $sql = "DELETE FROM usuario WHERE identificacion = :id";
                $statement = $this->db->prepare($sql);
                $statement->execute(['id' => $id]);
                echo "ELIMINADO SATISFACTORIAMENTE..... <br><br><br>REDIRIGIENDO....";
                header("Refresh: 5; url=/proyectocine/view/modificar_usuarios.php");
            } catch (PDOException $e) {
                error_log("Error en BD: ".$e->getMessage());
                echo "Error: ".$e->getMessage();
            }
        }
    }
?>