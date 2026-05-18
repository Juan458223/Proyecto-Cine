<?php
session_start();
require_once __DIR__ . '/../src/Service/PeliculaService.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$peliculaService = new PeliculaService();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenido UD Cinema</title>
    <link rel="stylesheet" href="http://localhost:5173/public/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="./img/logo.svg">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #000; color: #fff; }
        .font-bebas { font-family: 'Bebas Neue', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #09090b; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #27272a; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #E50914; }
    </style>
</head>
<body class="min-h-screen custom-scrollbar">
    
    <!-- Navbar (Estilo Premium Glassmorphism) -->
    <header class="bg-black/80 backdrop-blur-md border-b border-zinc-800 sticky top-0 z-50 transition-all duration-300">
        <nav class="container mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-12">
                <h2 class="text-4xl font-black text-[#E50914] uppercase tracking-[0.15em] leading-none font-bebas">
                    CINE FIRST
                </h2>
                <ul class="hidden lg:flex items-center gap-10 text-[10px] font-bold uppercase tracking-[0.25em] text-zinc-400">
                    <li class="text-white relative after:absolute after:-bottom-2 after:left-0 after:w-full after:h-0.5 after:bg-[#E50914] cursor-pointer">Cartelera</li>
                    <li class="hover:text-white cursor-pointer transition-all">Cines</li>
                    <?php if($_SESSION['permisos'] == 1): ?>
                        <li onclick="openAdminDashboard()" class="hover:text-[#E50914] cursor-pointer transition-all flex items-center gap-2 group">
                            <span class="w-1.5 h-1.5 rounded-full bg-zinc-600 group-hover:bg-[#E50914] transition-colors"></span>
                            Administrar
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="flex items-center gap-6">
                <div class="flex items-center gap-4 pl-6 border-l border-zinc-800">
                    <span class="hidden sm:block text-[10px] font-bold text-zinc-500 uppercase tracking-widest"><?php echo $_SESSION['usuario_nombre']; ?></span>
                    <a href="logout.php" class="text-zinc-500 hover:text-[#E50914] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section (Elegante y Cinematico) -->
    <section class="relative h-[45vh] w-full flex items-center justify-center overflow-hidden border-b border-zinc-900">
        <div class="absolute inset-0 bg-black z-10 opacity-60"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black z-10"></div>
        <img src="https://images.unsplash.com/photo-1478720568477-152d9b164e26?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
             class="absolute inset-0 w-full h-full object-cover scale-105" alt="Cinema">
        
        <div class="container mx-auto px-4 relative z-20 text-center">
            <h1 class="text-7xl md:text-9xl font-black uppercase tracking-tighter font-bebas leading-none mb-2 text-white">
                CARTELERA <span class="text-[#E50914]">NACIONAL</span>
            </h1>
            <p class="text-zinc-400 text-xs font-bold uppercase tracking-[0.5em]">La mejor experiencia en la pantalla grande</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-20">
        <!-- Filtros Estilo Cine Colombia -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-16 border-b border-zinc-900 pb-8">
            <div class="mb-6 md:mb-0">
                <h2 class="text-[#E50914] font-black uppercase tracking-[0.2em] text-[10px] mb-2">Selección</h2>
                <h3 class="text-4xl font-black uppercase tracking-tight font-bebas">En Cartelera</h3>
            </div>
            
            <div class="flex items-center gap-4">
                <span class="text-[10px] font-black text-zinc-600 uppercase tracking-widest">Filtrar por:</span>
                <select class="bg-zinc-900 border border-zinc-800 text-white text-[10px] font-bold uppercase tracking-widest px-4 py-2 rounded-sm focus:border-[#E50914] outline-none transition-all cursor-pointer">
                    <option>Todos los Géneros</option>
                    <option>Acción</option>
                    <option>Comedia</option>
                    <option>Dibujos</option>
                </select>
            </div>
        </div>

        <!-- Películas Grid (100% Tailwind) -->
        <ul class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-x-6 gap-y-12">
            <?php $peliculaService->obtenerTablas(); ?>
        </ul>
    </main>

    <!-- Footer Cine First Premium -->
    <footer class="bg-[#050505] border-t border-zinc-900 pt-24 pb-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-20">
                <!-- Brand -->
                <div class="space-y-8">
                    <h2 class="text-4xl font-black text-[#E50914] uppercase tracking-[0.2em] font-bebas">CINE FIRST</h2>
                    <p class="text-zinc-500 text-[11px] font-medium leading-relaxed uppercase tracking-tighter">
                        La cadena de cines líder en tecnología y confort. Disfruta de la mejor cartelera nacional con sonido envolvente y pantallas de última generación.
                    </p>
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-zinc-900 flex items-center justify-center text-zinc-400 hover:bg-[#E50914] hover:text-white transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-zinc-900 flex items-center justify-center text-zinc-400 hover:bg-[#E50914] hover:text-white transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div>
                    <h4 class="text-white font-black text-[10px] uppercase tracking-[0.4em] mb-8">Cartelera</h4>
                    <ul class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest space-y-4">
                        <li class="hover:text-[#E50914] cursor-pointer transition-colors">Próximos Estrenos</li>
                        <li class="hover:text-[#E50914] cursor-pointer transition-colors">Preventa Exclusiva</li>
                        <li class="hover:text-[#E50914] cursor-pointer transition-colors">Cine Premium</li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="text-white font-black text-[10px] uppercase tracking-[0.4em] mb-8">Atención</h4>
                    <ul class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest space-y-4">
                        <li class="hover:text-white cursor-pointer transition-colors">Contáctanos</li>
                        <li class="hover:text-white cursor-pointer transition-colors">Preguntas Frecuentes</li>
                        <li class="hover:text-white cursor-pointer transition-colors">Protocolos de Seguridad</li>
                    </ul>
                </div>

                <!-- Support -->
                <div class="space-y-6">
                    <h4 class="text-white font-black text-[10px] uppercase tracking-[0.4em] mb-8">Soporte</h4>
                    <div class="flex items-start gap-4 text-zinc-500">
                        <svg class="w-5 h-5 text-[#E50914]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span class="text-[10px] font-bold uppercase tracking-widest">cinefirst@gmail.com</span>
                    </div>
                    <div class="flex items-start gap-4 text-zinc-500">
                        <svg class="w-5 h-5 text-[#E50914]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span class="text-[10px] font-bold uppercase tracking-widest">Sede Principal, Colombia</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="pt-12 border-t border-zinc-900 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-zinc-700 text-[9px] font-black uppercase tracking-[0.3em]">© 2026 CINE FIRST - Todos los derechos reservados</p>
                <div class="flex gap-8 text-[9px] font-black text-zinc-700 uppercase tracking-widest">
                    <span class="hover:text-zinc-400 cursor-pointer transition-colors">Términos</span>
                    <span class="hover:text-zinc-400 cursor-pointer transition-colors">Privacidad</span>
                    <span class="hover:text-zinc-400 cursor-pointer transition-colors">Cookies</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Modales -->
    <?php include '../src/View/Modales/movie_admin_modal.php'; ?>
    <?php include '../src/View/Modales/admin_dashboard_modal.php'; ?>
    <?php include '../src/View/Modales/admin_insert_modal.php'; ?>

    <!-- Scripts -->
    <script src="./js/movie_admin.js"></script>
    <script src="./js/admin_panel.js"></script>
</body>
</html>
