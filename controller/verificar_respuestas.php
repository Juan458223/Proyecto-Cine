<?php
    include("../model/respuestas.php");
    $respuestas = new respuestas();
    $correo = $_REQUEST['correo'];
    $pregunta1 = $_REQUEST['pregunta1'];
    $pregunta2 = $_REQUEST['pregunta2'];
    $pregunta3 = $_REQUEST['pregunta3'];
    $respuestas->verificar($correo, $pregunta1, $pregunta2, $pregunta3);
?>