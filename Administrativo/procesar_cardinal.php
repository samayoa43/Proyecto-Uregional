<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../conexion.php'; 

$lista_estudiantes = [];
try {
    $stmt_est = $conexion->query("SELECT id_estudiante, nombres, apellidos FROM estudiantes ORDER BY apellidos ASC");
    $lista_estudiantes = $stmt_est->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error al cargar estudiantes: " . $e->getMessage();
}

$id_estudiante = isset($_GET['id_estudiante']) ? $_GET['id_estudiante'] : null;
$datos_alumno = null;
$historial_notas = [];
$promedio_general = 0;

if ($id_estudiante) {
    try {

        $sql_perfil = "SELECT e.id_estudiante, e.nombres, e.apellidos, c.nombre_carrera 
                       FROM estudiantes e
                       INNER JOIN carreras c ON e.id_carrera = c.id_carrera
                       WHERE e.id_estudiante = ?";
        $stmt_perfil = $conexion->prepare($sql_perfil);
        $stmt_perfil->execute([$id_estudiante]);
        $datos_alumno = $stmt_perfil->fetch(PDO::FETCH_ASSOC);

// 3. OBTENER EL KARDEX (Adaptado a tu base de datos Cambios_base.sql)
        // Usamos la tabla 'pensum' para obtener el semestre al que pertenece el curso
        $sql_notas = "SELECT 
                        p.semestre,
                        cur.nombre_curso, 
                        COALESCE(cal.nota_final, 0) AS nota_final 
                      FROM asignaciones a
                      INNER JOIN estudiantes e ON a.id_estudiante = e.id_estudiante
                      INNER JOIN asignaciones_docentes ad ON a.id_asignacion = ad.id_asignacion
                      INNER JOIN cursos cur ON ad.id_curso = cur.id_curso
                      LEFT JOIN pensum p ON cur.id_curso = p.id_curso AND e.id_carrera = p.id_carrera
                      LEFT JOIN calificaciones cal ON a.id_estudiante = cal.id_estudiante AND ad.id_curso = cal.id_curso
                      WHERE a.id_estudiante = ?
                      ORDER BY p.semestre ASC, cur.nombre_curso ASC";
                      
        $stmt_notas = $conexion->prepare($sql_notas);
        $stmt_notas->execute([$id_estudiante]);
        $historial_notas = $stmt_notas->fetchAll(PDO::FETCH_ASSOC);


        if (count($historial_notas) > 0) {
            $suma_total = 0;
            foreach ($historial_notas as $nota) {
                $suma_total += $nota['nota_final'];
            }
            $promedio_general = round($suma_total / count($historial_notas), 2);
        }

    } catch(PDOException $e) {
        echo "<div style='color:red;'>Error en la base de datos: " . $e->getMessage() . "</div>";
    }
}
?>
