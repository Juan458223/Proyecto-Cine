<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';
require_once __DIR__ . '/../Model/Protagonista.php';

class ProtagonistaDAO {
    private $db;
    private $limit = 6;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function obtenerTodosPaginados($page = 1) {
        try {
            $offset = ($page - 1) * $this->limit;
            $sql = "SELECT * FROM protagonista LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $this->limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $protagonistas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $protagonistas[] = new Protagonista($row['id_actor'], $row['nombre']);
            }
            return $protagonistas;
        } catch (PDOException $e) {
            error_log("Error en ProtagonistaDAO::obtenerTodosPaginados: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerListaCompleta() {
        try {
            $sql = "SELECT * FROM protagonista ORDER BY nombre ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            $protagonistas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $protagonistas[] = new Protagonista($row['id_actor'], $row['nombre']);
            }
            return $protagonistas;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT * FROM protagonista WHERE id_actor = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? new Protagonista($row['id_actor'], $row['nombre']) : null;
        } catch (PDOException $e) { return null; }
    }

    public function contarTodos() {
        return (int)$this->db->query("SELECT COUNT(*) FROM protagonista")->fetchColumn();
    }

    public function insertarProtagonista(Protagonista $prota) {
        try {
            $sql = "INSERT INTO protagonista (nombre) VALUES (:nombre)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['nombre' => $prota->getNombre()]);
        } catch (PDOException $e) { return false; }
    }

    public function actualizarProtagonista(Protagonista $prota) {
        try {
            $sql = "UPDATE protagonista SET nombre = :nombre WHERE id_actor = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nombre' => $prota->getNombre(),
                'id' => $prota->getIdActor()
            ]);
        } catch (PDOException $e) { return false; }
    }
}
?>
