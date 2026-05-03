<?php
include("../model/usuarios.php");
$log = new Usuario();
$correo=$_REQUEST['correo'];
$pass=$_REQUEST['passw'];
$log->verificarUsuario($correo,$pass);
?>