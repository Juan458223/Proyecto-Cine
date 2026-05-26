<?php
session_start();
require_once __DIR__ . '/../Service/UsuarioService.php';

$is_admin = isset($_SESSION['usuario_id']) && $_SESSION['permisos'] == 1;

$usuarioService = new UsuarioService();
$action = $_GET['action'] ?? 'list';

header('Content-Type: application/json');

switch ($action) {
    case 'list':
        if (!$is_admin) {
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $users = $usuarioService->listarUsuariosPaginados($page);
        $total = $usuarioService->contarUsuarios();
        $pages = ceil($total / 6);
        
        $data = array_map(fn($u) => [
            'id' => $u->getId(),
            'nombre' => $u->getNombre(),
            'correo' => $u->getCorreo(),
            'permiso' => $u->getPermisos(),
            'estado' => $u->getEstado()
        ], $users);
        
        echo json_encode(['data' => $data, 'pages' => $pages, 'currentPage' => $page]);
        break;

    case 'get_profile':
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(null);
            exit;
        }
        $user = $usuarioService->obtenerUsuarioDTO($_SESSION['usuario_id']);
        if ($user) {
            echo json_encode([
                'nombre' => $user->getNombre(),
                'correo' => $user->getCorreo()
            ]);
        } else {
            echo json_encode(null);
        }
        break;

    case 'update_profile':
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            exit;
        }
        // Lógica de perfil ya manejada en AuthController, 
        // este controlador se enfoca en administración de usuarios.
        break;
}
?>