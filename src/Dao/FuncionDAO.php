<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';
require_once __DIR__ . '/../Model/Funcion.php';
require_once __DIR__ . '/../Model/Pelicula.php';
require_once __DIR__ . '/../Model/Sala.php';
require_once __DIR__ . '/../Model/Cine.php';

class FuncionDAO {
    private $db;
    private $limit = 6;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function obtenerTodas($page = 1) {
        try {
            $offset = ($page - 1) * $this->limit;
            $sql = "SELECT f.*, 
                           p.titulo, p.director, p.clasificacion, p.url_image, p.genero_id_genero,
                           s.numero_sala, s.capacidad,
                           c.id_cine, c.nombre as cine_nombre, c.calle, c.numero, c.telefono
                    FROM funcion f 
                    JOIN pelicula p ON f.pelicula_id_pelicula = p.id_pelicula 
                    JOIN sala s ON f.sala_id_sala = s.id_sala
                    JOIN cine c ON s.cine_id_cine = c.id_cine
                    LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $this->limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $funciones = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $cine = new Cine($row['id_cine'], $row['cine_nombre'], $row['calle'], $row['numero'], $row['telefono']);
                $sala = new Sala($row['sala_id_sala'], $row['numero_sala'], $row['capacidad'], $cine);
                $pelicula = new Pelicula($row['pelicula_id_pelicula'], $row['titulo'], $row['director'], $row['clasificacion'], $row['url_image'], $row['genero_id_genero']);
                
                $funciones[] = new Funcion($row['id_funcion'], $row['fecha_hora'], $pelicula, $sala);
            }
            return $funciones;
        } catch (PDOException $e) {
            error_log("Error en FuncionDAO::obtenerTodas: " . $e->getMessage());
            return [];
        }
    }

    public function contarTodas() {
        return (int)$this->db->query("SELECT COUNT(*) FROM funcion")->fetchColumn();
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT f.*, 
                           p.titulo, p.director, p.clasificacion, p.url_image, p.genero_id_genero,
                           s.numero_sala, s.capacidad,
                           c.id_cine, c.nombre as cine_nombre, c.calle, c.numero, c.telefono
                    FROM funcion f 
                    JOIN pelicula p ON f.pelicula_id_pelicula = p.id_pelicula 
                    JOIN sala s ON f.sala_id_sala = s.id_sala
                    JOIN cine c ON s.cine_id_cine = c.id_cine
                    WHERE f.id_funcion = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            
            $cine = new Cine($row['id_cine'], $row['cine_nombre'], $row['calle'], $row['numero'], $row['telefono']);
            $sala = new Sala($row['sala_id_sala'], $row['numero_sala'], $row['capacidad'], $cine);
            $pelicula = new Pelicula($row['pelicula_id_pelicula'], $row['titulo'], $row['director'], $row['clasificacion'], $row['url_image'], $row['genero_id_genero']);
            
            return new Funcion($row['id_funcion'], $row['fecha_hora'], $pelicula, $sala);
        } catch (PDOException $e) {
            error_log("Error en FuncionDAO::obtenerPorId: " . $e->getMessage());
            return null;
        }
    }

    public function insertar(Funcion $funcion) {
        try {
            $sql = "INSERT INTO funcion (fecha_hora, pelicula_id_pelicula, sala_id_sala) 
                    VALUES (:fecha_hora, :pelicula_id, :sala_id)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'fecha_hora' => $funcion->getFechaHora(),
                'pelicula_id' => $funcion->getPelicula()->getIdPelicula(),
                'sala_id' => $funcion->getSala()->getIdSala()
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function actualizar(Funcion $funcion) {
        try {
            $sql = "UPDATE funcion SET fecha_hora = :fecha_hora, pelicula_id_pelicula = :pelicula_id, 
                    sala_id_sala = :sala_id WHERE id_funcion = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id' => $funcion->getIdFuncion(),
                'fecha_hora' => $funcion->getFechaHora(),
                'pelicula_id' => $funcion->getPelicula()->getIdPelicula(),
                'sala_id' => $funcion->getSala()->getIdSala()
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerFuncionesPorPelicula($pelicula_id) {
        try {
            $sql = "SELECT f.id_funcion, f.fecha_hora, s.numero_sala, c.nombre as cine_nombre, c.calle as cine_calle, c.numero as cine_numero
                    FROM funcion f
                    JOIN sala s ON f.sala_id_sala = s.id_sala
                    JOIN cine c ON s.cine_id_cine = c.id_cine
                    WHERE f.pelicula_id_pelicula = :pelicula_id
                    ORDER BY f.fecha_hora ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['pelicula_id' => $pelicula_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en FuncionDAO::obtenerFuncionesPorPelicula: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorCinePaginado($cine_id, $page = 1, $sala_id = null) {
        try {
            $limit = 5; // Límite para el modal
            $offset = ($page - 1) * $limit;
            $sql = "SELECT f.id_funcion, f.fecha_hora, s.numero_sala, p.titulo as pelicula_titulo, p.clasificacion as pelicula_clasificacion
                    FROM funcion f
                    JOIN sala s ON f.sala_id_sala = s.id_sala
                    JOIN pelicula p ON f.pelicula_id_pelicula = p.id_pelicula
                    WHERE s.cine_id_cine = :cine_id";
            
            if ($sala_id) $sql .= " AND f.sala_id_sala = :sala_id";
            $sql .= " ORDER BY f.fecha_hora ASC LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':cine_id', $cine_id, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            if ($sala_id) $stmt->bindValue(':sala_id', $sala_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function contarPorCine($cine_id, $sala_id = null) {
        $sql = "SELECT COUNT(*) FROM funcion f JOIN sala s ON f.sala_id_sala = s.id_sala WHERE s.cine_id_cine = :cine_id";
        if ($sala_id) $sql .= " AND f.sala_id_sala = :sala_id";
        $stmt = $this->db->prepare($sql);
        $params = ['cine_id' => $cine_id];
        if ($sala_id) $params['sala_id'] = $sala_id;
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }
}
?>
