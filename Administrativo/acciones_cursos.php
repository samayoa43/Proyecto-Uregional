<?php 
require_once __DIR__ . '/validar_sesion_admin.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plataforma Académica</title>
    <link rel="stylesheet" href="estilo_administrativo.css?v=<?php echo time(); ?>">
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

    <nav class="side-menu" id="sideMenu">
        <div class="menu-content">
            <?php require 'encabezado.php'; ?>
        </div>
    </nav>

    <main class="main-container" style="padding: 30px;">
            <div class="section-header">
                <h2>Gestión de Cursos</h2>
                <p>Administra la información de cursos, carreras y ciclos académicos</p>
            </div>
        <div class="container_links">
            <a href="formulario_requi.php">Prerrequisitos</a>
            <a href="carreras.php">Carreras</a>
            <a href="formulario_cursos.php">Cursos</a>
            <a href="ciclo_aca.php">Ciclos Académicos</a>
        </div>
    </main>

        <?php require 'footer.php'; ?>

<script src="script_admin.js?v=<?php echo time(); ?>"></script>
</body>
</html>