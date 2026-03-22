<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'conexion.php';

$id_docente = $_SESSION['id_docente'] ?? null; 
$cursos = [];
$alumnos = [];
$curso_seleccionado = null;


if ($id_docente) {
    try {
        $sql_cursos = "SELECT cursos.id_curso, cursos.nombre_curso 
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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['curso_seleccionado'])) {
    
    $curso_seleccionado = $_POST['curso_seleccionado'];
    
    try {

        $sql = "SELECT e.id_estudiante, e.nombres, e.apellidos, 
                       c.nota as u1, c.nota2 as u2, c.nota3 as u3, c.nota_final as nota_final
                FROM estudiantes e 
                LEFT JOIN calificaciones c ON e.id_estudiante = c.id_estudiante AND c.id_curso = :id_curso";
                        
        $stmt_alumnos = $conexion->prepare($sql);
        $stmt_alumnos->execute(['id_curso' => $curso_seleccionado]);
        $alumnos = $stmt_alumnos->fetchAll(PDO::FETCH_ASSOC);
                        
    } catch (PDOException $e) {
        echo "Error al cargar alumnos: " . $e->getMessage();
    }
}
?>