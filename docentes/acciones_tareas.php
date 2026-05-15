<?php 
require_once __DIR__ . '/validar_sesion_docentes.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plataforma Académica</title>
    <link rel="stylesheet" href="estilos_docente.css?v=<?php echo time(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body> 
        <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>

<?php 
$ruta_base = "../";
require 'encabezado.php'; ?>

    <main class="main-container" style="padding: 30px;">
            <div class="section-header">
                <h2>Gestión de Tareas</h2>
                <p>Administra las tareas asignadas a los estudiantes</p>
            </div>
        <div class="container_links">
        <a href="crear_tareas.php">Crear Tareas</a>
        <a href="lista_tareas.php">Calificar Tareas</a>
        </div>
    </main>
    <?php require 'footer.php'; ?>

    <script src="<?= $ruta_base ?>docentes/script_docentes.js?v=<?php echo time(); ?>"></script>
</body>
</html>