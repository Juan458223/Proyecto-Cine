<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';

class GeneroDAO {
    private $db;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function obtenerTodos() {
        try {
            $sql = "SELECT * FROM genero";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            // Retorna un array asociativo (como el código viejo)
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en GeneroDAO::obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

    public function insertarGenero($nombre) {
        try {
            $sql = "INSERT INTO genero (nombre) VALUES (:nombre)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['nombre' => $nombre]);
        } catch (PDOException $e) {
            error_log("Error en GeneroDAO::insertarGenero: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarGenero($id, $nombre) {
        try {
            $sql = "UPDATE genero SET nombre = :nombre WHERE id_genero = :id_genero";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nombre' => $nombre,
                'id_genero' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Error en GeneroDAO::actualizarGenero: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarGenero($id) {
        try {
            $sql = "DELETE FROM genero WHERE id_genero = :id_genero";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id_genero' => $id]);
        } catch (PDOException $e) {
            error_log("Error en GeneroDAO::eliminarGenero: " . $e->getMessage());
            return false;
        }
    }
}
?>
