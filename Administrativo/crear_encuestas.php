<?php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== "admin") {
    header("Location: login.php?error=acceso_denegado");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Encuesta - Plataforma Académica</title>
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
            <h2>Diseñador de Encuestas</h2>
            <p>Configura los detalles generales antes de proceder a agregar las preguntas.</p>
        </div>

        <div class="kpi-card form-card">
            <form action="procesar_encuestas.php" method="POST">
                
                <div class="form-group">
                    <label for="titulo" class="form-label">Título de la Encuesta:</label>
                    <input type="text" id="titulo" name="titulo" class="form-input" required placeholder="Ej: Evaluación de Instalaciones o Clima Laboral">
                </div>
                
                <div class="form-group">
                    <label for="descripcion" class="form-label">Descripción / Instrucciones:</label>
                    <textarea id="descripcion" name="descripcion" class="form-input textarea-resize" rows="4" required placeholder="Explica brevemente el propósito de esta encuesta para los participantes..."></textarea>
                </div>
                
                <div class="form-acciones">
                    <button type="submit" class="btn-primario">Guardar y Continuar</button>
                    <a href="inicio_admin.php" class="btn-secundario">Cancelar</a>
                </div>

            </form>
        </div>

    </main>
        <?php require 'footer.php'; ?>

    <script src="script_admin.js?v=<?php echo time(); ?>"></script>
</body>
</html>