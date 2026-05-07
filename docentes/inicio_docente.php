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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio Docente - Plataforma Académica</title>
    <link rel="stylesheet" href="estilos_docente.css?v=<?php echo time(); ?>">
</head>
<body>
    
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>

    <?php 
        $ruta_base = "../"; 
    require 'encabezado.php'; ?>

    <main class="main-container">
        
        <div class="section-header">
            <h2>Bienvenido(a), Profesor(a) <?= htmlspecialchars($nombre) ?></h2>
            <p>Panel principal de gestión académica y avisos oficiales.</p>
        </div>

        <div class="kpi-card" style="border-top: 4px solid #f59e0b;">
            <h3 style="margin-top: 0; color: #f59e0b; margin-bottom: 20px;">Tablón de Anuncios</h3>
            
            <?php if (count($lista_anuncios) > 0): ?>
                <div class="lista-anuncios">
                    <?php foreach ($lista_anuncios as $anuncio): ?>
                        <div class="anuncio-item">
                            <h4><?= htmlspecialchars($anuncio['titulo']) ?></h4>
                            <p><?= nl2br(htmlspecialchars($anuncio['mensaje'])) ?></p>
                            <small>
                                👤 Publicado por: <strong><?= htmlspecialchars($anuncio['autor']) ?></strong> 
                                el <?= date('d/m/Y g:i A', strtotime($anuncio['fecha_publicacion'])) ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="reporte-vacio">
                    <h3 style="color: #64748b;">Sin novedades</h3>
                    <p>No hay avisos nuevos por el momento.</p>
                </div>
            <?php endif; ?>
        </div>

    </main>

    <script src="<?= $ruta_base ?>docentes/script_docentes.js?v=<?php echo time(); ?>"></script>

</body>
</html>