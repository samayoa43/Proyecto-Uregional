
<?php
require 'calificaciones.php';
 /*
    tener cuidado con lo que tocan, agreguen diseño y colores, no tocar funciones encerradas con <?php; ?>, usen todos los documetos que tengan 
    <!DOCTYPE html>, los que empiezan por <?php no mover por favor 
  */
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calificaciones</title>
</head>
<body>

<?php
        require 'encabezado.php';
?>


    <h2>Registro de notas</h2>

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
            
            <form action="procesar_notas.php" method="POST">
                <input type="hidden" name="id_curso" value="<?= htmlspecialchars($curso_seleccionado) ?>">
                
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Primera / Segunda / Tercera </th>
                            <th>Nota Final </th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alumnos as $alumno): ?>
                            <tr>
                                <td><?= htmlspecialchars($alumno['id_estudiante']) ?></td>
                                <td><?= htmlspecialchars($alumno['apellidos'] . ", " . $alumno['nombres']) ?></td>
                                <td> 
                                    <input type="number" name="notas[<?= htmlspecialchars($alumno['id_estudiante']) ?>][u1]" 
                                        value="<?= ($alumno['u1'] > 0) ? htmlspecialchars($alumno['u1']) : '' ?>" 
                                         min="0" max="30" step="1">

                                    <input type="number" name="notas[<?= htmlspecialchars($alumno['id_estudiante']) ?>][u2]" 
                                        value="<?= ($alumno['u2'] > 0) ? htmlspecialchars($alumno['u2']) : '' ?>" 
                                         min="0" max="30" step="1">

                                    <input type="number" name="notas[<?= htmlspecialchars($alumno['id_estudiante']) ?>][u3]" 
                                        value="<?= ($alumno['u3'] > 0) ? htmlspecialchars($alumno['u3']) : '' ?>" 
                                        min="0" max="40" step="1">
                                    <td><?= ($alumno['nota_final'] > 0) ? htmlspecialchars($alumno['nota_final']) : '' ?></td>
                                </td>
                                
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <br>
                <label >Las notas ingresadas estan correctas?</label>
                <select required>
                <option value="">Seleccione una opción</option>
                <option value="Correcto">-- correctas --</option>
                <option value="No valido">-- No estoy seguro --</option>
                </select>
                
                <button type="submit">Guardar Notas</button>
            </form>

        <?php else: ?>
            <p>No hay alumnos inscritos en el curso seleccionado.</p>
        <?php endif; ?>

    <?php else: ?>
        <p>Seleccione un curso arriba para comenzar a ingresar notas.</p>
    <?php endif; ?>

<script>

    const celdas = document.querySelectorAll('input[type="number"]');
    

    const columnasPorAlumno = 3; 

    celdas.forEach((celda, index) => {
        celda.addEventListener('keydown', function(evento) {
            
            if (evento.key === 'Enter') {
                evento.preventDefault(); 
                
                const siguienteCelda = celdas[index + columnasPorAlumno];
                

                if (siguienteCelda) {
                    siguienteCelda.focus();
                }
            }
            
        });
    });
</script>
</body>
</html>