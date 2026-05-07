<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Carrera - Plataforma Académica</title>
    <link rel="stylesheet" href="estilo_administrativo.css?v=<?php echo time(); ?>">
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

    <main class="main-container">
        
        <div class="section-header">
            <h2>Crear Nueva Carrera</h2>
            <p>Añade nuevos programas académicos al catálogo de la universidad.</p>
        </div>

        <div class="kpi-card form-card" style="max-width: 500px;">
            <form action="procesar_carrera.php" method="POST">

                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre de la Carrera:</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" required placeholder="Ej: Licenciatura en Sistemas">
                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn-primario">Guardar Carrera</button>
                    <a href="inicio_admin.php" class="btn-secundario">Cancelar</a>
                </div>
                
            </form>
        </div>

    </main>

    <?php require 'footer.php'; ?>

    <script src="script_admin.js?v=<?php echo time(); ?>"></script>
</body>
</html>
