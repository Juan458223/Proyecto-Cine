<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';
require_once __DIR__ . '/../Model/Tarifa.php';
require_once __DIR__ . '/../Model/Cine.php';

class TarifaDAO {
    private $db;
    private $limit = 6;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function obtenerTarifasPorCinePaginadas($cine_id, $page = 1) {
        try {
            $offset = ($page - 1) * $this->limit;
            $sql = "SELECT t.*, c.nombre, c.calle, c.numero, c.telefono, 
                           d.nombre_dia, p.nombre_categoria
                    FROM tarifa t
                    JOIN cine c ON t.cine_id_cine = c.id_cine
                    JOIN tarifas_tipos_dia d ON t.tipo_dia_id = d.id
                    JOIN tarifas_categorias_publico p ON t.categoria_publico_id = p.id
                    WHERE t.cine_id_cine = :cine_id
                    ORDER BY t.id_tarifa LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':cine_id', (int)$cine_id, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $this->limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $tarifas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $cine = new Cine($row['id_cine'], $row['nombre'], $row['calle'], $row['numero'], $row['telefono']);
                $tarifas[] = new Tarifa($row['id_tarifa'], $cine, $row['nombre_dia'], $row['nombre_categoria'], $row['precio']);
            }
            return $tarifas;
        } catch (PDOException $e) {
            error_log("Error en TarifaDAO::obtenerTarifasPorCinePaginadas: " . $e->getMessage());
            return [];
        }
    }

    public function contarTarifasPorCine($cine_id) {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM tarifa WHERE cine_id_cine = :cine_id");
            $stmt->execute(['cine_id' => (int)$cine_id]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }
}
?>
