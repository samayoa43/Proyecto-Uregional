<?php
require_once __DIR__ . '/validar_sesion_docentes.php';
require 'curso_docentes.php';
    $ruta_base = "../"; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Asistencia - Portal Académico</title>
    <link rel="stylesheet" href="estilos_docente.css?v=<?php echo time(); ?>">
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
            <h2>Control de Asistencia</h2>
            <p>Seleccione el curso para registrar la asistencia de los estudiantes hoy.</p>
        </div>

        
        <?php if (isset($_GET['exito'])): ?>
        <div class="alert alert-success">
        <span>✅</span>
        <div><strong>¡Operación exitosa!</strong> Asistencia guardada con éxito.</div>
        </div>
        <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
        <span>⚠️</span>
        <div><strong>Error:</strong> Ocurrió un error al guardar la asistencia. Inténtalo de nuevo.</div>
        </div>
        <?php endif; ?>
        <div class="kpi-card">
            <form action="" method="POST" style="display: flex; align-items: flex-end; gap: 20px; flex-wrap: wrap;">
                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 250px;">
                    <label class="form-label">Seleccione una clase:</label>
                    <select name="asignacion_seleccionada" class="form-input" required>
                        <option value="">-- Elija una opción --</option>
                        <?php foreach ($cursos as $clase): ?>
                            <option value="<?= htmlspecialchars($clase['id_asignacion']) ?>" 
                                <?= (isset($asignacion_seleccionada) && $asignacion_seleccionada == $clase['id_asignacion']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($clase['nombre_curso']) ?>
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
                    <h3 style="margin-top: 0; margin-bottom: 20px;">Listado de Estudiantes</h3>
                    
                    <form action="procesar_asistencia.php" method="POST">
                        <input type="hidden" name="id_asignacion" value="<?= htmlspecialchars($asignacion_seleccionada) ?>">
                        
                        <div style="overflow-x: auto;">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre Completo</th>
                                        <th style="text-align: center;">Estado de Asistencia</th>
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
                                                <select name="estado[<?= $alumno['id_estudiante'] ?>]" class="form-input" style="width: auto; padding: 5px 10px;" required>
                                                    <option value="Asistente">✅ Presente</option>
                                                    <option value="Falta">❌ Ausente</option>
                                                    <option value="permiso">📝 Permiso</option>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-acciones">
                            <button type="submit" class="btn-primario">Guardar Asistencia del Día</button>
                            <a href="inicio_docente.php" class="btn-secundario">Cancelar</a>
                        </div>
                    </form>
                </div>

            <?php else: ?>
                <div class="kpi-card" style="text-align: center; padding: 40px;">
                    <p style="color: var(--color-texto); opacity: 0.7;">No hay alumnos inscritos en la clase seleccionada.</p>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="kpi-card" style="text-align: center; padding: 50px; border: 2px dashed var(--color-borde); background: transparent;">
                <p style="font-size: 16px; color: var(--color-texto); opacity: 0.6;">
                    Por favor, seleccione una clase arriba para comenzar a tomar asistencia.
                </p>
            </div>
        <?php endif; ?>

    </main>

    <?php require 'footer.php'; ?>

    <script src="<?= $ruta_base ?>docentes/script_docentes.js?v=<?php echo time(); ?>"></script>
</body>
</html>