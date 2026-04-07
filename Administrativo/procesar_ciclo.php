<?php
// Encendemos luces de depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../conexion.php'; // Ajusta la ruta a tu conexión

$mensaje_exito = null;
$mensaje_error = null;

// 1. CREAR UN NUEVO CICLO (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crear_ciclo'])) {
    $nombre_ciclo = trim($_POST['nombre_ciclo']);
    
    try {
        $sql_insert = "INSERT INTO ciclos_academicos (nombre_ciclo, estado, asignaciones_abiertas) VALUES (?, 'Activo', 0)";
        $stmt = $conexion->prepare($sql_insert);
        $stmt->execute([$nombre_ciclo]);
        $mensaje_exito = "¡El ciclo '$nombre_ciclo' ha sido creado!";
    } catch(PDOException $e) {
        $mensaje_error = "Error al crear el ciclo: " . $e->getMessage();
    }
}

// 2. EL INTERRUPTOR: ABRIR/CERRAR ASIGNACIONES (GET)
if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id_ciclo = $_GET['id'];
    
    try {
        if ($_GET['accion'] == 'toggle_asignacion') {
            // Cambiamos de 0 a 1, o de 1 a 0
            $nuevo_estado = ($_GET['estado_actual'] == 1) ? 0 : 1;
            $sql_toggle = "UPDATE ciclos_academicos SET asignaciones_abiertas = ? WHERE id_ciclo = ?";
            $stmt = $conexion->prepare($sql_toggle);
            $stmt->execute([$nuevo_estado, $id_ciclo]);
            $mensaje_exito = "¡Estado de asignaciones actualizado!";
            
        } elseif ($_GET['accion'] == 'cerrar_ciclo') {
            // Cierra el semestre definitivamente (Congela todo)
            $sql_cerrar = "UPDATE ciclos_academicos SET estado = 'Cerrado', asignaciones_abiertas = 0 WHERE id_ciclo = ?";
            $stmt = $conexion->prepare($sql_cerrar);
            $stmt->execute([$id_ciclo]);
            $mensaje_exito = "¡El ciclo académico ha sido CERRADO definitivamente!";
        }
    } catch(PDOException $e) {
        $mensaje_error = "Error al actualizar: " . $e->getMessage();
    }
}

// 3. OBTENER TODOS LOS CICLOS PARA LA TABLA
$lista_ciclos = [];
try {
    $stmt_ciclos = $conexion->query("SELECT * FROM ciclos_academicos ORDER BY id_ciclo DESC");
    $lista_ciclos = $stmt_ciclos->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $mensaje_error = "Error al cargar ciclos: " . $e->getMessage();
}
?>