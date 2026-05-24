<?php
session_start();
require_once __DIR__ . '/../Service/PeliculaService.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../public/index.php");
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
    <link rel="icon" type="image/svg+xml" href="../../public/img/logo.svg">
    <style>
        /* Modern Button Polish */
        .btn-primary {
            background-color: #E50914 !important;
            color: white !important;
            border: none !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
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
        body { font-family: 'Inter', sans-serif; background-color: #000; color: #fff; }
        .font-bebas { font-family: 'Bebas Neue', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #09090b; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #27272a; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #E50914; }
        
        #mobile-menu.active {
            max-height: 500px;
            opacity: 1;
            padding-top: 1rem;
            padding-bottom: 2rem;
        }
    </style>
</head>
<body class="min-h-screen custom-scrollbar">
    
    <!-- Navbar (Estilo Premium Glassmorphism) -->
    <header class="bg-black/80 backdrop-blur-md border-b border-zinc-800 sticky top-0 z-50 transition-all duration-300">
        <nav class="container mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-12">
                <h2 onclick="window.location.reload()" class="text-4xl font-black text-[#E50914] uppercase tracking-[0.15em] leading-none font-bebas cursor-pointer">
                    CINE FIRST
                </h2>
                <ul class="hidden lg:flex items-center gap-10 text-[10px] font-bold uppercase tracking-[0.25em] text-zinc-400">
                    <li onclick="showSection('cartelera')" id="nav-cartelera" class="text-white relative after:absolute after:-bottom-2 after:left-0 after:w-full after:h-0.5 after:bg-[#E50914] cursor-pointer">Cartelera</li>
                    <li onclick="showSection('cines')" id="nav-cines" class="hover:text-white cursor-pointer transition-all">Cines</li>
                    <?php if($_SESSION['permisos'] == 1): ?>
                        <li onclick="openAdminDashboard()" class="hover:text-[#E50914] cursor-pointer transition-all flex items-center gap-2 group">
                            <span class="w-1.5 h-1.5 rounded-full bg-zinc-600 group-hover:bg-[#E50914] transition-colors"></span>
                            Administrar
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="flex items-center gap-6">
                <div class="flex items-center gap-4 pl-6 border-l border-zinc-800 hidden sm:flex">
                    <div class="flex flex-col items-end">
                        <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest"><?php echo $_SESSION['usuario_nombre']; ?></span>
                        <button onclick="openUserSettings()" class="text-[8px] font-black text-[#E50914] uppercase tracking-widest hover:text-white transition-colors">Ajustes</button>
                    </div>
                    <a href="logout.php" class="text-zinc-500 hover:text-[#E50914] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </a>
                </div>

                <!-- Hamburger Button -->
                <button id="mobile-menu-btn" onclick="toggleMobileMenu()" class="lg:hidden text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="hamburger-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16m-7 6h7"/>
                    </svg>
                </button>
            </div>
        </nav>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="lg:hidden hidden bg-zinc-950 border-b border-zinc-800 overflow-hidden transition-all duration-300 max-h-0 opacity-0">
            <div class="container mx-auto px-6 py-4 flex flex-col gap-6">
                <a href="#" onclick="showSection('cartelera'); toggleMobileMenu()" class="text-white text-sm font-black uppercase tracking-[0.2em]">Cartelera</a>
                <a href="#" onclick="showSection('cines'); toggleMobileMenu()" class="text-zinc-400 text-sm font-black uppercase tracking-[0.2em]">Cines</a>
                <?php if($_SESSION['permisos'] == 1): ?>
                    <a href="#" onclick="openAdminDashboard(); toggleMobileMenu()" class="text-[#E50914] text-sm font-black uppercase tracking-[0.2em]">Administrar</a>
                <?php endif; ?>
                <div class="h-px bg-zinc-900 my-2"></div>
                <div class="flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest"><?php echo $_SESSION['usuario_nombre']; ?></span>
                        <button onclick="openUserSettings(); toggleMobileMenu()" class="text-[#E50914] text-[10px] font-black uppercase tracking-widest text-left">Ajustes de cuenta</button>
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

    <!-- Hero Section (Elegante y Cinematico) -->
    <section class="relative h-[45vh] w-full flex items-center justify-center overflow-hidden border-b border-zinc-900">
        <div class="absolute inset-0 bg-black z-10 opacity-60"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black z-10"></div>
        <img id="hero-bg" src="https://images.unsplash.com/photo-1478720568477-152d9b164e26?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
             class="absolute inset-0 w-full h-full object-cover scale-105" alt="Cinema">
        
        <div class="container mx-auto px-4 relative z-20 text-center">
            <h1 id="hero-title" class="text-7xl md:text-9xl font-black uppercase tracking-tighter font-bebas leading-none mb-2 text-white">
                CARTELERA <span class="text-[#E50914]">NACIONAL</span>
            </h1>
            <p id="hero-subtitle" class="text-zinc-400 text-xs font-bold uppercase tracking-[0.5em]">La mejor experiencia en la pantalla grande</p>
        </div>
    </section>

    <!-- Main Content -->
    <main id="main-content" class="container mx-auto px-6 py-20">
        <!-- Cartelera Section -->
        <div id="section-cartelera">
            <!-- Filtros Estilo Cine Colombia -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-16 border-b border-zinc-900 pb-8">
                <div class="mb-6 md:mb-0">
                    <h2 class="text-[#E50914] font-black uppercase tracking-[0.2em] text-[10px] mb-2">Selección</h2>
                    <h3 class="text-4xl font-black uppercase tracking-tight font-bebas">En cartelera</h3>
                </div>
                
                <div class="flex items-center gap-4">
                    <span class="text-[10px] font-black text-zinc-600 uppercase tracking-widest">Filtrar por:</span>
                    <select id="filter-genre" class="bg-zinc-900 border border-zinc-800 text-white text-[10px] font-bold uppercase tracking-widest px-4 py-2 rounded-sm focus:border-[#E50914] outline-none transition-all cursor-pointer">
                        <option value="">Todos los géneros</option>
                        <?php 
                            $generos = $peliculaService->listarGeneros();
                            foreach ($generos as $g): 
                        ?>
                            <option value="<?php echo $g->getIdGenero(); ?>"><?php echo $g->getNombreGenero(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Películas Grid (100% Tailwind) -->
            <ul class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-x-6 gap-y-12">
                <?php $peliculaService->obtenerTablas(); ?>
            </ul>
        </div>

        <!-- Cines Section (Hidden by default) -->
        <div id="section-cines" class="hidden">
            <div class="mb-16 border-b border-zinc-900 pb-8">
                <h2 class="text-[#E50914] font-black uppercase tracking-[0.2em] text-[10px] mb-2">Localización</h2>
                <h3 class="text-4xl font-black uppercase tracking-tight font-bebas">Nuestros Cines</h3>
            </div>

            <div id="cines-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Inyectado por JS -->
                <div class="animate-pulse flex flex-col gap-4">
                    <div class="h-64 bg-zinc-900 rounded-sm"></div>
                    <div class="h-6 w-3/4 bg-zinc-900 rounded-sm"></div>
                    <div class="h-4 w-1/2 bg-zinc-900 rounded-sm"></div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Ajustes de Usuario (ESTILO MODERNO UNIFICADO) -->
    <div id="user-settings-modal" class="hidden fixed inset-0 z-[120] flex items-center justify-center bg-black/80 backdrop-blur-md p-4 transition-all duration-500 opacity-0 pointer-events-none" onclick="if(event.target === this) closeUserSettings()">
        <div class="bg-zinc-950/40 backdrop-blur-2xl w-full max-w-md rounded-[3rem] border border-white/10 shadow-2xl overflow-hidden transform transition-all duration-500 scale-95" id="settings-modal-content">
            <div class="p-10 md:p-14">
                <!-- Botón Cerrar Absoluto (Como en la captura) -->
                <button onclick="closeUserSettings()" class="absolute top-8 right-8 btn-close-rot z-20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <form id="user-settings-form" class="space-y-10">
                    <div class="space-y-2 mb-10">
                        <h2 class="text-3xl font-black text-white font-bebas tracking-widest uppercase leading-none">AJUSTES</h2>
                        <p class="text-zinc-500 text-[9px] font-bold uppercase tracking-[0.3em]">Gestiona tu perfil personal</p>
                    </div>

                    <div class="crud-input-group">
                        <input type="text" name="nombre" value="<?php echo $_SESSION['usuario_nombre']; ?>" class="crud-input" placeholder=" " required>
                        <label class="crud-label">Nombre Completo</label>
                    </div>
                    
                    <div class="crud-input-group">
                        <input type="email" value="<?php echo $_SESSION['usuario_correo']; ?>" disabled class="crud-input !text-zinc-600 opacity-50 cursor-not-allowed" placeholder=" ">
                        <label class="crud-label !text-zinc-700">Correo (No Editable)</label>
                    </div>

                    <div class="auth-input-group">
                        <input type="password" name="password" id="password_settings" class="auth-input-modern" placeholder=" ">
                        <label class="auth-label-modern">Nueva Contraseña</label>
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password_settings')">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                <path class="eye-slash hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>

                    <div class="auth-input-group">
                        <input type="password" name="confirm_password" id="confirm_settings" class="auth-input-modern" placeholder=" ">
                        <label class="auth-label-modern">Confirmar Contraseña</label>
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('confirm_settings')">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                <path class="eye-slash hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>

                    <div id="settings-message" class="hidden text-[9px] font-black uppercase tracking-widest text-center py-4 rounded-xl"></div>

                    <button type="submit" class="w-full bg-[#E50914] hover:bg-[#b90710] text-white text-[11px] font-black uppercase tracking-[0.4em] py-5 rounded-2xl shadow-xl shadow-red-900/20 transition-all active:scale-95 font-montserrat mt-4">
                        Guardar Cambios
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer Cine First Simplificado -->
    <footer class="bg-[#050505] border-t border-zinc-900 py-12">
        <div class="container mx-auto px-6 text-center">
            <p class="text-zinc-700 text-[10px] font-black uppercase tracking-[0.3em]">
                © 2026 CINE FIRST - Todos los derechos reservados
            </p>
        </div>
    </footer>

    <!-- Modales -->
    <?php include './Modales/movie_admin_modal.php'; ?>
    <?php include './Modales/admin_dashboard_modal.php'; ?>
    <?php include './Modales/admin_insert_modal.php'; ?>

    <!-- Scripts -->
    <script src="../../public/js/movie_admin.js"></script>
    <script src="../../public/js/admin_panel.js"></script>
    <script src="../../public/js/home.js"></script>
</body>
</html>
