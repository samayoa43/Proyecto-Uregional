<?php

require_once __DIR__ . '/validar_sesion_admin.php'; 

require 'procesar_tickets.php'; // Carga las funciones para obtener los KPIs

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro de Gestión de Soporte - Plataforma Académica</title>
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
            <h2>Bandeja de Mesa de Ayuda (Helpdesk)</h2>
            <p>Administra y da seguimiento a los reportes de estudiantes y docentes.</p>
        </div>

        <?php if(!empty($mensaje_exito)) echo "<div class='alerta alerta-exito'>$mensaje_exito</div>"; ?>
        <?php if(!empty($mensaje_error)) echo "<div class='alerta alerta-error'>$mensaje_error</div>"; ?>

        <div class="kpi-card" style="width: 100%; overflow-x: auto; padding: 25px; box-sizing: border-box; margin-top: 20px;">
            <?php if(count($lista_tickets) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th style="width: 200px;">Solicitante</th>
                            <th>Detalle del Problema</th>
                            <th style="width: 100px;">Prioridad</th>
                            <th style="width: 120px;">Estado Actual</th>
                            <th style="width: 200px;">Acción / Respuesta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lista_tickets as $t): ?>
                            <!-- Si está resuelto, le asignamos una clase para opacarlo -->
                            <tr class="<?= ($t['estado'] == 'Resuelto') ? 'fila-resuelta' : '' ?>">
                                
                                <td>
                                    <strong>#<?= $t['id_ticket'] ?></strong><br>
                                    <span class="fecha-ticket"><?= date('d/m/y', strtotime($t['fecha_creacion'])) ?></span>
                                </td>
                                
                                <td>
                                    <strong><?= htmlspecialchars($t['nombre_solicitante']) ?></strong><br>
                                    <span class="rol-solicitante"><?= htmlspecialchars($t['nombre_rol']) ?></span><br>
                                    <small class="correo-solicitante"><?= htmlspecialchars($t['correo']) ?></small>
                                </td>
                                
                                <td>
                                    <strong><?= htmlspecialchars($t['asunto']) ?></strong>
                                    <div class="detalle-desc">
                                        <?= nl2br(htmlspecialchars($t['descripcion'])) ?>
                                    </div>
                                </td>
                                
                                <td>
                                    <!-- Inserta la prioridad (Alta, Media, Baja) a la clase -->
                                    <span class="badge prio-<?= $t['prioridad'] ?>"><?= $t['prioridad'] ?></span>
                                </td>
                                
                                <td>
                                    <!-- Crea clases est-Abierto, est-En, est-Resuelto -->
                                    <?php $clase_estado = "est-" . explode(' ', $t['estado'])[0]; ?>
                                    <span class="indicador-estado <?= $clase_estado ?>">● <?= $t['estado'] ?></span>
                                </td>
                                
                                <td>
                                    <form action="" method="POST" class="form-inline">
                                        <input type="hidden" name="id_ticket" value="<?= $t['id_ticket'] ?>">
                                        <select name="nuevo_estado" class="select-estado" required>
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
                <div class="bandeja-vacia">
                    <h3>¡Bandeja Limpia! 🎉</h3>
                    <p>No hay ningún ticket de soporte registrado en el sistema en este momento.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

        <?php require 'footer.php'; ?>

    <script src="script_admin.js?v=<?php echo time(); ?>"></script>
</body>
</html>