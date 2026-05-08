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
                $userData['correo'],
                $userData['permisos']
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

    public function validarCorreo($correo) {
        return filter_var($correo, FILTER_VALIDATE_EMAIL) && str_ends_with(strtolower($correo), '@gmail.com');
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
        // Verificar si el usuario ya existe
        $userData = $this->usuarioDAO->obtenerUsuarioPorCorreo($correo);
        
        if ($userData) {
            if ($userData['estado'] === 'pendiente') {
                return new UsuarioDTO($userData['id'], $userData['nombre'], $userData['correo'], $userData['permisos']);
            }
            return null; 
        }

        $usuario = new Usuario($nombre, $correo, $password);
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
            $mail->CharSet    = 'UTF-8';
            $mail->Host       = $this->smtp->get('smtp_host');
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->smtp->get('smtp_user');
            $mail->Password   = $this->smtp->get('smtp_pass');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $this->smtp->get('smtp_port');
            $mail->setFrom($this->smtp->get('smtp_user'), 'Cine First');
            $mail->addAddress($usuario->getCorreo(), $usuario->getNombre());

            $mail->isHTML(true);
            
            $titulo = '';
            $mensaje = '';
            $subject = '';

            switch($type) {
                case 'validate_user':
                    $subject = 'Código de Acceso - Cine First';
                    $titulo = '¡Bienvenido de Nuevo!';
                    $mensaje = 'Usa el siguiente código para completar tu inicio de sesión:';
                    break;
                case 'register_user':
                    $subject = 'Verifica tu Cuenta - Cine First';
                    $titulo = '¡Gracias por unirte!';
                    $mensaje = 'Para completar tu registro en Cine First, utiliza este código de verificación:';
                    break;
                case 'reset_password':
                    $subject = 'Recuperar Contraseña - Cine First';
                    $titulo = 'Restablecer Contraseña';
                    $mensaje = 'Has solicitado restablecer tu contraseña. Utiliza el siguiente código de seguridad:';
                    break;
            }

            $mail->Subject = $subject;
            
            $body = "
            <html>
            <head>
                <style>
                    .container {
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #000;
                        color: #ffffff;
                        border-radius: 15px;
                        overflow: hidden;
                        border: 2px solid #e11d48;
                        background-image: url('https://i.pinimg.com/originals/ea/2d/6e/ea2d6ec2d94e5d1e492c58b102688282.gif');
                        background-size: cover;
                        background-position: center;
                    }
                    .overlay {
                        background-color: rgba(0, 0, 0, 0.85);
                        padding: 40px;
                        text-align: center;
                    }
                    .logo {
                        font-size: 32px;
                        font-weight: bold;
                        color: #e11d48;
                        margin-bottom: 20px;
                        text-transform: uppercase;
                        letter-spacing: 3px;
                    }
                    .title {
                        font-size: 24px;
                        margin-bottom: 20px;
                        color: #fcd34d;
                    }
                    .code-box {
                        background-color: rgba(225, 29, 72, 0.1);
                        border: 2px dashed #e11d48;
                        border-radius: 10px;
                        padding: 20px;
                        margin: 30px 0;
                        display: inline-block;
                    }
                    .code {
                        font-size: 48px;
                        font-weight: bold;
                        letter-spacing: 10px;
                        color: #ffffff;
                        margin: 0;
                    }
                    .footer {
                        margin-top: 30px;
                        font-size: 14px;
                        color: #9ca3af;
                    }
                    .warning {
                        color: #f87171;
                        font-style: italic;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='overlay'>
                        <div class='logo'>CINE FIRST</div>
                        <div class='title'>{$titulo}</div>
                        <p style='font-size: 16px;'>Hola, <b>{$usuario->getNombre()}</b></p>
                        <p style='font-size: 16px;'>{$mensaje}</p>
                        
                        <div class='code-box'>
                            <p class='code'>{$tokenValue}</p>
                        </div>
                        
                        <p class='warning'>Este código expirará en 2 minutos.</p>
                        
                        <div class='footer'>
                            <hr style='border: 0; border-top: 1px solid #374151; margin: 20px 0;'>
                            <p>&copy; " . date('Y') . " Cine First. Todos los derechos reservados.</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            ";

            $mail->Body = $body;

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
