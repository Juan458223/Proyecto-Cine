<?php
session_start();
require_once __DIR__ . '/../Service/SalaService.php';

$is_admin = isset($_SESSION['usuario_id']) && $_SESSION['permisos'] == 1;
$salaService = new SalaService();
$action = $_GET['action'] ?? 'list';

header('Content-Type: application/json');

switch ($action) {
    case 'list':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $salas = $salaService->obtenerSalasPaginadas($page);
        $total = $salaService->contarSalas();
        $pages = ceil($total / 6);
        
        $data = array_map(fn($s) => [
            'id' => $s->getIdSala(),
            'numero' => $s->getNumeroSala(),
            'capacidad' => $s->getCapacidad(),
            'cine' => $s->getCine()->getNombre()
        ], $salas);
        
        echo json_encode(['data' => $data, 'pages' => $pages, 'currentPage' => $page]);
        break;

    case 'insert':
    case 'update':
        if (!$is_admin) { echo json_encode(['success' => false, 'error' => 'No autorizado']); exit; }
        $id = $_POST['id'] ?? null;
        $success = $id 
            ? $salaService->actualizarSala($id, $_POST['capacidad'], $_POST['cine_id'])
            : $salaService->insertarSala($_POST['capacidad'], $_POST['cine_id']);
        echo json_encode(['success' => (bool)$success]);
        break;

    case 'delete':
        if (!$is_admin) { echo json_encode(['success' => false, 'error' => 'No autorizado']); exit; }
        echo json_encode(['success' => $salaService->eliminarSala($_POST['id'] ?? null)]);
        break;
}
?>