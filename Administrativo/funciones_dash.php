<?php
// Encendemos depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../conexion.php'; 

// Inicializamos variables de KPIs
$kpi_estudiantes_activos = 0;
$kpi_alumnos_al_dia = 0;
$kpi_morosos = 0;
$kpi_carga_docente = 0;
$kpi_saturacion = 0;

// Definimos el mes actual para finanzas (Ej. Abril)
$mes_actual = 'Abril'; 

try {
    // 1. ÁREA: ESTUDIANTES (Crecimiento / Retención)
    // Hacemos JOIN con usuarios para leer el estado = 1
    $sql_estudiantes = "SELECT COUNT(e.id_estudiante) 
                        FROM estudiantes e
                        INNER JOIN usuarios u ON e.id_usuario = u.id_usuario
                        WHERE u.estado = 1";
    $stmt_est = $conexion->query($sql_estudiantes);
    $kpi_estudiantes_activos = $stmt_est->fetchColumn();

    // 2. ÁREA: FINANZAS (Morosidad y Pagos al día del mes actual)
    // Alumnos al día (Esto se queda igual)
    $stmt_aldia = $conexion->prepare("SELECT COUNT(DISTINCT id_estudiante) FROM pagos WHERE mes_pagado = ?");
    $stmt_aldia->execute([$mes_actual]);
    $kpi_alumnos_al_dia = $stmt_aldia->fetchColumn();
    
    // Alumnos morosos (Activos en 'usuarios' que no han pagado este mes)
    $sql_morosos = "SELECT COUNT(e.id_estudiante) 
                    FROM estudiantes e
                    INNER JOIN usuarios u ON e.id_usuario = u.id_usuario
                    WHERE u.estado = 1 
                    AND e.id_estudiante NOT IN (SELECT id_estudiante FROM pagos WHERE mes_pagado = ?)";
    $stmt_morosos = $conexion->prepare($sql_morosos);
    $stmt_morosos->execute([$mes_actual]);
    $kpi_morosos = $stmt_morosos->fetchColumn();

    $stmt_clases = $conexion->query("SELECT COUNT(*) AS total_clases, COUNT(DISTINCT id_docente) AS total_docentes FROM asignaciones_docentes");
    $data_clases = $stmt_clases->fetch(PDO::FETCH_ASSOC);
    
    $stmt_asig = $conexion->query("SELECT COUNT(*) FROM asignaciones");
    $total_asignaciones = $stmt_asig->fetchColumn();
    
    // 4. ÁREA: OPERACIÓN (Tickets Activos)
    $stmt_tickets = $conexion->query("SELECT COUNT(*) FROM tickets_soporte WHERE estado IN ('Abierto', 'En Proceso')");
    $kpi_tickets_activos = $stmt_tickets->fetchColumn();

    if ($data_clases['total_docentes'] > 0) {
        $kpi_carga_docente = round($data_clases['total_clases'] / $data_clases['total_docentes'], 1);
    }
    
    if ($data_clases['total_clases'] > 0) {
        $kpi_saturacion = round($total_asignaciones / $data_clases['total_clases'], 1);
    }

} catch(PDOException $e) {
    $error_bd = "Error al calcular KPIs: " . $e->getMessage();
}
?>

