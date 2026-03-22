<!DOCTYPE html>
<!--
mismas instrucciones 
-->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Universidad Regional</title>

</head>
<body>

    <div class="caja-login">
        <h2>Portal docentes</h2>
        <p>Ingresa tus credenciales</p>
        
        <form action="proceso_login.php" method="POST">
            <label>Carnet o Correo:</label><br>
            <input type="email" name="correo" required><br><br>
            
            <label>Contraseña:</label><br>
            <input type="password" name="contraseña" required><br><br>
            
            <button type="submit" class="btn-ingresar">Ingresar como Docente</button>
        </form>

        <hr style="margin: 20px 0;">

        <a href="../login_pruebas.php" class="enlace-secundario">¿Eres Estudiante? Ingresa aquí</a>
        <a href="../Administrativo/login.php" class="enlace-secundario">Personal Administrativo</a>
    </div>

</body>
</html>