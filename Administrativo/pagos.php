<?php

require_once __DIR__ . '/validar_sesion_admin.php'; 

require 'procesar_pagos.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Solvencias - Plataforma Académica</title>
    <link rel="stylesheet" href="estilo_administrativo.css?v=<?php echo time(); ?>">
</head>
<body>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>

    <?php 
    $ruta_base = "../";
    require 'encabezado.php'; ?>

    <main class="main-container">
        
        <div class="section-header">
            <h2>Módulo de Pagos y Solvencias</h2>
            <p>Gestiona la cobranza de colegiaturas y revisa el historial financiero de los estudiantes.</p>
        </div>

        <?php if(!empty($mensaje_exito)): ?>
            <div class='alerta alerta-exito'><?= $mensaje_exito ?></div>
        <?php endif; ?>
        <?php if(!empty($mensaje_error)): ?>
            <div class='alerta alerta-error'><?= $mensaje_error ?></div>
        <?php endif; ?>

        <div class="kpi-card form-card" style="margin-bottom: 25px; max-width: 800px;">
            <h3 style="margin-top:0; color:#0078d4; margin-bottom: 15px;">1. Seleccionar Estudiante</h3>
            
            <form action="" method="GET">
                <div class="form-group" style="margin-bottom: 0;">
                    <select name="id_estudiante" class="form-input" onchange="this.form.submit()" required>
                        <option value="" disabled <?= empty($estudiante_seleccionado) ? 'selected' : '' ?>>-- Busca un estudiante --</option>
                        <?php foreach ($lista_estudiantes as $est): ?>
                            <option value="<?= $est['id_estudiante'] ?>" <?= ($estudiante_seleccionado == $est['id_estudiante']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($est['apellidos'] . ", " . $est['nombres']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>

        <?php if (!empty($estudiante_seleccionado)): ?>

            <div class="kpi-card form-card" style="border-top: 4px solid #10b981; margin-bottom: 30px;">
                <h3 style="margin-top:0; color:#10b981; margin-bottom: 20px;">Registrar Nuevo Recibo</h3>
                
                <form action="" method="POST">
                    <input type="hidden" name="id_estudiante" value="<?= htmlspecialchars($estudiante_seleccionado) ?>">
                    
                    <div class="form-group">
                        <label class="form-label">Seleccione el mes o los meses a cancelar:</label>
                        <div class="grid-meses">
                            <?php foreach ($meses_permitidos as $mes): ?>
                                <label class="mes-checkbox">
                                    <input type="checkbox" name="meses[]" value="<?= $mes ?>"> 
                                    <span><?= $mes ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Monto Total en Boleta (Q):</label>
                            <input type="number" name="monto" step="0.01" min="1" class="form-input" placeholder="Ej. 1500.00" required>
                        </div>

                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">No. de Boleta / Ref. Banco:</label>
                            <input type="text" name="numero_boleta" class="form-input" placeholder="Ej. 987654321" required>
                        </div>
                    </div>

                    <div class="form-acciones">
                        <button type="submit" name="registrar_pago" class="btn-primario" style="background-color: #10b981; border: none;">Procesar Pago</button>
                        <a href="inicio_admin.php" class="btn-secundario">Cancelar</a>
                    </div>
                </form>
            </div>

            <div class="kpi-card" style="width: 100%; overflow-x: auto; padding: 25px; box-sizing: border-box;">
                <h3 style="margin-top:0; color:#334155; margin-bottom: 20px;">Historial de Pagos</h3>
                
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
                                    <td>
                                        <span class="badge badge-aprobado">✔ Solvente</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="reporte-vacio">
                        <h3 style="color: #64748b;">Sin Movimientos</h3>
                        <p>Este estudiante no tiene pagos registrados en el sistema todavía.</p>
                    </div>
                <?php endif; ?>
            </div>

        <?php endif; ?>

    </main>

    <?php require 'footer.php'; ?>

    <script src="script_admin.js?v=<?php echo time(); ?>"></script>
</body>
</html>