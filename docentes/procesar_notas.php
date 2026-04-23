<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    if (isset($_POST['notas'])) {
        
        $lista_notas = $_POST['notas'];
        $curso_actual = $_POST['id_curso'] ?? 1; 
        $sql = "INSERT INTO calificaciones(id_estudiante, id_curso, nota, nota2, nota3, nota_final) 
                VALUES (?, ?, ?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE 
                nota = ?, nota2 = ?, nota3 = ?, nota_final = ?";

        try {
            $consulta = $conexion->prepare($sql);

            
            foreach ($lista_notas as $id_estudiante => $unidades) {
                
                $u1 = !empty($unidades['u1']) ? $unidades['u1'] : 0;
                $u2 = !empty($unidades['u2']) ? $unidades['u2'] : 0;
                $u3 = !empty($unidades['u3']) ? $unidades['u3'] : 0;
                
                $nota_final = $u1 + $u2 + $u3;
                
                $consulta->execute([
                    $id_estudiante, $curso_actual, $u1, $u2, $u3, $nota_final, 
                    $u1, $u2, $u3, $nota_final                                 
                ]);
            }

            echo "<h3>¡Calificaciones guardadas/actualizadas correctamente!</h3>";
            echo "<a href='formulario_docentes.php'>Volver al panel</a>";
            
        } catch(PDOException $e) {
            echo "Error al intentar guardar: " . $e->getMessage();
        }

    } else {
        
        echo "<h3>No se recibieron calificaciones para guardar.</h3>";
        echo "<a href='formulario_docentes.php'>Volver al panel</a>";
    }

} else {
    echo "Acceso no autorizado. Por favor usa el formulario.";
}
?>