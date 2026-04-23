<?php
require 'procesar_reportes.php';  

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generador de Reportes</title>

</head>
<body>
         <?php
        require 'encabezado.php';
        ?>

<div class="contenedor">
    
    <h2 class="no-print">Centro de Reportes - Universidad Regional</h2>

    <div class="panel-controles no-print">
        
        <div class="caja-reporte">
            <h3 style="margin-top:0; color:#0056b3;">Alumnos Morosos</h3>
            <form action="" method="GET">
                <input type="hidden" name="tipo" value="morosos">
                <label>Seleccione el mes a revisar:</label>
                <select name="mes" required>
                    <option value="" disabled selected>-- Mes de adeudo --</option>
                    <?php foreach ($meses_permitidos as $mes): ?>
                        <option value="<?= $mes ?>"><?= $mes ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn-generar">Generar Reporte Financiero</button>
            </form>
        </div>

        <div class="caja-reporte">
            <h3 style="margin-top:0; color:#0056b3;">Listado para Docentes</h3>
            <form action="" method="GET">
                <input type="hidden" name="tipo" value="curso">
                <label>Seleccione el curso:</label>
                <select name="id_curso" required>
                    <option value="" disabled selected>-- Elige el curso --</option>
                    <?php foreach ($lista_cursos as $curso): ?>
                        <option value="<?= $curso['id_curso'] ?>"><?= htmlspecialchars($curso['nombre_curso']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn-generar">Generar Listado de Asistencia</button>
            </form>
        </div>

    </div>

    <?php if ($tipo_reporte !== ''): ?>
        <div class="hoja-reporte">
            
            <button class="btn-imprimir no-print" onclick="window.print()">🖨️ Imprimir Documento</button>
            
            <div class="hoja-header">
                <h1 style="margin: 0; color: #0056b3; font-size: 24px;">Universidad Regional</h1>
                <h3 style="margin: 5px 0 0 0; color: #333;"><?= $titulo_reporte ?></h3>
                <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">Fecha de emisión: <?= date('d/m/Y') ?></p>
            </div>

            <?php if (count($resultados) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Carnet / ID</th>
                            <th>Apellidos</th>
                            <th>Nombres</th>
                            <th>Correo Electrónico</th>
                            <?php if ($tipo_reporte === 'curso'): ?>
                                <th>Firma de Asistencia</th>
                            <?php else: ?>
                                <th>Observaciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $contador = 1; foreach ($resultados as $fila): ?>
                            <tr>
                                <td style="width: 30px; text-align: center;"><?= $contador++ ?></td>
                                <td style="width: 80px; text-align: center;"><?= $fila['id_estudiante'] ?></td>
                                <td><strong><?= htmlspecialchars($fila['apellidos']) ?></strong></td>
                                <td><?= htmlspecialchars($fila['nombres']) ?></td>
                                <td><?= htmlspecialchars($fila['correo']) ?></td>
                                
                                <td style="width: 150px;"></td> 
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p style="text-align: right; margin-top: 15px; font-weight: bold;">Total de registros: <?= count($resultados) ?></p>
            <?php else: ?>
                <div style="text-align: center; padding: 30px; background-color: #f8f9fa; border: 1px dashed #ccc;">
                    <h3 style="color: #666;">No se encontraron registros</h3>
                    <p>No hay alumnos morosos para este mes, o no hay alumnos inscritos en este curso.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>