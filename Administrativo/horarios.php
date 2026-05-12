<?php

require_once __DIR__ . '/validar_sesion_admin.php'; 

require 'procesar_horario.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Horarios - Plataforma Académica</title>
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
            <h2>Configuración de Horarios</h2>
            <p>Establece los días y horas específicas para las clases ya asignadas a los docentes.</p>
        </div>

        <?= $mensaje ?? '' ?>

        <div class="kpi-card form-card" style="max-width: 750px;">
            <form action="" method="POST">
                
                <div class="form-group">
                    <label class="form-label">1. Seleccione la Clase (Curso + Catedrático):</label>
                    <select name="id_asignacion" class="form-input" required>
                        <option value="" disabled selected>-- Elija la clase a programar --</option>
                        <?php foreach ($lista_asignaciones as $clase): ?>
                            <option value="<?= htmlspecialchars($clase['id_asignacion']) ?>">
                                <?= htmlspecialchars($clase['nombre_curso'] . " | Prof. " . $clase['apellidos']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 25px 0;">

                <div class="form-group">
                    <label class="form-label">2. Defina los días y horas:</label>
                    
                    <div id="contenedor_horarios">
                        <div class="fila-horario" style="display: flex; gap: 10px; margin-bottom: 15px; align-items: center;">
                            <select name="dias[]" class="form-input" required style="flex: 2;">
                                <option value="" disabled selected>Día...</option>
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miércoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sábado">Sábado</option>
                                <option value="Domingo">Domingo</option>
                            </select>
                            
                            <input type="time" name="horas_inicio[]" class="form-input" required title="Hora de inicio" style="flex: 1;">
                            <span style="color: #64748b; font-weight: bold;">a</span>
                            <input type="time" name="horas_fin[]" class="form-input" required title="Hora de fin" style="flex: 1;">

                            <div style="width: 35px;"></div>
                        </div>
                    </div>

                    <button type="button" class="btn-secundario" onclick="agregarFila()" style="margin-top: 5px; font-size: 13px; padding: 6px 12px;">
                        + Agregar otro día
                    </button>
                </div>

                <div class="form-acciones" style="margin-top: 35px;">
                    <button type="submit" class="btn-primario">Guardar Horario Completo</button>
                    <a href="inicio_admin.php" class="btn-secundario">Cancelar</a>
                </div>
            </form>
        </div>

    </main>

    <?php require 'footer.php'; ?>

    <script src="script_admin.js?v=<?php echo time(); ?>"></script>

    <script>
        function agregarFila() {
            const contenedor = document.getElementById('contenedor_horarios');
            const nuevaFila = document.createElement('div');

            nuevaFila.className = 'fila-horario';
            nuevaFila.style.cssText = 'display: flex; gap: 10px; margin-bottom: 15px; align-items: center;';

            nuevaFila.innerHTML = `
                <select name="dias[]" class="form-input" required style="flex: 2;">
                    <option value="" disabled selected>Día...</option>
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miércoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo">Domingo</option>
                </select>
                <input type="time" name="horas_inicio[]" class="form-input" required title="Hora de inicio" style="flex: 1;">
                <span style="color: #64748b; font-weight: bold;">a</span>
                <input type="time" name="horas_fin[]" class="form-input" required title="Hora de fin" style="flex: 1;">
                
                <!-- Botón rojo para eliminar la fila extra -->
                <button type="button" onclick="eliminarFila(this)" style="width: 35px; height: 35px; background-color: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#dc2626'" onmouseout="this.style.backgroundColor='#ef4444'">X</button>
            `;
            
            contenedor.appendChild(nuevaFila);
        }

        function eliminarFila(boton) {
            boton.parentElement.remove();
        }
    </script>

</body>
</html>