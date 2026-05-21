<?php

require_once __DIR__ . '/validar_sesion_admin.php'; 

require '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $correo = trim($_POST['correo']);
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);

    $nombre_completo = $nombres . " " . $apellidos;
    
    $id_rol_docente = 2;

    try {
        $conexion->beginTransaction();

        $sql_usuarios = "INSERT INTO usuarios (nombre, correo, contraseña, estado) VALUES (?, ?, ?, 1)";
        $stmt_usuarios = $conexion->prepare($sql_usuarios);
        $stmt_usuarios->execute([$nombre_completo, $correo, $contraseña]);

        $id_usuario_nuevo = $conexion->lastInsertId();

        $sql_roles = "INSERT INTO usuario_roles (id_usuario, id_rol) VALUES (?, ?)";
        $stmt_roles = $conexion->prepare($sql_roles);
        $stmt_roles->execute([$id_usuario_nuevo, $id_rol_docente]);

        $sql_docentes = "INSERT INTO docentes (nombres, apellidos, id_usuario) VALUES (?, ?, ?)";
        $stmt_docentes = $conexion->prepare($sql_docentes);
        $stmt_docentes->execute([$nombres, $apellidos, $id_usuario_nuevo]);

        $conexion->commit();

        header("Location: formulario_registro_d.php?exito=1");
        exit();

    } catch(PDOException $e) {
        $conexion->rollBack();
        
        header("Location: formulario_registro_d.php?error=1");
        exit();
    }
    
} else {
    echo "Acceso no autorizado. Por favor usa el formulario.";
}
?>