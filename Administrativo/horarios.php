<?php
require 'procesar_horario.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Horarios</title>
</head>
<?php
require 'encabezado.php';
?>
<body>

<div class="formulario-caja">
    <h2>Configuración de Horarios</h2>
    
    <?= $mensaje ?>

    <form action="" method="POST">
        
        <label><strong>1. Seleccione la Clase (Curso + Catedrático):</strong></label>
        <select name="id_asignacion" class="select-clase" required>
            <option value="">-- Elija la clase a programar --</option>
            <?php foreach ($lista_asignaciones as $clase): ?>
                <option value="<?= htmlspecialchars($clase['id_asignacion']) ?>">
                    <?= htmlspecialchars($clase['nombre_curso'] . " | Prof. " . $clase['apellidos']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <hr>

        <label><strong>2. Defina los días y horas:</strong></label><br><br>
        
        <div id="contenedor_horarios">
            <div class="fila-horario">
                <select name="dias[]" required>
                    <option value="">Día...</option>
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miércoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo">Domingo</option>
                </select>
                
                <input type="time" name="horas_inicio[]" required title="Hora de inicio">
                <input type="time" name="horas_fin[]" required title="Hora de fin">
            </div>
        </div>

        <button type="button" class="btn-agregar" onclick="agregarFila()">+ Agregar otro día</button>

        <button type="submit" class="btn-guardar">Guardar Horario Completo</button>
    </form>
</div>

<script>
    function agregarFila() {
        const contenedor = document.getElementById('contenedor_horarios');
        const nuevaFila = document.createElement('div');
        nuevaFila.className = 'fila-horario';
        
        nuevaFila.innerHTML = `
            <select name="dias[]" required>
                <option value="">Día...</option>
                <option value="Lunes">Lunes</option>
                <option value="Martes">Martes</option>
                <option value="Miércoles">Miércoles</option>
                <option value="Jueves">Jueves</option>
                <option value="Viernes">Viernes</option>
                <option value="Sábado">Sábado</option>
                <option value="Domingo">Domingo</option>
            </select>
            <input type="time" name="horas_inicio[]" required>
            <input type="time" name="horas_fin[]" required>
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