<?php
    include("../model/usuarios.php");
    $recordar = new Usuario();

    $correo_rec = $_REQUEST['correo_rec'];
    $recordar ->verificarCorreo($correo_rec);
?>