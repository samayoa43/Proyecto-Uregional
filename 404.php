<?php
// Iniciamos la sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ruta por defecto (si nadie ha iniciado sesión o si es un visitante)
$ruta_destino = "/proyecto/login.php"; 

// Verificamos si hay alguien logueado
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['rol'])) {
    
    // Dependiendo del rol, asignamos la ruta de su respectivo panel
    switch ($_SESSION['rol']) {
        case 'admin':
            $ruta_destino = "/proyecto/administrativo/inicio_admin.php";
            break;
        case 'docente':
            // Ajusta el nombre del archivo si es diferente
            $ruta_destino = "/proyecto/docentes/inicio_docente.php"; 
            break;
        case 'alumno':
            // Ajusta el nombre del archivo si es diferente
            $ruta_destino = "index.php"; 
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - Página no encontrada</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            text-align: center;
            padding-top: 10%;
        }
        .container-404 {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 80px;
            color: #e74c3c;
            margin: 0;
        }
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            color: #666;
            margin-bottom: 30px;
        }
        .btn-inicio {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .btn-inicio:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container-404">
        <h1>404</h1>
        <h2>¡Oops! Te has perdido en el campus.</h2>
        <p>La página que estás buscando no existe, fue movida o no tienes permisos para verla.</p>
        <a href="<?php echo $ruta_destino; ?>" class="btn-inicio">Volver al Inicio</a>
    </div>
</body>
</html>