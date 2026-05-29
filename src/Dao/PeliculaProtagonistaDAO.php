<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';

class PeliculaProtagonistaDAO {
    private $db;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function vincular($id_pelicula, $id_protagonista) {
        try {
            $sql = "INSERT INTO pelicula_has_protagonistas (pelicula_id_pelicula, protagonistas_id_protagonista) 
                    VALUES (:id_peli, :id_prota)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id_peli' => $id_pelicula,
                'id_prota' => $id_protagonista
            ]);
        } catch (PDOException $e) {
            error_log("Error en PeliculaProtagonistaDAO::vincular: " . $e->getMessage());
            return false;
        }
    }

    public function desvincularPorPelicula($id_pelicula) {
        try {
            $sql = "DELETE FROM pelicula_has_protagonistas WHERE pelicula_id_pelicula = :id_peli";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id_peli' => $id_pelicula]);
        } catch (PDOException $e) {
            error_log("Error en PeliculaProtagonistaDAO::desvincularPorPelicula: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerProtagonistasPorPelicula($id_pelicula) {
        try {
            $sql = "SELECT pr.nombre 
                    FROM pelicula_has_protagonistas php
                    JOIN protagonista pr ON php.protagonistas_id_protagonista = pr.id_actor
                    WHERE php.pelicula_id_pelicula = :id_pelicula";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_pelicula' => $id_pelicula]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Error en PeliculaProtagonistaDAO::obtenerProtagonistasPorPelicula: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerProtagonistasFullPorPelicula($id_pelicula) {
        try {
            $sql = "SELECT pr.id_actor as id, pr.nombre 
                    FROM pelicula_has_protagonistas php
                    JOIN protagonista pr ON php.protagonistas_id_protagonista = pr.id_actor
                    WHERE php.pelicula_id_pelicula = :id_pelicula";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_pelicula' => $id_pelicula]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en PeliculaProtagonistaDAO::obtenerProtagonistasFullPorPelicula: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerTodos() {
        try {
            $sql = "SELECT * FROM pelicula_has_protagonistas";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en PeliculaProtagonistaDAO::obtenerTodos: " . $e->getMessage());
            return [];
        }
    }
}
?>
