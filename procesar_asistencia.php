<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['estado']) && isset($_POST['id_curso'])) {
    
    $lista_asistencias = $_POST['estado'];
    $curso_actual = $_POST['id_curso']; 
    $fecha_hoy = date('Y-m-d'); 
    

    $sql = "INSERT INTO asistencia (id_estudiante, id_curso, estado,fecha) 
            VALUES (?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE 
            estado = ?";
    
    try {
        $consulta = $conexion->prepare($sql);
        
        foreach ($lista_asistencias as $id_estudiante => $estado_marcado) {
            

            $consulta->execute([
                $id_estudiante, $curso_actual, $estado_marcado,$fecha_hoy,
                $estado_marcado                                             
            ]);
            
        }
        
        echo "<h3>¡Asistencia del curso guardada/corregida con éxito!</h3>";
        echo "<a href='formulario_asistencia.php'>Volver al panel</a>";
        
    } catch(PDOException $e) {
        echo "Error al guardar la asistencia: " . $e->getMessage();
    }
    
} else {
    echo "Faltan datos (asegúrate de enviar el id_curso y marcar asistencias).";
}
?>