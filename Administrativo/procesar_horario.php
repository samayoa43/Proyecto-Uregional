<?php
require 'C:\laragon\www\proyecto\conexion.php';

$mensaje = '';
$lista_asignaciones = [];

// 1. CAPTURAR ÉXITO (PRG)
if (isset($_GET['exito']) && $_GET['exito'] == 1) {
    $mensaje = "<div style='color: green; background: #e6ffe6; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>¡Horario guardado exitosamente!</div>";
}

// 2. PROCESAR EL FORMULARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_asignacion = $_POST['id_asignacion'] ?? null;
    
    // Recibimos los arreglos de los horarios
    $dias = $_POST['dias'] ?? [];
    $horas_inicio = $_POST['horas_inicio'] ?? [];
    $horas_fin = $_POST['horas_fin'] ?? [];

    if ($id_asignacion && !empty($dias)) {
        try {
            $conexion->beginTransaction();

            $sql = "INSERT INTO horarios (id_asignacion, dia_semana, hora_inicio, hora_fin) 
                    VALUES (:id_asignacion, :dia, :inicio, :fin)";
            $stmt = $conexion->prepare($sql);

            $insertados = 0;

            // Recorremos los días seleccionados
            for ($i = 0; $i < count($dias); $i++) {
                if (!empty($dias[$i]) && !empty($horas_inicio[$i]) && !empty($horas_fin[$i])) {
                    $stmt->execute([
                        ':id_asignacion' => $id_asignacion,
                        ':dia' => $dias[$i],
                        ':inicio' => $horas_inicio[$i],
                        ':fin' => $horas_fin[$i],
                    ]);
                    $insertados++;
                }
            }

            $conexion->commit();

            if ($insertados > 0) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?exito=1");
                exit();
            }

        } catch (PDOException $e) {
            $conexion->rollBack();
            $mensaje = "<div style='color: red; background: #ffe6e6; padding: 10px; border-radius: 5px;'>Error al guardar: " . $e->getMessage() . "</div>";
        }
    } else {
        $mensaje = "<div style='color: orange;'>Complete todos los campos obligatorios.</div>";
    }
}

// 3. OBTENER LAS CLASES ASIGNADAS (Uniendo 3 tablas para que sea legible)
try {
    // Buscamos las asignaciones y unimos con cursos y docentes para mostrar los nombres
    $sql_asignaciones = "
        SELECT a.id_asignacion, c.nombre_curso, d.nombres, d.apellidos 
        FROM asignaciones_docentes a
        JOIN cursos c ON a.id_curso = c.id_curso
        JOIN docentes d ON a.id_docente = d.id_docente
        ORDER BY c.nombre_curso ASC
    ";
    $stmt_asig = $conexion->query($sql_asignaciones);
    $lista_asignaciones = $stmt_asig->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $mensaje = "<div style='color: red;'>Error al cargar las clases: " . $e->getMessage() . "</div>";
}
?>
