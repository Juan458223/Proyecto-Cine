<?php
    include("../model/usuarios.php");
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_REQUEST['nom'];
        $pass = $_REQUEST['pass'];
        $correo = $_REQUEST['correo'];
        $pregunta1 = $_REQUEST['pregunta1'];
        $pregunta2 = $_REQUEST['pregunta2'];
        $pregunta3 = $_REQUEST['pregunta3'];

        $log = new Usuario();
        $log->insertarUsuario($nom, $pass, $correo, 0, $pregunta1, $pregunta2, $pregunta3);
    }
?>