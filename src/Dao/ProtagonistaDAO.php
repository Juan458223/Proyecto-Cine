<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';

class ProtagonistaDAO {
    private $db;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function obtenerTodos() {
        try {
            $sql = "SELECT * FROM protagonistas";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            // Retorna un array asociativo (como el código viejo)
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en ProtagonistaDAO::obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerProtagonistasPaginados($limit, $offset) {
        try {
            $sql = "SELECT * FROM protagonistas LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en ProtagonistaDAO::obtenerProtagonistasPaginados: " . $e->getMessage());
            return [];
        }
    }

    public function contarProtagonistas() {
        try {
            return $this->db->query("SELECT COUNT(*) FROM protagonistas")->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en ProtagonistaDAO::contarProtagonistas: " . $e->getMessage());
            return 0;
        }
    }

    public function insertarProtagonista($nombre) {
        try {
            $sql = "INSERT INTO protagonistas (nombre) VALUES (:nombre)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['nombre' => $nombre]);
        } catch (PDOException $e) {
            error_log("Error en ProtagonistaDAO::insertarProtagonista: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarProtagonista($id, $nombre) {
        try {
            $sql = "UPDATE protagonistas SET nombre = :nombre WHERE id_actor = :id_actor";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nombre' => $nombre,
                'id_actor' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Error en ProtagonistaDAO::actualizarProtagonista: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarProtagonista($id) {
        try {
            $sql = "DELETE FROM protagonistas WHERE id_actor = :id_actor";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id_actor' => $id]);
        } catch (PDOException $e) {
            error_log("Error en ProtagonistaDAO::eliminarProtagonista: " . $e->getMessage());
            return false;
        }
    }
}
?>
