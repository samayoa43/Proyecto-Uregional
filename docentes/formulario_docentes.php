
<?php
require 'calificaciones.php';

    $ruta_base = "../"; 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Notas - Portal Académico</title>
    <link rel="stylesheet" href="estilos_docente.css?v=<?php echo time(); ?>">
    <style>
        .input-nota {
            width: 70px;
            text-align: center;
            padding: 8px 5px;
            font-weight: 600;
        }
        .input-nota::-webkit-inner-spin-button, 
        .input-nota::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        .input-nota[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>

    <?php require 'encabezado.php'; ?>

    <main class="main-container">

        <div class="section-header">
            <h2>Registro de Calificaciones</h2>
            <p>Ingrese las notas correspondientes a la 1ra, 2ra y 3ra unidad del curso.</p>
        </div>

        <div class="kpi-card">
            <form action="" method="POST" style="display: flex; align-items: flex-end; gap: 20px; flex-wrap: wrap;">
                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 250px;">
                    <label class="form-label" for="curso_seleccionado">Seleccione un curso:</label>
                    <select name="curso_seleccionado" id="curso_seleccionado" class="form-input" required>
                        <option value="">-- Elija una opción --</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= htmlspecialchars($curso['id_curso']) ?>" 
                                <?= (isset($curso_seleccionado) && $curso_seleccionado == $curso['id_curso']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($curso['nombre_curso']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-primario" style="height: 42px;">Cargar Alumnos</button>
            </form>
        </div>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            
            <?php if (!empty($alumnos)): ?>
                
                <div class="kpi-card" style="border-top: 4px solid var(--color-primario);">
                    <h3 style="margin-top: 0; margin-bottom: 20px;">Listado de Alumnos</h3>
                    
                    <form action="procesar_notas.php" method="POST">
                        <input type="hidden" name="id_curso" value="<?= htmlspecialchars($curso_seleccionado) ?>">
                        
                        <div style="overflow-x: auto;">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre del Estudiante</th>
                                        <th style="text-align: center;">1ra Unidad / 2da Unidad / 3ra Unidad</th>
                                        <th style="text-align: center;">Nota Final</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alumnos as $alumno): ?>
                                        <tr>
                                            <td style="font-family: monospace; color: var(--color-primario); font-weight: bold;">
                                                <?= htmlspecialchars($alumno['id_estudiante']) ?>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($alumno['apellidos'] . ", " . $alumno['nombres']) ?>
                                            </td>
                                            
                                            <td style="text-align: center;"> 
                                                <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                                                    <input type="number" class="form-input input-nota" name="notas[<?= htmlspecialchars($alumno['id_estudiante']) ?>][u1]" 
                                                        value="<?= ($alumno['u1'] > 0) ? htmlspecialchars($alumno['u1']) : '' ?>" 
                                                        min="0" max="30" step="1" placeholder="0">
                                                    
                                                    <span style="color: var(--color-borde);">|</span>

                                                    <input type="number" class="form-input input-nota" name="notas[<?= htmlspecialchars($alumno['id_estudiante']) ?>][u2]" 
                                                        value="<?= ($alumno['u2'] > 0) ? htmlspecialchars($alumno['u2']) : '' ?>" 
                                                        min="0" max="30" step="1" placeholder="0">

                                                    <span style="color: var(--color-borde);">|</span>

                                                    <input type="number" class="form-input input-nota" name="notas[<?= htmlspecialchars($alumno['id_estudiante']) ?>][u3]" 
                                                        value="<?= ($alumno['u3'] > 0) ? htmlspecialchars($alumno['u3']) : '' ?>" 
                                                        min="0" max="40" step="1" placeholder="0">
                                                </div>
                                            </td>

                                            <td style="text-align: center; font-weight: bold; font-size: 16px;">
                                                <?= ($alumno['nota_final'] > 0) ? htmlspecialchars($alumno['nota_final']) : '-' ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <br>
                        
                        <div style="background-color: var(--color-fondo); padding: 20px; border-radius: 6px; border: 1px solid var(--color-borde); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">¿Confirma que las notas ingresadas son correctas?</label>
                                <select class="form-input" required style="max-width: 250px;">
                                    <option value="">-- Seleccione confirmación --</option>
                                    <option value="Correcto">✅ Sí, están correctas</option>
                                    <option value="No valido">❌ No estoy seguro (Revisar)</option>
                                </select>
                            </div>
                            <div class="form-acciones" style="margin-top: 0;">
                                <button type="submit" class="btn-primario">Guardar Calificaciones</button>
                            </div>
                        </div>

                    </form>
                </div>

            <?php else: ?>
                <div class="kpi-card" style="text-align: center; padding: 40px;">
                    <p style="color: var(--color-texto); opacity: 0.7;">No hay alumnos inscritos en el curso seleccionado.</p>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="kpi-card" style="text-align: center; padding: 50px; border: 2px dashed var(--color-borde); background: transparent;">
                <p style="font-size: 16px; color: var(--color-texto); opacity: 0.6;">
                    Seleccione un curso arriba para comenzar a ingresar las calificaciones.
                </p>
            </div>
        <?php endif; ?>

    </main>

    <script src="<?= $ruta_base ?>docentes/script_docentes.js?v=<?php echo time(); ?>"></script>

    <script>
        const celdas = document.querySelectorAll('.input-nota');
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