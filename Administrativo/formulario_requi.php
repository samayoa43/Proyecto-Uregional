<?php

require 'procesar_requi.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Prerrequisitos</title>

</head>
<body>
<?php
require 'encabezado.php'; 
?>
    <div class="caja">
        <h2 style="text-align: center; color: #333; margin-top:0;">Gestor de Prerrequisitos</h2>
        
        <?php if(isset($mensaje_exito)) echo "<div class='alerta-exito'>$mensaje_exito</div>"; ?>
        <?php if(isset($mensaje_error)) echo "<div class='alerta-error'>$mensaje_error</div>"; ?>

        <div class="paso-titulo">Paso 1: Selecciona la Carrera</div>
        
        <form action="" method="GET">
            <select name="id_carrera" onchange="this.form.submit()">
                <option value="" disabled <?= !$carrera_seleccionada ? 'selected' : '' ?>>-- Elige una carrera para filtrar --</option>
                <?php foreach ($lista_carreras as $carrera): ?>
                    <option value="<?= htmlspecialchars($carrera['id_carrera']) ?>" <?= ($carrera_seleccionada == $carrera['id_carrera']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($carrera['nombre_carrera']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if ($carrera_seleccionada): ?>
    <div class="caja">
        <div class="paso-titulo">Paso 2: Asignar Regla</div>
        
        <?php if (count($lista_cursos_filtrados) > 0): ?>
            
            <form action="" method="POST">

                <label class="etiqueta">Curso Principal (El que se va a bloquear):</label>
                <select name="id_curso" required>
                    <option value="" disabled selected>-- Selecciona el curso --</option>
                    <?php foreach ($lista_cursos_filtrados as $curso): ?>
                        <option value="<?= htmlspecialchars($curso['id_curso']) ?>">
                            <?= htmlspecialchars($curso['nombre_curso']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="etiqueta">Requiere haber aprobado (Prerrequisito):</label>
                <select name="id_curso_previo" required>
                    <option value="" disabled selected>-- Selecciona el prerrequisito --</option>
                    <?php foreach ($lista_cursos_filtrados as $curso): ?>
                        <option value="<?= htmlspecialchars($curso['id_curso']) ?>">
                            <?= htmlspecialchars($curso['nombre_curso']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn-guardar">Guardar Regla</button>
            </form>
        <?php else: ?>
            <p style="color: #666; text-align: center;">No hay cursos asignados al pensum de esta carrera todavía.</p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</body>
</html>