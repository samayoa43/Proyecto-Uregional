<?php
 require_once __DIR__ . '/validar_sesion_docentes.php';
 require 'proceso_horario.php';

    $ruta_base = "../"; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Horario - Portal Docente</title>
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
            <h2>Mi Horario de Clases</h2>
            <p>Consulta tus asignaciones, días y horarios para este ciclo académico.</p>
        </div>
        
        <?php if (count($horarios) > 0): ?>
            <div class="kpi-card" style="border-top: 4px solid var(--color-primario);">
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Curso Asignado</th>
                                <th>Día</th>
                                <th style="text-align: center;">Hora Inicio</th>
                                <th style="text-align: center;">Hora Fin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($horarios as $clase): ?>
                                <tr>
                                    <td style="font-weight: 600; color: var(--color-titulos);">
                                        <?= htmlspecialchars($clase['nombre_curso']); ?>
                                    </td>
                                    <td>
                                        <span style="color: var(--color-primario); font-weight: bold;">
                                            <?= htmlspecialchars($clase['dia_semana']); ?>
                                        </span>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <span style="background-color: var(--color-fondo); border: 1px solid var(--color-borde); padding: 4px 10px; border-radius: 4px; font-family: monospace; font-size: 14px;">
                                            <?= substr($clase['hora_inicio'], 0, 5); ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span style="background-color: var(--color-fondo); border: 1px solid var(--color-borde); padding: 4px 10px; border-radius: 4px; font-family: monospace; font-size: 14px;">
                                            <?= substr($clase['hora_fin'], 0, 5); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php else: ?>
            <div class="kpi-card" style="text-align: center; padding: 50px; border: 2px dashed var(--color-borde); background: transparent;">
                <h3 style="color: var(--color-texto); margin-top: 0;">Sin asignaciones</h3>
                <p style="font-size: 15px; color: var(--color-texto); opacity: 0.7; margin-bottom: 0;">
                    No tienes cursos asignados para este ciclo por el momento.
                </p>
            </div>
        <?php endif; ?>

    </main>

    <script src="<?= $ruta_base ?>docentes/script_docentes.js?v=<?php echo time(); ?>"></script>

</body>
</html>