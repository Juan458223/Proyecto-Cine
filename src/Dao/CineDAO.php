<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';
require_once __DIR__ . '/../Model/Cine.php';

class CineDAO {
    private $db;
    private $limit = 6;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function obtenerTodosSinPaginar() {
        try {
            $sql = "SELECT * FROM cine ORDER BY nombre ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $cines = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $cines[] = new Cine($row['id_cine'], $row['nombre'], $row['calle'], $row['numero'], $row['telefono']);
            }
            return $cines;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerTodos($page = 1) {
        try {
            $offset = ($page - 1) * $this->limit;
            $sql = "SELECT * FROM cine LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $this->limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $cines = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $cines[] = new Cine($row['id_cine'], $row['nombre'], $row['calle'], $row['numero'], $row['telefono']);
            }
            return $cines;
        } catch (PDOException $e) {
            error_log("Error en CineDAO::obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

    public function contarTodos() {
        return (int)$this->db->query("SELECT COUNT(*) FROM cine")->fetchColumn();
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT * FROM cine WHERE id_cine = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            return new Cine($row['id_cine'], $row['nombre'], $row['calle'], $row['numero'], $row['telefono']);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function insertar(Cine $cine) {
        try {
            $sql = "INSERT INTO cine (nombre, calle, numero, telefono) VALUES (:nombre, :calle, :numero, :telefono)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nombre' => $cine->getNombre(),
                'calle' => $cine->getCalle(),
                'numero' => $cine->getNumero(),
                'telefono' => $cine->getTelefono()
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function actualizar(Cine $cine) {
        try {
            $sql = "UPDATE cine SET nombre = :nombre, calle = :calle, numero = :numero, telefono = :telefono WHERE id_cine = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id' => $cine->getIdCine(),
                'nombre' => $cine->getNombre(),
                'calle' => $cine->getCalle(),
                'numero' => $cine->getNumero(),
                'telefono' => $cine->getTelefono()
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
