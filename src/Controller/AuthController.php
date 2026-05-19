<?php
session_start();
require_once __DIR__ . '/../Service/AuthService.php';

$authService = new AuthService();
$action = $_GET['action'] ?? 'login';

if ($action === 'login') {
    $correo = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($correo) || empty($password)) {
        echo "Por favor, complete todos los campos.";
        exit;
    }

    $usuario = $authService->validar($correo, $password);
    
    if ($usuario === 'bloqueado') {
        echo "Su cuenta está desactivada. Si cree que esto ha sido un error, por favor contacte al administrador.";
    } elseif ($usuario === 'pendiente') {
        $userData = (new UsuarioDAO())->obtenerUsuarioPorCorreo($correo);
        $usuarioDto = new UsuarioDTO($userData['id'], $userData['nombre'], $userData['correo'], $userData['permisos']);
        $authService->generarToken($usuarioDto, 'register_user');
        echo "Estado de usuario: pendiente. Se ha enviado un nuevo token de activación.";
    } elseif ($usuario != null) {
        $authService->generarToken($usuario, 'validate_user');
        echo "Token generado con éxito.";
    } else {
        echo "Correo o contraseña incorrectos. Si cree que esto ha sido un error, por favor contacte al administrador.";
    }

} elseif ($action === 'change_password') {
    $correo = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($correo) || empty($password)) {
        echo "Datos incompletos.";
        exit;
    }

    if (!$authService->validarPassword($password)) {
        echo "La contraseña debe tener mínimo 6 caracteres, una mayúscula y números.";
        exit;
    }

    if ($authService->cambiarPassword($correo, $password)) {
        echo "Contraseña actualizada con éxito.";
    } else {
        echo "Error al actualizar la contraseña.";
    }

} elseif ($action === 'register') {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($nombre) || empty($correo) || empty($password)) {
        echo "Todos los campos son obligatorios.";
        exit;
    }

    if (!$authService->validarCorreo($correo)) {
        echo "Solo se permiten correos de Gmail (@gmail.com).";
        exit;
    }

    if (!$authService->validarPassword($password)) {
        echo "La contraseña debe tener mínimo 6 caracteres, una mayúscula y números.";
        exit;
    }

    $usuario = $authService->registrarUsuario($nombre, $correo, $password);
    if ($usuario) {
        $authService->generarToken($usuario, 'register_user');
        echo "Estado de usuario: pendiente. Registro exitoso. Verifique su correo.";
    } else {
        echo "Error al registrar el usuario. El correo ya existe. Si cree que esto ha sido un error, por favor contacte al administrador.";
    }

} elseif ($action === 'recover') {
    $correo = $_POST['email'] ?? '';
    if (empty($correo)) {
        echo "Ingrese su correo electrónico.";
        exit;
    }

    if (!$authService->validarCorreo($correo)) {
        echo "Solo se permiten correos de Gmail (@gmail.com).";
        exit;
    }

    $userData = (new UsuarioDAO())->obtenerUsuarioPorCorreo($correo);
    if ($userData) {
        $usuario = new UsuarioDTO($userData['id'], $userData['nombre'], $userData['correo'], $userData['permisos']);
        $authService->generarToken($usuario, 'reset_password');
        echo "Token de recuperación generado con éxito.";
    } else {
        echo "Correo no encontrado.  Si cree que esto ha sido un error, por favor contacte al administrador.";
    }

} elseif ($action === 'verify') {
    $correo = $_POST['email'] ?? '';
    $token = $_POST['token'] ?? '';
    $type = $_POST['type'] ?? 'validate_user';

    if (empty($correo) || empty($token)) {
        echo "Faltan parámetros.";
        exit;
    }

    $resultado = $authService->validarToken($correo, $token, $type);
    
    if (isset($resultado['resultado']) && $resultado['resultado'] === 'token valido') {

        $userData = (new UsuarioDAO())->obtenerUsuarioPorCorreo($correo);
        if ($userData) {
            $_SESSION['usuario_id'] = $userData['id'];
            $_SESSION['usuario_nombre'] = $userData['nombre'];
            $_SESSION['usuario_correo'] = $userData['correo'];
            $_SESSION['permisos'] = $userData['permisos'];
        }
        echo "token valido";
    } elseif (isset($resultado['resultado'])) {
        echo $resultado['resultado']; 
    } else {
        echo "Error en la validación.";
    }
} elseif ($action === 'update_profile') {
    header('Content-Type: application/json');
    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode(['success' => false, 'error' => 'No autorizado']);
        exit;
    }

    $nombre = $_POST['nombre'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($nombre)) {
        echo json_encode(['success' => false, 'error' => 'El nombre es obligatorio']);
        exit;
    }

    $success = $authService->actualizarPerfil($_SESSION['usuario_id'], $nombre, $password);
    
    if ($success) {
        $_SESSION['usuario_nombre'] = $nombre;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al actualizar el perfil']);
    }
}
?>