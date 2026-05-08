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

    public function desvincular($id_pelicula, $id_protagonista) {
        try {
            $sql = "DELETE FROM pelicula_has_protagonistas 
                    WHERE pelicula_id_pelicula = :id_peli 
                    AND protagonistas_id_protagonista = :id_prota";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id_peli' => $id_pelicula,
                'id_prota' => $id_protagonista
            ]);
        } catch (PDOException $e) {
            error_log("Error en PeliculaProtagonistaDAO::desvincular: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarTodosPorPelicula($id_pelicula) {
        try {
            $sql = "DELETE FROM pelicula_has_protagonistas WHERE pelicula_id_pelicula = :id_peli";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id_peli' => $id_pelicula]);
        } catch (PDOException $e) {
            error_log("Error en PeliculaProtagonistaDAO::eliminarTodosPorPelicula: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerTodos() {
        try {
            $sql = "SELECT * FROM pelicula_has_protagonistas";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            // Retorna un array asociativo (como el código viejo)
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en PeliculaProtagonistaDAO::obtenerTodos: " . $e->getMessage());
            return [];
        }
    }
}
?>
