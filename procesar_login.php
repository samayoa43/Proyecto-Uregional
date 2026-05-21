<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $password_ingresada = $_POST['contraseña'];

    try { // Aquí iniciamos el try que faltaba

        // PASO 1: Buscar al usuario Y SU ROL cruzando las tablas (JOIN)
        $sql = "SELECT u.id_usuario, u.nombre, u.contraseña, u.estado, r.nombre_rol 
                FROM usuarios u
                INNER JOIN usuario_roles ur ON u.id_usuario = ur.id_usuario
                INNER JOIN roles r ON ur.id_rol = r.id_rol
                WHERE u.correo = ?";
                
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // PASO 2: Verificar si el usuario existe y si la contraseña coincide con el hash
        if ($usuario && password_verify($password_ingresada, $usuario['contraseña'])) {
            
            if ($usuario['estado'] == 0) {
                header("Location: login.php?error=cuenta_inactiva");
                exit();
            }
            // Lógica normal de inicio de sesión (¡Aquí faltaba tu pase VIP!)
            $_SESSION['logged_in'] = true;
            $_SESSION['rol'] = $usuario['nombre_rol'];
            
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            
            // 3. EL PUENTE: Buscamos en tus tablas físicas usando el id_usuario y el rol
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
                header("Location: estudiantes/inicio_estudiantes.php");
                
            } elseif ($usuario['nombre_rol'] === 'admin') {
                
                $sql_admin = "SELECT id_personal FROM administrativo WHERE id_usuario = ?";
                $stmt = $conexion->prepare($sql_admin);
                $stmt->execute([$usuario['id_usuario']]);
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if($admin) {
                    $_SESSION['id_admin'] = $admin['id_personal'];
                }
                header("Location: Administrativo/inicio_admin.php"); // Asegúrate que la A mayúscula coincida con tu carpeta
                
            } else {
                header("Location: login.php?error=rol_invalido");
            }
            
            exit(); 
            
        } else {
            // MODO DIAGNÓSTICO: Borrar esto después de arreglarlo
            die("Correo encontrado: " . ($usuario ? 'SÍ' : 'NO') . " | Contraseña en BD: " . ($usuario['contraseña'] ?? 'Nada'));
            // Si el correo no existe o la contraseña no hace match con el hash
        //header("Location: login.php?error=credenciales");
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