<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Olvide Mi Contraseña</title>
    </head>
    <body>
        <h3>Ingrese su email: </h3>
        <form action="/proyectocine/controller/verificaremail.php">
            <div class="col-md-6">
                <label for="user">EMAIL</label>
                <input type="correo_rec" name="correo_rec" class="form-control" placeholder="DIGITE EL CORREO">
            </div>
            <div>
                <input type="submit" value="Buscar email">
            </div>
        </form>
    </body>
</html>