<?php
require 'procesar_ciclo.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Ciclos Académicos</title>

</head>
<body>
        <?php
        require 'encabezado.php';
        ?>

<div class="contenedor">
    <h2 style="color: #333; border-bottom: 2px solid #ccc; padding-bottom: 10px;">Control de Ciclos Académicos</h2>

    <?php if($mensaje_exito) echo "<div class='alerta-exito'>$mensaje_exito</div>"; ?>
    <?php if($mensaje_error) echo "<div class='alerta-error'>$mensaje_error</div>"; ?>

    <div class="caja" style="border-top: 4px solid #0056b3;">
        <h3 style="margin-top:0; color:#0056b3;">Aperturar Nuevo Semestre</h3>
        <form action="" method="POST">
            <label class="etiqueta">Nombre del Ciclo:</label>
            <input type="text" name="nombre_ciclo" placeholder="Ej. Primer Semestre 2026" required>
            <button type="submit" name="crear_ciclo" class="btn-crear">Crear Ciclo Académico</button>
        </form>
    </div>

    <div class="caja">
        <h3 style="margin-top:0; color:#333;">Historial de Ciclos</h3>
        
        <?php if (count($lista_ciclos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ciclo Académico</th>
                        <th>Estado del Semestre</th>
                        <th>Periodo de Asignaciones</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_ciclos as $ciclo): ?>
                        <tr>
                            <td><?= $ciclo['id_ciclo'] ?></td>
                            <td><strong><?= htmlspecialchars($ciclo['nombre_ciclo']) ?></strong></td>
                            
                            <td>
                                <?php if($ciclo['estado'] == 'Activo'): ?>
                                    <span class="estado-activo">Activo</span>
                                <?php else: ?>
                                    <span class="estado-cerrado">Cerrado</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if($ciclo['estado'] == 'Activo'): ?>
                                    <?php if($ciclo['asignaciones_abiertas'] == 1): ?>
                                        <a href="?accion=toggle_asignacion&id=<?= $ciclo['id_ciclo'] ?>&estado_actual=1" class="btn-switch-on">ABIERTAS (Click para cerrar)</a>
                                    <?php else: ?>
                                        <a href="?accion=toggle_asignacion&id=<?= $ciclo['id_ciclo'] ?>&estado_actual=0" class="btn-switch-off">CERRADAS (Click para abrir)</a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span style="color: #888; font-size: 13px;">No disponible (Semestre cerrado)</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if($ciclo['estado'] == 'Activo'): ?>
                                    <a href="?accion=cerrar_ciclo&id=<?= $ciclo['id_ciclo'] ?>" class="btn-cerrar-ciclo" onclick="return confirm('¿Estás seguro de cerrar este semestre? Las notas quedarán congeladas y no se podrá reabrir.');">Terminar Semestre</a>
                                <?php else: ?>
                                    <span style="color: #aaa;">Sin acciones</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: #666; text-align: center;">No hay ciclos registrados. Crea el primer semestre arriba.</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>