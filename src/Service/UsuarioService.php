<?php
require_once __DIR__ . '/../Dao/UsuarioDAO.php';
require_once __DIR__ . '/../Model/Usuario.php';
require_once __DIR__ . '/../Dto/UsuarioDTO.php';

class UsuarioService {
    private $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
    }

    public function obtenerUsuarioDTO($id) {
        $users = $this->usuarioDAO->obtenerUsuariosPaginados(1000, 0);
        foreach ($users as $u) {
            if ((int)$u['id'] === (int)$id) {
                return new UsuarioDTO($u['id'], $u['nombre'], $u['correo'], $u['permiso'], $u['estado'], '', $u['estado_id'], $u['permisos_id']);
            }
        }
        return null;
    }

    public function listarUsuariosPaginados($page = 1, $excluded_id = null) {
        $data = $this->usuarioDAO->obtenerUsuariosPaginados(6, ($page - 1) * 6, $excluded_id);
        $dtos = [];
        foreach ($data as $u) {
            $dtos[] = new UsuarioDTO($u['id'], $u['nombre'], $u['correo'], $u['permiso'], $u['estado'], $u['registro'], $u['estado_id'], $u['permisos_id']);
        }
        return $dtos;
    }

    public function contarUsuarios($excluded_id = null) {
        return $this->usuarioDAO->contarUsuarios($excluded_id);
    }

    public function actualizarUsuario(Usuario $usuario) {
        return $this->usuarioDAO->actualizarUsuario($usuario);
    }

    public function actualizarUsuarioAdmin($id, $estado, $permisos) {
        // En el constructor de UsuarioDTO: el 4to parámetro es permisos y el 5to es estado
        $userObj = new UsuarioDTO($id, "", "", $permisos, $estado, "", 0, 0);
        return $this->usuarioDAO->actualizarUsuarioAdmin($userObj);
    }

    public function insertarUsuario(Usuario $usuario) {
        return $this->usuarioDAO->insertarUsuario($usuario);
    }
}
?>
