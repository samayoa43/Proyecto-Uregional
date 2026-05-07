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
<a href="<?= $ruta_base ?>administrativo/inicio_admin.php">
    <img src="<?= $ruta_base ?>img/img_03.1.png" alt="Logo Universidad" class="logo-navbar">
</a>
        <div class="brand-title">
            <h1>Portal Académico</h1>
            <p>Panel Administrativo</p>
        </div>
    </div>
    <div class="nav-right">
        <span class="user-greeting">Bienvenido, <strong>Admin</strong></span>
        <a href="../logout.php" class="btn-salir">Cerrar Sesión</a>
        <button id="themeToggle" class="theme-btn" aria-label="Alternar modo oscuro">🌙</button>
    </div>
</header>

<nav class="side-menu" id="sideMenu">
    <div class="menu-content">
        <a href="<?= $ruta_base ?>administrativo/inicio_admin.php">Inicio</a>
        <a href="<?= $ruta_base ?>administrativo/acciones_docentes.php">Docentes</a>
        <a href="<?= $ruta_base ?>administrativo/acciones_cursos.php">Carreras</a>
        <a href="<?= $ruta_base ?>administrativo/acciones_alumnos.php">Alumnos</a>
        
        <a href="<?= $ruta_base ?>anuncios.php">Anuncios</a>
        
        <a href="<?= $ruta_base ?>administrativo/gestion_u.php">Gestión de Usuarios</a>
        <a href="<?= $ruta_base ?>administrativo/reportes.php">Reportes</a>
        <a href="<?= $ruta_base ?>administrativo/gestion_tickets.php">Soporte</a>
        <a href="<?= $ruta_base ?>administrativo/crear_encuestas.php">Crear Encuesta</a>
    </div>
</nav>
</body>
</html>

