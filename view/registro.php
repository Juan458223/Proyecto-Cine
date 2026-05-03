<?php
include('../model/usuarios.php');
?>
<!doctype html>
<html lang="es">
    <head>

        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registrar Usuario</title>
    </head>
    <body>
        <form id="form" name="form" action="/proyectocine/controller/registrar_usuario.php" method="post" novalidate>
            <div class="row">
                <div class="col-md-6">
                    <label for="nom">NOMBRES</label>
                    <input type="text" name="nom" id="nom">
                </div>
                
                <div class="col-md-6">
                    <label for="em">EMAIL</label>
                    <input type="email" name="correo" id="correo">
                </div>
                <div class="col-md-6">
                    <label for="ape">CONTRASEÑA</label>
                    <input type="text" name="pass" id="passs">
                </div>
                <div class="col-md-6">
                    <label for="user">¿Cual es tu color Favorito?</label>
                    <input type="text" name="pregunta1" class="form-control" placeholder="¿Color Favorito?">
                </div>
                <div class="col-md-6">
                    <label for="user">¿Cual es el nombre de tu mascota?</label>
                    <input type="text" name="pregunta2" class="form-control" placeholder="Nombre de la Mascota">
                </div>
                <div class="col-md-6">
                    <label for="user">¿Donde vives?</label>
                    <input type="text" name="pregunta3" class="form-control" placeholder="Ciudad">
                </div>
                <div class="col-md-12">
                    <br>
                    <input type="submit" value="REGISTRAR EMPLEADO">
                </div>
            </div>
        </form>
    </body>
</html>