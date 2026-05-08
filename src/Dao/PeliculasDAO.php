<?php
    require_once __DIR__ . '/../Core/DatabaseConnection.php';
    require_once __DIR__ . '/../Model/Pelicula.php';
    class peliculaDAO {
        private $db;

        public function __construct() {
            $this->db = DatabaseConnection::getInstance()->getConnection();
        }

        public function capturarPelis(){
            $sql = "SELECT * FROM pelicula";
            $peli = $this->db->prepare($sql);
            $peli->execute();
            return $peli->fetchAll(PDO::FETCH_ASSOC);
        }

        public function insertarPelicula(Pelicula $pelicula) {
            try {
                $sql = "INSERT INTO pelicula (titulo, director, clasificacion, url_image, genero_id_genero)
                        VALUES (:titulo, :director, :clasificacion, :url_image, :genero_id)";
                $statement = $this->db->prepare($sql);
                $statement->execute([
                    'titulo'    => $pelicula->getTitulo(),
                    'director'  => $pelicula->getDirector(), 
                    'clasificacion'    => $pelicula->getClasificacion(),
                    'url_image'    => $pelicula->getUrlImage(),
                    'genero_id'    => $pelicula->getGeneroIdGenero(),
                ]);
                return $this->db->lastInsertId();
            } catch (PDOException $e) {
                error_log("Error al insertar pelicula: ".$e->getMessage());
                return false;
            }
        }

        public function actualizarPelicula(Pelicula $pelicula) {
            try {
                $sql = "UPDATE pelicula (titulo, director, clasificacion, url_image, genero_id_genero)
                        SET titulo = :titulo, director = :director, clasificacion = :clasificacion,
                        url_image = :url_image, genero_id_genero = genero_id
                        WHERE id_pelicula = :id_pelicula";
                $statement = $this->db->prepare($sql);
                $statement->execute([
                    'titulo'    => $pelicula->getTitulo(),
                    'director'  => $pelicula->getDirector(), 
                    'clasificacion'    => $pelicula->getClasificacion(),
                    'url_image'    => $pelicula->getUrlImage(),
                    'genero_id'    => $pelicula->getGeneroIdGenero(),
                    'id_pelicula'    => $pelicula->getIdPelicula()
                ]);
                return $statement->rowCount() > 0;
            } catch (PDOException $e) {
                error_log("Error al actualizar pelicula: ".$e->getMessage());
                return false;
            }
        }

        public function eliminarPelicula($id) {
            try {
                $sql = "DELETE FROM pelicula WHERE id_pelicula = :id_pelicula";
                $statement = $this->db->prepare($sql);
                $statement->execute(['id_pelicula' => $id]);
                return $statement->rowCount() > 0;  
            } catch (PDOException $e) {
                error_log("Error al eliminar la pelicula: ".$e->getMessage());
                return false;
            }
        }
    }
?>