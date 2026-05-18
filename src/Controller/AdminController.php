<?php
session_start();
require_once __DIR__ . '/../src/Service/PeliculaService.php';
require_once __DIR__ . '/../src/Service/UsuarioService.php';
require_once __DIR__ . '/../src/Service/ProtagonistaService.php';
require_once __DIR__ . '/../Model/Pelicula.php';
require_once __DIR__ . '/../Model/Usuario.php';
require_once __DIR__ . '/../Model/Protagonista.php';

// Verificación de seguridad robusta siguiendo el patrón del proyecto
if (!isset($_SESSION['usuario_id']) || $_SESSION['permisos'] != 1) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$action = $_GET['action'] ?? 'list';
$type = $_GET['type'] ?? 'movies'; // 'movies', 'users', 'protagonists'
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$peliculaService = new PeliculaService();
$usuarioService = new UsuarioService();
$protagonistaService = new ProtagonistaService();

header('Content-Type: application/json');

// Obtener lista de géneros (Para el select dinámico)
if ($action === 'genres') {
    $generos = $peliculaService->listarGeneros();
    $data = array_map(function($g) {
        return [
            'id_genero' => $g->getIdGenero(),
            'nombre_genero' => $g->getNombreGenero()
        ];
    }, $generos);
    echo json_encode(['data' => $data]);
    exit;
}

if ($action === 'list') {
    if ($type === 'users') {
        $data = $usuarioService->listarUsuariosPaginados($limit, $offset);
        $total = $usuarioService->contarUsuarios();
    } elseif ($type === 'protagonists') {
        $protagonistas = $protagonistaService->listarProtagonistasPaginados($limit, $offset);
        $total = $protagonistaService->contarProtagonistas();
        
        $data = array_map(function($prota) {
            return [
                'ID actor' => $prota->getIdActor(),
                'Nombre'   => $prota->getNombre()
            ];
        }, $protagonistas);
    } else {
        $peliculas = $peliculaService->obtenerPeliculasPaginadas($limit, $offset);
        $total = $peliculaService->contarPeliculas();
        
        $data = array_map(function($peli) {
            return [
                'ID pelicula'      => $peli->getIdPelicula(),
                'Titulo'           => $peli->getTitulo(),
                'Director'         => $peli->getDirector(),
                'Clasificacion'    => $peli->getClasificacion(),
                'Genero'           => $peli->getGenero()
            ];
        }, $peliculas);
    }
    
    echo json_encode([
        'data' => $data,
        'total' => (int)$total,
        'pages' => ceil($total / $limit),
        'currentPage' => $page
    ]);
    exit;
}

if ($action === 'delete') {
    $id = $_POST['id'] ?? null;
    $success = false;
    
    if ($id) {
        if ($type === 'users') {
            if ($id != $_SESSION['usuario_id']) {
                $success = $usuarioService->eliminarUsuario($id);
            } else {
                echo json_encode(['success' => false, 'error' => 'No puedes eliminarte a ti mismo']);
                exit;
            }
        } elseif ($type === 'protagonists') {
            $success = $protagonistaService->eliminarProtagonista($id);
        } else {
            $success = $peliculaService->eliminarPelicula($id);
        }
    }
    
    echo json_encode(['success' => $success]);
    exit;
}

if ($action === 'get') {
    $id = $_GET['id'] ?? null;
    $data = null;
    
    if ($id) {
        if ($type === 'users') {
            // Buscamos el usuario para pre-rellenar el modal
            $usuarios = $usuarioService->listarUsuariosPaginados(100, 0);
            foreach ($usuarios as $u) {
                if ($u['id_usuario'] == $id) {
                    $data = [
                        'nombre' => $u['nombre'],
                        'correo' => $u['correo'],
                        'estado_id' => $u['estado_id'],
                        'permisos' => $u['permisos']
                    ];
                    break;
                }
            }
        } elseif ($type === 'protagonists') {
            $protagonistas = $protagonistaService->listarProtagonistasPaginados(1000, 0);
            foreach ($protagonistas as $p) {
                if ($p->getIdActor() == $id) {
                    $data = [
                        'nombre' => $p->getNombre()
                    ];
                    break;
                }
            }
        } else {
            // Pre-rellenado para películas
            $peliculas = $peliculaService->listarPeliculas();
            foreach ($peliculas as $p) {
                if ($p->getIdPelicula() == $id) {
                    $clasif = str_replace('+', '', $p->getClasificacion());
                    if ($clasif === 'Todo Publico') $clasif = 0;

                    $data = [
                        'titulo' => $p->getTitulo(),
                        'director' => $p->getDirector(),
                        'clasificacion' => (int)$clasif,
                        'url_image' => $p->getUrlImage()
                    ];
                    
                    // Obtener el ID del género para el select
                    $generos = $peliculaService->listarGeneros();
                    foreach ($generos as $g) {
                        if ($g->getNombreGenero() === $p->getGenero()) {
                            $data['genero_id'] = $g->getIdGenero();
                            break;
                        }
                    }
                    break;
                }
            }
        }
    }
    
    echo json_encode($data);
    exit;
}

if ($action === 'update') {
    $id = $_POST['id'] ?? null;
    $success = false;
    
    if ($id) {
        if ($type === 'movies') {
            $titulo = $_POST['titulo'] ?? '';
            $director = $_POST['director'] ?? '';
            $clasificacion = (int)($_POST['clasificacion'] ?? 0);
            $url_image = $_POST['url_image'] ?? '';
            $genero_id = (int)($_POST['genero_id'] ?? 1);
            
            $pelicula = new Pelicula($id, $titulo, $director, $clasificacion, $url_image, $genero_id);
            $success = $peliculaService->actualizarPelicula($pelicula);
            
        } elseif ($type === 'users') {
            $nombre = $_POST['nombre'] ?? '';
            $correo = $_POST['email'] ?? '';
            $estado_id = (int)($_POST['estado_id'] ?? 1);
            $permisos = (int)($_POST['permisos'] ?? 0);
            
            // Recuperamos la contraseña actual para la actualización ya que no está en el formulario
            $password = '';
            if ($id) {
                // Buscamos en la DB vía DAO directamente para obtener el hash de forma segura
                $userData = (new UsuarioDAO())->obtenerUsuarioPorCorreo($correo);
                if ($userData) {
                    $password = $userData['password'];
                }
            }
            
            $usuario = new Usuario($nombre, $correo, $password, $permisos, $id);
            $usuario->setEstadoId($estado_id);
            $success = $usuarioService->actualizarUsuario($usuario);
        } elseif ($type === 'protagonists') {
            $nombre = $_POST['nombre'] ?? '';
            $success = $protagonistaService->actualizarProtagonista($id, $nombre);
        }
    }
    
    echo json_encode(['success' => (bool)$success]);
    exit;
}

if ($action === 'insert') {
    $success = false;
    
    if ($type === 'movies') {
        $titulo = $_POST['titulo'] ?? '';
        $director = $_POST['director'] ?? '';
        $clasificacion = (int)($_POST['clasificacion'] ?? 0);
        $url_image = $_POST['url_image'] ?? '';
        $genero_id = (int)($_POST['genero_id'] ?? 1);
        
        $pelicula = new Pelicula(0, $titulo, $director, $clasificacion, $url_image, $genero_id);
        $success = $peliculaService->insertarPelicula($titulo, $director, $clasificacion, $url_image, $genero_id);
        
    } elseif ($type === 'users') {
        $nombre = $_POST['nombre'] ?? '';
        $correo = $_POST['email'] ?? '';
        $estado_id = (int)($_POST['estado_id'] ?? 1);
        $permisos = (int)($_POST['permisos'] ?? 0);
        
        // Contraseña por defecto para nuevos usuarios ya que el campo se eliminó del admin
        $password = password_hash('CineFirst123*', PASSWORD_BCRYPT); 
        
        $usuario = new Usuario($nombre, $correo, $password, $permisos);
        $usuario->setEstadoId($estado_id);
        $success = $usuarioService->insertarUsuario($usuario);
    } elseif ($type === 'protagonists') {
        $nombre = $_POST['nombre'] ?? '';
        $success = $protagonistaService->insertarProtagonista($nombre);
    }
    
    echo json_encode(['success' => (bool)$success]);
    exit;
}
?>