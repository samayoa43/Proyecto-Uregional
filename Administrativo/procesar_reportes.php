<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../conexion.php'; 

$tipo_reporte = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$resultados = [];
$titulo_reporte = "";

$meses_permitidos = ['Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre'];

$lista_cursos = [];
try {
    $stmt_cursos = $conexion->query("SELECT id_curso, nombre_curso FROM cursos ORDER BY nombre_curso ASC");
    $lista_cursos = $stmt_cursos->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error al cargar cursos: " . $e->getMessage();
}

try {
    if ($tipo_reporte === 'morosos' && isset($_GET['mes'])) {
        $mes = $_GET['mes'];
        $titulo_reporte = "Listado de Alumnos con Saldo Pendiente - Mes: " . strtoupper($mes);

        $sql_morosos = "SELECT id_estudiante, nombres, apellidos, correo 
                        FROM estudiantes 
                        WHERE id_estudiante NOT IN (
                            SELECT id_estudiante FROM pagos WHERE mes_pagado = ?
                        ) ORDER BY apellidos ASC";
        $stmt = $conexion->prepare($sql_morosos);
        $stmt->execute([$mes]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } elseif ($tipo_reporte === 'curso' && isset($_GET['id_curso'])) {
        $id_curso = $_GET['id_curso'];

        $stmt_nom = $conexion->prepare("SELECT nombre_curso FROM cursos WHERE id_curso = ?");
        $stmt_nom->execute([$id_curso]);
        $nombre_curso = $stmt_nom->fetchColumn();
        
        $titulo_reporte = "Listado Oficial de Inscritos - Curso: " . strtoupper($nombre_curso);

        $sql_inscritos = "SELECT e.id_estudiante, e.nombres, e.apellidos, e.correo 
                          FROM asignaciones a
                          INNER JOIN estudiantes e ON a.id_estudiante = e.id_estudiante
                          INNER JOIN asignaciones_docentes ad ON a.id_asignacion = ad.id_asignacion
                          WHERE ad.id_curso = ?
                          ORDER BY e.apellidos ASC";
        $stmt = $conexion->prepare($sql_inscritos);
        $stmt->execute([$id_curso]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch(PDOException $e) {
    echo "<div style='color:red;'>Error en la base de datos: " . $e->getMessage() . "</div>";
}
?>