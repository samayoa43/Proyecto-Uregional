<?php
require_once __DIR__ . '/validar_sesion_docentes.php';
require '../conexion.php';

$id_usuario = $_SESSION['id_usuario'];
$stmt = $conexion->prepare("
    SELECT d.nombres, d.apellidos, u.correo 
    FROM docentes d
    INNER JOIN usuarios u ON d.id_usuario = u.id_usuario
    WHERE d.id_usuario = ?
");
$stmt->execute([$id_usuario]);
$perfil = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Plataforma Académica</title>
    <link rel="stylesheet" href="estilos_docente.css?v=<?php echo time(); ?>">
    <style>
        .form-perfil { padding: 1rem; max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        .form-control { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; background: #fff; color: #333; box-sizing: border-box; }
        .form-control:disabled { opacity: 0.6; cursor: not-allowed; }
        
        .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; }
        .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #047857; }
        .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #b91c1c; }
        
        .nota-pass { font-size: 0.85rem; margin-top: 5px; display: block; opacity: 0.8; }
        .btn-submit { width: 100%; padding: 12px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: background 0.3s; }
        .btn-submit:hover { background: #2563eb; }

        /* Adaptación automática al modo oscuro del docente */
        body.dark-mode .form-control { background: #1e293b; border-color: #334155; color: #f8fafc; }
        body.dark-mode .alert-success { color: #34d399; }
        body.dark-mode .alert-error { color: #f87171; }
    </style>
</head>
<body>
    
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>

    <?php 
        $ruta_base = "../"; 
        require 'encabezado.php'; 
    ?>

    <main class="main-container">
        
        <div class="section-header">
            <h2>Mi Perfil</h2>
            <p>Actualiza tu información de contacto y credenciales de seguridad.</p>
        </div>

        <?php if (isset($_GET['exito'])): ?>
            <div class="alert alert-success">¡Tus datos han sido actualizados correctamente!</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <?php if ($_GET['error'] == 'correo_duplicado'): ?>
                <div class="alert alert-error">Ese correo electrónico ya está registrado en otra cuenta.</div>
            <?php elseif ($_GET['error'] == 'password_mismatch'): ?>
                <div class="alert alert-error">Las contraseñas nuevas no coinciden. Inténtalo de nuevo.</div>
            <?php else: ?>
                <div class="alert alert-error">Ocurrió un error en el servidor. Inténtalo más tarde.</div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="kpi-card" style="border-top: 4px solid #3b82f6;">
            <div class="form-perfil">
                <form action="../procesar_perfil.php" method="POST">
                    
                    <div class="form-group">
                        <label>Nombre Completo</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($perfil['nombres'] . ' ' . $perfil['apellidos']); ?>" disabled>
                        <span class="nota-pass">Si necesitas cambiar tu nombre legal, contacta a la administración.</span>
                    </div>

                    <div class="form-group">
                        <label>Correo Electrónico</label>
                        <input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($perfil['correo']); ?>" required>
                    </div>

                    <hr style="border: 0; border-top: 1px solid rgba(150, 150, 150, 0.2); margin: 2rem 0;">

                    <h3 style="margin-top: 0;">Cambiar Contraseña</h3>
                    <span class="nota-pass" style="margin-bottom: 1rem;">Deja estos campos en blanco si no deseas cambiar tu contraseña actual.</span>

                    <div class="form-group">
                        <label>Nueva Contraseña</label>
                        <input type="password" name="nueva_password" class="form-control" placeholder="Escribe tu nueva contraseña">
                    </div>

                    <div class="form-group">
                        <label>Confirmar Nueva Contraseña</label>
                        <input type="password" name="confirmar_password" class="form-control" placeholder="Repite la nueva contraseña">
                    </div>

                    <button type="submit" class="btn-submit">Guardar Cambios</button>
                </form>
            </div>
        </div>

    </main>

    <?php require 'footer.php'; ?>

    <script src="<?= $ruta_base ?>docentes/script_docentes.js?v=<?php echo time(); ?>"></script>

</body>
</html>