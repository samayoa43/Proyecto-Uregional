<?php
require_once 'validar_sesion_docentes.php';
require_once '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_entrega = $_POST['id_entrega'];
    $id_tarea = $_POST['id_tarea']; // Para redireccionar de vuelta
    $calificacion = $_POST['calificacion'];
    $retroalimentacion = trim($_POST['retroalimentacion']);

    try {
        $sql = "UPDATE entregas_tareas 
                SET calificacion = ?, retroalimentacion_docente = ? 
                WHERE id_entrega = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$calificacion, $retroalimentacion, $id_entrega]);

        // Regresar a la pantalla de calificación con un ancla o parámetro de éxito
        header("Location: calificar_tarea.php?id_tarea=" . $id_tarea . "&exito=calificado");
        exit();
        
    } catch(PDOException $e) {
        error_log("Error al calificar tarea: " . $e->getMessage());
        die("Error interno al guardar la calificación.");
    }
} else {
    header("Location: inicio_docente.php");
    exit();
}
?>