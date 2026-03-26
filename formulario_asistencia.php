<?php

require 'curso_docentes.php';

 /*
    Tener cuidado con lo que tocan, agreguen diseño y colores, no tocar funciones encerradas con <?php; ?>, usen todos los documetos que tengan 
    <!DOCTYPE html>, los que empiezan por <?php no mover por favor BORRAR ESTE COMETARIO AL TERMINAR
  */
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asistencia</title>
</head>
<body>

    <h2>Control de Asistencia</h2>
    <a href="index.php">Home</a>

    <form action="" method="POST"> 
        <label for="asignacion_seleccionada">Seleccione una clase:</label>
        <select name="asignacion_seleccionada" id="asignacion_seleccionada" required>
            <option value="">-- Elija una opción --</option>
            
            <?php foreach ($cursos as $clase): // $cursos viene de tu require ?>
                <option value="<?= htmlspecialchars($clase['id_asignacion']) ?>" 
                    <?= ($asignacion_seleccionada == $clase['id_asignacion']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($clase['nombre_curso']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Cargar Alumnos</button>
    </form> <hr>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        
        <?php if (!empty($alumnos)): ?>
            <h3>Listado de Alumnos</h3>
            
            <form action="procesar_asistencia.php" method="POST">
                
                <input type="hidden" name="id_asignacion" value="<?= htmlspecialchars($asignacion_seleccionada) ?>">
                
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alumnos as $alumno): ?>
                            <tr>
                                <td><?= htmlspecialchars($alumno['id_estudiante']) ?></td>
                                <td><?= htmlspecialchars($alumno['apellidos'] . ", " . $alumno['nombres']) ?></td>
                                <td> 
                                    <select name="estado[<?= $alumno['id_estudiante'] ?>]" required>
                                        <option value="Asistente">Presente</option>
                                        <option value="Falta">Ausente</option>
                                        <option value="permiso">Permiso</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <br>
                
                <button type="submit">Guardar Asistencia del Día</button>
            </form>

        <?php else: ?>
            <p>No hay alumnos inscritos en la clase seleccionada.</p>
        <?php endif; ?>

    <?php else: ?>
        <p>Seleccione una clase arriba para comenzar a tomar asistencia.</p>
    <?php endif; ?>

</body>
</html>