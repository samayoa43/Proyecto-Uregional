<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Estudiantil</title>
</head>
<body>
<aside class="sidebar glass" 
       data-step="1" 
       data-title="Bienvenido a tu Portal" 
       data-intro="Este es tu menú principal. Desde aquí podrás gestionar toda tu información académica y financiera.">
    
    <div class="logo-wrap">
        <a href="inicio_estudiantes.php">
            <img src="../img/img_03.1.png" alt="Logo Universidad" class="logo-navbar">
        </a>
        <div>
            <h2>Portal Académico</h2>
            <p class="muted">Panel estudiantil</p>
        </div>
    </div>
    
    <nav class="menu">
        <a href="inicio_estudiantes.php">Inicio</a>
        
        <a href="cursos.php" 
           data-step="2" 
           data-title="Tus Cursos" 
           data-intro="Aquí encontrarás el material de estudio y el listado de los cursos a los que estás asignado actualmente.">Cursos</a>
           
        <a href="notas.php" 
           data-step="3" 
           data-title="Tus Calificaciones" 
           data-intro="Revisa el progreso de tus notas por cada unidad y tu nota final.">Calificaciones</a>
           
        <a href="tareas.php" 
           data-step="4" 
           data-title="Entrega de Tareas" 
           data-intro="Consulta las tareas pendientes que han dejado tus catedráticos y sube tus archivos desde esta sección.">Tareas</a>
           
        <a href="pagos.php" 
           data-step="5" 
           data-title="Módulo Financiero" 
           data-intro="Mantente al día. Aquí puedes revisar tu estado de cuenta y realizar los pagos de tus colegiaturas.">Pagos y Estado de Cuenta</a>
           
        <a href="asignacion_cursos.php" 
           data-step="6" 
           data-title="Asignación Semestral" 
           data-intro="¡Muy importante! Al inicio de cada semestre, debes ingresar aquí para asignarte a tus nuevos cursos.">Asignación de Cursos</a>
           
        <a href="perfil.php" 
           data-step="7" 
           data-title="Configuración de Perfil" 
           data-intro="Actualiza tu información personal y mantén tus datos al día.">Mi Perfil</a>
    
        <a class="logout-link" href="../logout.php" 
        data-step="8" 
        data-title="Cerrar Sesión" 
        data-intro="Por tu seguridad, recuerda siempre cerrar sesión cuando termines de utilizar la plataforma, especialmente en equipos compartidos.">Cerrar sesión</a>
    </nav>

</aside>
</body>
</html>