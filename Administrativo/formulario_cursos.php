<?php
require 'procesar_cursos.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Múltiples Cursos - Plataforma Académica</title>
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
            <h2>Registro Masivo de Cursos</h2>
            <p>Agrega múltiples materias al pensum de una carrera en un solo paso.</p>
        </div>

        <?= $mensaje ?? '' ?>

        <div class="kpi-card form-card" style="max-width: 750px;">
            <form action="" method="POST">
                
                <div class="form-group">
                    <label for="id_carrera" class="form-label">1. Seleccione la Carrera:</label>
                    <select name="id_carrera" id="id_carrera" class="form-input" required>
                        <option value="" disabled selected>-- Elija una carrera --</option>
                        <?php foreach ($lista_carreras as $carrera): ?>
                            <option value="<?= htmlspecialchars($carrera['id_carrera']) ?>">
                                <?= htmlspecialchars($carrera['nombre_carrera']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 25px 0;">

                <div class="form-group">
                    <label class="form-label">2. Ingrese los cursos y su semestre correspondiente:</label>
                    
                    <div id="contenedor_cursos">
                        <div class="fila-curso" style="display: flex; gap: 10px; margin-bottom: 15px; align-items: center;">
                            
                            <input type="text" name="nombres_cursos[]" class="form-input" placeholder="Ej. Programación 1" required style="flex: 2;">
                            
                            <input type="number" name="semestres_cursos[]" class="form-input" placeholder="Semestre (Ej. 1)" min="1" max="10" required style="flex: 1;">

                            <div style="width: 35px;"></div>
                        </div>
                    </div>

                    <button type="button" class="btn-secundario" onclick="agregarFila()" style="margin-top: 5px; font-size: 13px; padding: 6px 12px;">
                        + Agregar otro curso
                    </button>
                </div>

                <div class="form-acciones" style="margin-top: 35px;">
                    <button type="submit" class="btn-primario">Guardar Todos los Cursos</button>
                    <a href="inicio_admin.php" class="btn-secundario">Cancelar</a>
                </div>
            </form>
        </div>

    </main>

    <?php require 'footer.php'; ?>

    <script src="script_admin.js?v=<?php echo time(); ?>"></script>

    <script>
        function agregarFila() {
            const contenedor = document.getElementById('contenedor_cursos');
            const nuevaFila = document.createElement('div');

            nuevaFila.className = 'fila-curso';
            nuevaFila.style.cssText = 'display: flex; gap: 10px; margin-bottom: 15px; align-items: center;';

            nuevaFila.innerHTML = `
                <input type="text" name="nombres_cursos[]" class="form-input" placeholder="Nombre del curso" required style="flex: 2;">
                
                <input type="number" name="semestres_cursos[]" class="form-input" placeholder="Semestre (Ej. 1)" min="1" max="10" required style="flex: 1;">
                
                <!-- Botón rojo dinámico para eliminar la fila extra -->
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