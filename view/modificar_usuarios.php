<?php
include ('../model/usuarios.php');
if(isset($_SESSION['id']) && $_SESSION['permisos']==1){   
    ?>
    <!doctype html>
    <html lang="es">
        <head>
            <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Modificar Usuarios</title>
        </head>
        <body>
            <div class="table-responsive">
                <table id="emple" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <td>Id de Cuenta</td>
                            <td>Nombres</td>
                            <td>Contraseña</td>
                            <td>Permisos</td>
                            <td>Correo</td>
                            <td>Respuesta de Seguridad 1</td>
                            <td>Respuesta de Seguridad 2</td>
                            <td>Respuesta de Seguridad 3</td>
                            <td>Editar</td>
                            <td>Eliminar</td>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $user = new Usuario();
                            $user->verUsuario();
                            
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
            <title>Modificar Usuarios</title>
        </head>
        <body>
            <h1>Ocurrio un error, intente nuevamente mas tarde....</h1>
        </body>
    </html>
    <?php
}
?>