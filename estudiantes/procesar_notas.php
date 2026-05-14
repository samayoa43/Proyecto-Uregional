<?php
require 'validar_sesion_estudiantes.php';
require '../conexion.php';

$id_usuario = $_SESSION['id_usuario'];

// --- BLOQUE PARA OBTENER EL ID_ESTUDIANTE ---
$stmt_id = $conexion->prepare("SELECT id_estudiante FROM estudiantes WHERE id_usuario = ?");
$stmt_id->execute([$id_usuario]);
$estudiante_data = $stmt_id->fetch(PDO::FETCH_ASSOC);

if (!$estudiante_data) {
    // Si por alguna razón el usuario no tiene un registro en la tabla estudiantes
    die("Error: No se encontró perfil de estudiante para este usuario.");
}

$id_estudiante = $estudiante_data['id_estudiante'];
// --------------------------------------------

// Ahora ya puedes usar $id_estudiante en tu consulta de notas
$stmt_notas = $conexion->prepare("
    SELECT c.nombre_curso, cal.nota, cal.nota2, cal.nota3, cal.nota_final 
    FROM calificaciones cal
    INNER JOIN cursos c ON cal.id_curso = c.id_curso
    WHERE cal.id_estudiante = ?
");
$stmt_notas->execute([$id_estudiante]);
$notas = $stmt_notas->fetchAll(PDO::FETCH_ASSOC);
?>
