<?php

require 'curso_docentes.php';

 /*
    tener cuidado con lo que tocan, agreguen diseño y colores, no tocar funciones encerradas con <?php; ?>, usen todos los documetos que tengan 
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
    <a href="index.php">home</a>

    <form action="" method="POST"> 
        <label for="curso_seleccionado">Seleccione un curso:</label>
        <select name="curso_seleccionado" id="curso_seleccionado" required>
            <option value="">-- Elija una opción --</option>
            
            <?php foreach ($cursos as $curso): ?>
                <option value="<?= htmlspecialchars($curso['id_curso']) ?>" 
                    <?= ($curso_seleccionado == $curso['id_curso']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($curso['nombre_curso']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Cargar Alumnos</button>
    </form>

    <hr>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        
        <?php if (!empty($alumnos)): ?>
            <h3>Listado de Alumnos</h3>
            
            <form action="procesar_asistencia.php" method="POST">
                <input type="hidden" name="id_curso" value="<?= htmlspecialchars($curso_seleccionado) ?>">
                
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
                                        <option value="falta">Ausente</option>
                                        <option value="Permiso">Permiso</option>
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
            <p>No hay alumnos inscritos en el curso seleccionado.</p>
        <?php endif; ?>

    <?php else: ?>
        <p>Seleccione un curso arriba para comenzar a tomar asistencia.</p>
    <?php endif; ?>

</body>
</html>