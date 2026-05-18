<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost:5173/public/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="./img/logo.svg">
    <title>CINE FIRST - Acceso Premium</title>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #000; color: #fff; }
        .font-bebas { font-family: 'Bebas Neue', sans-serif; }
        
        /* Estilo Minimalista: Solo línea inferior */
        .auth-input {
            background-color: transparent; 
            border: none;
            border-bottom: 2px solid #27272a; /* zinc-800 */
            color: #fff;
            padding: 0.6rem 0;
            border-radius: 0;
            transition: all 0.4s ease;
            outline: none;
            font-size: 1rem;
            width: 100%;
        }
        .auth-input:focus {
            border-bottom-color: #E50914;
        }
        .auth-input::placeholder {
            color: #3f3f46; /* zinc-700 */
            font-size: 0.9rem;
        }
        
        /* Labels más claros y grandes */
        .auth-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #a1a1aa; /* zinc-400 más claro */
            margin-bottom: 0.25rem;
        }

        /* Texto de bienvenida más claro */
        .auth-subtitle {
            color: #a1a1aa;
            font-size: 0.875rem;
            font-weight: 500;
        }
    </style>
</head>
<body class="flex flex-col md:flex-row h-screen w-full overflow-hidden bg-black">

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
                <h1 class="text-4xl font-normal uppercase tracking-tight font-bebas leading-none">Iniciar Sesión</h1>
                <p class="auth-subtitle">Bienvenido a la experiencia Cine First</p>
            </div>
            
            <form action="login.php" method="post" class="flex flex-col space-y-8" id="login-form">
                <div class="flex flex-col">
                    <label for="email" class="auth-label">Correo electrónico</label>
                    <input class="auth-input" type="email" name="email" id="email" placeholder="Ingresa tu correo">
                </div>
                
                <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                        <label for="password" class="auth-label">Contraseña</label>
                        <a href="#" id="recover-link" class="text-[12px] font-bold text-[#E50914] hover:text-red-400 transition-colors">¿Olvidaste tu contraseña?</a>
                    </div>
                    <input class="auth-input" type="password" name="password" id="password" placeholder="••••••••">
                </div>
                
                <input class="bg-[#E50914] text-white font-black uppercase tracking-[0.3em] text-[10px] py-3.5 rounded-sm cursor-pointer hover:bg-[#b90710] transition-all shadow-xl active:scale-95 w-full" type="submit" value="Iniciar Sesión">
            </form>
            
            <div class="pt-6 border-t border-zinc-900 flex justify-between items-center">
                <p class="text-[12px] font-medium text-zinc-400">¿No tienes cuenta?</p>
                <a href="#" class="text-[12px] font-bold text-[#E50914] hover:text-red-400 transition-colors" id="register-link">Regístrate aquí</a>
            </div>
        </div>

        <!-- Recuperar -->
        <div class="hidden space-y-10 animate-in fade-in slide-in-from-left duration-500" id="recover-section">
            <a id="back-to-login-recover" href="#" class="inline-flex items-center text-zinc-400 hover:text-white transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 mr-2 group-hover:-translate-x-1 transition-transform">
                    <path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z" clip-rule="evenodd" />
                </svg>
                <span class="text-[13px] font-medium">Volver</span>
            </a>
            
            <div class="space-y-2">
                <h1 class="text-4xl font-normal uppercase tracking-tight font-bebas leading-none text-white">Recuperar Acceso</h1>
                <p class="auth-subtitle italic">Enviaremos un código a tu correo</p>
            </div>
            
            <form action="recover.php" method="post" class="flex flex-col space-y-8" id="recover-form">
                <div class="flex flex-col">
                    <label for="email_rec" class="auth-label">Correo registrado</label>
                    <input class="auth-input" type="email" name="email" id="email_rec" placeholder="Ingresa tu correo">
                </div>
                <input class="bg-[#E50914] text-white font-black uppercase tracking-[0.3em] text-[10px] py-3.5 rounded-sm cursor-pointer hover:bg-[#b90710] transition-all shadow-xl" type="submit" value="Enviar Código">
            </form>
        </div>

        <!-- Registro -->
        <div class="hidden space-y-8 animate-in fade-in slide-in-from-left duration-500" id="register-section">
            <a id="back-to-login" href="#" class="inline-flex items-center text-zinc-400 hover:text-white transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 mr-2 group-hover:-translate-x-1 transition-transform">
                    <path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z" clip-rule="evenodd" />
                </svg>
                <span class="text-[13px] font-medium">Volver</span>
            </a>
            
            <div class="space-y-2">
                <h1 class="text-4xl font-normal uppercase tracking-tight font-bebas leading-none text-white">Crear Cuenta</h1>
            </div>
            
            <form action="register.php" method="post" class="flex flex-col space-y-6" id="register-form">
                <div class="flex flex-col">
                    <label for="nombre" class="auth-label">Nombre completo</label>
                    <input class="auth-input" type="text" name="nombre" id="nombre" placeholder="Ingrese tu nombre">
                </div>
                <div class="flex flex-col">
                    <label for="email_r" class="auth-label">Correo electrónico</label>
                    <input class="auth-input" type="email" name="email" id="email_r" placeholder="Ingresa tu correo">
                </div>
                <div class="flex flex-col">
                    <label for="password_r" class="auth-label">Contraseña</label>
                    <input class="auth-input" type="password" name="password" id="password_r" placeholder="••••••••">
                </div>
                <div class="flex flex-col">
                    <label for="confirm_password" class="auth-label">Confirmar contraseña</label>
                    <input class="auth-input" type="password" name="confirm_password" id="confirm_password" placeholder="C tu contraseña">
                </div>
                <input class="bg-[#E50914] text-white font-black uppercase tracking-[0.3em] text-[10px] py-3.5 rounded-sm cursor-pointer hover:bg-[#b90710] transition-all mt-4 shadow-xl" type="submit" value="Registrarse">
            </form>
        </div>
    </div>

    <!-- Sección de Imagen -->
    <div class="hidden md:block flex-1 relative bg-black">
        <div class="absolute inset-0 bg-gradient-to-r from-black via-transparent to-transparent z-10"></div>
        <img class="w-full h-full object-cover opacity-100 transition-all duration-1000" src="https://i.pinimg.com/originals/8c/4e/c5/8c4ec5138991592cca31db6b2ed46e5e.gif" alt="cine">
    </div>

    <?php include '../src/View/Modales/auth_modal.php'; ?>

</body>
<script src="./js/index.js"></script>
</html>