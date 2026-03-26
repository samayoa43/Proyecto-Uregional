<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['estado']) && isset($_POST['id_asignacion'])) {
    
    $lista_asistencias = $_POST['estado'];
    $asignacion_actual = $_POST['id_asignacion']; 
    $fecha_hoy = date('Y-m-d'); 
    
    try {
        $sql_buscar_curso = "SELECT id_curso FROM asignaciones_docentes WHERE id_asignacion = ?";
        $stmt_buscar = $conexion->prepare($sql_buscar_curso);
        $stmt_buscar->execute([$asignacion_actual]);
        $resultado = $stmt_buscar->fetch(PDO::FETCH_ASSOC);
        
        $id_curso_real = $resultado['id_curso'];

        $sql = "INSERT INTO asistencia (id_estudiante, id_curso, estado, fecha) 
                VALUES (?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE 
                estado = ?";
                
        $consulta = $conexion->prepare($sql);
        
        foreach ($lista_asistencias as $id_estudiante => $estado_marcado) {
            $consulta->execute([
                $id_estudiante, $id_curso_real, $estado_marcado, $fecha_hoy,
                $estado_marcado                                             
            ]);
        }
        
        echo "<h3>¡Asistencia del curso guardada/corregida con éxito!</h3>";
        echo "<a href='index.php'>Volver al panel</a>"; 
        
    } catch(PDOException $e) {
        echo "Error al guardar la asistencia: " . $e->getMessage();
    }
    
} else {
    echo "Faltan datos (asegúrate de seleccionar la clase y marcar asistencias).";
}
?>