<?php
// Encendemos luces de depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../conexion.php'; // Ajusta la ruta a tu archivo de conexión

// 1. SEGURIDAD: Solo el Administrador puede dar de baja usuarios
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado.");
}

$mensaje = null;

// 2. PROCESAR EL CAMBIO DE ESTADO (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cambiar_estado'])) {
    $id_usuario = $_POST['id_usuario'];
    $nuevo_estado = $_POST['nuevo_estado']; // Recibe 0 o 1

    try {
        $sql_update = "UPDATE usuarios SET estado = ? WHERE id_usuario = ?";
        $stmt = $conexion->prepare($sql_update);
        $stmt->execute([$nuevo_estado, $id_usuario]);
        
        $msg_texto = ($nuevo_estado == 1) ? "activado" : "desactivado";
        $mensaje = "<div class='alerta-exito'>Usuario $msg_texto correctamente.</div>";
    } catch(PDOException $e) {
        $mensaje = "<div class='alerta-error'>Error al actualizar: " . $e->getMessage() . "</div>";
    }
}

// 3. OBTENER LISTA DE USUARIOS (Con su rol y nombre)
$usuarios = [];
try {
    $sql_busqueda = "SELECT u.id_usuario, u.nombre, u.correo, u.estado, r.nombre_rol 
                     FROM usuarios u
                     INNER JOIN usuario_roles ur ON u.id_usuario = ur.id_usuario
                     INNER JOIN roles r ON ur.id_rol = r.id_rol
                     WHERE r.nombre_rol != 'Administrador'
                     ORDER BY u.nombre ASC";
    $usuarios = $conexion->query($sql_busqueda)->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $mensaje = "Error al cargar la lista: " . $e->getMessage();
}
?>
