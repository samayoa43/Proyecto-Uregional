<?php

require 'procesar_requi.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Prerrequisitos - Plataforma Académica</title>
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
            <h2>Gestor de Prerrequisitos</h2>
            <p>Configura las reglas de correlatividad de los cursos (pensum) de cada carrera.</p>
        </div>

        <?php if(isset($mensaje_exito)): ?>
            <div class="alerta alerta-exito"><?= $mensaje_exito ?></div>
        <?php endif; ?>
        
        <?php if(isset($mensaje_error)): ?>
            <div class="alerta alerta-error"><?= $mensaje_error ?></div>
        <?php endif; ?>

        <div class="kpi-card form-card" style="margin-bottom: 25px; max-width: 700px;">
            <h3 style="margin-top: 0; color: #0078d4; margin-bottom: 15px;">Paso 1: Selecciona la Carrera</h3>
            
            <form action="" method="GET">
                <div class="form-group" style="margin-bottom: 0;">
                    <select name="id_carrera" class="form-input" onchange="this.form.submit()">
                        <option value="" disabled <?= !$carrera_seleccionada ? 'selected' : '' ?>>-- Elige una carrera para filtrar el pensum --</option>
                        <?php foreach ($lista_carreras as $carrera): ?>
                            <option value="<?= htmlspecialchars($carrera['id_carrera']) ?>" <?= ($carrera_seleccionada == $carrera['id_carrera']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($carrera['nombre_carrera']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>

        <?php if ($carrera_seleccionada): ?>
        <div class="kpi-card form-card" style="max-width: 700px;">
            <h3 style="margin-top: 0; color: #0078d4; margin-bottom: 15px;">Paso 2: Asignar Regla de Prerrequisito</h3>
            
            <?php if (count($lista_cursos_filtrados) > 0): ?>
                <form action="" method="POST">

                    <div class="form-group">
                        <label class="form-label">Curso Principal (El que se va a bloquear):</label>
                        <select name="id_curso" class="form-input" required>
                            <option value="" disabled selected>-- Selecciona el curso --</option>
                            <?php foreach ($lista_cursos_filtrados as $curso): ?>
                                <option value="<?= htmlspecialchars($curso['id_curso']) ?>">
                                    <?= htmlspecialchars($curso['nombre_curso']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Requiere haber aprobado (Prerrequisito):</label>
                        <select name="id_curso_previo" class="form-input" required>
                            <option value="" disabled selected>-- Selecciona el prerrequisito --</option>
                            <?php foreach ($lista_cursos_filtrados as $curso): ?>
                                <option value="<?= htmlspecialchars($curso['id_curso']) ?>">
                                    <?= htmlspecialchars($curso['nombre_curso']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-acciones">
                        <button type="submit" class="btn-primario">Guardar Regla</button>
                        <a href="inicio_admin.php" class="btn-secundario">Cancelar</a>
                    </div>
                </form>
            <?php else: ?>
                <div class="reporte-vacio">
                    <h3 style="color: #64748b;">Sin cursos disponibles</h3>
                    <p>No hay cursos asignados al pensum de esta carrera todavía.</p>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </main>

    <?php require 'footer.php'; ?>

    <script src="script_admin.js?v=<?php echo time(); ?>"></script>
</body>
</html>