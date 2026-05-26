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

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM funcion WHERE id_funcion = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
