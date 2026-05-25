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

        public function capturarPelisPorGenero($genero_id){
            $sql = "SELECT * FROM pelicula WHERE genero_id_genero = :genero_id";
            $peli = $this->db->prepare($sql);
            $peli->execute(['genero_id' => $genero_id]);
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
                    'genero_id'    => $pelicula->getGenero(),
                ]);
                return $this->db->lastInsertId();
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
                $statement = $this->db->prepare($sql);
                return $statement->execute([
                    'titulo'    => $pelicula->getTitulo(),
                    'director'  => $pelicula->getDirector(), 
                    'clasificacion'    => $pelicula->getClasificacion(),
                    'url_image'    => $pelicula->getUrlImage(),
                    'genero_id'    => $pelicula->getGenero(),
                    'id_pelicula'  => $pelicula->getIdPelicula()
                ]);
            } catch (PDOException $e) {
                error_log("Error al actualizar pelicula: ".$e->getMessage());
                return false;
            }
        }

        public function eliminarPelicula($id) {
            try {
                // Primero eliminamos las funciones asociadas por integridad referencial
                $sqlFunciones = "DELETE FROM funcion WHERE pelicula_id_pelicula = :id";
                $stmtFunciones = $this->db->prepare($sqlFunciones);
                $stmtFunciones->execute(['id' => $id]);

                $sql = "DELETE FROM pelicula WHERE id_pelicula = :id_pelicula";
                $statement = $this->db->prepare($sql);
                $statement->execute(['id_pelicula' => $id]);
                return $statement->rowCount() > 0;  
            } catch (PDOException $e) {
                error_log("Error al eliminar la pelicula: ".$e->getMessage());
                return false;
            }
        }

        public function obtenerPeliculasPaginadas($limit, $offset, $genero_id = null) {
            $sql = "SELECT p.*, g.nombre_genero as genero_nombre 
                    FROM pelicula p 
                    LEFT JOIN genero g ON p.genero_id_genero = g.id_genero";
            
            if ($genero_id) {
                $sql .= " WHERE p.genero_id_genero = :genero_id";
            }
            
            $sql .= " LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            if ($genero_id) {
                $stmt->bindValue(':genero_id', (int)$genero_id, PDO::PARAM_INT);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function contarPeliculas($genero_id = null) {
            $sql = "SELECT COUNT(*) FROM pelicula";
            if ($genero_id) {
                $sql .= " WHERE genero_id_genero = :genero_id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(['genero_id' => $genero_id]);
                return $stmt->fetchColumn();
            }
            return $this->db->query($sql)->fetchColumn();
        }
        }?>