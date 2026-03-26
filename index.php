<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php"); 
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
    
</body>
</html>
