<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';
require_once __DIR__ . '/../Model/Pelicula.php';

class PeliculaDAO {
    private $db;
    private $limit = 6;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function obtenerTodas() {
        try {
            $sql = "SELECT * FROM pelicula ORDER BY titulo ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $peliculas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $peliculas[] = new Pelicula(
                    $row['id_pelicula'], 
                    $row['titulo'], 
                    $row['director'], 
                    $row['clasificacion'], 
                    $row['url_image'], 
                    $row['genero_id_genero']
                );
            }
            return $peliculas;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerPeliculasPaginadas($page = 1, $genero_id = null) {
        try {
            $offset = ($page - 1) * $this->limit;
            $sql = "SELECT p.*, g.nombre_genero 
                    FROM pelicula p 
                    JOIN genero g ON p.genero_id_genero = g.id_genero";
            
            if ($genero_id) {
                $sql .= " WHERE p.genero_id_genero = :genero_id";
            }
            $sql .= " LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $this->limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            if ($genero_id) {
                $stmt->bindValue(':genero_id', (int)$genero_id, PDO::PARAM_INT);
            }
            $stmt->execute();
            
            $peliculas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $peliculas[] = new Pelicula(
                    $row['id_pelicula'], 
                    $row['titulo'], 
                    $row['director'], 
                    $row['clasificacion'], 
                    $row['url_image'], 
                    $row['genero_id_genero']
                );
            }
            return $peliculas;
        } catch (PDOException $e) {
            error_log("Error en PeliculaDAO::obtenerPeliculasPaginadas: " . $e->getMessage());
            return [];
        }
    }

    public function contarPeliculas($genero_id = null) {
        $sql = "SELECT COUNT(*) FROM pelicula";
        if ($genero_id) {
            $sql .= " WHERE genero_id_genero = :genero_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['genero_id' => $genero_id]);
            return (int)$stmt->fetchColumn();
        }
        return (int)$this->db->query($sql)->fetchColumn();
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT * FROM pelicula WHERE id_pelicula = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            return new Pelicula(
                $row['id_pelicula'], 
                $row['titulo'], 
                $row['director'], 
                $row['clasificacion'], 
                $row['url_image'], 
                $row['genero_id_genero']
            );
        } catch (PDOException $e) {
            return null;
        }
    }

    public function insertarPelicula(Pelicula $pelicula) {
        try {
            $sql = "INSERT INTO pelicula (titulo, director, clasificacion, url_image, genero_id_genero)
                    VALUES (:titulo, :director, :clasificacion, :url_image, :genero_id)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'titulo' => $pelicula->getTitulo(),
                'director' => $pelicula->getDirector(), 
                'clasificacion' => $pelicula->getClasificacion(),
                'url_image' => $pelicula->getUrlImage(),
                'genero_id' => $pelicula->getGeneroId(),
            ]);
        } catch (PDOException $e) {
            error_log("Error al insertar pelicula: ".$e->getMessage());
            return false;
        }
    }

    public function actualizarPelicula(Pelicula $pelicula) {
        try {
            $sql = "UPDATE pelicula 
                    SET titulo = :titulo, director = :director, 
                        clasificacion = :clasificacion, url_image = :url_image, 
                        genero_id_genero = :genero_id
                    WHERE id_pelicula = :id_pelicula";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'titulo' => $pelicula->getTitulo(),
                'director' => $pelicula->getDirector(), 
                'clasificacion' => $pelicula->getClasificacion(),
                'url_image' => $pelicula->getUrlImage(),
                'genero_id' => $pelicula->getGeneroId(),
                'id_pelicula' => $pelicula->getIdPelicula()
            ]);
        } catch (PDOException $e) {
            error_log("Error al actualizar pelicula: ".$e->getMessage());
            return false;
        }
    }

    public function obtenerEstadisticasPeliculas() {
        try {
            $sql = "SELECT p.titulo, p.director, p.clasificacion, g.nombre_genero as genero 
                    FROM pelicula p 
                    JOIN genero g ON p.genero_id_genero = g.id_genero 
                    ORDER BY p.id_pelicula DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerEstadisticasPeliculas: " . $e->getMessage());
            return [];
        }
    }
}
?>