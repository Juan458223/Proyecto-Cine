<?php
/**
 * Script de Cierre de Sesión
 * Este archivo se encarga de limpiar la memoria del servidor y redirigir al usuario.
 */

// Iniciamos la sesión para poder acceder a ella y cerrarla
session_start();

// Limpiamos todas las variables de la sesión actual
$_SESSION = array();

// Destruimos la sesión en el servidor
session_destroy();

// Redirigimos al usuario a la página de inicio (Login)
header("Location: index.php");
exit();
?>
