<?php
require 'procesar_tickets.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Soporte Técnico y Administrativo</title>

</head>
<body>

<div class="contenedor">
    <h2 style="color: #17a2b8; border-bottom: 2px solid #ccc; padding-bottom: 10px;">🛠️ Mesa de Ayuda y Soporte</h2>

    <?php if($mensaje_exito) echo "<div class='alerta-exito'>$mensaje_exito</div>"; ?>
    <?php if($mensaje_error) echo "<div style='color:red;'>$mensaje_error</div>"; ?>

    <div class="caja" style="border-top: 4px solid #17a2b8;">
        <h3 style="margin-top:0;">Crear Nuevo Ticket</h3>
        <p style="color: #666; font-size: 14px;">Reporta problemas de plataforma, dudas con pagos o inconvenientes en aulas.</p>
        
        <form action="" method="POST">
            <label class="etiqueta">Asunto / Resumen del problema:</label>
            <input type="text" name="asunto" placeholder="Ej. Error al subir notas, Proyector dañado en aula 4..." required>

            <label class="etiqueta">Prioridad:</label>
            <select name="prioridad" required>
                <option value="Baja">Baja (Consultas generales)</option>
                <option value="Media" selected>Media (Inconvenientes menores)</option>
                <option value="Alta">Alta (Afecta mis clases o asignaciones)</option>
                <option value="Urgente">Urgente (Bloqueo total del sistema)</option>
            </select>

            <label class="etiqueta">Descripción detallada:</label>
            <textarea name="descripcion" placeholder="Explica tu problema con el mayor detalle posible..." required></textarea>

            <button type="submit" name="enviar_ticket" class="btn-enviar">Enviar Ticket de Soporte</button>
        </form>
    </div>

    <div class="caja">
        <h3 style="margin-top:0;">Mis Solicitudes</h3>
        <?php if(count($mis_tickets) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th># Ticket</th>
                        <th>Asunto</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($mis_tickets as $t): ?>
                        <tr>
                            <td><?= $t['id_ticket'] ?></td>
                            <td><?= htmlspecialchars($t['asunto']) ?></td>
                            <td><?= $t['prioridad'] ?></td>
                            <td>
                                <?php 
                                    if($t['estado'] == 'Abierto') echo "<span class='badge bg-abierto'>Abierto</span>";
                                    elseif($t['estado'] == 'En Proceso') echo "<span class='badge bg-proceso'>En Proceso</span>";
                                    else echo "<span class='badge bg-resuelto'>Resuelto</span>";
                                ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($t['fecha_creacion'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: #666;">No has creado ningún ticket de soporte.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>