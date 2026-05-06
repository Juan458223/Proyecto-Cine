<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';  
require_once __DIR__ . '/../Dao/UsuarioDAO.php';
require_once __DIR__ . '/../Dto/UsuarioDTO.php';
require_once __DIR__ . '/../Dto/TokenDTO.php';
require_once __DIR__ . '/../Dao/AuthDAO.php';
require_once __DIR__ . '/../Core/SmtpConfig.php';

class AuthService {
    private $usuarioDAO;
    private $authDAO;
    private $smtp;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
        $this->authDAO = new AuthDAO();
        $this->smtp = SmtpConfig::getInstance();
    }

    public function validar($correo, $password) {
        $userData = $this->usuarioDAO->obtenerUsuarioPorCorreo($correo);

        if ($userData && password_verify($password, $userData['password'])) {
            if ($userData['estado'] === 'bloqueado') {
                return 'bloqueado';
            }
            if ($userData['estado'] !== 'activo') {
                return 'pendiente';
            }
            return new UsuarioDTO(
                $userData['id'],
                $userData['nombre'],
                $userData['correo']
            );
        }

        return null; 
    }

    public function validarPassword($password) {
        // Mínimo 6 caracteres, al menos una mayúscula y al menos un número
        return strlen($password) >= 6 && 
               preg_match('/[A-Z]/', $password) && 
               preg_match('/[0-9]/', $password);
    }

    public function cambiarPassword($correo, $nuevaPassword) {
        $userData = $this->usuarioDAO->obtenerUsuarioPorCorreo($correo);
        if ($userData) {
            $hashedPassword = password_hash($nuevaPassword, PASSWORD_DEFAULT);
            try {
                $sql = "UPDATE usuarios SET password = :password WHERE id = :id";
                $db = DatabaseConnection::getInstance()->getConnection();
                $stmt = $db->prepare($sql);
                return $stmt->execute([
                    'password' => $hashedPassword,
                    'id' => $userData['id']
                ]);
            } catch (PDOException $e) {
                error_log("Error al cambiar password: " . $e->getMessage());
                return false;
            }
        }
        return false;
    }

    public function registrarUsuario($nombre, $correo, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $usuario = new Usuario($nombre, $correo, $hashedPassword);
        $id = $this->usuarioDAO->insertarUsuario($usuario);
        
        if ($id) {
            $usuarioDto = new UsuarioDTO($id, $nombre, $correo);
            return $usuarioDto;
        }
        return null;
    }

    public function generarToken(UsuarioDTO $usuario, $type) {
        $tokenValue = (string)random_int(100000, 999999); 
        
        $tokenDto = new TokenDTO(
            $usuario->getId(),
            $tokenValue,
            $type,
            date('Y-m-d H:i:s')
        );
        
        $this->authDAO->insertToken($tokenDto);
        
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $this->smtp->get('smtp_host');
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->smtp->get('smtp_user');
            $mail->Password   = $this->smtp->get('smtp_pass');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $this->smtp->get('smtp_port');
            $mail->setFrom($this->smtp->get('smtp_user'), 'Cine First');
            $mail->addAddress($usuario->getCorreo(), $usuario->getNombre());

            $mail->isHTML(true);
            $subject = '';
            $body = '';

            switch($type) {
                case 'validate_user':
                    $subject = 'Código de acceso - Cine First';
                    $body = "Hola <b>{$usuario->getNombre()}</b>, tu código de inicio de sesión es: <h2 style='color: #b91c1c;'>{$tokenValue}</h2>";
                    break;
                case 'register_user':
                    $subject = 'Verifica tu cuenta - Cine First';
                    $body = "Hola <b>{$usuario->getNombre()}</b>, gracias por registrarte. Tu código de verificación es: <h2 style='color: #b91c1c;'>{$tokenValue}</h2>";
                    break;
                case 'reset_password':
                    $subject = 'Recuperar contraseña - Cine First';
                    $body = "Hola <b>{$usuario->getNombre()}</b>, has solicitado restablecer tu contraseña. Usa este código: <h2 style='color: #b91c1c;'>{$tokenValue}</h2>";
                    break;
            }

            $mail->Subject = $subject;
            $mail->Body    = $body . "<br>Este código expira en 2 minutos.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
            return false;
        }
    }

    public function validarToken($correo, $token, $type) {
        return $this->authDAO->validateToken($token, $type, $correo);
    }
}
?>