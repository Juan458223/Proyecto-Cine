<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';

class SalaDAO {
    private $db;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function obtenerTodas($limit = null, $offset = null) {
        try {
            $sql = "SELECT s.*, c.nombre as cine_nombre FROM sala s JOIN cine c ON s.cine_id_cine = c.id_cine";
            if ($limit !== null && $offset !== null) {
                $sql .= " LIMIT $limit OFFSET $offset";
            }
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function contarTodas() {
        try {
            $sql = "SELECT COUNT(*) FROM sala";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT * FROM sala WHERE id_sala = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function insertar($capacidad, $cine_id) {
        try {
            $sql = "INSERT INTO sala (capacidad, cine_id_cine) VALUES (:capacidad, :cine_id)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'capacidad' => $capacidad,
                'cine_id' => $cine_id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function actualizar($id, $capacidad, $cine_id) {
        try {
            $sql = "UPDATE sala SET capacidad = :capacidad, cine_id_cine = :cine_id WHERE id_sala = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id' => $id,
                'capacidad' => $capacidad,
                'cine_id' => $cine_id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM sala WHERE id_sala = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
