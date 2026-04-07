<?php
require 'procesa_gestion_u.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Estados de Usuario</title>

</head>
<body>
    
        <?php
        require 'encabezado.php';
        ?>

<div class="contenedor">
    <h2>Control de Altas y Bajas de Usuarios</h2>
    <p>Utilice este panel para revocar el acceso a estudiantes retirados o docentes que ya no laboran.</p>

    <?= $mensaje ?>

    <table>
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Correo Electrónico</th>
                <th>Rol</th>
                <th>Estado Actual</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['nombre']) ?></td>
                    <td><?= htmlspecialchars($u['correo']) ?></td>
                    <td><strong><?= htmlspecialchars($u['nombre_rol']) ?></strong></td>
                    <td>
                        <?php if($u['estado'] == 1): ?>
                            <span class="badge activo">ACTIVO (Con Acceso)</span>
                        <?php else: ?>
                            <span class="badge inactivo">INACTIVO (Bloqueado)</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form action="" method="POST" onsubmit="return confirm('¿Confirmar cambio de estado para este usuario?');">
                            <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                            
                            <?php if($u['estado'] == 1): ?>
                                <input type="hidden" name="nuevo_estado" value="0">
                                <button type="submit" name="cambiar_estado" class="btn-toggle btn-dar-baja">Dar de Baja</button>
                            <?php else: ?>
                                <input type="hidden" name="nuevo_estado" value="1">
                                <button type="submit" name="cambiar_estado" class="btn-toggle btn-dar-alta">Reactivar Acceso</button>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>