<?php
require 'C:\laragon\www\proyecto\conexion.php';

try {

    $sql_carreras = "SELECT id_carrera, nombre_carrera FROM carreras ORDER BY nombre_carrera ASC";
    $stmt_carreras = $conexion->prepare($sql_carreras);
    $stmt_carreras->execute();

    $lista_carreras = $stmt_carreras->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    echo "Error al cargar las carreras: " . $e->getMessage();
}
?>
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
    <h2>Registrar Nuevo estudiante</h2>
    
    <form action="registrar_estudiantes.php" method="POST">

        <label>Nombres:</label><br>
        <input type="text" name="nombres" required><br><br>

        <label>Apellidos:</label><br>
        <input type="text" name="apellidos" required><br><br>

        <label>Correo:</label><br>
        <input type = "email" name ="correo" placeholder="Ej. estudiante@universidad.edu" required><br><br>
        
        <label>Contraseña:</label><br>
        <input type="password" name="contraseña" required><br><br>

<label class="etiqueta" for="id_carrera">Carrera a la que aplica:</label><br>

<select name="id_carrera" id="id_carrera" required style="width: 100%; padding: 10px; margin-top: 5px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
    <option value="" disabled selected>-- Seleccione una carrera --</option>
    <?php
    if (!empty($lista_carreras)) {
        foreach ($lista_carreras as $carrera) {
            echo "<option value='" . htmlspecialchars($carrera['id_carrera']) . "'>";
            echo htmlspecialchars($carrera['nombre_carrera']);
            echo "</option>";
        }
    } else {
        echo "<option value='' disabled>No hay carreras registradas</option>";
    }
    ?>
</select>
        <button type="submit">Guardar nuevo estudiante</button>
        
    </form>
</body>
</html>