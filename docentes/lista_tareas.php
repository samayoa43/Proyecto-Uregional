<?php
require_once 'validar_sesion_docentes.php';
require_once '../conexion.php';

$id_docente = $_SESSION['id_docente'];

// Obtenemos todas las tareas que ESTE docente ha creado, junto con el nombre del curso
try {
    $sql_tareas = "SELECT t.id_tarea, t.titulo, t.fecha_limite, c.nombre_curso 
                   FROM tareas t
                   INNER JOIN cursos c ON t.id_curso = c.id_curso
                   WHERE t.id_docente = ?
                   ORDER BY t.fecha_limite DESC";
                   
    $stmt = $conexion->prepare($sql_tareas);
    $stmt->execute([$id_docente]);
    $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error al cargar las tareas: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Tareas Asignadas | Panel Docente</title>
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
                <h2>Mis Tareas Publicadas</h2>
                <p style="color: #64748b; margin-top: 5px;">Administra las actividades asignadas a tus estudiantes.</p>
            </div>
            <a href="crear_tareas.php" class="btn-primario">+ Crear Nueva Tarea</a>
        </div>
        
        <div class="table-card">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Título de la Tarea</th>
                            <th>Fecha Límite</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($tareas) > 0): ?>
                            <?php foreach($tareas as $tarea): ?>
                                <tr>
                                    <td class="text-muted"><?= htmlspecialchars($tarea['nombre_curso']) ?></td>
                                    
                                    <td class="fw-bold"><?= htmlspecialchars($tarea['titulo']) ?></td>
                                    
                                    <td><span class="badge-fecha"><?= $tarea['fecha_limite'] ?></span></td>
                                    
                                    <td>
                                        <a href="calificar_tarea.php?id_tarea=<?= $tarea['id_tarea'] ?>" 
                                           class="btn-primario btn-sm" 
                                           style="background-color: #10b981; border: none; text-align: center; display: inline-block;">
                                            Ver y Calificar Entregas
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="empty-state">No has publicado ninguna tarea aún.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
        <script src="<?= $ruta_base ?>docentes/script_docentes.js?v=<?php echo time(); ?>"></script>
</body>
</html>