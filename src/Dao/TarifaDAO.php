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
            $sql = "SELECT t.*, c.id_cine, c.nombre, c.calle, c.numero, c.telefono, 
                           d.nombre_dia, p.nombre_categoria
                    FROM tarifa t
                    JOIN cine c ON t.cine_id_cine = c.id_cine
                    JOIN tarifas_tipos_dia d ON t.tipo_dia_id = d.id
                    JOIN tarifas_categorias_publico p ON t.categoria_publico_id = p.id";
            
            if ($cine_id > 0) {
                $sql .= " WHERE t.cine_id_cine = :cine_id";
            }
            
            $sql .= " ORDER BY t.id_tarifa LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($sql);
            if ($cine_id > 0) {
                $stmt->bindValue(':cine_id', (int)$cine_id, PDO::PARAM_INT);
            }
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

    public function insertar(Tarifa $t) {
        try {
            $sqlIds = "SELECT (SELECT id FROM tarifas_tipos_dia WHERE nombre_dia = :dia) as dia_id,
                              (SELECT id FROM tarifas_categorias_publico WHERE nombre_categoria = :cat) as cat_id";
            $stmtIds = $this->db->prepare($sqlIds);
            $stmtIds->execute(['dia' => $t->getNombreDia(), 'cat' => $t->getCategoria()]);
            $ids = $stmtIds->fetch(PDO::FETCH_ASSOC);

            $sql = "INSERT INTO tarifa (cine_id_cine, tipo_dia_id, categoria_publico_id, precio) 
                    VALUES (:cine, :dia, :cat, :precio)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'cine' => $t->getCine()->getIdCine(),
                'dia' => $ids['dia_id'] ?? 1,
                'cat' => $ids['cat_id'] ?? 1,
                'precio' => $t->getPrecio()
            ]);
        } catch (PDOException $e) { return false; }
    }

    public function actualizar(Tarifa $t) {
        try {
            $sqlIds = "SELECT (SELECT id FROM tarifas_tipos_dia WHERE nombre_dia = :dia) as dia_id,
                              (SELECT id FROM tarifas_categorias_publico WHERE nombre_categoria = :cat) as cat_id";
            $stmtIds = $this->db->prepare($sqlIds);
            $stmtIds->execute(['dia' => $t->getNombreDia(), 'cat' => $t->getCategoria()]);
            $ids = $stmtIds->fetch(PDO::FETCH_ASSOC);

            $sql = "UPDATE tarifa SET cine_id_cine = :cine, tipo_dia_id = :dia, categoria_publico_id = :cat, precio = :precio 
                    WHERE id_tarifa = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'cine' => $t->getCine()->getIdCine(),
                'dia' => $ids['dia_id'],
                'cat' => $ids['cat_id'],
                'precio' => $t->getPrecio(),
                'id' => $t->getIdTarifa()
            ]);
        } catch (PDOException $e) { return false; }
    }

    public function contarTarifasPorCine($cine_id = 0) {
        try {
            $sql = "SELECT COUNT(*) FROM tarifa";
            if ($cine_id > 0) $sql .= " WHERE cine_id_cine = :cine_id";
            $stmt = $this->db->prepare($sql);
            if ($cine_id > 0) $stmt->execute(['cine_id' => (int)$cine_id]);
            else $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT t.*, c.id_cine, c.nombre, c.calle, c.numero, c.telefono, d.nombre_dia, p.nombre_categoria
                    FROM tarifa t
                    JOIN cine c ON t.cine_id_cine = c.id_cine
                    JOIN tarifas_tipos_dia d ON t.tipo_dia_id = d.id
                    JOIN tarifas_categorias_publico p ON t.categoria_publico_id = p.id
                    WHERE t.id_tarifa = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            $cine = new Cine($row['id_cine'], $row['nombre'], $row['calle'], $row['numero'], $row['telefono']);
            return new Tarifa($row['id_tarifa'], $cine, $row['nombre_dia'], $row['nombre_categoria'], $row['precio']);
        } catch (PDOException $e) { return null; }
    }
}
?>
