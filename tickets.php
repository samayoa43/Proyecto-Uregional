<?php
require 'procesar_tickets.php'; 
    $ruta_base = ""; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte Técnico - Portal Académico</title>
    <link rel="stylesheet" href="<?= $ruta_base ?>docentes/estilos_docente.css?v=<?php echo time(); ?>">
    <style>
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
        }
        .bg-abierto { background-color: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; }
        .bg-proceso { background-color: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid #f59e0b; }
        .bg-resuelto { background-color: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid #10b981; }
    </style>
</head>
<body>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>

    <?php 
        if (isset($rol_usuario) && $rol_usuario === 'admin') {
            require 'administrativo/encabezado.php'; 
        } else {
            require 'docentes/encabezado.php'; 
        }
    ?>

    <main class="main-container">
        
        <div class="section-header">
            <h2>🛠️ Mesa de Ayuda y Soporte</h2>
            <p>Reporta problemas de plataforma, dudas con pagos o inconvenientes en aulas.</p>
        </div>

        <?php if(!empty($mensaje_exito)): ?>
            <div class='kpi-card' style="background-color: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; padding: 15px;">
                <?= $mensaje_exito ?>
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 25px; align-items: start;">
            
            <div class="kpi-card" style="border-top: 4px solid var(--color-primario);">
                <h3 style="margin-top:0; color: var(--color-primario);">Crear Nuevo Ticket</h3>
                <p style="color: var(--color-texto); opacity: 0.7; font-size: 13px; margin-bottom: 20px;">
                    Explica tu problema con el mayor detalle posible.
                </p>
                
                <form action="" method="POST">
                    <div class="form-group">
                        <label class="form-label">Asunto / Resumen:</label>
                        <input type="text" name="asunto" class="form-input" placeholder="Ej. Error al subir notas..." required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Prioridad:</label>
                        <select name="prioridad" class="form-input" required>
                            <option value="Baja">🟢 Baja (Consultas generales)</option>
                            <option value="Media" selected>🟡 Media (Inconvenientes menores)</option>
                            <option value="Alta">🟠 Alta (Afecta mis clases)</option>
                            <option value="Urgente">🔴 Urgente (Bloqueo total)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción detallada:</label>
                        <textarea name="descripcion" class="form-input" style="min-height: 120px; resize: vertical;" placeholder="Escribe aquí los detalles..." required></textarea>
                    </div>

                    <button type="submit" name="enviar_ticket" class="btn-primario" style="width: 100%;">Enviar Ticket de Soporte</button>
                </form>
            </div>

            <div class="kpi-card">
                <h3 style="margin-top:0;">Mis Solicitudes Recientes</h3>
                
                <?php if(count($mis_tickets) > 0): ?>
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Asunto</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($mis_tickets as $t): ?>
                                    <tr>
                                        <td style="font-family: monospace; font-weight: bold; color: var(--color-primario);">
                                            #<?= $t['id_ticket'] ?>
                                        </td>
                                        <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <?= htmlspecialchars($t['asunto']) ?>
                                            <br><small style="opacity: 0.6;"><?= $t['prioridad'] ?></small>
                                        </td>
                                        <td>
                                            <?php 
                                                if($t['estado'] == 'Abierto') echo "<span class='badge bg-abierto'>Abierto</span>";
                                                elseif($t['estado'] == 'En Proceso') echo "<span class='badge bg-proceso'>En Proceso</span>";
                                                else echo "<span class='badge bg-resuelto'>Resuelto</span>";
                                            ?>
                                        </td>
                                        <td style="font-size: 12px; opacity: 0.8;">
                                            <?= date('d/m/Y', strtotime($t['fecha_creacion'])) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; border: 2px dashed var(--color-borde); border-radius: 8px;">
                        <p style="color: var(--color-texto); opacity: 0.6;">No has creado ningún ticket aún.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </main>
        <script src="<?= $ruta_base ?>docentes/script_docentes.js?v=<?php echo time(); ?>"></script>
</body>
</html>