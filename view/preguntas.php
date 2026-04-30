<?php
    if (isset($_GET['correo'])) {
        $correo = $_GET['correo'];
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Preguntas de Seguridad</title>
    </head>
    <body>
        <div class="container">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="text-white text-center">LOGIN DE CINE</h3>
                </div>
                <div class="card-body">
                    <form name="form" action="/proyectocine/controller/verificar_respuestas.php" method="post">
                        <div class="row">
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
                                <input type="hidden" name="correo" value="<?php echo $correo; ?>">
                                <input type="submit" class="btn btn-primary" value="Enviar">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>

<?php
    }else{
        
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ERROR</title>
    </head>
    <body>
        <h1>ALGO SALIO MAL, VUELVA A INTENTARLO</h1>
    </body>
</html>
<?php
    }
?>