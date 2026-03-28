<?php
session_start();
require 'C:\laragon\www\proyecto\conexion.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];


    $sql = "INSERT INTO docentes (nombres, apellidos, correo, contraseña) VALUES (?, ?, ?, ?)";

    try {
        $consulta = $conexion->prepare($sql);
        $consulta->execute([$nombres, $apellidos, $correo, $contraseña]);

        echo "¡El docente ha sido registrado exitosamente";
        echo "<a href='formulario_registro_d.php'>Volver al panel</a>";
        
    } catch(PDOException $e) {
        echo "Error al intentar guardar: " . $e->getMessage();
    }
    
} else {
    
    echo "Acceso no autorizado. Por favor usa el formulario.";
}
?>