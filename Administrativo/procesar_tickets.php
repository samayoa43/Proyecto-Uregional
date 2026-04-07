<?php
// Encendemos depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../conexion.php'; // Asegúrate de que apunte a tu conexión

// 1. SEGURIDAD: Solo Administradores
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("<h2 style='color:red; text-align:center;'>Acceso Denegado. Solo personal de TI / Administración.</h2>");
}

$mensaje_exito = null;
$mensaje_error = null;

// 2. PROCESAR ACTUALIZACIÓN DE ESTADO
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
    $nuevo_estado = $_POST['nuevo_estado'];

    try {
        $sql_update = "UPDATE tickets_soporte SET estado = ? WHERE id_ticket = ?";
        $stmt = $conexion->prepare($sql_update);
        $stmt->execute([$nuevo_estado, $id_ticket]);
        
        $mensaje_exito = "¡El ticket #$id_ticket ha sido actualizado a '$nuevo_estado' exitosamente!";
    } catch(PDOException $e) {
        $mensaje_error = "Error al actualizar el ticket: " . $e->getMessage();
    }
}

// 3. OBTENER LISTADO DE TICKETS (Con Inteligencia de Ordenamiento)
$lista_tickets = [];
try {
    // Unimos la tabla de tickets con usuarios y roles para saber exactamente quién pide ayuda
    $sql_tickets = "SELECT t.*, u.nombre AS nombre_solicitante, u.correo, r.nombre_rol 
                    FROM tickets_soporte t
                    INNER JOIN usuarios u ON t.id_usuario = u.id_usuario
                    INNER JOIN usuario_roles ur ON u.id_usuario = ur.id_usuario
                    INNER JOIN roles r ON ur.id_rol = r.id_rol
                    ORDER BY 
                        FIELD(t.estado, 'Abierto', 'En Proceso', 'Resuelto'), 
                        FIELD(t.prioridad, 'Urgente', 'Alta', 'Media', 'Baja'),
                        t.fecha_creacion DESC";
                        
    $stmt_tickets = $conexion->query($sql_tickets);
    $lista_tickets = $stmt_tickets->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $mensaje_error = "Error al cargar la bandeja de entrada: " . $e->getMessage();
}
?>
