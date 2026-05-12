<?php

require_once __DIR__ . '/validar_sesion_admin.php'; 

require 'asignar_docentes.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Docente a Curso - Plataforma Académica</title>
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
            <h2>Asignar Docente a Curso</h2>
            <p>Gestiona la carga académica vinculando catedráticos con los cursos correspondientes.</p>
        </div>

        <?= $mensaje ?>

        <div class="kpi-card form-card">
            <form action="" method="POST">
                
                <div class="form-group">
                    <label for="id_docente" class="form-label">1. Seleccione al Catedrático:</label>
                    <select name="id_docente" id="id_docente" class="form-input" required>
                        <option value="" disabled selected>-- Elija un catedrático --</option>
                        <?php foreach ($lista_docentes as $docente): ?>
                            <option value="<?= htmlspecialchars($docente['id_docente']) ?>">
                                <?= htmlspecialchars($docente['apellidos'] . ", " . $docente['nombres']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_curso" class="form-label">2. Seleccione el Curso:</label>
                    <select name="id_curso" id="id_curso" class="form-input" required>
                        <option value="" disabled selected>-- Elija un curso --</option>
                        <?php foreach ($lista_cursos as $curso): ?>
                            <option value="<?= htmlspecialchars($curso['id_curso']) ?>">
                                <?= htmlspecialchars($curso['nombre_curso']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn-primario">Guardar Asignación</button>
                    <a href="inicio_admin.php" class="btn-secundario">Cancelar</a>
                </div>

            </form>
        </div>

    </main>

    <?php require 'footer.php'; ?>

    <script src="script_admin.js?v=<?php echo time(); ?>"></script>
</body>
</html>