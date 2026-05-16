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

    public function actualizarUsuario(Usuario $usuario) {
        try {
            $sql = "UPDATE usuarios 
                    SET nombre = :nombre, password = :password, correo = :correo, 
                        estado_id = :estado_id, permisos = :permisos 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nombre'    => $usuario->getNombre(),
                'password'  => $usuario->getPassword(),
                'correo'    => $usuario->getCorreo(),
                'estado_id' => $usuario->getEstadoId(),
                'permisos'  => $usuario->getPermisos(),
                'id'        => $usuario->getId()
            ]);
        } catch (PDOException $e) {
            error_log("Error en UsuarioDAO::actualizarUsuario: " . $e->getMessage());
            return false;
        }
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

    public function obtenerUsuariosPaginados($limit, $offset) {
        $sql = "SELECT u.id as id_usuario, u.nombre, u.correo, u.password, u.estado_id, u.permisos, e.nombre as estado 
                FROM usuarios u 
                INNER JOIN estados e ON u.estado_id = e.id 
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarUsuarios() {
        return $this->db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    }
}
?>
