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
        .cine-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem 1.5rem;
        }
        @media (min-width: 640px) { .cine-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (min-width: 768px) { .cine-grid { grid-template-columns: repeat(4, 1fr); } }
        @media (min-width: 1024px) { .cine-grid { grid-template-columns: repeat(5, 1fr); } }
        @media (min-width: 1280px) { .cine-grid { grid-template-columns: repeat(6, 1fr); } }
    </style>
</head>
<body class="min-h-screen">
    
    <!-- Navbar (Estilo Cine Colombia / CINE FIRST) -->
    <header class="bg-black border-b border-zinc-800 sticky top-0 z-50">
        <nav class="container mx-auto px-4 h-24 flex items-center justify-between">
            <div class="flex items-center gap-12">
                <h2 class="text-5xl font-black text-[#E50914] uppercase tracking-widest leading-none font-bebas" style="text-shadow: 0 0 15px rgba(229, 9, 20, 0.3);">
                    CINE FIRST
                </h2>
                <ul class="hidden lg:flex items-center gap-8 text-[11px] font-black uppercase tracking-[0.2em] text-zinc-500">
                    <li class="text-white border-b-2 border-[#E50914] pb-1 cursor-pointer transition-all">Cartelera</li>
                    <li class="hover:text-white cursor-pointer transition-all">Cines</li>
                    <?php
                    if($_SESSION['permisos']==1){
                    ?>
                        <li class="hover:text-white cursor-pointer transition-all">Administrar</li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Hero Section (Elegante y Cinematico) -->
    <section class="relative h-[45vh] w-full flex items-center justify-center overflow-hidden border-b border-zinc-900">
        <div class="absolute inset-0 bg-black z-10 opacity-40"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black z-10"></div>
        <img src="https://images.unsplash.com/photo-1478720568477-152d9b164e26?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
             class="absolute inset-0 w-full h-full object-cover scale-105" alt="Cinema">
        
        <div class="container mx-auto px-4 relative z-20 text-center">
            <h1 class="text-7xl md:text-9xl font-black uppercase tracking-tighter font-bebas leading-none mb-2">
                CARTELERA <span class="text-[#E50914]">NACIONAL</span>
            </h1>
            <p class="text-zinc-400 text-xs font-bold uppercase tracking-[0.5em]">La mejor experiencia en la pantalla grande</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-20">
        <!-- Filtros Estilo Cine Colombia -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-16 border-b border-zinc-900 pb-8">
            <div class="mb-6 md:mb-0">
                <h2 class="text-[#E50914] font-black uppercase tracking-[0.2em] text-xs mb-2">Selección</h2>
                <h3 class="text-4xl font-black uppercase tracking-tight font-bebas">En Cartelera</h3>
            </div>
            
            <div class="flex items-center gap-4">
                <span class="text-[10px] font-black text-zinc-600 uppercase tracking-widest">Filtrar por:</span>
                <select class="bg-zinc-900 border border-zinc-800 text-white text-[10px] font-bold uppercase tracking-widest px-4 py-2 rounded-sm focus:border-[#E50914] outline-none transition-all">
                    <option>Todos los Géneros</option>
                    <option>Acción</option>
                    <option>Comedia</option>
                    <option>Dibujos</option>
                </select>
            </div>
        </div>

        <!-- Películas Grid -->
        <ul class="cine-grid">
            <?php $peliculaService->obtenerTablas(); ?>
        </ul>
    </main>

    <!-- Footer Estilo Cine Colombia -->
    <footer class="bg-zinc-950 border-t border-zinc-900 py-20">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left">
            <div>
                <h2 class="text-3xl font-black text-[#E50914] uppercase tracking-widest font-bebas mb-6">CINE FIRST</h2>
                <p class="text-zinc-500 text-xs leading-relaxed max-w-xs mx-auto md:mx-0 uppercase tracking-tighter">
                    Líderes en entretenimiento cinematográfico con tecnología de punta y la mejor atención.
                </p>
            </div>
            <div class="space-y-4">
                <h4 class="text-white font-bold text-[10px] uppercase tracking-[0.3em] mb-6">Información</h4>
                <ul class="text-zinc-600 text-[10px] font-bold uppercase tracking-widest space-y-3">
                    <li class="hover:text-white cursor-pointer transition-colors">Sobre Nosotros</li>
                    <li class="hover:text-white cursor-pointer transition-colors">Términos y Condiciones</li>
                    <li class="hover:text-white cursor-pointer transition-colors">Privacidad</li>
                </ul>
            </div>
            <div class="flex flex-col items-center md:items-end justify-center">
                <p class="text-zinc-700 text-[9px] font-black uppercase tracking-[0.3em]">© 2026 UD Cinema Group</p>
            </div>
        </div>
    </footer>

    <!-- Modal de Detalles -->
    <?php include '../src/View/Modales/movie_admin_modal.php'; ?>

    <!-- Scripts -->
    <script src="./js/movie_admin.js"></script>
</body>
</html>
