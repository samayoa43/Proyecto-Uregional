<?php
require 'proceso_horario.php';
?>

<!DOCTYPE html>
<!--
mismas instrucciones 
-->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Horario - Portal Docente</title>

</head>
<body>

<div class="contenedor">
    <h2>Mi Horario de Clases</h2>
    
    <?php if (count($horarios) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Día</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($horarios as $clase): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($clase['nombre_curso']); ?></td>
                        <td><?php echo htmlspecialchars($clase['dia_semana']); ?></td>
                        
                        <td><?php echo substr($clase['hora_inicio'], 0, 5); ?></td>
                        <td><?php echo substr($clase['hora_fin'], 0, 5); ?></td>
                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="mensaje-vacio">No tienes cursos asignados para este ciclo.</p>
    <?php endif; ?>
</div>

</body>
</html>