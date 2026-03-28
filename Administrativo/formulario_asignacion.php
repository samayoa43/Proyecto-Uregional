<?php
require 'asignar_docentes.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Docente a Curso</title>

</head>
<body>

<div class="formulario-caja">
    <h2>Asignar Docente a Curso</h2>
        <nav>
        <?php
        require 'encabezado.php';
        ?>
        </nav>
    
    <?= $mensaje ?>

    <form action="" method="POST">
        
        <div class="campo">
            <label for="id_docente">1. Seleccione al Catedrático:</label>
            <select name="id_docente" id="id_docente" required>
                <option value="">-- Elija un catedrático --</option>
                <?php foreach ($lista_docentes as $docente): ?>
                    <option value="<?= htmlspecialchars($docente['id_docente']) ?>">
                        <?= htmlspecialchars($docente['apellidos'] . ", " . $docente['nombres']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="campo">
            <label for="id_curso">2. Seleccione el Curso:</label>
            <select name="id_curso" id="id_curso" required>
                <option value="">-- Elija un curso --</option>
                <?php foreach ($lista_cursos as $curso): ?>
                    <option value="<?= htmlspecialchars($curso['id_curso']) ?>">
                        <?= htmlspecialchars($curso['nombre_curso']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn-guardar">Guardar Asignación</button>
    </form>
</div>

</body>
</html>