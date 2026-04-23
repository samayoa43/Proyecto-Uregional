<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'funciones_dash.php'; 
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php"); 
    exit();
}

if ($_SESSION['rol'] !== 'admin') {
    // Si un Docente o Admin intenta husmear aquí, le bloqueamos el paso
    echo "<div style='text-align: center; margin-top: 50px; font-family: Arial;'>";
    echo "<h3 style='color: #d9534f;'>Acceso Denegado. Esta área es exclusiva para Administradores.</h3>";
    echo "<a href='login.php' style='text-decoration: none; background: #0056b3; color: white; padding: 10px 15px; border-radius: 5px;'>Volver a mi panel</a>";
    echo "</div>";
    exit();
}

$nombre = $_SESSION['nombre_usuario'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plataforma Académica</title>
    <link rel="stylesheet" href="estilo_administrativo.css">
</head>
<body>
    
    <header class="top-navbar">
        <div class="nav-left">
            <button class="hamburger-btn" id="menuToggle" aria-label="Abrir menú">
                <svg viewBox="0 0 24 24" width="30" height="30" fill="white">
                    <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
                </svg>
            </button>
            
            <div class="brand-title">
                <h1>Portal Académico</h1>
                <p>Universidad Regional</p>
            </div>
        </div>

        <div class="nav-right">
            <span class="user-greeting">Bienvenido, <strong><?php echo htmlspecialchars($nombre); ?></strong></span>
            <a href="../logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </header>

    <nav class="side-menu" id="sideMenu">
        <div class="menu-content">
            <?php require 'encabezado.php'; ?>
        </div>
    </nav>

    <main class="main-container">
        <h2 class="section-title">Panel de Inteligencia Estratégica</h2>
        
</html>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const sideMenu = document.getElementById('sideMenu');

            menuToggle.addEventListener('click', function() {
                // Alterna la clase 'active' para abrir o cerrar el menú
                sideMenu.classList.toggle('active');
            });
        });
    </script>
</body>
