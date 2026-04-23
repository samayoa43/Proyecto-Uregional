<?php

require 'conexion.php';
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

// 4. EXTRAER LOS ANUNCIOS PARA EL ESTUDIANTE
$lista_anuncios = [];
try {
    // Buscamos anuncios dirigidos a 'Todos' o a 'Estudiantes', ordenados por los más recientes
    $sql_anuncios = "SELECT a.titulo, a.mensaje, a.fecha_publicacion, u.nombre AS autor 
                     FROM anuncios a
                     INNER JOIN usuarios u ON a.id_autor = u.id_usuario
                     WHERE a.audiencia IN ('Todos', 'Estudiantes') 
                     ORDER BY a.fecha_publicacion DESC 
                     LIMIT 5"; // Mostramos solo los últimos 5 avisos
    
    $stmt_anuncios = $conexion->prepare($sql_anuncios);
    $stmt_anuncios->execute();
    $lista_anuncios = $stmt_anuncios->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error al cargar los anuncios: " . $e->getMessage();
}
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
    <div class="tarjeta" style="border-top: 4px solid #ff9800;">
    <h3 style="color: #ff9800; margin-top: 0;">Tablón de Anuncios</h3>
    
    <?php if (count($lista_anuncios) > 0): ?>
        <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 15px;">
            <?php foreach ($lista_anuncios as $anuncio): ?>
                <div style="background-color: #fff9f0; padding: 15px; border-left: 4px solid #ffb74d; border-radius: 4px;">
                    <h4 style="margin: 0 0 5px 0; color: #333;"><?= htmlspecialchars($anuncio['titulo']) ?></h4>
                    <p style="margin: 0 0 10px 0; font-size: 14px; color: #555;">
                        <?= nl2br(htmlspecialchars($anuncio['mensaje'])) ?>
                    </p>
                    <small style="color: #888;">
                        👤 Publicado por: <strong><?= htmlspecialchars($anuncio['autor']) ?></strong> el <?= date('d/m/Y g:i A', strtotime($anuncio['fecha_publicacion'])) ?>
                    </small>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="color: #666; font-style: italic;">No hay anuncios nuevos por el momento.</p>
    <?php endif; ?>
</div>

</body>
</html>