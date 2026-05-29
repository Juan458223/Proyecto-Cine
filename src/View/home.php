<?php
session_start();
require_once __DIR__ . '/Components/password_input.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../public/index.php");
    exit();
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cine First</title>
    <link rel="stylesheet" href="http://localhost:5173/public/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="../../public/img/logo.svg">
    <style>
        .btn-primary {
            background-color: #E50914 !important;
            color: white !important;
            border: none !important;
            font-weight: 900 !important;
            letter-spacing: 0.1em !important;
            border-radius: 1.25rem !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 10px 25px -5px rgba(229, 9, 20, 0.3) !important;
        }
        .btn-primary:hover {
            background-color: #ff1f2a !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 15px 30px -5px rgba(229, 9, 20, 0.5) !important;
        }
        body { font-family: 'Inter', sans-serif; background-color: #000; color: #fff; }
        .font-bebas { font-family: 'Bebas Neue', sans-serif; }
        
        #mobile-menu.active {
            max-height: 500px;
            opacity: 1;
            padding-top: 1rem;
            padding-bottom: 2rem;
        }

        /* Ajustes específicos para el botón del modal de acción */
        #form-submit-btn.btn-primary {
            width: auto !important;
            padding-left: 3rem !important;
            padding-right: 3rem !important;
            min-width: 220px !important;
        }

        /* Color rojo para el icono del calendario nativo */
        .calendar-input::-webkit-calendar-picker-indicator {
            filter: invert(15%) sepia(95%) saturate(6932%) hue-rotate(354deg) brightness(92%) contrast(92%);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .calendar-input::-webkit-calendar-picker-indicator:hover {
            transform: scale(1.1);
            filter: invert(20%) sepia(95%) saturate(7000%) hue-rotate(354deg) brightness(100%) contrast(100%);
        }
    </style>
</head>
<body class="min-h-screen custom-scrollbar">
    
    <header class="bg-black/80 backdrop-blur-md border-b border-zinc-800 sticky top-0 z-50 transition-all duration-300">
        <nav class="container mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-12">
                <div class="flex items-center gap-4">
                    <h2 onclick="window.location.reload()" class="text-4xl font-black text-[#E50914] uppercase tracking-[0.15em] leading-none font-bebas cursor-pointer">
                        CINE FIRST
                    </h2>
                </div>
                <ul class="hidden lg:flex items-center gap-10 text-xs font-bold text-zinc-400">
                    <li onclick="showSection('cartelera')" id="nav-cartelera" class="text-white relative after:absolute after:-bottom-2 after:left-0 after:w-full after:h-0.5 after:bg-[#E50914] cursor-pointer">Cartelera</li>
                    <li onclick="showSection('cines')" id="nav-cines" class="hover:text-white cursor-pointer transition-all">Cines</li>
                    <?php if($_SESSION['permisos'] === 'Administrador'): ?>
                        <li onclick="openAdminDashboard()" class="hover:text-white cursor-pointer transition-all flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-[#E50914]"></span>
                            Administrar
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="flex items-center gap-6">
                <div class="flex items-center gap-4 pl-6 border-l border-zinc-800 hidden sm:flex">
                    <div class="flex flex-col items-end">
                        <span class="text-sm font-bold text-zinc-300"><?php echo $_SESSION['usuario_nombre']; ?></span>
                        <button onclick="openUserSettings()" class="text-xs font-black text-[#E50914] hover:text-white transition-colors">Ajustes</button>
                    </div>
                    <a href="logout.php" class="text-zinc-500 hover:text-[#E50914] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </a>
                </div>

                <button id="mobile-menu-btn" onclick="toggleMobileMenu()" class="lg:hidden text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="hamburger-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16m-7 6h7"/>
                    </svg>
                </button>
            </div>
        </nav>

        <div id="mobile-menu" class="lg:hidden hidden bg-zinc-950 border-b border-zinc-800 overflow-hidden transition-all duration-300 max-h-0 opacity-0">
            <div class="container mx-auto px-6 py-4 flex flex-col gap-6">
                <a href="#" onclick="showSection('cartelera'); toggleMobileMenu()" class="text-white text-sm font-black uppercase tracking-[0.2em]">Cartelera</a>
                <a href="#" onclick="showSection('cines'); toggleMobileMenu()" class="text-zinc-400 text-sm font-black uppercase tracking-[0.2em]">Cines</a>
                <?php if($_SESSION['permisos'] === 'Administrador'): ?>
                    <a href="#" onclick="openAdminDashboard(); toggleMobileMenu()" class="text-zinc-400 hover:text-white text-sm font-bold flex items-center gap-2">
                        <span class="w-1 h-1 rounded-full bg-[#E50914]"></span>
                        Administrar
                    </a>
                <?php endif; ?>
                <div class="h-px bg-zinc-900 my-2"></div>
                <div class="flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-zinc-500 text-xs font-bold"><?php echo $_SESSION['usuario_nombre']; ?></span>
                        <button onclick="openUserSettings(); toggleMobileMenu()" class="text-[#E50914] text-[10px] font-black text-left">Ajustes de cuenta</button>
                    </div>
                    <a href="logout.php" class="text-zinc-500 hover:text-[#E50914]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <section class="relative h-[45vh] w-full flex items-center justify-center overflow-hidden border-b border-zinc-900">
        <div class="absolute inset-0 bg-black z-10 opacity-60"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black z-10"></div>
        <img id="hero-bg" src="https://images.unsplash.com/photo-1478720568477-152d9b164e26?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
             class="absolute inset-0 w-full h-full object-cover scale-105" alt="Cinema">
        
        <div class="container mx-auto px-4 relative z-20 text-center">
            <h1 id="hero-title" class="text-7xl md:text-9xl font-black uppercase tracking-tighter font-bebas leading-none mb-2 text-white">
                CARTELERA <span class="text-[#E50914]">NACIONAL</span>
            </h1>
            <p id="hero-subtitle" class="text-zinc-400 text-sm font-medium">La mejor experiencia en la pantalla grande</p>
        </div>
    </section>

    <main id="main-content" class="container mx-auto px-6 py-20">
        <div id="section-cartelera">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-16 border-b border-zinc-900 pb-8">
                <div class="mb-6 md:mb-0">
                    <h2 class="text-[#E50914] font-bold tracking-widest text-sm mb-1 font-outfit">Selección</h2>
                    <h3 class="text-2xl font-extrabold tracking-tight font-outfit">En cartelera</h3>
                </div>
                
                <div class="flex items-center gap-4">
                    <span class="text-xs font-bold text-zinc-600">Filtrar por:</span>
                    <select id="filter-genre" class="bg-zinc-900 border border-zinc-800 text-white text-xs font-bold px-4 py-2 rounded-sm focus:border-[#E50914] outline-none transition-all cursor-pointer">
                        <option value="">Todos los géneros</option>
                    </select>
                </div>
            </div>

            <ul id="movie-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-x-6 gap-y-12 transition-opacity duration-300">
            </ul>

            <div id="movie-pagination">
                <!-- Paginación gestionada por JS -->
            </div>
        </div>

        <div id="section-cines" class="hidden">
            <div class="mb-16 border-b border-zinc-900 pb-8">
                <h2 class="text-[#E50914] font-bold tracking-widest text-sm mb-1 font-outfit">Localización</h2>
                <h3 class="text-2xl font-extrabold tracking-tight font-outfit">Nuestros cines</h3>
            </div>

            <div id="cines-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            </div>

            <div id="cines-pagination" class="flex items-center justify-center gap-4 mt-16">
            </div>
        </div>
    </main>

    <?php include './Modales/user_settings_modal.php'; ?>

    <footer class="bg-[#050505] border-t border-zinc-900 py-12">
        <div class="container mx-auto px-6 text-center">
            <p class="text-zinc-700 text-xs font-bold font-outfit">
                © 2026 Cine First - Todos los derechos reservados
            </p>
        </div>
    </footer>

    <?php include './Modales/movie_admin_modal.php'; ?>
    <?php include './Modales/cine_detail_modal.php'; ?>
    <?php include './Modales/admin_dashboard_modal.php'; ?>
    <?php include './Modales/admin_action_modal.php'; ?>
    <?php include './Modales/admin_alert_modal.php'; ?>

    <script src="../../public/js/pelicula.js"></script>
    <script src="../../public/js/cine.js"></script>
    <script src="../../public/js/admin_panel.js"></script>
    <script src="../../public/js/home.js"></script>
</body>
</html>
