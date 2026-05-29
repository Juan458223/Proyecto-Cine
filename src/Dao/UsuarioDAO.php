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
            $sqlIds = "SELECT (SELECT id FROM estados WHERE nombre = :estado) as estado_id, 
                              (SELECT id FROM permisos WHERE nombre = :permisos) as permisos_id";
            $stmtIds = $this->db->prepare($sqlIds);
            $stmtIds->execute(['estado' => $usuario->getEstado(), 'permisos' => $usuario->getPermisos()]);
            $ids = $stmtIds->fetch(PDO::FETCH_ASSOC);

            $sql = "INSERT INTO usuarios (nombre, password, correo, estado_id, permisos_id)
                    VALUES (:nombre, :password, :correo, :estado_id, :permisos_id)";
            $statement = $this->db->prepare($sql);
            $statement->execute([
                'nombre'      => $usuario->getNombre(),
                'password'    => $usuario->getPassword(), 
                'correo'      => $usuario->getCorreo(),
                'estado_id'   => $ids['estado_id'] ?? 2, 
                'permisos_id' => $ids['permisos_id'] ?? 2 
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al insertar usuario: ".$e->getMessage());
            return false;
        }
    }

    public function obtenerUsuarioPorCorreo($correo) {
        $sql = "SELECT u.*, e.nombre as estado_nombre, p.nombre as permiso_nombre 
                FROM usuarios u
                INNER JOIN estados e ON u.estado_id = e.id
                INNER JOIN permisos p ON u.permisos_id = p.id
                WHERE u.correo = :correo";
        $statement = $this->db->prepare($sql);
        $statement->execute(['correo' => $correo]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarUsuario(Usuario $usuario) {
        try {
            $sqlIds = "SELECT (SELECT id FROM estados WHERE nombre = :estado) as estado_id, 
                              (SELECT id FROM permisos WHERE nombre = :permisos) as permisos_id";
            $stmtIds = $this->db->prepare($sqlIds);
            $stmtIds->execute(['estado' => $usuario->getEstado(), 'permisos' => $usuario->getPermisos()]);
            $ids = $stmtIds->fetch(PDO::FETCH_ASSOC);

            $sql = "UPDATE usuarios 
                    SET nombre = :nombre, password = :password, correo = :correo, 
                        estado_id = :estado_id, permisos_id = :permisos_id 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nombre'      => $usuario->getNombre(),
                'password'    => $usuario->getPassword(),
                'correo'      => $usuario->getCorreo(),
                'estado_id'   => $ids['estado_id'],
                'permisos_id' => $ids['permisos_id'],
                'id'          => $usuario->getId()
            ]);
        } catch (PDOException $e) {
            error_log("Error en UsuarioDAO::actualizarUsuario: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerUsuariosPaginados($limit, $offset, $excluded_id = null) {
        $sql = "SELECT u.id, u.nombre, u.correo, e.nombre as estado, p.nombre as permiso, '28/05/2026' as registro
                FROM usuarios u 
                INNER JOIN estados e ON u.estado_id = e.id 
                INNER JOIN permisos p ON u.permisos_id = p.id";
        
        if ($excluded_id) {
            $sql .= " WHERE u.id != :excluded_id";
        }
        
        $sql .= " LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        if ($excluded_id) {
            $stmt->bindValue(':excluded_id', (int)$excluded_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarUsuarios($excluded_id = null) {
        $sql = "SELECT COUNT(*) FROM usuarios";
        if ($excluded_id) {
            $sql .= " WHERE id != :excluded_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['excluded_id' => $excluded_id]);
            return (int)$stmt->fetchColumn();
        }
        return (int)$this->db->query($sql)->fetchColumn();
    }
}
?>
