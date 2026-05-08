<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';
require_once __DIR__ . '/../Model/Usuario.php';

class UsuarioDAO {
    private $db;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function insertarUsuario(Usuario $usuario) {
        try {
            $sql = "INSERT INTO usuarios (nombre, password, correo, estado_id)
                    VALUES (:nombre, :password, :correo, 2)";
            $statement = $this->db->prepare($sql);
            $statement->execute([
                'nombre'    => $usuario->getNombre(),
                'password'  => $usuario->getPassword(), 
                'correo'    => $usuario->getCorreo()
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al insertar usuario: ".$e->getMessage());
            return false;
        }
    }

    public function actualizarUsuario(Usuario $usuario) {
        try {
            $sql = "UPDATE usuarios 
                    SET nombre = :nombre, correo = :correo
                    WHERE id = :id";
            $statement = $this->db->prepare($sql);
            $statement->execute([
                'nombre' => $usuario->getNombre(),
                'correo' => $usuario->getCorreo(),
                'id' => $usuario->getIdUsuario()
            ]);
            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al actualizar usuario: ".$e->getMessage());
            return false;
        }
    }

    public function actualizarPermisos($id, $permisos) {
        try {
            $sql = "UPDATE usuarios SET permisos = :permisos WHERE id = :id";
            $statement = $this->db->prepare($sql);
            return $statement->execute([
                'permisos' => $permisos,
                'id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Error al actualizar permisos: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerUsuarioPorCorreo($correo) {
        $sql = "SELECT u.*, e.nombre as estado 
                FROM usuarios u
                INNER JOIN estados e ON u.estado_id = e.id
                WHERE u.correo = :correo";
        $statement = $this->db->prepare($sql);
        $statement->execute(['correo' => $correo]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerUsuarios() {
        $sql = "SELECT * FROM usuarios";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarUsuario($id) {
        try {
            $sql = "DELETE FROM usuarios WHERE id = :id";
            $statement = $this->db->prepare($sql);
            $statement->execute(['id' => $id]);
            return $statement->rowCount() > 0;  
        } catch (PDOException $e) {
            error_log("Error al eliminar usuario: ".$e->getMessage());
            return false;
        }
    }
}
?>