<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {

    if ($_SESSION['rol'] === 'docente') header("Location: docentes/inicio_docente.php");
    elseif ($_SESSION['rol'] === 'estudiante') header("Location: estudiantes/inicio_estudiantes.php");
    elseif ($_SESSION['rol'] === 'admin') header("Location: administrativo/inicio_admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Plataforma Académica</title>
    <link rel="stylesheet" href="estilos.css?v=<?php echo time(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<script>
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>

    <div class="caja-login">
        <h2>Portal Universitario</h2>
        <p style="color: #666; margin-bottom: 25px;">Ingresa tus credenciales para acceder</p>
        
        <?php
        if (isset($_GET['error'])) {
            if ($_GET['error'] == 'credenciales') {
                echo '<div class="mensaje-error">⚠ Correo o contraseña incorrectos.</div>';
            } elseif ($_GET['error'] == 'rol_invalido') {
                echo '<div class="mensaje-error">⚠ Error con tu rol de usuario. Contacta a soporte.</div>';
            }
        }
        ?>
        <form action="procesar_login.php" method="POST">
            
            <label class="etiqueta">Correo Electrónico:</label>
            <input type="text" name="correo" placeholder="ejemplo@universidad.edu" required>
            
            <label class="etiqueta">Contraseña:</label>
            <input type="password" name="contraseña" required>
            
            <button type="submit" class="btn-ingresar">Iniciar Sesión</button>
        </form>
    </div>

</body>
</html>