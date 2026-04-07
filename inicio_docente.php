<?php
require 'C:\laragon\www\proyecto\conexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php"); 
    exit();
}

if ($_SESSION['rol'] !== 'docente') {
    echo "<div style='text-align: center; margin-top: 50px; font-family: Arial;'>";
    echo "<h3 style='color: #d9534f;'>Acceso Denegado. Esta área es exclusiva para docentes.</h3>";
    echo "<a href='login.php' style='text-decoration: none; background: #0056b3; color: white; padding: 10px 15px; border-radius: 5px;'>Volver a mi panel</a>";
    echo "</div>";
    exit();

}
$lista_anuncios = [];
try {
   
    $sql_anuncios = "SELECT a.titulo, a.mensaje, a.fecha_publicacion, u.nombre AS autor 
                     FROM anuncios a
                     INNER JOIN usuarios u ON a.id_autor = u.id_usuario
                     WHERE a.audiencia IN ('Todos', 'Docentes') 
                     ORDER BY a.fecha_publicacion DESC 
                     LIMIT 5"; 
    
    $stmt_anuncios = $conexion->prepare($sql_anuncios);
    $stmt_anuncios->execute();
    $lista_anuncios = $stmt_anuncios->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error al cargar los anuncios: " . $e->getMessage();
}

$nombre = $_SESSION['nombre_usuario'] ?? 'Administrador';
?>

<!DOCTYPE html>
<!--
mismas instrucciones 
-->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plataforma Académica</title>
</head>
<body>
    <h1>Portal Académico</h1>
    <h2>Bienvenido Profesor: <? ?> </h2>

        <nav>
        <?php
        require 'encabezado.php';
        ?>
        </nav>
            <div class="navbar">
        <a href="../logout.php" class="btn-salir">Cerrar Sesión</a>
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
