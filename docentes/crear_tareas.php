<?php
require_once 'validar_sesion_docentes.php';
require_once '../conexion.php';

$id_docente = $_SESSION['id_docente'];

// Obtenemos los cursos asignados al docente para llenar el select
$sql_cursos = "SELECT c.id_curso, c.nombre_curso 
               FROM cursos c
               INNER JOIN asignaciones_docentes ad ON c.id_curso = ad.id_curso
               WHERE ad.id_docente = ?";
$stmt = $conexion->prepare($sql_cursos);
$stmt->execute([$id_docente]);
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Tarea | Panel Docente</title>
    <link rel="stylesheet" href="estilos_docente.css?v=<?php echo time(); ?>">
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
            <h2>Asignar Nueva Tarea</h2>
            <p style="color: #64748b; margin-top: 5px;">Completa los campos para publicar una actividad a tus estudiantes.</p>
        </div>

        <div class="form-card">
            <form action="procesar_tarea.php" method="POST">
                
                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label for="id_curso">Seleccionar Curso <span class="text-danger">*</span></label>
                        <select name="id_curso" id="id_curso" class="form-control" required>
                            <option value="" disabled selected>Elige un curso de tu carga académica...</option>
                            <?php if(!empty($cursos)): ?>
                                <?php foreach($cursos as $curso): ?>
                                    <option value="<?= $curso['id_curso'] ?>"><?= htmlspecialchars($curso['nombre_curso']) ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No tienes cursos asignados.</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label for="fecha_limite">Fecha y Hora Límite <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="fecha_limite" id="fecha_limite" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="titulo">Título de la Tarea <span class="text-danger">*</span></label>
                    <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Ej: Ensayo sobre el impacto de la IA..." required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Instrucciones Detalladas <span class="text-danger">*</span></label>
                    <textarea name="descripcion" id="descripcion" rows="6" class="form-control" placeholder="Describe claramente lo que esperas que el alumno entregue, formato de archivo, etc." required></textarea>
                </div>

                <div class="form-actions">
                    <a href="inicio_docente.php" class="btn-secundario">Cancelar</a>
                    <button type="submit" class="btn-primario">Publicar Tarea</button>
                </div>
                
            </form>
        </div>
    </main>

    <?php require 'footer.php'; ?>

    <script src="<?= $ruta_base ?>docentes/script_docentes.js?v=<?php echo time(); ?>"></script>
</body>
</html>