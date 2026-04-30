<?php
    include("../model/recordar.php");
    $recordar = new recordar();

    $correo_rec = $_REQUEST['correo_rec'];
    $recordar ->verificar($correo_rec);
?>