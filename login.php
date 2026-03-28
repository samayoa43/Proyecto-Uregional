<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {

    if ($_SESSION['rol'] === 'Docente') header("Location: index_docente.php");
    elseif ($_SESSION['rol'] === 'Estudiante') header("Location: index_alumno.php");
    elseif ($_SESSION['rol'] === 'Administrador') header("Location: index_admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Plataforma Académica</title>

</head>
<body>

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