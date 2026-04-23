<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'conexion.php'; 

if (!isset($_SESSION['id_usuario'])) {
    die("Acceso denegado. Debes iniciar sesión.");
}

$id_usuario = $_SESSION['id_usuario'];
$mensaje_exito = null;
$mensaje_error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enviar_ticket'])) {
    $asunto = trim($_POST['asunto']);
    $prioridad = $_POST['prioridad'];
    $descripcion = trim($_POST['descripcion']);

    try {
        $sql_insert = "INSERT INTO tickets_soporte (id_usuario, asunto, descripcion, prioridad, estado) 
                       VALUES (?, ?, ?, ?, 'Abierto')";
        $stmt = $conexion->prepare($sql_insert);
        $stmt->execute([$id_usuario, $asunto, $descripcion, $prioridad]);
        
        $mensaje_exito = "¡Ticket enviado correctamente! El equipo de soporte lo revisará pronto. (Ticket #" . $conexion->lastInsertId() . ")";
    } catch(PDOException $e) {
        $mensaje_error = "Error al crear el ticket: " . $e->getMessage();
    }
}

$mis_tickets = [];
try {
    $sql_historial = "SELECT id_ticket, asunto, prioridad, estado, fecha_creacion 
                      FROM tickets_soporte 
                      WHERE id_usuario = ? 
                      ORDER BY fecha_creacion DESC";
    $stmt_hist = $conexion->prepare($sql_historial);
    $stmt_hist->execute([$id_usuario]);
    $mis_tickets = $stmt_hist->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $mensaje_error = "Error al cargar el historial: " . $e->getMessage();
}
?>
