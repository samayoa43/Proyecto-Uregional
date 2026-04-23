<?php

require 'C:\laragon\www\proyecto\conexion.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = $_POST['nombre'];
    
    $sql = "INSERT INTO carreras(nombre_carrera) VALUES (?)";

    try {
        $consulta = $conexion->prepare($sql);
        $consulta->execute([$nombre]);

        echo "La nueva Carrera ha sido creada";
        echo "<a href='carreras.php'>Volver al panel</a>";
        
    } catch(PDOException $e) {
        echo "Error al intentar guardar: " . $e->getMessage();
    }
    
} else {
    
    echo "Acceso no autorizado. Por favor usa el formulario.";
}
?>