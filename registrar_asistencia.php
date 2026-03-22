<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'conexion.php';
require 'curso_docentes.php'; 

$sql = "SELECT id_estudiante, nombres, apellidos, id_carrera FROM estudiantes WHERE id_carrera IN (SELECT id_carrera FROM cursos WHERE id_curso = :id_curso)";

try {
    $consulta = $conexion->prepare($sql);
    $consulta->execute();
    $estudiantes = $consulta->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error al intentar obtener estudiantes: " . $e->getMessage();
    $estudiantes = [];
}
?>
