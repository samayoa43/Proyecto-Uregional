<?php
session_start();
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_SESSION['id_usuario'];
    $rol = $_SESSION['rol']; 

    $nuevo_correo = trim($_POST['correo']);
    $nueva_password = $_POST['nueva_password'];
    $confirmar_password = $_POST['confirmar_password'];

    // Definir la ruta de retorno según el rol
    if ($rol === 'estudiante') {
        $ruta_retorno = 'estudiantes/perfil.php';
    } elseif ($rol === 'docente') {
        $ruta_retorno = 'docentes/perfil.php';
    } elseif ($rol === 'admin') {
        $ruta_retorno = 'Administrativo/perfil.php';
    } else {
        die("Rol inválido en el sistema.");
    }

    try {
        // Validar que el nuevo correo no esté siendo usado por OTRA persona en la tabla usuarios
        $stmt_check = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE correo = ? AND id_usuario != ?");
        $stmt_check->execute([$nuevo_correo, $id_usuario]);
        if ($stmt_check->rowCount() > 0) {
            header("Location: $ruta_retorno?error=correo_duplicado");
            exit();
        }

        $conexion->beginTransaction();

        // 1. Actualizar el correo ÚNICAMENTE en la tabla usuarios
        $stmt_u = $conexion->prepare("UPDATE usuarios SET correo = ? WHERE id_usuario = ?");
        $stmt_u->execute([$nuevo_correo, $id_usuario]);

        // 2. Actualizar contraseña ÚNICAMENTE en la tabla usuarios (si se rellenaron los campos)
        if (!empty($nueva_password)) {
            if ($nueva_password !== $confirmar_password) {
                header("Location: $ruta_retorno?error=password_mismatch");
                exit();
            }
            $hash = password_hash($nueva_password, PASSWORD_DEFAULT);
            
            $stmt_pass_u = $conexion->prepare("UPDATE usuarios SET contraseña = ? WHERE id_usuario = ?");
            $stmt_pass_u->execute([$hash, $id_usuario]);
        }

        $conexion->commit();
        header("Location: $ruta_retorno?exito=1");
        exit();

    } catch (Exception $e) {
        $conexion->rollBack();
        error_log("Error al actualizar perfil: " . $e->getMessage());
        header("Location: $ruta_retorno?error=db_fail");
        exit();
    }
}
?>