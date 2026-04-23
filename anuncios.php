<?php
require 'procesar_anuncio.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Publicar Nuevo Anuncio</title>

</head>
<body>


<div class="contenedor">
    <div class="caja">
        <h2 style="color: #ff9800; margin-top:0; text-align: center;">Redactar Aviso Oficial</h2>
        <p style="text-align: center; color: #666; font-size: 14px;">Este mensaje aparecerá en el panel de inicio de los usuarios seleccionados.</p>

        <?php if($mensaje_exito) echo "<div class='alerta-exito'>$mensaje_exito</div>"; ?>
        <?php if($mensaje_error) echo "<div class='alerta-error'>$mensaje_error</div>"; ?>

        <form action="" method="POST">
            
            <label class="etiqueta">Público Objetivo (¿Quién verá esto?):</label>
            <select name="audiencia" required>
                <?php if ($rol_usuario === 'admin'): ?>;
                    <option value="" disabled selected>-- Seleccione a quién va dirigido --</option>
                    <option value="Todos">Toda la Universidad (Todos)</option>
                    <option value="Docentes">Solo Catedráticos (Docentes)</option>
                    <option value="Estudiantes">Solo Alumnado (Estudiantes)</option>
                <?php else: ?>
                    <option value="Estudiantes" selected>Solo Alumnado (Estudiantes)</option>
                <?php endif; ?>
            </select>

            <label class="etiqueta">Título del Anuncio:</label>
            <input type="text" name="titulo" placeholder="Ej. Suspensión de clases por asueto, Recordatorio de Exámenes..." maxlength="100" required>

            <label class="etiqueta">Mensaje / Detalle:</label>
            <textarea name="mensaje" placeholder="Escriba aquí los detalles del aviso..." required></textarea>

            <button type="submit" name="publicar_anuncio" class="btn-publicar">Publicar Anuncio Ahora</button>
        </form>
    </div>
</div>

</body>
</html>