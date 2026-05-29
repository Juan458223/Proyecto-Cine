<?php 
session_start();
require_once __DIR__ . '/../src/View/Components/password_input.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost:5173/public/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="img/logo.svg">
    <title>CINE FIRST</title>
    <style>
        .btn-primary {
            background-color: #E50914 !important;
            color: white !important;
            border: none !important;
            font-weight: 900 !important;
            letter-spacing: 0.15em !important;
            border-radius: 1.25rem !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 10px 25px -5px rgba(229, 9, 20, 0.3) !important;
        }
        .btn-primary:hover {
            background-color: #ff1f2a !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 15px 30px -5px rgba(229, 9, 20, 0.5) !important;
        }
    </style>
</head>
<body class="flex flex-col md:flex-row h-screen w-full overflow-hidden bg-black font-outfit">

    <!-- Sección de Formulario -->
    <div class="bg-black text-white h-full w-full md:w-[450px] flex flex-col justify-center px-12 py-8 border-r border-zinc-900 shadow-2xl z-20">
        <div class="mb-12 flex flex-col items-center">
            <h2 class="text-5xl font-black text-[#E50914] uppercase tracking-[0.2em] leading-none font-bebas transition-all hover:scale-105" style="text-shadow: 0 0 20px rgba(229, 9, 20, 0.4);">
                CINE FIRST
            </h2>
        </div>

        <!-- Login -->
        <div id="login-section" class="space-y-10 animate-in fade-in slide-in-from-left duration-700">
            <div class="space-y-2">
                <h1 class="text-4xl font-normal uppercase tracking-tight font-bebas leading-none">INICIAR SESIÓN</h1>
                <p class="text-zinc-500 text-sm font-medium font-outfit">Bienvenido a la experiencia Cine First</p>
            </div>
            
            <form action="login.php" method="post" class="flex flex-col space-y-10" id="login-form">
                <div class="auth-input-group">
                    <input class="auth-input-modern" type="email" name="email" id="email" placeholder=" ">
                    <label for="email" class="auth-label-modern">Correo electrónico</label>
                </div>
                
                <?php echo render_password_input('password', 'password', 'Contraseña'); ?>
                
                <div class="flex justify-end -mt-6">
                    <a href="#" id="recover-link" class="link-responsive text-[#E50914] hover:text-red-400">¿Olvidó su contraseña?</a>
                </div>
                
                <input class="btn-primary" type="submit" value="Iniciar sesión">
            </form>
            
            <div class="pt-6 border-t border-zinc-900 flex justify-between items-center font-outfit">
                <p class="text-[13px] md:text-base lg:text-lg font-medium text-zinc-400">¿No tiene cuenta?</p>
                <a href="#" class="link-responsive text-[#E50914] hover:text-red-400 font-bold" id="register-link">Regístrate aquí</a>
            </div>
        </div>

        <div class="hidden space-y-10 animate-in fade-in slide-in-from-left duration-500" id="recover-section">
            <a id="back-to-login-recover" href="#" class="inline-flex items-center text-zinc-400 hover:text-white transition-colors group font-outfit">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 mr-2 group-hover:-translate-x-1 transition-transform">
                    <path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z" clip-rule="evenodd" />
                </svg>
                <span class="text-[14px] font-medium">Volver</span>
            </a>
            
            <div class="space-y-2">
                <h1 class="text-4xl font-normal uppercase tracking-tight font-bebas leading-none text-white">RECUPERAR CONTRASEÑA</h1>
                <p class="text-zinc-500 text-sm font-medium font-outfit">Enviaremos un código a su correo</p>
            </div>
            
            <form action="recover.php" method="post" class="flex flex-col space-y-10" id="recover-form">
                <div class="auth-input-group">
                    <input class="auth-input-modern" type="email" name="email" id="email_rec" placeholder=" ">
                    <label for="email_rec" class="auth-label-modern">Correo electrónico</label>
                </div>
                <input class="btn-primary" type="submit" value="Enviar código">
            </form>
        </div>

        <div class="hidden space-y-8 animate-in fade-in slide-in-from-left duration-500" id="register-section">
            <a id="back-to-login" href="#" class="inline-flex items-center text-zinc-400 hover:text-white transition-colors group mb-6 font-outfit">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 mr-2 group-hover:-translate-x-1 transition-transform">
                    <path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z" clip-rule="evenodd" />
                </svg>
                <span class="text-[14px] font-medium">Volver</span>
            </a>
            
            <div class="space-y-2">
                <h1 class="text-4xl font-normal uppercase tracking-tight font-bebas leading-none text-white">REGISTRARSE</h1>
            </div>
            
            <form action="register.php" method="post" class="flex flex-col space-y-8" id="register-form">
                <div class="auth-input-group">
                    <input class="auth-input-modern" type="text" name="nombre" id="nombre" placeholder=" ">
                    <label for="nombre" class="auth-label-modern">Nombre completo</label>
                </div>
                <div class="auth-input-group">
                    <input class="auth-input-modern" type="email" name="email" id="email_r" placeholder=" ">
                    <label for="email_r" class="auth-label-modern">Correo electrónico</label>
                </div>
                <?php echo render_password_input('password_r', 'password', 'Contraseña'); ?>
                <?php echo render_password_input('confirm_password', 'confirm_password', 'Confirmar contraseña'); ?>
                <input class="btn-primary" type="submit" value="Registrarse" style="background-color: #E50914 !important; color: white !important;">
            </form>
        </div>
    </div>

    <div class="hidden md:block flex-1 relative bg-black">
        <div class="absolute inset-0 bg-gradient-to-r from-black via-transparent to-transparent z-10"></div>
        <img class="w-full h-full object-cover opacity-100 transition-all duration-1000" src="https://i.pinimg.com/originals/8c/4e/c5/8c4ec5138991592cca31db6b2ed46e5e.gif" alt="cine">
    </div>

    <?php include '../src/View/Modales/auth_modal.php'; ?>

</body>
<script src="js/index.js"></script>
</html>