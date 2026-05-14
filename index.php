<?php
session_start();

// Redirección inteligente: Si ya hay una sesión activa, lo mandamos a su área de trabajo
if (isset($_SESSION['id_usuario']) && isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] === 'estudiante') {
        header("Location: estudiantes/inicio_estudiantes.php");
    } elseif ($_SESSION['rol'] === 'docente') {
        header("Location: docentes/inicio_docentes.php");
    } elseif ($_SESSION['rol'] === 'admin') {
        header("Location: admin/inicio_admin.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma Académica - Universidad Regional</title>
    
    <link rel="stylesheet" href="css/estilos_dashboard.css">
    
    <style>
        /* Estilos específicos para centrar esta pantalla de bienvenida */
        .landing-shell {
            position: relative;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 2rem;
            overflow: hidden;
            text-align: center;
        }
        /* Luces de fondo del Glassmorphism */
        .auth-bg {
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            filter: blur(30px);
            opacity: 0.5;
        }
        .auth-bg-one { top: -80px; left: -60px; background: rgba(91, 140, 255, 0.25); }
        .auth-bg-two { bottom: -100px; right: -60px; background: rgba(24, 194, 156, 0.18); }
        
        .landing-card {
            position: relative;
            z-index: 1;
            width: min(100%, 650px);
            border-radius: 28px;
            padding: 4rem 2rem;
        }
        .logo-large {
            width: 120px;
            margin-bottom: 1.5rem;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.05);
            padding: 10px;
        }
        h1 { margin: 1rem 0; font-size: clamp(2.2rem, 4vw, 3.5rem); }
        
        /* Botón gigante de llamada a la acción */
        .btn-large {
            display: inline-block;
            margin-top: 2.5rem;
            padding: 1.2rem 3rem;
            font-size: 1.15rem;
            border-radius: 999px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(91, 140, 255, 0.3);
        }
        .btn-large:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 15px 35px rgba(91, 140, 255, 0.4);
        }
    </style>
</head>
<body>

<div class="landing-shell">
    <div class="auth-bg auth-bg-one"></div>
    <div class="auth-bg auth-bg-two"></div>

    <div class="landing-card glass">
        
        <img src="img/img_02.png" alt="Logo Universidad Regional" class="logo-large" onerror="this.style.display='none'">
        
        <div>
            <div class="pill" style="margin-bottom: 1rem; border-color: rgba(24, 194, 156, 0.4); color: #18c29c; background: rgba(24, 194, 156, 0.1);">
                Sede Barberena, Santa Rosa
            </div>
        </div>
        
        <h1>Plataforma Académica</h1>
        <p class="muted" style="font-size: 1.1rem; max-width: 80%; margin: 0 auto; line-height: 1.6;">
            Bienvenido a la plataforma académica central. Ingresa para acceder a tus asignaciones, calificaciones, subir tareas y revisar tu estado de cuenta.
        </p>

        <a href="login.php" class="btn btn-primary btn-large">Iniciar Sesión</a>
        
    </div>
</div>

</body>
</html>