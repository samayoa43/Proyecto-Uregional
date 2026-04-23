<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro docentes</title>
</head>
<header>
    <div>
        <h1>Portal Académico</h1>
    </div>
    <nav>
        <?php
        require 'encabezado.php';
        ?>
    </nav>
</header>
<body>
    <h2>Registrar Nuevo docente</h2>
    
    <form action="registrar_docentes.php" method="POST">

        <label>Nombres:</label><br>
        <input type="text" name="nombres" required><br><br>

        <label>Apellidos:</label><br>
        <input type="text" name="apellidos" required><br><br>

        <label>Correo:</label><br>
        <input type = "email" name ="correo" required><br><br>
        
        <label>Contraseña:</label><br>
        <input type="password" name="contraseña" required><br><br>



        <button type="submit">Guardar nuevo docente</button>
        
    </form>
</body>
</html>