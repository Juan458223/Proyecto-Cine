<?php
session_start();
require_once __DIR__ . '/../Service/PeliculaService.php';
require_once __DIR__ . '/../Service/UsuarioService.php';
require_once __DIR__ . '/../Service/ProtagonistaService.php';
require_once __DIR__ . '/../Dao/CineDAO.php';
require_once __DIR__ . '/../Dao/SalaDAO.php';
require_once __DIR__ . '/../Dao/FuncionDAO.php';
require_once __DIR__ . '/../Dao/TarifaDAO.php';
require_once __DIR__ . '/../Dao/GeneroDAO.php';
require_once __DIR__ . '/../Model/Pelicula.php';
require_once __DIR__ . '/../Model/Usuario.php';
require_once __DIR__ . '/../Model/Protagonista.php';

// Verificación de seguridad
if (!isset($_SESSION['usuario_id']) || $_SESSION['permisos'] != 1) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$action = $_GET['action'] ?? 'list';
$type = $_GET['type'] ?? 'movies';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$peliculaService = new PeliculaService();
$usuarioService = new UsuarioService();
$protagonistaService = new ProtagonistaService();
$cineDAO = new CineDAO();
$salaDAO = new SalaDAO();
$funcionDAO = new FuncionDAO();
$tarifaDAO = new TarifaDAO();
$generoDAO = new GeneroDAO();

header('Content-Type: application/json');

if ($action === 'genres') {
    $data = $generoDAO->obtenerTodos();
    echo json_encode(['data' => $data]);
    exit;
}

if ($action === 'cines_list') {
    $data = $cineDAO->obtenerTodos();
    echo json_encode(['data' => $data]);
    exit;
}

if ($action === 'salas_list') {
    $data = $salaDAO->obtenerTodas();
    echo json_encode(['data' => $data]);
    exit;
}

if ($action === 'tarifas_list') {
    $data = $tarifaDAO->obtenerTodas();
    echo json_encode(['data' => $data]);
    exit;
}

if ($action === 'list') {
    $data = [];
    $total = 0;

    switch ($type) {
        case 'users':
            $data = $usuarioService->listarUsuariosPaginados($limit, $offset);
            $total = $usuarioService->contarUsuarios();
            break;
        case 'protagonists':
            $protagonistas = $protagonistaService->listarProtagonistasPaginados($limit, $offset);
            $total = $protagonistaService->contarProtagonistas();
            $data = array_map(fn($p) => ['id' => $p->getIdActor(), 'nombre' => $p->getNombre()], $protagonistas);
            break;
        case 'movies':
            $peliculas = $peliculaService->obtenerPeliculasPaginadas($limit, $offset);
            $total = $peliculaService->contarPeliculas();
            $data = array_map(fn($p) => [
                'id' => $p->getIdPelicula(),
                'titulo' => $p->getTitulo(),
                'director' => $p->getDirector(),
                'clasificacion' => $p->getClasificacion(),
                'genero' => $p->getGenero()
            ], $peliculas);
            break;
        case 'cines':
            $res = $cineDAO->obtenerTodos($limit, $offset);
            $total = $cineDAO->contarTodos();
            $data = array_map(fn($c) => [
                'id' => $c['id_cine'],
                'Nombre' => $c['nombre'],
                'Direccion' => $c['direccion'],
                'Telefono' => $c['telefono']
            ], $res);
            break;
        case 'salas':
            $res = $salaDAO->obtenerTodas($limit, $offset);
            $total = $salaDAO->contarTodas();
            $data = array_map(fn($s) => [
                'id' => $s['id_sala'],
                'Capacidad' => $s['capacidad'],
                'Cine' => $s['cine_nombre']
            ], $res);
            break;
        case 'funciones':
            $res = $funcionDAO->obtenerTodas($limit, $offset);
            $total = $funcionDAO->contarTodas();
            $data = array_map(fn($f) => [
                'id' => $f['id_funcion'],
                'Fecha' => $f['fecha_hora'],
                'Pelicula' => $f['pelicula_titulo'],
                'Cine' => $f['cine_nombre'],
                'Sala' => $f['sala_id_sala'],
                'Vendidas' => $f['boletas_vendidas'],
                'Tarifa' => $f['tarifa_id_dia']
            ], $res);
            break;
        case 'generos':
            $res = $generoDAO->obtenerTodos();
            $total = count($res);
            $data = array_slice($res, $offset, $limit);
            $data = array_map(fn($g) => ['id' => $g['id_genero'], 'Nombre' => $g['nombre_genero']], $data);
            break;
        case 'tarifas':
            $res = $tarifaDAO->obtenerTodas();
            $total = count($res);
            $data = array_slice($res, $offset, $limit);
            $data = array_map(fn($t) => ['id' => $t['id_dia'], 'Precio' => $t['precio']], $data);
            break;
        case 'estados':
            $db = DatabaseConnection::getInstance()->getConnection();
            $res = $db->query("SELECT * FROM estados")->fetchAll(PDO::FETCH_ASSOC);
            $total = count($res);
            $data = array_slice($res, $offset, $limit);
            $data = array_map(fn($e) => ['id' => $e['id'], 'Nombre' => $e['nombre']], $data);
            break;
        case 'tipos_tokens':
            $db = DatabaseConnection::getInstance()->getConnection();
            $res = $db->query("SELECT * FROM tipos_tokens")->fetchAll(PDO::FETCH_ASSOC);
            $total = count($res);
            $data = array_slice($res, $offset, $limit);
            $data = array_map(fn($t) => ['id' => $t['id'], 'Nombre' => $t['nombre']], $data);
            break;
        case 'tokens':
            $db = DatabaseConnection::getInstance()->getConnection();
            $res = $db->query("SELECT t.*, u.correo FROM tokens t JOIN usuarios u ON t.usuario_id = u.id ORDER BY t.fecha_c DESC")->fetchAll(PDO::FETCH_ASSOC);
            $total = count($res);
            $data = array_slice($res, $offset, $limit);
            $data = array_map(fn($t) => [
                'id' => $t['idtoken'],
                'Token' => $t['token_valor'],
                'Usuario' => $t['correo'],
                'Fecha' => $t['fecha_c']
            ], $data);
            break;
    }

    echo json_encode([
        'data' => $data,
        'total' => (int)$total,
        'pages' => ceil($total / $limit),
        'currentPage' => $page
    ]);
    exit;
}

if ($action === 'get') {
    $id = $_GET['id'] ?? null;
    $data = null;
    if (!$id) exit;

    switch ($type) {
        case 'users':
            $users = $usuarioService->listarUsuariosPaginados(100, 0);
            foreach ($users as $u) if ($u['id_usuario'] == $id) $data = $u;
            break;
        case 'protagonists':
            $p = $protagonistaService->obtenerProtagonistaPorId($id);
            if ($p) $data = ['nombre' => $p->getNombre()];
            break;
        case 'movies':
            $peliculas = $peliculaService->listarPeliculas();
            foreach ($peliculas as $p) {
                if ($p->getIdPelicula() == $id) {
                    $data = [
                        'titulo' => $p->getTitulo(),
                        'director' => $p->getDirector(),
                        'clasificacion' => (int)str_replace('+', '', $p->getClasificacion()),
                        'url_image' => $p->getUrlImage()
                    ];
                    $gs = $generoDAO->obtenerTodos();
                    foreach ($gs as $g) if ($g['nombre_genero'] === $p->getGenero()) $data['genero_id'] = $g['id_genero'];
                }
            }
            break;
        case 'cines':
            $data = $cineDAO->obtenerPorId($id);
            break;
        case 'salas':
            $data = $salaDAO->obtenerPorId($id);
            break;
        case 'funciones':
            $data = $funcionDAO->obtenerPorId($id);
            break;
        case 'generos':
            $gs = $generoDAO->obtenerTodos();
            foreach ($gs as $g) if ($g['id_genero'] == $id) $data = $g;
            break;
        case 'tarifas':
            $data = $tarifaDAO->obtenerPorId($id);
            break;
        case 'estados':
            $db = DatabaseConnection::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT * FROM estados WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            break;
    }
    echo json_encode($data);
    exit;
}

if ($action === 'delete') {
    $id = $_POST['id'] ?? null;
    $success = false;
    if (!$id) exit;

    switch ($type) {
        case 'users': $success = ($id != $_SESSION['usuario_id']) ? $usuarioService->eliminarUsuario($id) : false; break;
        case 'protagonists': $success = $protagonistaService->eliminarProtagonista($id); break;
        case 'movies': $success = $peliculaService->eliminarPelicula($id); break;
        case 'cines': $success = $cineDAO->eliminar($id); break;
        case 'salas': $success = $salaDAO->eliminar($id); break;
        case 'funciones': $success = $funcionDAO->eliminar($id); break;
        case 'generos': $success = $generoDAO->eliminarGenero($id); break;
        case 'tarifas': $success = $tarifaDAO->eliminar($id); break;
        case 'estados': 
            $db = DatabaseConnection::getInstance()->getConnection();
            $stmt = $db->prepare("DELETE FROM estados WHERE id = ?");
            $success = $stmt->execute([$id]);
            break;
    }
    echo json_encode(['success' => $success]);
    exit;
}

if ($action === 'insert' || $action === 'update') {
    $id = $_POST['id'] ?? null;
    $success = false;

    switch ($type) {
        case 'movies':
            $p = new Pelicula($id ?? 0, $_POST['titulo'], $_POST['director'], (int)$_POST['clasificacion'], $_POST['url_image'], (int)$_POST['genero_id']);
            $success = $id ? $peliculaService->actualizarPelicula($p) : $peliculaService->insertarPelicula($p->getTitulo(), $p->getDirector(), $p->getClasificacion(), $p->getUrlImage(), $p->getGeneroId());
            break;
        case 'users':
            $u = new Usuario($_POST['nombre'], $_POST['email'], '', (int)$_POST['permisos'], $id);
            $u->setEstadoId((int)$_POST['estado_id']);
            if (!$id) $u->setPassword(password_hash('CineFirst123*', PASSWORD_BCRYPT));
            else {
                $existing = (new UsuarioDAO())->obtenerUsuarioPorCorreo($_POST['email']);
                $u->setPassword($existing['password']);
            }
            $success = $id ? $usuarioService->actualizarUsuario($u) : $usuarioService->insertarUsuario($u);
            break;
        case 'protagonists':
            $success = $id ? $protagonistaService->actualizarProtagonista($id, $_POST['nombre']) : $protagonistaService->insertarProtagonista($_POST['nombre']);
            break;
        case 'cines':
            $success = $id ? $cineDAO->actualizar($id, $_POST['nombre'], $_POST['direccion'], $_POST['telefono']) : $cineDAO->insertar($_POST['nombre'], $_POST['direccion'], $_POST['telefono']);
            break;
        case 'salas':
            $success = $id ? $salaDAO->actualizar($id, $_POST['capacidad'], $_POST['cine_id']) : $salaDAO->insertar($_POST['capacidad'], $_POST['cine_id']);
            break;
        case 'funciones':
            $success = $id ? $funcionDAO->actualizar($id, $_POST['fecha_hora'], $_POST['pelicula_id'], $_POST['sala_id'], $_POST['boletas_vendidas'], $_POST['tarifa_id']) : $funcionDAO->insertar($_POST['fecha_hora'], $_POST['pelicula_id'], $_POST['sala_id'], $_POST['boletas_vendidas'], $_POST['tarifa_id']);
            break;
        case 'generos':
            $success = $id ? $generoDAO->actualizarGenero($id, $_POST['nombre']) : $generoDAO->insertarGenero($_POST['nombre']);
            break;
        case 'tarifas':
            $success = $id ? $tarifaDAO->actualizar($_POST['id_dia'], $_POST['precio']) : $tarifaDAO->insertar($_POST['id_dia'], $_POST['precio']);
            break;
        case 'estados':
            $db = DatabaseConnection::getInstance()->getConnection();
            if ($id) {
                $stmt = $db->prepare("UPDATE estados SET nombre = ? WHERE id = ?");
                $success = $stmt->execute([$_POST['nombre'], $id]);
            } else {
                $stmt = $db->prepare("INSERT INTO estados (nombre) VALUES (?)");
                $success = $stmt->execute([$_POST['nombre']]);
            }
            break;
    }
    echo json_encode(['success' => (bool)$success]);
    exit;
}
?>
