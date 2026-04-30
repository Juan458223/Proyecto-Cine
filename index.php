<!doctype html>
<html lang="es">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LOGIN DE Barcos</title>
    </head>
    <body>
        <div class="container">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="text-white text-center">LOGIN DE CINE</h3>
                </div>
                <div class="card-body">
                    <form name="form" action="/proyectocine/controller/verificar_contraseña.php" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="user">CORREO</label>
                                <input type="text" name="correo" class="form-control" placeholder="DIGITE EL CORREO">
                            </div>
                            <div class="col-md-6">
                                <label for="passw">PASSWORD</label>
                                <input type="password" name="passw" class="form-control" placeholder="DIGITE EL PASSWORD">
                            </div>
                                <div class="col-md-12">
                                <br>

                                <a href="./view/DatosRecordar.php">
                                    Olvide mi Contraseña...
                                </a>

                                <input type="submit" class="btn btn-primary" value="INGRESAR" >
                            </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
    </body>
</html>