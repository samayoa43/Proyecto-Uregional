<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'conexion.php';

$id_docente = $_SESSION['id_docente'] ?? null; 
$cursos = [];
$alumnos = [];
$asignacion_seleccionada = null;

if ($id_docente) {
    try {
        $sql_cursos = "SELECT asignaciones_docentes.id_asignacion, cursos.nombre_curso 
                       FROM cursos
                       INNER JOIN asignaciones_docentes ON cursos.id_curso = asignaciones_docentes.id_curso 
                       WHERE asignaciones_docentes.id_docente = :id_docente";  
        
        $stmt_cursos = $conexion->prepare($sql_cursos);  
        $stmt_cursos->execute(['id_docente' => $id_docente]);
        $cursos = $stmt_cursos->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al cargar cursos: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asignacion_seleccionada'])) {

    $asignacion_seleccionada = $_POST['asignacion_seleccionada'];
    
    try {
        $sql_alumnos = "SELECT e.id_estudiante, e.nombres, e.apellidos 
                        FROM estudiantes e
                        INNER JOIN asignaciones a ON e.id_estudiante = a.id_estudiante
                        WHERE a.id_asignacion = :id_asignacion
                        ORDER BY e.apellidos ASC"; 
                        
        $stmt_alumnos = $conexion->prepare($sql_alumnos);
        $stmt_alumnos->execute(['id_asignacion' => $asignacion_seleccionada]);
        $alumnos = $stmt_alumnos->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        echo "Error al cargar alumnos: " . $e->getMessage();
    }
}
?>