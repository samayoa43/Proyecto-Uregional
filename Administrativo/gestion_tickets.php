<?php

require 'procesar_tickets.php'; // Carga las funciones para obtener los KPIs

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Centro de Gestión de Soporte</title>

</head>
<body>
            <?php
        require 'encabezado.php';
        ?>

<div class="contenedor">
    <h2 style="margin-bottom: 5px;">Bandeja de Mesa de Ayuda (Helpdesk)</h2>
    <p style="color: #666; margin-top: 0;">Administra y da seguimiento a los reportes de estudiantes y docentes.</p>

    <?php if($mensaje_exito) echo "<div class='alerta-exito'>$mensaje_exito</div>"; ?>
    <?php if($mensaje_error) echo "<div class='alerta-error'>$mensaje_error</div>"; ?>

    <div class="caja">
        <?php if(count($lista_tickets) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th style="width: 200px;">Solicitante</th>
                        <th>Detalle del Problema</th>
                        <th style="width: 100px;">Prioridad</th>
                        <th style="width: 120px;">Estado Actual</th>
                        <th style="width: 250px;">Acción / Respuesta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lista_tickets as $t): ?>
                        <tr style="<?= ($t['estado'] == 'Resuelto') ? 'background-color: #f8f9fa; opacity: 0.8;' : '' ?>">
                            
                            <td><strong>#<?= $t['id_ticket'] ?></strong><br><small style="color:#888;"><?= date('d/m/y', strtotime($t['fecha_creacion'])) ?></small></td>
                            
                            <td>
                                <strong><?= htmlspecialchars($t['nombre_solicitante']) ?></strong><br>
                                <span style="font-size: 12px; color: #0056b3;"><?= htmlspecialchars($t['nombre_rol']) ?></span><br>
                                <small style="color: #666;"><?= htmlspecialchars($t['correo']) ?></small>
                            </td>
                            
                            <td>
                                <strong><?= htmlspecialchars($t['asunto']) ?></strong>
                                <div class="detalle-desc">
                                    <?= nl2br(htmlspecialchars($t['descripcion'])) ?>
                                </div>
                            </td>
                            
                            <td>
                                <span class="badge prio-<?= $t['prioridad'] ?>"><?= $t['prioridad'] ?></span>
                            </td>
                            
                            <td>
                                <?php $clase_estado = "est-" . explode(' ', $t['estado'])[0]; ?>
                                <span class="<?= $clase_estado ?>">● <?= $t['estado'] ?></span>
                            </td>
                            
                            <td>
                                <form action="" method="POST" class="form-inline">
                                    <input type="hidden" name="id_ticket" value="<?= $t['id_ticket'] ?>">
                                    <select name="nuevo_estado" required>
                                        <option value="Abierto" <?= ($t['estado'] == 'Abierto') ? 'selected' : '' ?>>Abierto</option>
                                        <option value="En Proceso" <?= ($t['estado'] == 'En Proceso') ? 'selected' : '' ?>>En Proceso</option>
                                        <option value="Resuelto" <?= ($t['estado'] == 'Resuelto') ? 'selected' : '' ?>>Resuelto</option>
                                    </select>
                                    <button type="submit" name="actualizar_ticket" class="btn-actualizar">Guardar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="text-align: center; padding: 40px;">
                <h3 style="color: #28a745;">¡Bandeja Limpia! 🎉</h3>
                <p style="color: #666;">No hay ningún ticket de soporte registrado en el sistema en este momento.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>