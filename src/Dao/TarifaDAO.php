<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';

class TarifaDAO {
    private $db;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    /**
     * Obtiene las tarifas de un cine específico con soporte para búsqueda y paginación
     */
    public function obtenerTarifasPorCinePaginadas($cine_id, $search = "", $limit = 5, $offset = 0) {
        try {
            $sql = "SELECT t.*, d.nombre_dia as tipo_dia, p.nombre_categoria as tipo_publico 
                    FROM tarifa t
                    JOIN tarifas_tipos_dia d ON t.tipo_dia_id = d.id
                    JOIN tarifas_categorias_publico p ON t.categoria_publico_id = p.id
                    WHERE t.cine_id_cine = :cine_id";
            
            if ($search !== "") {
                $sql .= " AND (d.nombre_dia LIKE :search OR p.nombre_categoria LIKE :search)";
            }
            
            $sql .= " ORDER BY d.id, p.id LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':cine_id', (int)$cine_id, PDO::PARAM_INT);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            if ($search !== "") {
                $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en TarifaDAO::obtenerTarifasPorCinePaginadas: " . $e->getMessage());
            return [];
        }
    }

    public function contarTarifasPorCine($cine_id, $search = "") {
        try {
            $sql = "SELECT COUNT(*) FROM tarifa t
                    JOIN tarifas_tipos_dia d ON t.tipo_dia_id = d.id
                    JOIN tarifas_categorias_publico p ON t.categoria_publico_id = p.id
                    WHERE t.cine_id_cine = :cine_id";
            
            if ($search !== "") {
                $sql .= " AND (d.nombre_dia LIKE :search OR p.nombre_categoria LIKE :search)";
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':cine_id', (int)$cine_id, PDO::PARAM_INT);
            if ($search !== "") {
                $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
            }
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    // Métodos antiguos para compatibilidad o administración básica
    public function obtenerTodas() {
        try {
            $sql = "SELECT t.*, d.nombre_dia, p.nombre_categoria 
                    FROM tarifa t 
                    JOIN tarifas_tipos_dia d ON t.tipo_dia_id = d.id
                    JOIN tarifas_categorias_publico p ON t.categoria_publico_id = p.id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>
