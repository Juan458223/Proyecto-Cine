<?php
session_start();
require_once __DIR__ . '/../Service/GeneroService.php';

$is_admin = isset($_SESSION['usuario_id']) && $_SESSION['permisos'] == 1;
$generoService = new GeneroService();
$action = $_GET['action'] ?? 'list';

header('Content-Type: application/json');

switch ($action) {
    case 'list':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $generos = $generoService->listarGeneros($page);
        $total = $generoService->contarGeneros();
        $pages = ceil($total / 6);
        
        $data = array_map(fn($g) => [
            'id' => $g->getIdGenero(),
            'nombre' => $g->getNombreGenero()
        ], $generos);
        
        echo json_encode(['data' => $data, 'pages' => $pages, 'currentPage' => $page]);
        break;

    case 'insert':
    case 'update':
        if (!$is_admin) { echo json_encode(['success' => false, 'error' => 'No autorizado']); exit; }
        $id = $_POST['id'] ?? null;
        $success = $id 
            ? $generoService->actualizarGenero($id, $_POST['nombre'])
            : $generoService->insertarGenero($_POST['nombre']);
        echo json_encode(['success' => (bool)$success]);
        break;

    case 'delete':
        if (!$is_admin) { echo json_encode(['success' => false, 'error' => 'No autorizado']); exit; }
        echo json_encode(['success' => $generoService->eliminarGenero($_POST['id'] ?? null)]);
        break;
}
?>