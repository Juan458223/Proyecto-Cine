<?php
    $id = $_GET['id'];
    include("../model/usuarios.php");
    if(isset($_SESSION['id']) && $_SESSION['permisos']==1){ 
        $log = new Usuario();
        $log->elminarUsuario($id);
    }else{
        echo "error";
    }
?>