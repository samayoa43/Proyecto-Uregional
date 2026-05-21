<?php

require_once __DIR__ . '/validar_sesion_admin.php'; 


require '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $correo = trim($_POST['correo']);
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $id_carrera = $_POST['id_carrera'];

    $nombre_completo = $nombres . " " . $apellidos;
    
    $id_rol_estudiante = 3;

    try {
        $conexion->beginTransaction();

        $sql_usuarios = "INSERT INTO usuarios (nombre, correo, contraseña, estado) VALUES (?, ?, ?, 1)";
        $stmt_usuarios = $conexion->prepare($sql_usuarios);
        $stmt_usuarios->execute([$nombre_completo, $correo, $contraseña]);

        $id_usuario_nuevo = $conexion->lastInsertId();

        $sql_roles = "INSERT INTO usuario_roles (id_usuario, id_rol) VALUES (?, ?)";
        $stmt_roles = $conexion->prepare($sql_roles);
        $stmt_roles->execute([$id_usuario_nuevo, $id_rol_estudiante]);

        $sql_estudiantes = "INSERT INTO estudiantes (nombres, apellidos, id_carrera, id_usuario) VALUES (?, ?, ?, ?)";
        $stmt_estudiantes = $conexion->prepare($sql_estudiantes);
        $stmt_estudiantes->execute([$nombres, $apellidos, $id_carrera, $id_usuario_nuevo]);

        $conexion->commit();

        header("Location: formulario_registro_e.php?exito=1");
        exit();

    } catch(PDOException $e) {
        $conexion->rollBack();
        header("Location: formulario_registro_e.php?error=1");
        exit();
        echo "<div style='font-family: Arial; margin: 20px;'>";
        echo "<h3 style='color: red;'>Error al intentar guardar. No se registró al estudiante.</h3>";
        echo "<p>Detalle técnico: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<a href='formulario_registro_e.php'>Volver a intentar</a>";
        echo "</div>";
    }
    
} else {
    echo "Acceso no autorizado. Por favor usa el formulario.";
}
?>