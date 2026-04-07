<?php
require 'procesar_pagos.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Solvencias</title>

</head>
<body>
<?php
require 'encabezado.php'; 
?>
<div class="contenedor">
    <h2 style="color: #333;">Módulo de Pagos y Solvencias</h2>

    <?php if($mensaje_exito) echo "<div class='alerta-exito'>$mensaje_exito</div>"; ?>
    <?php if($mensaje_error) echo "<div class='alerta-error'>$mensaje_error</div>"; ?>

    <div class="caja">
        <form action="" method="GET">
            <label class="etiqueta">1. Seleccionar Estudiante:</label>
            <select name="id_estudiante" onchange="this.form.submit()" required>
                <option value="" disabled <?= !$estudiante_seleccionado ? 'selected' : '' ?>>-- Busca un estudiante --</option>
                <?php foreach ($lista_estudiantes as $est): ?>
                    <option value="<?= $est['id_estudiante'] ?>" <?= ($estudiante_seleccionado == $est['id_estudiante']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($est['apellidos'] . ", " . $est['nombres']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if ($estudiante_seleccionado): ?>
        
        <div class="caja" style="border-top: 4px solid #28a745;">
            <h3 style="margin-top:0; color:#28a745;">Registrar Nuevo Recibo (Soporta múltiples meses)</h3>
            <form action="" method="POST">
                <input type="hidden" name="id_estudiante" value="<?= htmlspecialchars($estudiante_seleccionado) ?>">
                
                <label class="etiqueta">Seleccione el mes o los meses a cancelar:</label>
                <div class="grid-meses">
                    <?php foreach ($meses_permitidos as $mes): ?>
                        <label class="checkbox-label">
                            <input type="checkbox" name="meses[]" value="<?= $mes ?>"> <?= $mes ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <label class="etiqueta">Monto Total Pagado en Boleta (Q):</label>
                <input type="number" name="monto" step="0.01" min="1" placeholder="Ej. 1500.00" required>

                <label class="etiqueta">Número de Boleta / Referencia Banco:</label>
                <input type="text" name="numero_boleta" placeholder="Ej. 987654321" required>

                <button type="submit" name="registrar_pago" class="btn-accion">Procesar Pago</button>
            </form>
        </div>

        <div class="caja">
            <h3 style="margin-top:0; color:#0056b3;">Historial de Pagos</h3>
            
            <?php if (count($historial_pagos) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Fecha de Registro</th>
                            <th>Mes Cancelado</th>
                            <th>Monto</th>
                            <th>No. Boleta</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historial_pagos as $pago): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($pago['fecha_pago'])) ?></td>
                                <td><strong><?= htmlspecialchars($pago['mes_pagado']) ?></strong></td>
                                <td>Q <?= number_format($pago['monto'], 2) ?></td>
                                <td><?= htmlspecialchars($pago['numero_boleta']) ?></td>
                                <td style="color: green; font-weight: bold;">✔ Solvente</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #666;">Este estudiante no tiene pagos registrados en el sistema todavía.</p>
            <?php endif; ?>
        </div>

    <?php endif; ?>

</div>

</body>
</html>
