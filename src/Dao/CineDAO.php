<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';

class CineDAO {
    private $db;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function obtenerTodos($limit = null, $offset = null) {
        try {
            $sql = "SELECT * FROM cine";
            if ($limit !== null && $offset !== null) {
                $sql .= " LIMIT $limit OFFSET $offset";
            }
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en CineDAO::obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

    public function contarTodos() {
        try {
            $sql = "SELECT COUNT(*) FROM cine";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT * FROM cine WHERE id_cine = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function insertar($nombre, $direccion, $telefono) {
        try {
            $sql = "INSERT INTO cine (nombre, direccion, telefono) VALUES (:nombre, :direccion, :telefono)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nombre' => $nombre,
                'direccion' => $direccion,
                'telefono' => $telefono
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function actualizar($id, $nombre, $direccion, $telefono) {
        try {
            $sql = "UPDATE cine SET nombre = :nombre, direccion = :direccion, telefono = :telefono WHERE id_cine = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id' => $id,
                'nombre' => $nombre,
                'direccion' => $direccion,
                'telefono' => $telefono
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM cine WHERE id_cine = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
