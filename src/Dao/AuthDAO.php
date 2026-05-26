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
            // Buscamos el ID del tipo de token
            $stmt = $this->db->prepare("SELECT id FROM tipos_tokens WHERE nombre = :tipo_nombre");
            $stmt->execute(['tipo_nombre' => $tokenDto->getType()]);
            $tipoId = $stmt->fetchColumn();

            if (!$tipoId) {
                error_log("Error: Tipo de token '" . $tokenDto->getType() . "' no encontrado en la base de datos.");
                return false;
            }

            // Eliminamos tokens previos del mismo tipo para este usuario
            $deleteStmt = $this->db->prepare("DELETE FROM tokens WHERE usuario_id = :usuario_id AND tipo_id = :tipo_id");
            $deleteStmt->execute([
                'usuario_id' => $tokenDto->getUsuarioId(),
                'tipo_id'    => $tipoId
            ]);

            // Insertamos el nuevo token
            $sql = "INSERT INTO tokens (usuario_id, token_valor, tipo_id, fecha_c) 
                    VALUES (:usuario_id, :token_valor, :tipo_id, NOW())";
            $statement = $this->db->prepare($sql);
            return $statement->execute([
                'usuario_id'  => $tokenDto->getUsuarioId(),
                'token_valor' => $tokenDto->getTokenValue(),
                'tipo_id'     => $tipoId
            ]);
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
