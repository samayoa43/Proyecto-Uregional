<?php
session_start();
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    $sql = "SELECT id_docente, correo, contraseña 
            FROM docentes 
            WHERE correo = ? AND contraseña = ?";

    try {
        $consulta = $conexion->prepare($sql);
        $consulta->execute([$correo, $contraseña]);
        $docente = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($docente) {
            $_SESSION['id_docente'] = $docente['id_docente'];
            $_SESSION['logged_in'] = true;

            header("Location: index.php");
            exit();
        } else {
            echo "<h3>Credenciales incorrectas. Por favor, inténtalo de nuevo.</h3>";
            echo "<a href='login.php'>Volver al login</a>";
        }

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

} else {
    echo "Acceso no autorizado.";
}
?>