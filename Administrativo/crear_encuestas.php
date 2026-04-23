<?php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== "admin") {
    header("Location: login.php?error=acceso_denegado");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nueva Encuesta</title>
    <link rel="stylesheet" href="estilos.css"> 
    <style>
        .form-container { max-width: 500px; margin: 40px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn-crear { background-color: #28a745; color: white; padding: 10px 15px; border: none; cursor: pointer; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Crear Nueva Encuesta</h2>
        <form action="procesar_encuestas.php" method="POST">
            <div class="form-group">
                <label for="titulo">Título de la Encuesta:</label>
                <input type="text" id="titulo" name="titulo" required placeholder="Ej: Evaluación de Instalaciones">
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción / Instrucciones:</label>
                <textarea id="descripcion" name="descripcion" rows="4" required placeholder="Por favor, responde honestamente..."></textarea>
            </div>
            
            <button type="submit" class="btn-crear">Guardar y Continuar</button>
            <a href="inicio_admin.php" style="margin-left: 10px;">Cancelar</a>
        </form>
    </div>
</body>
</html>