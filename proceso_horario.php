<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'conexion.php';

$id_docente = $_SESSION['id_docente'] ?? null;
$horarios = [];

$sql = "SELECT 
            c.nombre_curso,
            h.dia_semana,
            h.hora_inicio,
            h.hora_fin
        FROM asignaciones_docentes s
        INNER JOIN cursos c ON s.id_curso = c.id_curso
        INNER JOIN horarios h ON s.id_asignacion = h.id_asignacion
        WHERE s.id_docente = :id_docente
        ORDER BY 
            FIELD(h.dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'), 
            h.hora_inicio";


                $stmt_horarios = $conexion->prepare($sql);  
                $stmt_horarios->execute([':id_docente' => $id_docente]); // Nota la flecha => en lugar de la coma
                $horarios = $stmt_horarios->fetchAll(PDO::FETCH_ASSOC);
   

?>
                