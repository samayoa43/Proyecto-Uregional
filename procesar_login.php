<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'conexion.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $correo = trim($_POST['correo']);
    $password_ingresado = $_POST['contraseña']; 

    try {
        // 1. LA SUPER CONSULTA
        // Extraemos 'u.contraseña' de tu tabla usuarios
        $sql = "SELECT u.id_usuario, u.nombre, u.contraseña, u.estado, r.nombre_rol 
                FROM usuarios u
                INNER JOIN usuario_roles ur ON u.id_usuario = ur.id_usuario
                INNER JOIN roles r ON ur.id_rol = r.id_rol
                WHERE u.correo = ? AND u.estado = 1 
                LIMIT 1";
                
        $consulta = $conexion->prepare($sql);
        $consulta->execute([$correo]);
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        // 2. Verificamos credenciales comparando con la columna 'contraseña'
        if ($usuario && $usuario['contraseña'] === $password_ingresado) {
            
            // Llenamos la sesión general
            $_SESSION['logged_in'] = true;
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre_usuario'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['nombre_rol'];
            
            // 3. EL PUENTE: Buscamos en tus tablas físicas usando el id_usuario
            if ($usuario['nombre_rol'] === 'docente') {
                
                $sql_doc = "SELECT id_docente FROM docentes WHERE id_usuario = ?";
                $stmt = $conexion->prepare($sql_doc);
                $stmt->execute([$usuario['id_usuario']]);
                $docente = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if($docente) {
                    $_SESSION['id_docente'] = $docente['id_docente'];
                }
                header("Location: docentes/inicio_docente.php");
                
            } elseif ($usuario['nombre_rol'] === 'estudiante') {
                
                $sql_est = "SELECT id_estudiante FROM estudiantes WHERE id_usuario = ?";
                $stmt = $conexion->prepare($sql_est);
                $stmt->execute([$usuario['id_usuario']]);
                $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if($estudiante) {
                    $_SESSION['id_estudiante'] = $estudiante['id_estudiante'];
                }
                header("Location: index.php");
                
            } elseif ($usuario['nombre_rol'] === 'admin') {
                
                $sql_admin = "SELECT id_personal FROM administrativo WHERE id_usuario = ?";
                $stmt = $conexion->prepare($sql_admin);
                $stmt->execute([$usuario['id_usuario']]);
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if($admin) {
                    $_SESSION['id_admin'] = $admin['id_personal'];
                }
                header("Location: administrativo/inicio_admin.php");
                
            } else {
                header("Location: login.php?error=rol_invalido");
            }
            
            exit(); 
            
        } else {
            header("Location: login.php?error=credenciales");
            exit();
        }
        
    } catch(PDOException $e) {
        echo "Error en el sistema: " . $e->getMessage();
    }
    
} else {
    header("Location: login.php");
    exit();
}
?>