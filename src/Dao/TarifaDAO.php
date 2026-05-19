<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';

class TarifaDAO {
    private $db;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function obtenerTodas() {
        try {
            $sql = "SELECT * FROM tarifa";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT * FROM tarifa WHERE id_dia = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function insertar($id_dia, $precio) {
        try {
            $sql = "INSERT INTO tarifa (id_dia, precio) VALUES (:id_dia, :precio)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id_dia' => $id_dia,
                'precio' => $precio
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function actualizar($id_dia, $precio) {
        try {
            $sql = "UPDATE tarifa SET precio = :precio WHERE id_dia = :id_dia";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id_dia' => $id_dia,
                'precio' => $precio
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function eliminar($id_dia) {
        try {
            $sql = "DELETE FROM tarifa WHERE id_dia = :id_dia";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id_dia' => $id_dia]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
