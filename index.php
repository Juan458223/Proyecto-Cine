<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <script src="https://cdn.tailwindcss.com"></script>
    <title>Login</title>
</head>
<body class="flex flex-col md:flex-row h-screen w-full overflow-hidden">

    <div class="bg-black text-white h-full w-full md:w-[400px] flex flex-col justify-center p-9 ">
        <h1 class="text-3xl font-bold mb-5">Login</h1>
        <form action="login.php" method="post" class=" flex flex-col">
            <label class="font-semibold">Email</label>
            <input class="border p-1 mb-4" type="email" name="email" required>
            <label class="font-semibold">Password</label>
            <a href="register.php" class="p-1 text-red-500 hover:text-red-400">Regístrate aquí</a>
            <input class="border p-2 mb-4" type="password" name="password" required>
            <input class="bg-red-800 text-white p-2 cursor-pointer" type="submit" value="Login">
        </form>
        <p>¿Aún no tienes cuenta?<a href="register.php" class="text-red-500 hover:text-red-400"> Regístrate aquí</a></p>
    </div>

    <div class="hidden md:block flex-1 bg-gray-200">
        <img class="w-full h-full object-cover" src="https://i.pinimg.com/originals/8c/4e/c5/8c4ec5138991592cca31db6b2ed46e5e.gif" alt="cine">
    </div>

</body>
</html>