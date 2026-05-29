<?php
session_start();
ob_clean();
header('Content-Type: application/json');

// Verificación de seguridad básica
$is_admin = isset($_SESSION['usuario_id']) && $_SESSION['permisos'] === 'Administrador';
if (!$is_admin) {
    echo json_encode(['success' => false, 'error' => 'Acceso denegado']);
    exit;
}

require_once __DIR__ . '/../Service/PeliculaService.php';
require_once __DIR__ . '/../Service/CineService.php';
require_once __DIR__ . '/../Service/SalaService.php';
require_once __DIR__ . '/../Service/FuncionService.php';
require_once __DIR__ . '/../Service/GeneroService.php';
require_once __DIR__ . '/../Service/TarifaService.php';
require_once __DIR__ . '/../Service/ProtagonistaService.php';
require_once __DIR__ . '/../Service/UsuarioService.php';

$action = $_GET['action'] ?? 'list';
$type = $_GET['type'] ?? '';

switch ($action) {
    case 'list':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 6;
        $data = [];
        $total = 0;

        switch ($type) {
            case 'movies':
                $service = new PeliculaService();
                $items = $service->obtenerPeliculasPaginadas($page);
                $total = $service->contarPeliculas();
                $data = array_map(fn($p) => [
                    'id' => $p->getIdPelicula(),
                    'titulo' => $p->getTitulo(),
                    'director' => $p->getDirector(),
                    'clasificacion' => $p->getClasificacion(),
                    'genero' => $p->getGeneroId() 
                ], $items);
                break;
            case 'users':
                $service = new UsuarioService();
                $excluded_id = $_SESSION['usuario_id'] ?? null;
                $items = $service->listarUsuariosPaginados($page, $excluded_id);
                $total = $service->contarUsuarios($excluded_id);
                $data = array_map(fn($u) => [
                    'id' => $u->getId(),
                    'nombre' => $u->getNombre(),
                    'correo' => $u->getCorreo(),
                    'estado' => $u->getEstado(),
                    'permisos' => $u->getPermisos(),
                    'fecha_registro' => $u->registro
                ], $items);
                break;
            case 'cines':
                $service = new CineService();
                $items = $service->obtenerCinesPaginados($page);
                $total = $service->contarCines();
                $data = array_map(fn($c) => [
                    'id' => $c->getIdCine(),
                    'nombre' => $c->getNombre(),
                    'calle' => $c->getCalle(),
                    'numero' => $c->getNumero(),
                    'telefono' => $c->getTelefono()
                ], $items);
                break;
            case 'salas':
                $service = new SalaService();
                $items = $service->obtenerSalasPaginadas($page);
                $total = $service->contarSalas();
                $data = array_map(fn($s) => [
                    'id' => $s->getIdSala(),
                    'cine' => $s->getCine() ? $s->getCine()->getNombre() : 'N/A',
                    'numero_sala' => $s->getNumeroSala(),
                    'capacidad' => $s->getCapacidad()
                ], $items);
                break;
            case 'funciones':
                $service = new FuncionService();
                $items = $service->obtenerFuncionesPaginadas($page);
                $total = $service->contarFunciones();
                $data = array_map(fn($f) => [
                    'id' => $f->getIdFuncion(),
                    'pelicula' => $f->getPelicula()->getTitulo(),
                    'cine' => $f->getSala()->getCine()->getNombre(),
                    'sala' => $f->getSala()->getNumeroSala(),
                    'fecha' => $f->getFechaHora()
                ], $items);
                break;
            case 'generos':
                $service = new GeneroService();
                $items = $service->listarGeneros($page);
                $total = $service->contarGeneros();
                $data = array_map(fn($g) => [
                    'id' => $g->getIdGenero(),
                    'nombre' => $g->getNombreGenero()
                ], $items);
                break;
            case 'tarifas':
                $service = new TarifaService();
                $items = $service->obtenerTarifasPorCine(0, $page);
                $total = $service->contarTarifasPorCine(0);
                $data = array_map(fn($t) => [
                    'id' => $t->getIdTarifa(),
                    'cine' => $t->getCine()->getNombre(),
                    'dia' => $t->getNombreDia(),
                    'categoria' => $t->getCategoria(),
                    'precio' => $t->getPrecio()
                ], $items);
                break;
            case 'protagonists':
                $service = new ProtagonistaService();
                $items = $service->listarProtagonistasPaginados($page);
                $total = $service->contarProtagonistas();
                $data = array_map(fn($p) => [
                    'id' => $p->getIdActor(),
                    'nombre' => $p->getNombre()
                ], $items);
                break;
        }

        echo json_encode([
            'data' => $data,
            'pages' => ceil($total / $limit),
            'currentPage' => $page
        ]);
        break;

    case 'get':
        $id = $_GET['id'] ?? 0;
        $result = null;
        switch($type) {
            case 'movies': 
                $p = (new PeliculaService())->obtenerPeliculaPorId($id);
                if ($p) {
                    $result = [
                        'titulo' => $p->getTitulo(),
                        'director' => $p->getDirector(),
                        'clasificacion' => $p->getClasificacion(),
                        'genero_id' => $p->getGeneroId(),
                        'url_image' => $p->getUrlImage(),
                        'protagonistas' => $p->getProtagonistas()
                    ];
                }
                break;
            case 'users': 
                $u = (new UsuarioService())->obtenerUsuarioDTO($id); 
                if ($u) $result = ['nombre' => $u->nombre, 'correo' => $u->correo, 'estado_id' => $u->estado, 'permisos_id' => $u->permisos];
                break;
            case 'cines': 
                $c = (new CineService())->obtenerCinePorId($id); 
                if ($c) $result = ['nombre' => $c->getNombre(), 'calle' => $c->getCalle(), 'numero' => $c->getNumero(), 'telefono' => $c->getTelefono()];
                break;
            case 'salas': 
                $s = (new SalaService())->obtenerSalaPorId($id); 
                if ($s) $result = ['cine_id' => $s->getCine()->getIdCine(), 'capacidad' => $s->getCapacidad()];
                break;
            case 'funciones': 
                $f = (new FuncionService())->obtenerFuncionPorId($id); 
                if ($f) $result = [
                    'pelicula_id' => $f->getPelicula()->getIdPelicula(), 
                    'cine_id' => $f->getSala()->getCine()->getIdCine(), 
                    'sala_id' => $f->getSala()->getIdSala(), 
                    'fecha_hora' => $f->getFechaHora()
                ];
                break;
            case 'generos': 
                $g = (new GeneroService())->obtenerGeneroPorId($id); 
                if ($g) $result = ['nombre' => $g->getNombreGenero()];
                break;
            case 'tarifas': 
                $t = (new TarifaService())->obtenerTarifaPorId($id); 
                if ($t) $result = [
                    'cine_id' => $t->getCine()->getIdCine(), 
                    'categoria' => $t->getCategoria(), 
                    'dia_id' => $t->getNombreDia(), 
                    'precio' => $t->getPrecio()
                ];
                break;
            case 'protagonists': 
                $pro = (new ProtagonistaService())->obtenerProtagonistaPorId($id); 
                if ($pro) $result = ['nombre' => $pro->getNombre()];
                break;
        }
        
        echo json_encode(['success' => (bool)$result, 'data' => $result]);
        break;

    case 'insert':
    case 'update':
        $success = false;
        $id = $_POST['id'] ?? null;
        $action_type = $_GET['action'];

        switch ($type) {
            case 'movies':
                $service = new PeliculaService();
                if ($action_type === 'insert') {
                    $id_new = $service->insertarPelicula($_POST['titulo'], $_POST['director'], $_POST['clasificacion'], $_POST['url_image'], (int)$_POST['genero_id']);
                    if ($id_new) {
                        $success = true;
                        if (isset($_POST['protagonistas'])) {
                            $service->actualizarReparto($id_new, $_POST['protagonistas']);
                        }
                    }
                } else {
                    $success = $service->actualizarPelicula($id, $_POST['titulo'], $_POST['director'], $_POST['clasificacion'], $_POST['url_image'], (int)$_POST['genero_id']);
                    if ($success && isset($_POST['protagonistas'])) {
                        $service->actualizarReparto($id, $_POST['protagonistas']);
                    }
                }
                break;

            case 'cines':
                $service = new CineService();
                $success = ($action_type === 'insert') 
                    ? $service->insertarCine($_POST['nombre'], $_POST['calle'], $_POST['numero'], $_POST['telefono'])
                    : $service->actualizarCine($id, $_POST['nombre'], $_POST['calle'], $_POST['numero'], $_POST['telefono']);
                break;

            case 'salas':
                $service = new SalaService();
                $success = ($action_type === 'insert')
                    ? $service->insertarSala((int)$_POST['capacidad'], (int)$_POST['cine_id'])
                    : $service->actualizarSala($id, (int)$_POST['capacidad'], (int)$_POST['cine_id']);
                break;

            case 'funciones':
                $service = new FuncionService();
                $success = ($action_type === 'insert')
                    ? $service->insertarFuncion((int)$_POST['pelicula_id'], (int)$_POST['sala_id'], $_POST['fecha_hora'])
                    : $service->actualizarFuncion($id, (int)$_POST['pelicula_id'], (int)$_POST['sala_id'], $_POST['fecha_hora']);
                break;

            case 'generos':
                $service = new GeneroService();
                $success = ($action_type === 'insert')
                    ? $service->insertarGenero($_POST['nombre'])
                    : $service->actualizarGenero($id, $_POST['nombre']);
                break;

            case 'protagonists':
                $service = new ProtagonistaService();
                $success = ($action_type === 'insert')
                    ? $service->insertarProtagonista($_POST['nombre'])
                    : $service->actualizarProtagonista($id, $_POST['nombre']);
                break;

            case 'tarifas':
                $service = new TarifaService();
                $success = ($action_type === 'insert')
                    ? $service->insertarTarifa((int)$_POST['cine_id'], $_POST['dia_id'], $_POST['categoria'], (float)$_POST['precio'])
                    : $service->actualizarTarifa($id, (int)$_POST['cine_id'], $_POST['dia_id'], $_POST['categoria'], (float)$_POST['precio']);
                break;

            case 'users':
                $service = new UsuarioService();
                $u = $service->obtenerUsuarioDTO($id);
                if ($u) {
                    $userObj = new Usuario($id, $u->nombre, null, $u->correo, $_POST['estado_id'], $_POST['permisos_id']);
                    $success = $service->actualizarUsuario($userObj);
                }
                break;
        }

        echo json_encode(['success' => (bool)$success]);
        break;
}
