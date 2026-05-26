<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';

class FuncionDAO {
    private $db;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function obtenerTodas($limit = null, $offset = null) {
        try {
            $sql = "SELECT f.*, p.titulo as pelicula_titulo, s.numero_sala as sala_nombre, c.nombre as cine_nombre 
                    FROM funcion f 
                    JOIN pelicula p ON f.pelicula_id_pelicula = p.id_pelicula 
                    JOIN sala s ON f.sala_id_sala = s.id_sala
                    JOIN cine c ON s.cine_id_cine = c.id_cine";
            if ($limit !== null && $offset !== null) {
                $sql .= " LIMIT $limit OFFSET $offset";
            }
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function contarTodas() {
        try {
            $sql = "SELECT COUNT(*) FROM funcion";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT * FROM funcion WHERE id_funcion = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function insertar($fecha_hora, $pelicula_id, $sala_id, $boletas, $tarifa_id) {
        try {
            $sql = "INSERT INTO funcion (fecha_hora, pelicula_id_pelicula, sala_id_sala, boletas_vendidas, tarifa_id_dia) 
                    VALUES (:fecha_hora, :pelicula_id, :sala_id, :boletas, :tarifa_id)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'fecha_hora' => $fecha_hora,
                'pelicula_id' => $pelicula_id,
                'sala_id' => $sala_id,
                'boletas' => $boletas,
                'tarifa_id' => $tarifa_id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function actualizar($id, $fecha_hora, $pelicula_id, $sala_id, $boletas, $tarifa_id) {
        try {
            $sql = "UPDATE funcion SET fecha_hora = :fecha_hora, pelicula_id_pelicula = :pelicula_id, 
                    sala_id_sala = :sala_id, boletas_vendidas = :boletas, tarifa_id_dia = :tarifa_id 
                    WHERE id_funcion = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id' => $id,
                'fecha_hora' => $fecha_hora,
                'pelicula_id' => $pelicula_id,
                'sala_id' => $sala_id,
                'boletas' => $boletas,
                'tarifa_id' => $tarifa_id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerPorPelicula($id_pelicula) {
        try {
            $sql = "SELECT f.*, s.numero_sala as sala_nombre, c.nombre as cine_nombre, c.direccion as cine_direccion, c.telefono as cine_telefono
                    FROM funcion f 
                    JOIN sala s ON f.sala_id_sala = s.id_sala
                    JOIN cine c ON s.cine_id_cine = c.id_cine
                    WHERE f.pelicula_id_pelicula = :id_pelicula
                    ORDER BY f.fecha_hora ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_pelicula' => $id_pelicula]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerPorCine($id_cine) {
        try {
            $sql = "SELECT f.*, p.titulo as pelicula_titulo, p.url_image as pelicula_imagen, s.numero_sala as sala_nombre
                    FROM funcion f 
                    JOIN pelicula p ON f.pelicula_id_pelicula = p.id_pelicula
                    JOIN sala s ON f.sala_id_sala = s.id_sala
                    WHERE s.cine_id_cine = :id_cine
                    ORDER BY f.fecha_hora ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_cine' => $id_cine]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerSalasPorCine($id_cine) {
        try {
            $sql = "SELECT id_sala, numero_sala FROM sala WHERE cine_id_cine = :id_cine";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_cine' => $id_cine]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
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
