<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../Service/PeliculaService.php';

$peliculaService = new PeliculaService();
$action = $_GET['action'] ?? 'renderGrid';

if ($action === 'renderGrid') {
    $genero_id = isset($_GET['genero_id']) && $_GET['genero_id'] !== "" ? (int)$_GET['genero_id'] : null;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $peliculaService->obtenerTablas($genero_id, $page);

} elseif ($action === 'renderPagination') {
    $genero_id = isset($_GET['genero_id']) && $_GET['genero_id'] !== "" ? (int)$_GET['genero_id'] : null;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $peliculaService->obtenerPaginacion($genero_id, $page);

} elseif ($action === 'tablaPelicula') {
    // Compatibilidad con llamadas antiguas
    $peliculaService->obtenerTablas();
}
?>
