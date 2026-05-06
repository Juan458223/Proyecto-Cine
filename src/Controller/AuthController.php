<?php
require_once __DIR__ . '/../Service/AuthService.php';

$authService = new AuthService();
$action = $_GET['action'] ?? 'login';

if ($action === 'login') {
    $correo = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($correo) || empty($password)) {
        echo "Por favor, completa todos los campos.";
        exit;
    }

    $usuario = $authService->validar($correo, $password);
    
    if ($usuario === 'bloqueado') {
        echo "Tu cuenta está bloqueada. Por favor, contacta al administrador.";
    } elseif ($usuario === 'pendiente') {
        $userData = (new UsuarioDAO())->obtenerUsuarioPorCorreo($correo);
        $usuarioDto = new UsuarioDTO($userData['id'], $userData['nombre'], $userData['correo']);
        $authService->generarToken($usuarioDto, 'register_user');
        echo "Cuenta pendiente de activación. Se ha enviado un nuevo código.";
    } elseif ($usuario != null) {
        $authService->generarToken($usuario, 'validate_user');
        echo "Token generado con éxito.";
    } else {
        echo "Correo o contraseña incorrectos.";
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

    if (!$authService->validarPassword($password)) {
        echo "La contraseña debe tener mínimo 6 caracteres, una mayúscula y números.";
        exit;
    }

    $usuario = $authService->registrarUsuario($nombre, $correo, $password);
    if ($usuario) {
        $authService->generarToken($usuario, 'register_user');
        echo "Registro exitoso. Token enviado.";
    } else {
        echo "Error al registrar el usuario. El correo ya existe.";
    }

} elseif ($action === 'recover') {
    $correo = $_POST['email'] ?? '';
    if (empty($correo)) {
        echo "Ingresa tu correo electrónico.";
        exit;
    }

    $userData = (new UsuarioDAO())->obtenerUsuarioPorCorreo($correo);
    if ($userData) {
        $usuario = new UsuarioDTO($userData['id'], $userData['nombre'], $userData['correo']);
        $authService->generarToken($usuario, 'reset_password');
        echo "Código de recuperación enviado.";
    } else {
        echo "Correo no encontrado.";
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
    
    if (isset($resultado['resultado'])) {
        echo $resultado['resultado'];
    } else {
        echo "Error en la validación.";
    }
}
?>
