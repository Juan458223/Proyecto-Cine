<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';
require_once __DIR__ . '/../Model/Genero.php';

class GeneroDAO {
    private $db;
    private $limit = 6;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function obtenerTodos($page = 1) {
        try {
            $offset = ($page - 1) * $this->limit;
            $sql = "SELECT * FROM genero LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $this->limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $generos = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $generos[] = new Genero($row['id_genero'], $row['nombre_genero']);
            }
            return $generos;
        } catch (PDOException $e) {
            error_log("Error en GeneroDAO::obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

    public function contarTodos() {
        return (int)$this->db->query("SELECT COUNT(*) FROM genero")->fetchColumn();
    }

    public function insertarGenero(Genero $genero) {
        try {
            $sql = "INSERT INTO genero (nombre_genero) VALUES (:nombre)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['nombre' => $genero->getNombreGenero()]);
        } catch (PDOException $e) {
            error_log("Error en GeneroDAO::insertarGenero: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarGenero(Genero $genero) {
        try {
            $sql = "UPDATE genero SET nombre_genero = :nombre WHERE id_genero = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nombre' => $genero->getNombreGenero(),
                'id' => $genero->getIdGenero()
            ]);
        } catch (PDOException $e) {
            error_log("Error en GeneroDAO::actualizarGenero: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarGenero($id) {
        try {
            $sql = "DELETE FROM genero WHERE id_genero = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Error en GeneroDAO::eliminarGenero: " . $e->getMessage());
            return false;
        }
    }
}
?>
