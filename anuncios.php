<?php
require 'procesar_anuncio.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Nuevo Anuncio - Plataforma Académica</title>
    <link rel="stylesheet" href="administrativo/estilo_administrativo.css?v=<?php echo time(); ?>">
</head>
<body>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>

    <?php 
        $ruta_base = ""; 

        if (isset($rol_usuario) && $rol_usuario === 'admin') {
            require 'administrativo/encabezado.php'; 
        } else {
            require 'docentes/encabezado.php'; 
        }
    ?>

    <main class="main-container">
        
        <div class="section-header">
            <h2>Redactar Aviso Oficial</h2>
            <p>Este mensaje aparecerá de forma destacada en el panel de inicio de los usuarios seleccionados.</p>
        </div>

        <?php if(!empty($mensaje_exito)): ?>
            <div class='alerta alerta-exito'><?= $mensaje_exito ?></div>
        <?php endif; ?>
        <?php if(!empty($mensaje_error)): ?>
            <div class='alerta alerta-error'><?= $mensaje_error ?></div>
        <?php endif; ?>

        <div class="kpi-card form-card" style="max-width: 700px; margin: 0 auto; border-top: 4px solid #f59e0b;">
            
            <form action="" method="POST">
                
                <div class="form-group">
                    <label class="form-label">Público Objetivo (¿Quién verá esto?):</label>
                    <select name="audiencia" class="form-input" required>
                        <?php if ($rol_usuario === 'admin'): ?>
                            <option value="" disabled selected>-- Seleccione a quién va dirigido --</option>
                            <option value="Todos">Toda la Universidad (Todos)</option>
                            <option value="Docentes">Solo Catedráticos (Docentes)</option>
                            <option value="Estudiantes">Solo Alumnado (Estudiantes)</option>
                        <?php else: ?>
                            <option value="Estudiantes" selected>Mis Estudiantes</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Título del Anuncio:</label>
                    <input type="text" name="titulo" class="form-input" placeholder="Ej. Suspensión de clases por asueto..." maxlength="100" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Mensaje / Detalle:</label>
                    <textarea name="mensaje" class="form-input" style="min-height: 150px; resize: vertical;" placeholder="Escriba aquí los detalles del aviso..." required></textarea>
                </div>

                <div class="form-acciones">
                    <button type="submit" name="publicar_anuncio" class="btn-primario" style="background-color: #f59e0b; border: none;">Publicar Anuncio</button>

                    <button type="button" onclick="javascript:history.back()" class="btn-secundario">Cancelar</button>
                </div>

            </form>
        </div>

    </main>

    <?php require 'administrativo/footer.php'; ?>

    <script src="administrativo/script_admin.js?v=<?php echo time(); ?>"></script>
</body>
</html>