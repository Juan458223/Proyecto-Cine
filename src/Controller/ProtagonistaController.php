<?php
session_start();
require_once __DIR__ . '/../Service/ProtagonistaService.php';

$is_admin = isset($_SESSION['usuario_id']) && $_SESSION['permisos'] == 1;
$protagonistaService = new ProtagonistaService();
$action = $_GET['action'] ?? 'list';

header('Content-Type: application/json');

switch ($action) {
    case 'list':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $protagonistas = $protagonistaService->listarProtagonistasPaginados($page);
        $total = $protagonistaService->contarProtagonistas();
        $pages = ceil($total / 6);
        
        $data = array_map(fn($p) => [
            'id' => $p->getIdActor(),
            'nombre' => $p->getNombre()
        ], $protagonistas);
        
        echo json_encode(['data' => $data, 'pages' => $pages, 'currentPage' => $page]);
        break;

    case 'insert':
    case 'update':
        if (!$is_admin) { echo json_encode(['success' => false, 'error' => 'No autorizado']); exit; }
        $id = $_POST['id'] ?? null;
        $success = $id 
            ? $protagonistaService->actualizarProtagonista($id, $_POST['nombre'])
            : $protagonistaService->insertarProtagonista($_POST['nombre']);
        echo json_encode(['success' => (bool)$success]);
        break;

    case 'delete':
        if (!$is_admin) { echo json_encode(['success' => false, 'error' => 'No autorizado']); exit; }
        echo json_encode(['success' => $protagonistaService->eliminarProtagonista($_POST['id'] ?? null)]);
        break;
}
?>