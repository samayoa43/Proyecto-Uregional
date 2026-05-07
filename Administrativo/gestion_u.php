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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Estados - Plataforma Académica</title>
    <link rel="stylesheet" href="estilo_administrativo.css?v=<?php echo time(); ?>">
</head>
<body>
    
    <!-- SCRIPT ANTI-PARPADEO PARA MODO OSCURO -->
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>

    <!-- Asumiendo que encabezado.php contiene tu <header> y <nav> -->
    <?php 
    $ruta_base = "../";
    require 'encabezado.php'; ?>

    <!-- CONTENEDOR PRINCIPAL -->
    <main class="main-container">
        
        <div class="section-header">
            <h2>Control de Altas y Bajas de Usuarios</h2>
        </div>
        
        <p>Utilice este panel para revocar el acceso a estudiantes retirados o docentes que ya no laboran.</p>

        <?= $mensaje ?>

        <!-- Envolvemos tu tabla intacta en una tarjeta para que se vea elegante -->
        <div class="kpi-card" style="width: 100%; overflow-x: auto; padding: 25px; box-sizing: border-box; margin-top: 20px;">
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
                                <form action="" method="POST" onsubmit="return confirm('¿Confirmar cambio de estado para este usuario?');" style="margin: 0;">
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

    </main>

        <?php require 'footer.php'; ?>

    <!-- Llamamos a tu archivo JS para que funcione el modo oscuro y el menú -->
    <script src="script_admin.js?v=<?php echo time(); ?>"></script>
</body>
</html>