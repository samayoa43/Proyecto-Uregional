<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro docentes</title>
</head>
<header>
    <div>
        <h1>plataforma academica</h1>
    </div>
    <nav>
        <?php
        require 'encabezado.php';
        ?>
    </nav>
</header>
<body>
    <h2>Crear Carreras</h2>
    
    <form action="procesar_carrera.php" method="POST">

        <label>Nombre de Carrera:</label><br>
        <input type="text" name="nombre" required><br><br>

        
        <button type="submit">Guardar Nueva carrera</button>
        
    </form>
</body>
</html>

