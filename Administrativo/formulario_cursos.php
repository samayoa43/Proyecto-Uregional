<?php
require 'procesar_cursos.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Múltiples Cursos</title>

</head>
<body>

<div class="formulario-caja">
    <h2>Registro Masivo de Cursos</h2>
<?php
require 'encabezado.php';
?>
    
    <?= $mensaje ?>
    <br>

    <form action="" method="POST">
        
        <label for="id_carrera"><strong>1. Seleccione la Carrera:</strong></label>
        <select name="id_carrera" id="id_carrera" required>
            <option value="">-- Elija una carrera --</option>
            <?php foreach ($lista_carreras as $carrera): ?>
                <option value="<?= htmlspecialchars($carrera['id_carrera']) ?>">
                    <?= htmlspecialchars($carrera['nombre_carrera']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <hr>

        <label><strong>2. Ingrese los cursos:</strong></label>
        <br><br>
        
        <div id="contenedor_cursos">
            <div class="fila-curso">
                <input type="text" name="nombres_cursos[]" placeholder="Ej. Programación 1" required>
                <input type="number" name="semestres_cursos[]" placeholder="Semestre (Ej. 1)" min="1" max="10" style="width: 150px;" required>
                </div>
        </div>
        <button type="button" class="btn-agregar" onclick="agregarFila()">+ Agregar otro curso</button>
        <button type="submit" class="btn-guardar">Guardar Todos los Cursos</button>
    </form>
</div>

<script>
    function agregarFila() {
        const contenedor = document.getElementById('contenedor_cursos');
        const nuevaFila = document.createElement('div');
        nuevaFila.className = 'fila-curso';
        nuevaFila.innerHTML = `
            <input type="text" name="nombres_cursos[]" placeholder="Nombre del curso" required>
            <input type="number" name="semestres_cursos[]" placeholder="Semestre" min="1" max ="10" style="width: 150px;" required>
            <button type="button" class="btn-eliminar" onclick="eliminarFila(this)">X</button>
        `;

        contenedor.appendChild(nuevaFila);
    }

    function eliminarFila(boton) {
        boton.parentElement.remove();
    }
</script>

</body>
</html>