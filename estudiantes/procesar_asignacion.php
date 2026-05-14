<?php
require 'validar_sesion_estudiantes.php';
require '../conexion.php';

// Recibimos el ID de la asignación_docente que el alumno eligió
$id_asignacion = $_POST['id_asignacion'];

// Obtenemos el ID del estudiante
$stmt_id = $conexion->prepare("SELECT id_estudiante FROM estudiantes WHERE id_usuario = ?");
$stmt_id->execute([$_SESSION['id_usuario']]);
$perfil = $stmt_id->fetch(PDO::FETCH_ASSOC);
$id_estudiante = $perfil['id_estudiante'];

try {
    // 1. Verificación Defensiva: Confirmar que el ciclo siga abierto
    $stmt_ciclo = $conexion->query("SELECT asignaciones_abiertas FROM ciclos_academicos WHERE estado = 'Activo' LIMIT 1");
    $ciclo = $stmt_ciclo->fetch(PDO::FETCH_ASSOC);
    
    if (!$ciclo || $ciclo['asignaciones_abiertas'] == 0) {
        die("Error: El proceso de asignaciones ha sido cerrado por administración.");
    }

    // 2. Verificación Defensiva: Evitar duplicados (Doble clic)
    $stmt_check = $conexion->prepare("SELECT id_asignacion_a FROM asignaciones WHERE id_estudiante = ? AND id_asignacion = ?");
    $stmt_check->execute([$id_estudiante, $id_asignacion]);
    
    if ($stmt_check->rowCount() > 0) {
        // Ya está asignado
        header("Location: asignacion_cursos.php?error=ya_asignado");
        exit();
    }

    // 3. Inserción oficial en la base de datos
    $sql_insert = "INSERT INTO asignaciones (id_estudiante, id_asignacion) VALUES (?, ?)";
    $stmt_insert = $conexion->prepare($sql_insert);
    $stmt_insert->execute([$id_estudiante, $id_asignacion]);

    // Opcional: Podrías insertar de una vez una fila en blanco en la tabla `calificaciones` 
    // para este alumno y este curso, preparando el terreno para el catedrático.
    
    // Regresamos al alumno a la vista de asignación con un mensaje de éxito
    header("Location: asignacion_cursos.php?exito=1");
    exit();

} catch (PDOException $e) {
    die("Error al procesar la asignación: " . $e->getMessage());
}
?>