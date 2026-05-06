<?php
    include("../model/usuario.php");
    $respuestas = new Usuario();
    $correo = $_REQUEST['correo'];
    $pregunta1 = $_REQUEST['pregunta1'];
    $pregunta2 = $_REQUEST['pregunta2'];
    $pregunta3 = $_REQUEST['pregunta3'];
    $respuestas->verificarPreguntas($correo, $pregunta1, $pregunta2, $pregunta3);
?>