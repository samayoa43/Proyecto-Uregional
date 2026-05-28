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
                data-title="Menú de Navegación" 
                data-intro="Haz clic aquí para expandir o contraer tu menú principal de herramientas docentes.">
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
        <button id="themeToggle" class="theme-btn" aria-label="Alternar modo oscuro"
                data-step="10" 
                data-title="Modo Oscuro" 
                data-intro="Puedes alternar entre el modo claro y oscuro en cualquier momento para proteger tu vista durante la lectura de tareas.">🌙</button>
                
        <a href="../logout.php" class="btn-salir"
           data-step="11" 
           data-title="Cerrar Sesión" 
           data-intro="Por seguridad, recuerda cerrar sesión al terminar tu jornada para proteger los datos de los alumnos.">Cerrar Sesión</a>
    </div>
</header>

<nav class="side-menu" id="sideMenu">
    <div class="menu-content">
        <a href="<?= $ruta_base ?>docentes/inicio_docente.php">Inicio</a>
        
        <a href="<?= $ruta_base ?>docentes/formulario_docentes.php"
           data-step="2" 
           data-title="Ingreso de Notas" 
           data-intro="Aquí podrás ingresar y modificar las calificaciones (Nota 1, 2, 3 y Final) de los estudiantes asignados a tus cursos.">Notas</a>
           
        <a href="<?= $ruta_base ?>docentes/formulario_asistencia.php"
           data-step="3" 
           data-title="Control de Asistencia" 
           data-intro="Lleva el registro diario de asistencia, faltas y permisos de cada sección.">Asistencia</a>
           
        <a href="<?= $ruta_base ?>docentes/horarios.php"
           data-step="4" 
           data-title="Tus Horarios" 
           data-intro="Consulta los días y horas de las clases que tienes asignadas para impartir este semestre.">Horarios</a>
           
        <a href="<?= $ruta_base ?>anuncios.php"
           data-step="5" 
           data-title="Tablón de Anuncios" 
           data-intro="Publica comunicados importantes. Puedes dirigirlos a todos tus alumnos o a un curso en específico.">Anuncios</a>
           
        <a href="<?= $ruta_base ?>tickets.php"
           data-step="6" 
           data-title="Soporte Técnico" 
           data-intro="¿Problemas con la plataforma? Abre un ticket aquí para que la administración te ayude.">Soporte</a>
           
        <a href="<?= $ruta_base ?>docentes/acciones_tareas.php"
           data-step="7" 
           data-title="Gestión de Tareas" 
           data-intro="Crea nuevas asignaciones, establece fechas límite y califica los archivos que envíen tus alumnos.">Tareas</a>
           
        <a href="<?= $ruta_base ?>docentes/perfil.php"
           data-step="8" 
           data-title="Mi Perfil" 
           data-intro="Actualiza tus datos de contacto y contraseña institucional.">Mi Perfil</a>
           
        <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0;">
        <a href="#" onclick="iniciarTour(); return false;" 
           data-step="9" 
           data-title="¿Necesitas ayuda?" 
           data-intro="Si olvidas cómo funciona alguna sección de tu panel, presiona este botón para repetir este recorrido."
           style="color: #ffca28; font-weight: bold;">
           ❓ Repetir Tutorial
        </a>
    </div>
</nav>
</body>
</html>

