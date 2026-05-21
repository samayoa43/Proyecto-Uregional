<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plataforma Académica</title>
</head>
<body>
<script>
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
    }
</script>

<header class="top-navbar">
    <div class="nav-left">
        <button class="hamburger-btn" id="menuToggle" aria-label="Abrir menú">
            <svg viewBox="0 0 24 24" width="30" height="30" fill="white">
                <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
            </svg>
        </button>
<a href="<?= $ruta_base ?>docentes/inicio_docente.php">
    <img src="<?= $ruta_base ?>img/img_03.1.png" alt="Logo Universidad" class="logo-navbar">
</a>
        <div class="brand-title">
            <h1>Portal Académico</h1>
            <p>Panel Docente</p>
        </div>
    </div>
    <div class="nav-right">
        <a href="../logout.php" class="btn-salir">Cerrar Sesión</a>
        <button id="themeToggle" class="theme-btn" aria-label="Alternar modo oscuro">🌙</button>
    </div>
</header>

<nav class="side-menu" id="sideMenu">
    <div class="menu-content">
        <a href="<?= $ruta_base ?>docentes/inicio_docente.php">Inicio</a>
        <a href="<?= $ruta_base ?>docentes/formulario_docentes.php">Notas</a>
        <a href="<?= $ruta_base ?>docentes/formulario_asistencia.php">Asistencia</a>
        <a href="<?= $ruta_base ?>docentes/horarios.php">Horarios</a>
        <a href="<?= $ruta_base ?>anuncios.php">Anuncios</a>
        <a href="<?= $ruta_base ?>tickets.php">Soporte</a>
        <a href="<?= $ruta_base ?>docentes/acciones_tareas.php">Tareas</a>
        <a href="<?= $ruta_base ?>docentes/perfil.php">Mi Perfil</a>
    </div>
</nav>
</body>
</html>

