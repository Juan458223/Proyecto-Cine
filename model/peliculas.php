<?php
    include('DatabaseConnection.php');
    class peliculas{
        private $db;

        public function __construct() {
            $this->db = DatabaseConnection::getInstance()->getConnection();
        }

        public function capturarPelis(){
            $sql = "SELECT * FROM pelicula";
            $peli = $this->db->prepare($sql);
            $peli->execute();
            $peliculas = $peli->fetchAll(PDO::FETCH_ASSOC);

            $sql2 = "SELECT * FROM pelicula_has_protagonistas";
            $phs = $this->db->prepare($sql2);
            $phs->execute();
            $pelihasp = $phs->fetchAll(PDO::FETCH_ASSOC);

            $sql3 = "SELECT * FROM protagonistas";
            $prota = $this->db->prepare($sql3);
            $prota->execute();
            $protagonistas = $prota->fetchAll(PDO::FETCH_ASSOC);

            if($peliculas){
                foreach ($peliculas as $pelis) {
                    echo "<tr>
                    <td>".$pelis['id_pelicula']."</td>
                    <td>".$pelis['titulo']."</td>
                    <td>".$pelis['director']."</td>
                    <td>".$pelis['clasificacion']."</td>
                    <td>".$pelis['url_image']."</td>";
                    if($pelis['genero_id_genero'] == "1"){
                        echo "<td>Dibujos</td>";
                    }elseif($pelis['genero_id_genero'] == "2"){
                        echo "<td>Comedia</td>";
                    }elseif($pelis['genero_id_genero'] == "3"){
                        echo "<td>Drama</td>";
                    }else{
                        echo "<td>404: ERROR</td>";
                    }
                    echo "<td style='text-align: center;'>";
                    foreach($pelihasp as $php){
                        
                        if($php['pelicula_id_pelicula']==$pelis['id_pelicula']){
                            
                            foreach($protagonistas as $protas){
                                if($protas['id_actor']==$php['protagonistas_id_protagonista']){
                                    echo $protas['nombre']."<br>";
                                }
                            }
                        }
                    }
                    echo "</td>";
                }
            }else{
                echo "no hay peliculas";
            }
            
        }
    }
?>