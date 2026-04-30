<?php
include("../model/login.php");
$log = new Login();
$correo=$_REQUEST['correo'];
$pass=$_REQUEST['passw'];
$log->verificarUsuario($correo,$pass);
?>