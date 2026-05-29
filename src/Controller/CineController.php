<?php
session_start();
require_once __DIR__ . '/../Service/CineService.php';

$is_admin = isset($_SESSION['usuario_id']) && $_SESSION['permisos'] == 1;

$cineService = new CineService();
$action = $_GET['action'] ?? 'list';

header('Content-Type: application/json');

switch ($action) {
    case 'list_all':
        $cines = $cineService->obtenerTodosSinPaginar();
        $data = array_map(fn($c) => [
            'id' => $c->getIdCine(),
            'nombre' => $c->getNombre()
        ], $cines);
        echo json_encode(['data' => $data]);
        break;

    case 'list':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $cines = $cineService->obtenerCinesPaginados($page);
        $total = $cineService->contarCines();
        $pages = ceil($total / 6);
        
        $data = array_map(fn($c) => [
            'id' => $c->getIdCine(),
            'Nombre' => $c->getNombre(),
            'Calle' => $c->getCalle(),
            'Numero' => $c->getNumero(),
            'Telefono' => $c->getTelefono()
        ], $cines);
        
        echo json_encode(['data' => $data, 'pages' => $pages, 'currentPage' => $page]);
        break;

    case 'insert':
    case 'update':
        if (!$is_admin) {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            exit;
        }
        $id = $_POST['id'] ?? null;
        $success = $id 
            ? $cineService->actualizarCine($id, $_POST['nombre'], $_POST['calle'], $_POST['numero'], $_POST['telefono'])
            : $cineService->insertarCine($_POST['nombre'], $_POST['calle'], $_POST['numero'], $_POST['telefono']);
        echo json_encode(['success' => (bool)$success]);
        break;
}
?>