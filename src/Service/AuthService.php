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
                    $titulo = 'Verificación de Seguridad';
                    $mensaje = 'Utilice el siguiente código para completar su inicio de sesión en nuestra plataforma.';
                    break;
                case 'register_user':
                    $subject = 'Verifique su Cuenta - Cine First';
                    $titulo = 'Activación de Cuenta';
                    $mensaje = 'Bienvenido a la experiencia Cine First. Para activar su perfil, ingrese el siguiente código.';
                    break;
                case 'reset_password':
                    $subject = 'Recuperar Contraseña - Cine First';
                    $titulo = 'Cambio de Contraseña';
                    $mensaje = 'Hemos recibido una solicitud para restablecer su clave. Utilice este código de seguridad.';
                    break;
            }

            $mail->Subject = $subject;
            
            $body = "
            <html>
            <body style='margin: 0; padding: 0; background-color: #000; font-family: \"Segoe UI\", Helvetica, Arial, sans-serif;'>
                <table width='100%' border='0' cellspacing='0' cellpadding='0' style='background-color: #000; min-height: 100vh;'>
                    <tr>
                        <td align='center' style='padding: 40px 0;'>
                            <table width='600' border='0' cellspacing='0' cellpadding='0' style='background-color: #0a0a0a; border-radius: 4px; overflow: hidden; border: 1px solid #1f1f1f;'>
                                <!-- Header con Logo y GIF -->
                                <tr>
                                    <td style='background-color: #000; padding: 40px; text-align: center; border-bottom: 3px solid #E50914;'>
                                        <h1 style='color: #E50914; font-size: 32px; font-weight: 900; margin: 0; letter-spacing: 6px; text-transform: uppercase;'>CINE FIRST</h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='position: relative; height: 300px; overflow: hidden;'>
                                        <img src='https://i.pinimg.com/originals/ea/2d/6e/ea2d6ec2d94e5d1e492c58b102688282.gif' width='600' style='display: block; border: 0;' alt='Cinema Background'>
                                    </td>
                                </tr>
                                <!-- Contenido -->
                                <tr>
                                    <td style='padding: 50px 40px; text-align: center;'>
                                        <h2 style='color: #ffffff; font-size: 24px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 2px;'>{$titulo}</h2>
                                        <p style='color: #a1a1aa; font-size: 14px; line-height: 1.6; margin-bottom: 30px;'>
                                            Hola, <strong style='color: #ffffff;'>{$usuario->getNombre()}</strong>.<br>
                                            {$mensaje}
                                        </p>
                                        
                                        <div style='padding: 30px; margin: 40px 0;'>
                                            <p style='color: #71717a; font-size: 10px; font-weight: 800; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 3px;'>Su código de acceso</p>
                                            <h3 style='color: #c29d09; font-size: 56px; font-weight: 900; margin: 0; letter-spacing: 12px;'>{$tokenValue}</h3>
                                        </div>
                                        
                                        <p style='color: #aa1717; font-size: 11px; font-weight: 700; font-style: italic; margin-top: 20px;'>
                                            Este código tiene una validez de 2 minutos por seguridad.
                                        </p>
                                    </td>
                                </tr>
                                <!-- Footer -->
                                <tr>
                                    <td style='background-color: #050505; padding: 30px 40px; text-align: center; border-top: 1px solid #1f1f1f;'>
                                        <p style='color: #52525b; font-size: 10px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 2px;'>
                                            © " . date('Y') . " Cine First - Colombia
                                        </p>
                                    
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
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

    public function actualizarPerfil($id, $nombre, $password) {
        try {
            $db = DatabaseConnection::getInstance()->getConnection();
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE usuarios SET nombre = :nombre, password = :password WHERE id = :id";
                $stmt = $db->prepare($sql);
                return $stmt->execute([
                    'nombre' => $nombre,
                    'password' => $hashedPassword,
                    'id' => $id
                ]);
            } else {
                $sql = "UPDATE usuarios SET nombre = :nombre WHERE id = :id";
                $stmt = $db->prepare($sql);
                return $stmt->execute([
                    'nombre' => $nombre,
                    'id' => $id
                ]);
            }
        } catch (PDOException $e) {
            error_log("Error al actualizar perfil: " . $e->getMessage());
            return false;
        }
    }
}
?>