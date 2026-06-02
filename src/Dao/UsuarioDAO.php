<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';
require_once __DIR__ . '/../Model/Usuario.php';
require_once __DIR__ . '/../Dto/UsuarioDTO.php';



class UsuarioDAO {
    private $db;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function insertarUsuario(Usuario $usuario) {
        try {
            $sqlIds = "SELECT (SELECT id FROM estados WHERE nombre = :estado) as estado_id, 
                              (SELECT id FROM permisos WHERE nombre = :permisos) as permisos_id";
            $stmtIds = $this->db->prepare($sqlIds);
            $stmtIds->execute(['estado' => $usuario->getEstado(), 'permisos' => $usuario->getPermisos()]);
            $ids = $stmtIds->fetch(PDO::FETCH_ASSOC);

            $sql = "INSERT INTO usuarios (nombre, password, correo, estado_id, permisos_id)
                    VALUES (:nombre, :password, :correo, :estado_id, :permisos_id)";
            $statement = $this->db->prepare($sql);
            $statement->execute([
                'nombre'      => $usuario->getNombre(),
                'password'    => $usuario->getPassword(), 
                'correo'      => $usuario->getCorreo(),
                'estado_id'   => $ids['estado_id'] ?? 2, 
                'permisos_id' => $ids['permisos_id'] ?? 2 
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al insertar usuario: ".$e->getMessage());
            return false;
        }
    }

    public function obtenerUsuarioPorCorreo($correo) {
        $sql = "SELECT u.*, e.nombre as estado_nombre, p.nombre as permiso_nombre 
                FROM usuarios u
                INNER JOIN estados e ON u.estado_id = e.id
                INNER JOIN permisos p ON u.permisos_id = p.id
                WHERE u.correo = :correo";
        $statement = $this->db->prepare($sql);
        $statement->execute(['correo' => $correo]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarUsuario(Usuario $usuario) {
        try {
            $sqlIds = "SELECT (SELECT id FROM estados WHERE nombre = :estado) as estado_id, 
                              (SELECT id FROM permisos WHERE nombre = :permisos) as permisos_id";
            $stmtIds = $this->db->prepare($sqlIds);
            $stmtIds->execute(['estado' => $usuario->getEstado(), 'permisos' => $usuario->getPermisos()]);
            $ids = $stmtIds->fetch(PDO::FETCH_ASSOC);

            $sql = "UPDATE usuarios 
                    SET nombre = :nombre, password = :password, correo = :correo, 
                        estado_id = :estado_id, permisos_id = :permisos_id 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nombre'      => $usuario->getNombre(),
                'password'    => $usuario->getPassword(),
                'correo'      => $usuario->getCorreo(),
                'estado_id'   => $ids['estado_id'],
                'permisos_id' => $ids['permisos_id'],
                'id'          => $usuario->getId()
            ]);
        } catch (PDOException $e) {
            error_log("Error en UsuarioDAO::actualizarUsuario: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarUsuarioAdmin(UsuarioDTO $usuario) {
        try {
            $sqlIds = "SELECT (SELECT id FROM estados WHERE nombre = :estado) as estado_id, 
                              (SELECT id FROM permisos WHERE nombre = :permisos) as permisos_id";
            $stmtIds = $this->db->prepare($sqlIds);
            $stmtIds->execute(['estado' => $usuario->getEstado(), 'permisos' => $usuario->getPermisos()]);
            $ids = $stmtIds->fetch(PDO::FETCH_ASSOC);

            if (!$ids['estado_id'] || !$ids['permisos_id']) {
                error_log("Error en UsuarioDAO: resolución de ID fallida. Estado: {$usuario->getEstado()}, Permiso: {$usuario->getPermisos()}");
                return false;
            }

            $sql = "UPDATE usuarios 
                    SET estado_id = :estado_id, permisos_id = :permisos_id 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'estado_id'   => $ids['estado_id'],
                'permisos_id' => $ids['permisos_id'],
                'id'          => (int)$usuario->getId()
            ]);
        } catch (PDOException $e) {
            error_log("Error en UsuarioDAO::actualizarUsuarioAdmin: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerUsuariosPaginados($limit, $offset, $excluded_id = null) {
        $sql = "SELECT u.id, u.nombre, u.correo, u.estado_id, u.permisos_id, e.nombre as estado, p.nombre as permiso, DATE_FORMAT(u.fecha_registro, '%Y-%m-%d') as registro
                FROM usuarios u 
                INNER JOIN estados e ON u.estado_id = e.id 
                INNER JOIN permisos p ON u.permisos_id = p.id";
        
        if ($excluded_id) {
            $sql .= " WHERE u.id != :excluded_id";
        }
        
        $sql .= " LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        if ($excluded_id) {
            $stmt->bindValue(':excluded_id', (int)$excluded_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarUsuarios($excluded_id = null) {
        $sql = "SELECT COUNT(*) FROM usuarios";
        if ($excluded_id) {
            $sql .= " WHERE id != :excluded_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['excluded_id' => $excluded_id]);
            return (int)$stmt->fetchColumn();
        }
        return (int)$this->db->query($sql)->fetchColumn();
    }

    public function obtenerEstadisticasRegistro() {
        try {
            // Por día (últimos 30 días) - Totales
            $sqlDia = "SELECT DATE(fecha_registro) as fecha, COUNT(*) as total 
                       FROM usuarios 
                       WHERE fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                       GROUP BY DATE(fecha_registro) 
                       ORDER BY fecha DESC";
            $stmtDia = $this->db->query($sqlDia);
            $porDia = $stmtDia->fetchAll(PDO::FETCH_ASSOC);

            // Listado detallado de los últimos 30 días
            $sqlDetalle = "SELECT nombre, correo, fecha_registro as fecha 
                           FROM usuarios 
                           WHERE fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                           ORDER BY fecha_registro DESC";
            $stmtDetalle = $this->db->query($sqlDetalle);
            $detalleReciente = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);

            // Por semana (últimas 12 semanas)
            $sqlSemana = "SELECT YEAR(fecha_registro) as anio, WEEK(fecha_registro, 1) as semana, COUNT(*) as total 
                          FROM usuarios 
                          WHERE fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 12 WEEK)
                          GROUP BY YEAR(fecha_registro), WEEK(fecha_registro, 1) 
                          ORDER BY anio DESC, semana DESC";
            $stmtSemana = $this->db->query($sqlSemana);
            $porSemana = $stmtSemana->fetchAll(PDO::FETCH_ASSOC);

            // Por mes (últimos 12 meses)
            $sqlMes = "SELECT YEAR(fecha_registro) as anio, MONTH(fecha_registro) as mes, COUNT(*) as total 
                       FROM usuarios 
                       WHERE fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                       GROUP BY YEAR(fecha_registro), MONTH(fecha_registro) 
                       ORDER BY anio DESC, mes DESC";
            $stmtMes = $this->db->query($sqlMes);
            $porMes = $stmtMes->fetchAll(PDO::FETCH_ASSOC);

            return [
                'por_dia' => $porDia,
                'por_semana' => $porSemana,
                'por_mes' => $porMes,
                'listado_detallado' => $detalleReciente
            ];
        } catch (PDOException $e) {
            error_log("Error en obtenerEstadisticasRegistro: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerUsuariosPorPeriodo($dias) {
        try {
            $sql = "SELECT nombre, correo, fecha_registro as fecha 
                    FROM usuarios 
                    WHERE fecha_registro >= DATE_SUB(NOW(), INTERVAL :dias DAY)
                    ORDER BY fecha_registro DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':dias', (int)$dias, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerUsuariosPorPeriodo: " . $e->getMessage());
            return [];
        }
    }
}
?>
