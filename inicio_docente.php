<?php
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
    
</body>
</html>
