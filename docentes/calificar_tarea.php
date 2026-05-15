<?php
require_once 'validar_sesion_docentes.php';
require_once '../conexion.php';

if (!isset($_GET['id_tarea'])) {
    die("Debe seleccionar una tarea válida.");
}

$id_tarea = $_GET['id_tarea'];

$sql_tarea = "SELECT titulo FROM tareas WHERE id_tarea = ?";
$stmt_tarea = $conexion->prepare($sql_tarea);
$stmt_tarea->execute([$id_tarea]);
$tarea = $stmt_tarea->fetch(PDO::FETCH_ASSOC);

// Obtenemos todas las entregas para esta tarea específica
$sql_entregas = "SELECT et.id_entrega, et.archivo_ruta, et.comentarios_estudiante, 
                        et.fecha_entrega, et.calificacion, et.retroalimentacion_docente,
                        e.nombres, e.apellidos 
                 FROM entregas_tareas et
                 INNER JOIN estudiantes e ON et.id_estudiante = e.id_estudiante
                 WHERE et.id_tarea = ?";
$stmt_entregas = $conexion->prepare($sql_entregas);
$stmt_entregas->execute([$id_tarea]);
$entregas = $stmt_entregas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificar Entregas | Panel Docente</title>
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
        <div class="section-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2>Entregas: <?= htmlspecialchars($tarea['titulo']) ?></h2>
                <p style="color: #64748b; margin-top: 5px;">Revisa los archivos y asigna una calificación a tus estudiantes.</p>
            </div>
            <a href="inicio_docente.php" class="btn-secundario">Volver al Panel</a>
        </div>
        
        <div class="table-card">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Fecha de Entrega</th>
                            <th>Archivo</th>
                            <th>Comentario Alumno</th>
                            <th style="min-width: 250px;">Calificar y Retroalimentar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($entregas) > 0): ?>
                            <?php foreach($entregas as $entrega): ?>
                                <tr>
                                    <td class="fw-bold"><?= htmlspecialchars($entrega['nombres'] . ' ' . $entrega['apellidos']) ?></td>
                                    <td><span class="badge-fecha"><?= $entrega['fecha_entrega'] ?></span></td>
                                    <td>
                                        <a href="../estudiantes/<?= htmlspecialchars($entrega['archivo_ruta']) ?>" target="_blank" class="btn-enlace">
                                            📥 Descargar
                                        </a>
                                    </td>
                                    <td class="text-muted"><?= htmlspecialchars($entrega['comentarios_estudiante'] ?: 'Sin comentarios') ?></td>
                                    <td>
                                        <form action="procesar_calificacion.php" method="POST" class="form-calificacion">
                                            <input type="hidden" name="id_entrega" value="<?= $entrega['id_entrega'] ?>">
                                            <input type="hidden" name="id_tarea" value="<?= $id_tarea ?>">
                                            
                                            <div style="display: flex; gap: 10px; align-items: flex-start;">
                                                <input type="number" step="0.01" name="calificacion" 
                                                       class="form-control" placeholder="Nota" 
                                                       value="<?= $entrega['calificacion'] ?>" required style="width: 80px;">
                                                       
                                                <textarea name="retroalimentacion" class="form-control" 
                                                          placeholder="Comentarios..." rows="1" 
                                                          style="flex: 1; resize: none;"><?= htmlspecialchars($entrega['retroalimentacion_docente'] ?? '') ?></textarea>
                                                          
                                                <button type="submit" class="btn-primario btn-sm">Guardar</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="empty-state">
                                    Aún no hay entregas registradas para esta tarea.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php require 'footer.php'; ?>

    <script src="<?= $ruta_base ?>docentes/script_docentes.js?v=<?php echo time(); ?>"></script>
</body>
</html>