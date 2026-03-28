<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
</head>
<body>
    <h1>Portal Académico</h1>
    <h2>Bienvenido: <?php echo htmlspecialchars($nombre); ?>!</strong></h2>

        <nav>
        <?php
        require 'encabezado.php';
        ?>
        </nav>
    
</body>
</html>
