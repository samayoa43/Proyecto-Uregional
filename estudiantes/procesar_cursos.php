<?php
require 'validar_sesion_estudiantes.php';
require '../conexion.php';



$id_usuario = $_SESSION['id_usuario'];

// 2. Obtener el id_estudiante vinculado al usuario en sesión
$stmt_id = $conexion->prepare("SELECT id_estudiante FROM estudiantes WHERE id_usuario = ?");
$stmt_id->execute([$id_usuario]);
$perfil = $stmt_id->fetch(PDO::FETCH_ASSOC);
$id_estudiante = $perfil['id_estudiante'];

// 3. Consulta para obtener los cursos, docentes y horarios
$stmt_cursos = $conexion->prepare("
    SELECT 
        c.nombre_curso, 
        d.nombres AS docente_nomb, 
        d.apellidos AS docente_ape, 
        h.dia_semana, 
        h.hora_inicio, 
        h.hora_fin
    FROM asignaciones a
    INNER JOIN asignaciones_docentes ad ON a.id_asignacion = ad.id_asignacion
    INNER JOIN cursos c ON ad.id_curso = c.id_curso
    INNER JOIN docentes d ON ad.id_docente = d.id_docente
    LEFT JOIN horarios h ON ad.id_asignacion = h.id_asignacion
    WHERE a.id_estudiante = ?
");
$stmt_cursos->execute([$id_estudiante]);
$cursos = $stmt_cursos->fetchAll(PDO::FETCH_ASSOC);
?>
