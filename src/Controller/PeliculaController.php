<?php
session_start();
require_once __DIR__ . '/../Service/PeliculaService.php';

// Verificación de seguridad
$is_admin = isset($_SESSION['usuario_id']) && $_SESSION['permisos'] == 1;

$peliculaService = new PeliculaService();
$action = $_GET['action'] ?? 'list';

header('Content-Type: application/json');

switch ($action) {
    case 'list':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $genero_id = isset($_GET['genero_id']) ? (int)$_GET['genero_id'] : null;
        
        $peliculas = $peliculaService->obtenerPeliculasPaginadas($page, $genero_id);
        $total = $peliculaService->contarPeliculas($genero_id);
        $pages = ceil($total / 6); // Límite fijo de 6
        
        $data = array_map(fn($p) => [
            'id' => $p->getIdPelicula(),
            'titulo' => $p->getTitulo(),
            'director' => $p->getDirector(),
            'clasificacion' => $p->getClasificacion(),
            'url_image' => $p->getUrlImage(),
            'genero' => $p->getGeneroId(), // Se puede expandir a nombre si el DAO lo trae
            'protagonistas' => $p->getProtagonistas(),
            'funciones' => $p->getFunciones()
        ], $peliculas);
        
        echo json_encode(['data' => $data, 'pages' => $pages, 'currentPage' => $page]);
        break;

    case 'insert':
    case 'update':
        if (!$is_admin) {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            exit;
        }
        $id = $_POST['id'] ?? null;
        $success = false;
        
        if ($action === 'insert') {
            $success = $peliculaService->insertarPelicula($_POST['titulo'], $_POST['director'], $_POST['clasificacion'], $_POST['url_image'], (int)$_POST['genero_id']);
        } else {
            $success = $peliculaService->actualizarPelicula($id, $_POST['titulo'], $_POST['director'], $_POST['clasificacion'], $_POST['url_image'], (int)$_POST['genero_id']);
        }
        echo json_encode(['success' => (bool)$success]);
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}
?>