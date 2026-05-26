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
        $users = $this->usuarioDAO->obtenerUsuariosPaginados(100, 0); 
        foreach ($users as $u) {
            if ((int)$u['id'] === (int)$id) {
                return new UsuarioDTO($u['id'], $u['nombre'], $u['correo'], $u['permiso'], $u['estado']);
            }
        }
        return null;
    }

    public function listarUsuariosPaginados($page = 1) {
        $data = $this->usuarioDAO->obtenerUsuariosPaginados(6, ($page - 1) * 6);
        $dtos = [];
        foreach ($data as $u) {
            $dtos[] = new UsuarioDTO($u['id'], $u['nombre'], $u['correo'], $u['permiso'], $u['estado']);
        }
        return $dtos;
    }

    public function contarUsuarios() {
        return $this->usuarioDAO->contarUsuarios();
    }

    public function actualizarUsuario(Usuario $usuario) {
        return $this->usuarioDAO->actualizarUsuario($usuario);
    }

    public function eliminarUsuario($id) {
        return $this->usuarioDAO->eliminarUsuario($id);
    }

    public function insertarUsuario(Usuario $usuario) {
        return $this->usuarioDAO->insertarUsuario($usuario);
    }
}
?>
