<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../Service/PeliculaService.php';

$peliculaService = new PeliculaService();
$action = $_GET['action'] ?? 'renderGrid';

if ($action === 'renderGrid') {
    $genero_id = isset($_GET['genero_id']) ? (int)$_GET['genero_id'] : null;
    $peliculaService->obtenerTablas($genero_id);

} elseif ($action === 'tablaPelicula') {
    // Compatibilidad con llamadas antiguas
    $peliculaService->obtenerTablas();
}
?>
