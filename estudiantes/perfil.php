<?php
require 'validar_sesion_estudiantes.php';
require '../conexion.php';

$id_usuario = $_SESSION['id_usuario'];
// Obtenemos nombres de la tabla estudiante y el correo de la tabla usuarios
$stmt = $conexion->prepare("
    SELECT e.nombres, e.apellidos, u.correo 
    FROM estudiantes e
    INNER JOIN usuarios u ON e.id_usuario = u.id_usuario
    WHERE e.id_usuario = ?
");
$stmt->execute([$id_usuario]);
$perfil = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - Plataforma Académica</title>
    <link rel="stylesheet" href="estilos_estudiantes.css">
    <link rel="icon" href="../img/img_02.png" type="image/png">
<style>
        .form-perfil { padding: 2rem; border-radius: 16px; max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        .form-control { width: 100%; padding: 10px; border-radius: 8px; box-sizing: border-box; }
        .form-control:disabled { cursor: not-allowed; opacity: 0.6; }
        .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; }
        .nota-pass { font-size: 0.85rem; margin-top: 5px; display: block; opacity: 0.8; }
    </style>
</head>
<body>
<div class="app-shell">
    <?php include 'encabezado.php'; ?>

    <main class="content">
        <section class="hero glass">
            <div>
                <span class="hero-badge">Ajustes</span>
                <h1>Mi Perfil</h1>
                <p class="muted">Actualiza tu información de contacto y credenciales de seguridad.</p>
            </div>
        </section>

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

                <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 2rem 0;">

                <h3 style="margin-top: 0; color: #fff;">Cambiar Contraseña</h3>
                <span class="nota-pass" style="margin-bottom: 1rem;">Deja estos campos en blanco si no deseas cambiar tu contraseña actual.</span>

                <div class="form-group">
                    <label>Nueva Contraseña</label>
                    <input type="password" name="nueva_password" class="form-control" placeholder="Escribe tu nueva contraseña">
                </div>

                <div class="form-group">
                    <label>Confirmar Nueva Contraseña</label>
                    <input type="password" name="confirmar_password" class="form-control" placeholder="Repite la nueva contraseña">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Guardar Cambios</button>
            </form>
        </div>
    </main>
</div>
</body>
</html>