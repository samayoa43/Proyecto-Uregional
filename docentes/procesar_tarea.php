<?php
require_once 'validar_sesion_docentes.php';
require_once '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_curso = $_POST['id_curso'];
    $id_docente = $_SESSION['id_docente'];
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $fecha_limite = $_POST['fecha_limite'];

    try {
        $sql = "INSERT INTO tareas (id_curso, id_docente, titulo, descripcion, fecha_limite) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$id_curso, $id_docente, $titulo, $descripcion, $fecha_limite]);

        header("Location: crear_tareas.php?exito=tarea_creada");
        exit();
    } catch(PDOException $e) {
        error_log("Error al crear tarea: " . $e->getMessage());
        die("Error interno al guardar la tarea.");
    }
} else {
    header("Location: crear_tareas.php");
    exit();
}
?>