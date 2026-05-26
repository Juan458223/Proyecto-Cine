<?php
session_start();
require_once __DIR__ . '/../Service/FuncionService.php';

$is_admin = isset($_SESSION['usuario_id']) && $_SESSION['permisos'] == 1;
$funcionService = new FuncionService();
$action = $_GET['action'] ?? 'list';

header('Content-Type: application/json');

switch ($action) {
    case 'list':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $funciones = $funcionService->obtenerFuncionesPaginadas($page);
        $total = $funcionService->contarFunciones();
        $pages = ceil($total / 6);
        
        $data = array_map(fn($f) => [
            'id' => $f->getIdFuncion(),
            'fecha' => $f->getFechaHora(),
            'pelicula' => $f->getPelicula()->getTitulo(),
            'sala' => $f->getSala()->getNumeroSala(),
            'cine' => $f->getSala()->getCine()->getNombre()
        ], $funciones);
        
        echo json_encode(['data' => $data, 'pages' => $pages, 'currentPage' => $page]);
        break;

    case 'insert':
    case 'update':
        if (!$is_admin) { echo json_encode(['success' => false, 'error' => 'No autorizado']); exit; }
        $id = $_POST['id'] ?? null;
        $success = $id 
            ? $funcionService->actualizarFuncion($id, $_POST['fecha_hora'], $_POST['pelicula_id'], $_POST['sala_id'])
            : $funcionService->insertarFuncion($_POST['fecha_hora'], $_POST['pelicula_id'], $_POST['sala_id']);
        echo json_encode(['success' => (bool)$success]);
        break;

    case 'delete':
        if (!$is_admin) { echo json_encode(['success' => false, 'error' => 'No autorizado']); exit; }
        echo json_encode(['success' => $funcionService->eliminarFuncion($_POST['id'] ?? null)]);
        break;
}
?>