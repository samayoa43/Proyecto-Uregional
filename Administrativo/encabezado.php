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
        <button class="hamburger-btn" id="menuToggle" aria-label="Abrir menú"
                data-step="1" 
                data-title="Menú Administrativo" 
                data-intro="Haz clic aquí para expandir o contraer el menú de herramientas de administración.">
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
        <button id="themeToggle" class="theme-btn" aria-label="Alternar modo oscuro"
                data-step="12" 
                data-title="Modo Oscuro" 
                data-intro="Alterna entre el tema claro y oscuro para reducir la fatiga visual durante tu jornada.">🌙</button>

        <a href="../logout.php" class="btn-salir"
           data-step="13" 
           data-title="Cierre Seguro" 
           data-intro="Al ser administrador, tienes acceso a datos sensibles. Recuerda siempre cerrar tu sesión antes de alejarte del equipo.">Cerrar Sesión</a>
    </div>
</header>

<nav class="side-menu" id="sideMenu">
    <div class="menu-content">
        <a href="<?= $ruta_base ?>administrativo/inicio_admin.php">Inicio</a>
        
        <a href="<?= $ruta_base ?>administrativo/acciones_docentes.php"
           data-step="2" 
           data-title="Gestión de Docentes" 
           data-intro="Registra nuevos catedráticos, asígnales cursos y administra sus perfiles institucionales.">Docentes</a>
           
        <a href="<?= $ruta_base ?>administrativo/acciones_cursos.php"
           data-step="3" 
           data-title="Carreras y Cursos" 
           data-intro="Administra los pensums de estudio, prerrequisitos y crea nuevos cursos para los ciclos académicos activos.">Carreras</a>
           
        <a href="<?= $ruta_base ?>administrativo/acciones_alumnos.php"
           data-step="4" 
           data-title="Control de Alumnos" 
           data-intro="Consulta el directorio de estudiantes, verifica sus solvencias de pago y su historial académico.">Alumnos</a>
        
        <a href="<?= $ruta_base ?>anuncios.php"
           data-step="5" 
           data-title="Comunicaciones" 
           data-intro="Publica avisos oficiales. Puedes enviarlos a toda la universidad o filtrarlos por rol.">Anuncios</a>
        
        <a href="<?= $ruta_base ?>administrativo/gestion_u.php"
           data-step="6" 
           data-title="Seguridad y Usuarios" 
           data-intro="Administra las credenciales, roles y el estado (activo/inactivo) de todas las cuentas del sistema.">Gestión de Usuarios</a>
           
        <a href="<?= $ruta_base ?>administrativo/reportes.php"
           data-step="7" 
           data-title="Reportes Estadísticos" 
           data-intro="Genera informes sobre el rendimiento académico, cantidad de inscritos y reportes financieros.">Reportes</a>
           
        <a href="<?= $ruta_base ?>administrativo/gestion_tickets.php"
           data-step="8" 
           data-title="Centro de Soporte" 
           data-intro="Atiende y dale resolución a los tickets de ayuda enviados por estudiantes y docentes.">Soporte</a>
           
        <a href="<?= $ruta_base ?>administrativo/crear_encuestas.php"
           data-step="9" 
           data-title="Encuestas Institucionales" 
           data-intro="Diseña y envía encuestas de evaluación docente o de satisfacción para la comunidad universitaria.">Crear Encuesta</a>
           
        <a href="<?= $ruta_base ?>administrativo/perfil.php"
           data-step="10" 
           data-title="Tu Perfil" 
           data-intro="Modifica tus credenciales de acceso como administrador.">Mi Perfil</a>

        <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0;">
        <a href="#" onclick="iniciarTour(); return false;" 
           data-step="11" 
           data-title="¿Necesitas ayuda?" 
           data-intro="Haz clic aquí si ingresa un nuevo miembro a la administración y necesita conocer este panel."
           style="color: #ffca28; font-weight: bold;">
           Repetir Tutorial
        </a>
    </div>
</nav>
</body>
</html>

