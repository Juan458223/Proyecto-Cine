<?php
require_once __DIR__ . '/../Dao/UsuarioDAO.php';
require_once __DIR__ . '/../Model/Usuario.php';

class UsuarioService {
    private $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
    }

    public function listarUsuariosPaginados($limit, $offset) {
        return $this->usuarioDAO->obtenerUsuariosPaginados($limit, $offset);
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

    public function insertarUsuarioAdmin($nombre, $correo, $password) {
        $usuario = new Usuario($nombre, $correo, $password, 1);
        return $this->usuarioDAO->insertarUsuario($usuario);
    }
}
?>
