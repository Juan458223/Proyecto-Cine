<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Service/UsuarioService.php';
require_once __DIR__ . '/../Service/PeliculaService.php';
require_once __DIR__ . '/../Service/FuncionService.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Verificación de seguridad: solo administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['permisos'] !== 'Administrador') {
    http_response_code(403);
    echo "Acceso denegado. Su rol actual es: " . ($_SESSION['permisos'] ?? 'No definido') . ". Por favor, cierre sesión y vuelva a entrar.";
    exit;
}

$action = $_GET['action'] ?? 'generate';
$type = $_GET['type'] ?? 'usuarios'; // usuarios, funciones, peliculas
$period = $_GET['period'] ?? 'mensual'; // semanal, quincenal, mensual

if ($action === 'generate') {
    try {
        if (ob_get_length()) ob_end_clean();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');
        $dompdf = new Dompdf($options);

        $html = '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: "Helvetica", sans-serif; color: #333; }
                h1 { color: #E50914; text-align: center; border-bottom: 2px solid #E50914; padding-bottom: 10px; }
                h2 { color: #444; border-left: 5px solid #E50914; padding-left: 10px; margin-top: 30px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th { background-color: #f2f2f2; color: #555; font-weight: bold; text-align: left; padding: 10px; border: 1px solid #ddd; }
                td { padding: 10px; border: 1px solid #ddd; }
                tr:nth-child(even) { background-color: #fafafa; }
                .header { text-align: right; font-size: 12px; color: #777; margin-bottom: 20px; }
                .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 5px; }
            </style>
        </head>
        <body>
            <div class="header">Generado el: ' . date('d/m/Y H:i') . '</div>';

        if ($type === 'usuarios') {
            $usuarioService = new UsuarioService();
            
            // Determinar días según el periodo
            $dias = 30;
            $tituloPeriodo = 'Mensual';
            if ($period === 'semanal') { $dias = 7; $tituloPeriodo = 'Semanal'; }
            elseif ($period === 'quincenal') { $dias = 15; $tituloPeriodo = 'Quincenal'; }

            $usuarios = $usuarioService->obtenerUsuariosPorPeriodo($dias);
            
            $html .= '<h1>Informe ' . $tituloPeriodo . ' de Usuarios - CINE FIRST</h1>';
            $html .= '<p style="font-size: 12px; color: #666;">Registros de los últimos ' . $dias . ' días.</p>';
            
            $html .= '<h2>Total Registros: ' . count($usuarios) . '</h2>';

            $html .= '<table>
                <thead><tr><th>Nombre</th><th>Correo</th><th>Fecha de Registro</th></tr></thead>
                <tbody>';
            if (empty($usuarios)) {
                $html .= '<tr><td colspan="3">No hay registros en este periodo.</td></tr>';
            } else {
                foreach ($usuarios as $user) {
                    $html .= '<tr><td>' . htmlspecialchars($user['nombre']) . '</td><td>' . htmlspecialchars($user['correo']) . '</td><td>' . date('d/m/Y H:i', strtotime($user['fecha'])) . '</td></tr>';
                }
            }
            $html .= '</tbody></table>';

        } elseif ($type === 'funciones') {
            $funcionService = new FuncionService();
            $funciones = $funcionService->obtenerFuncionesPasadas();
            $html .= '<h1>Informe de Funciones Realizadas - CINE FIRST</h1>';
            $html .= '<p style="font-size: 12px; color: #666;">Funciones hasta la fecha actual (' . date('d/m/Y H:i') . ').</p>';
            $html .= '<table>
                <thead><tr><th>Fecha y Hora</th><th>Película</th><th>Cine</th><th>Sala</th></tr></thead>
                <tbody>';
            if (empty($funciones)) {
                $html .= '<tr><td colspan="4">No hay funciones pasadas.</td></tr>';
            } else {
                foreach ($funciones as $f) {
                    $html .= '<tr><td>' . date('d/m/Y H:i', strtotime($f['fecha_hora'])) . '</td><td>' . htmlspecialchars($f['pelicula']) . '</td><td>' . htmlspecialchars($f['cine']) . '</td><td>Sala ' . $f['sala'] . '</td></tr>';
                }
            }
            $html .= '</tbody></table>';

        } elseif ($type === 'peliculas') {
            $peliculaService = new PeliculaService();
            $peliculas = $peliculaService->obtenerEstadisticasPeliculas();
            $html .= '<h1>Informe de Películas en Sistema - CINE FIRST</h1>';
            $html .= '<table>
                <thead><tr><th>Título</th><th>Director</th><th>Género</th><th>Clasificación</th></tr></thead>
                <tbody>';
            if (empty($peliculas)) {
                $html .= '<tr><td colspan="4">No hay películas registradas.</td></tr>';
            } else {
                foreach ($peliculas as $p) {
                    $html .= '<tr><td>' . htmlspecialchars($p['titulo']) . '</td><td>' . htmlspecialchars($p['director']) . '</td><td>' . htmlspecialchars($p['genero']) . '</td><td>' . $p['clasificacion'] . '</td></tr>';
                }
            }
            $html .= '</tbody></table>';
        }

        $html .= '<div class="footer">Cine First - Sistema de Administración Interna</div></body></html>';
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Reporte_" . ucfirst($type) . "_" . date('Ymd') . ".pdf", ["Attachment" => true]);
    } catch (Exception $e) {
        echo "Error al generar el PDF: " . $e->getMessage();
    }
}
?>
