<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="http://localhost:5173/public/css/style.css">
    <title>Iniciar Sesión</title>
</head>
<body class="flex flex-col md:flex-row h-screen w-full overflow-hidden">

    <div class="bg-black text-white h-full w-full md:w-[400px] flex flex-col justify-center p-9 " id="login-section">
        <h1 class="text-3xl font-bold mb-5">Iniciar Sesión</h1>
        <form action="login.php" method="post" class=" flex flex-col" id="login-form">
            <label for="email" class="pt-1 mb-4 font-semibold">Correo Electrónico</label>
            <input class="bg-white text-black border p-1 mb-4" type="email" name="email" id="email">
            <div class="pt-1 flex justify-between items-center mb-4">
            <label for="password" class="font-semibold">Contraseña</label>
            <a href="#" id="recover-link" class=" text-red-500 hover:text-red-400">¿Olvidaste tu contraseña?</a>
            </div>
            <input class="bg-white text-black border p-1 mb-4" type="password" name="password" id="password">
            <input class="bg-red-800 text-white p-2 cursor-pointer hover:bg-red-700" type="submit" value="Iniciar Sesión">
        </form>
        <div class="pt-4 flex justify-between items-center mb-4">
        <p>¿Aún no tienes cuenta?</p>
        <a href="#" class="text-red-500 hover:text-red-400" id="register-link"> Regístrate aquí</a>
        </div>
    </div>

    <div class="hidden bg-black text-white h-full w-full md:w-[400px] flex flex-col justify-center p-9 " id="recover-section">
        <a id="back-to-login-recover" href="#" class="cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="mb-5 size-6">
                <path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z" clip-rule="evenodd" />
            </svg>
        </a>
        <h1 class="text-3xl font-bold mb-5">Recuperar Contraseña</h1>
        <p class="mb-5 text-gray-400 text-sm">Ingresa tu correo y te enviaremos un código para restablecer tu contraseña.</p>
        <form action="recover.php" method="post" class=" flex flex-col" id="recover-form">
            <label for="email_rec" class="mb-4 font-semibold">Correo Electrónico</label>
            <input class="bg-white text-black border p-1 mb-6" type="email" name="email" id="email_rec">
            <input class="bg-red-800 text-white p-2 cursor-pointer hover:bg-red-700" type="submit" value="Enviar Código">
        </form>
    </div>
    <div class="hidden bg-black text-white h-full w-full md:w-[400px] flex flex-col justify-center p-9 " id="register-section">
    <a id="back-to-login" href="#" class="cursor-pointer"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="mb-5 size-6">
    <path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z" clip-rule="evenodd" />
    </svg>
    </a>
    <h1 class="text-3xl font-bold mb-5">Registrarse</h1>
        <form action="register.php" method="post" class=" flex flex-col" id="register-form">
            <label for="nombre" class="mb-4 font-semibold">Nombre</label>
            <input class="bg-white text-black border p-1 mb-4" type="text" name="nombre" id="nombre">
            <label for="email_r" class="pt-1 mb-4 font-semibold">Correo Electrónico</label>
            <input class="bg-white text-black border p-1 mb-4" type="email" name="email" id="email_r">
            <label for="password_r" class="pt-1 mb-4 font-semibold">Contraseña</label>
            <input class="bg-white text-black border p-1 mb-4" type="password" name="password" id="password_r">
            <label for="confirm_password" class="font-semibold">Confirmar Contraseña</label>
            <input class="bg-white text-black border p-1 mb-4" type="password" name="confirm_password" id="confirm_password">
            <input class="bg-red-800 text-white p-2 cursor-pointer hover:bg-red-700" type="submit" value="Registrarse">
        </form>
    </div>

    <div class="hidden md:block flex-1 bg-gray-200">
        <img class="w-full h-full object-cover" src="https://i.pinimg.com/originals/8c/4e/c5/8c4ec5138991592cca31db6b2ed46e5e.gif" alt="cine">
    </div>

    <?php include '../src/View/Modales/auth_modal.php'; ?>

</body>
<script src="./js/index.js"></script>
</html>