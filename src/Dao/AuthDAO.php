<?php
require_once __DIR__ . '/../Core/DatabaseConnection.php';
require_once __DIR__ . '/../Dto/TokenDTO.php';

class AuthDAO {
    private $db;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }
public function insertToken(TokenDTO $tokenDto) {
    try {
        // 1. Obtener el ID del tipo de token basado en su nombre
        $tipoSql = "SELECT id FROM tipos_tokens WHERE nombre = :tipo_nombre";
        $tipoStmt = $this->db->prepare($tipoSql);
        $tipoStmt->execute(['tipo_nombre' => $tokenDto->getType()]);
        $tipoId = $tipoStmt->fetchColumn();

        if (!$tipoId) return false;

        // 2. Limpieza de tokens antiguos para este usuario y tipo
        $deleteSql = "DELETE FROM tokens WHERE usuario_id = :usuario_id AND tipo_id = :tipo_id";
        $deleteStmt = $this->db->prepare($deleteSql);
        $deleteStmt->execute([
            'usuario_id' => $tokenDto->getUsuarioId(),
            'tipo_id'    => $tipoId
        ]);

        // 3. Insertar el nuevo token
        $sql = "INSERT INTO tokens (usuario_id, token_valor, tipo_id, fecha_c) 
                VALUES (:usuario_id, :token_valor, :tipo_id, NOW())";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            'usuario_id'  => $tokenDto->getUsuarioId(),
            'token_valor' => $tokenDto->getTokenValue(),
            'tipo_id'     => $tipoId
        ]);

        return true;
    } catch (PDOException $e) {
        error_log("Error in AuthDAO::insertToken: " . $e->getMessage());
        return false;
    }
}
    public function validateToken($token, $type, $correo) {
        try {
            $sql = "CALL sp_validar_token(:correo, :token, :type, @resultado); SELECT @resultado AS resultado;";
            $statement = $this->db->prepare($sql);
            $statement->execute([
                'correo' => $correo, 
                'token'  => $token, 
                'type'   => $type
            ]);
            
            $statement->nextRowset();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in AuthDAO::validateToken: " . $e->getMessage());
            return null;
        }
    }
}
?>
