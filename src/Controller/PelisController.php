<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../Service/PeliculaService.php';

$peliculaService = new PeliculaService();
$action = $_GET['action'] ?? 'renderGrid';

if ($action === 'renderGrid') {
    // El método obtenerTablas ya realiza el echo de las tarjetas HTML
    $peliculaService->obtenerTablas();

} elseif ($action === 'tablaPelicula') {
    // Compatibilidad con llamadas antiguas
    $peliculaService->obtenerTablas();
}
?>
