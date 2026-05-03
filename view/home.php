<?php
session_start();
include('../model/peliculas.php');
if(isset($_SESSION['id'])){
?>
<!doctype html>
<html lang="es">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bienvenido UD Cinema</title>
    </head>
    <body>
        <header>
            <nav>
                <ul>
                    <li>Peliculas</li>
                    <li>Nuestros Cines</li>
                    <li>Mi perfil</li>
                </ul>
            </nav>
        </header>
        <div class="table-responsive">
            <table id="emple" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <td>N° Pelicula</td>
                        <td>Titulo</td>
                        <td>Director</td>
                        <td>Clasificacion</td>
                        <td>Imagen</td>
                        <td>Genero</td>
                        <td>Protagonistas</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $pelis = new peliculas();
                        $pelis->capturarPelis();
                        
                    ?>
                </tbody>
            </table>
        </div>
    </body>   
</html>
<?php
}else{
?>
<!doctype html>
<html lang="es">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bienvenido UD Cinema</title>
    </head>
    <body>
        <h1>Ocurrio un error, intente nuevamente mas tarde....</h1>
    </body>
</html>
<?php
}
?>