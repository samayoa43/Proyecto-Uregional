<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['rol'] !== 'estudiante') {
    echo "<div style='text-align: center; margin-top: 50px; font-family: Arial;'>";
    echo "<h3 style='color: #d9534f;'>Acceso Denegado. Esta área es exclusiva para Estudiantes.</h3>";
    echo "<a href='login.php' style='text-decoration: none; background: #0056b3; color: white; padding: 10px 15px; border-radius: 5px;'>Volver a mi panel</a>";
    echo "</div>";
    exit();
}

$nombre_alumno = $_SESSION['nombre_usuario'];
$id_estudiante = $_SESSION['id_estudiante']; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Portal Estudiantil - Universidad Regional</title>

</head>
<body>

    <div class="navbar">
        <h2>Universidad Regional</h2>
        <a href="logout.php" class="btn-salir">Cerrar Sesión</a>
    </div>

    <div class="contenedor">
        <div class="tarjeta">
            <h3>¡Bienvenido a tu portal, <?= htmlspecialchars($nombre_alumno) ?>!</h3>
            <p>Tu número de carnet/ID interno es: <strong><?= htmlspecialchars($id_estudiante) ?></strong></p>
            <p>Desde este panel podrás gestionar todo tu progreso académico de este semestre.</p>
        </div>

        <div class="tarjeta">
            <h3>Mis Herramientas Académicas</h3>
            <ul class="menu-opciones">
                <li><a href="#">📖 Ver mis Calificaciones</a></li>
                <li><a href="#">📅 Consultar mi Asistencia</a></li>
                <li><a href="#">🎓 Ver mi Pensum de Carrera</a></li>
                <li><a href="#">📝 Inscribirme a nuevos cursos</a></li>
            </ul>
            <p style="color: #777; font-size: 14px; margin-top: 20px;">
                <em>* Los enlaces estarán disponibles conforme vayamos activando los módulos.</em>
            </p>
        </div>
    </div>

</body>
</html>