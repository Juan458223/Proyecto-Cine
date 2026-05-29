<?php
session_start();
require_once __DIR__ . '/../Service/TarifaService.php';

$is_admin = isset($_SESSION['usuario_id']) && $_SESSION['permisos'] == 1;
$tarifaService = new TarifaService();
$action = $_GET['action'] ?? 'list';

header('Content-Type: application/json');

switch ($action) {
    case 'list_by_cine':
        $cine_id = $_GET['cine_id'] ?? 0;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $tarifas = $tarifaService->obtenerTarifasPorCine($cine_id, $page);
        $total = $tarifaService->contarTarifasPorCine($cine_id);
        $pages = ceil($total / 5); // Límite de 5 para el modal
        
        $data = array_map(fn($t) => [
            'id' => $t->getIdTarifa(),
            'categoria' => $t->getCategoria(),
            'tipo_dia' => $t->getNombreDia(),
            'precio' => $t->getPrecio()
        ], $tarifas);
        
        echo json_encode(['data' => $data, 'pages' => $pages, 'currentPage' => $page]);
        break;
}
?>