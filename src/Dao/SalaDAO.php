<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';
require_once __DIR__ . '/../Model/Sala.php';
require_once __DIR__ . '/../Model/Cine.php';

class SalaDAO {
    private $db;
    private $limit = 6;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function obtenerTodas($page = 1) {
        try {
            $offset = ($page - 1) * $this->limit;
            $sql = "SELECT s.*, c.nombre, c.calle, c.numero, c.telefono 
                    FROM sala s 
                    JOIN cine c ON s.cine_id_cine = c.id_cine 
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $this->limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $salas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $cine = new Cine($row['cine_id_cine'], $row['nombre'], $row['calle'], $row['numero'], $row['telefono']);
                $salas[] = new Sala($row['id_sala'], $row['numero_sala'], $row['capacidad'], $cine);
            }
            return $salas;
        } catch (PDOException $e) {
            error_log("Error en SalaDAO::obtenerTodas: " . $e->getMessage());
            return [];
        }
    }

    public function contarTodas() {
        return (int)$this->db->query("SELECT COUNT(*) FROM sala")->fetchColumn();
    }

    public function contarTodos() {
        return $this->contarTodas();
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT s.*, c.nombre, c.calle, c.numero, c.telefono 
                    FROM sala s 
                    JOIN cine c ON s.cine_id_cine = c.id_cine 
                    WHERE s.id_sala = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            
            $cine = new Cine($row['cine_id_cine'], $row['nombre'], $row['calle'], $row['numero'], $row['telefono']);
            return new Sala($row['id_sala'], $row['numero_sala'], $row['capacidad'], $cine);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function insertar(Sala $sala) {
        try {
            $sql = "INSERT INTO sala (capacidad, cine_id_cine) VALUES (:capacidad, :cine_id)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'capacidad' => $sala->getCapacidad(),
                'cine_id' => $sala->getCine()->getIdCine()
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function actualizar(Sala $sala) {
        try {
            // Solo permitimos actualizar la capacidad por integridad de las funciones vinculadas
            $sql = "UPDATE sala SET capacidad = :capacidad WHERE id_sala = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id' => $sala->getIdSala(),
                'capacidad' => $sala->getCapacidad()
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerPorCine($cine_id) {
        try {
            $sql = "SELECT * FROM sala WHERE cine_id_cine = :cine_id ORDER BY numero_sala ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['cine_id' => $cine_id]);
            
            $salas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $salas[] = new Sala($row['id_sala'], $row['numero_sala'], $row['capacidad'], null);
            }
            return $salas;
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>
