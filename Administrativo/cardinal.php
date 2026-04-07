<?php
require 'procesar_cardinal.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Kardex Académico - Secretaría</title>

</head>
<body>
         <?php
        require 'encabezado.php';
        ?>
<div class="contenedor">
    
    <div class="panel-busqueda no-print">
        <h3 style="margin-top:0; color:#0056b3;">Generador de Kardex Estudiantil</h3>
        <form action="" method="GET" style="display: flex; gap: 10px;">
            <select name="id_estudiante" required>
                <option value="" disabled <?= !$id_estudiante ? 'selected' : '' ?>>-- Busque al estudiante por sus apellidos --</option>
                <?php foreach ($lista_estudiantes as $est): ?>
                    <option value="<?= $est['id_estudiante'] ?>" <?= ($id_estudiante == $est['id_estudiante']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($est['apellidos'] . ", " . $est['nombres'] . " (Carnet: " . $est['id_estudiante'] . ")") ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn-buscar">Generar Certificado</button>
        </form>
    </div>

    <?php if ($datos_alumno): ?>
        <div class="hoja-kardex">
            <button class="btn-imprimir no-print" onclick="window.print()">Imprimir Kardex</button>
            
            <div class="header-oficial">
                <h1>Universidad Regional de Guatemala</h1>
                <h3>Certificación de Estudios Académicos (Kardex)</h3>
            </div>

            <div class="info-estudiante">
                <div>
                    <p><strong>Nombres:</strong> <?= htmlspecialchars($datos_alumno['nombres']) ?></p>
                    <p><strong>Apellidos:</strong> <?= htmlspecialchars($datos_alumno['apellidos']) ?></p>
                </div>
                <div style="text-align: right;">
                    <p><strong>Carnet / ID:</strong> <?= htmlspecialchars($datos_alumno['id_estudiante']) ?></p>
                    <p><strong>Carrera Inscrita:</strong> <?= htmlspecialchars($datos_alumno['nombre_carrera']) ?></p>
                    <p><strong>Fecha de Emisión:</strong> <?= date('d/m/Y') ?></p>
                </div>
            </div>

<?php if (count($historial_notas) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Semestre</th>
                            <th>Nombre del Curso</th>
                            <th style="text-align: center;">Nota Final</th>
                            <th style="text-align: center;">Resultado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historial_notas as $nota): ?>
                            <tr>
                                <td><?= $nota['semestre'] ? 'Semestre ' . $nota['semestre'] : 'Extra' ?></td>
                                <td><strong><?= htmlspecialchars($nota['nombre_curso']) ?></strong></td>
                                <td style="text-align: center;"><?= number_format($nota['nota_final'], 0) ?> pts</td>
                                <td style="text-align: center;">
                                    <?php if ($nota['nota_final'] >= 61): ?>
                                        <span class="estado-aprobado">APROBADO</span>
                                    <?php else: ?>
                                        <span class="estado-reprobado">REPROBADO</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="resumen">
                    PROMEDIO GENERAL ACUMULADO: <?= number_format($promedio_general, 2) ?> Pts.
                </div>

            <?php else: ?>
                <div style="text-align: center; padding: 40px; border: 1px dashed #ccc;">
                    <p style="color: #666; font-size: 16px;">Este estudiante no tiene historial de notas registrado en el sistema.</p>
                </div>
            <?php endif; ?>

            <div class="area-firmas">
                <div>
                    <div class="linea-firma"></div>
                    <p style="margin: 5px 0; font-weight: bold;">Secretaría Académica</p>
                    <p style="margin: 0; font-size: 12px; color: #666;">Firma y Sello Oficial</p>
                </div>
                <div>
                    <div class="linea-firma"></div>
                    <p style="margin: 5px 0; font-weight: bold;">Director de Carrera</p>
                    <p style="margin: 0; font-size: 12px; color: #666;">Vo. Bo.</p>
                </div>
            </div>

        </div>
    <?php endif; ?>

</div>

</body>
</html>