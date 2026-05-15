<?php
require 'validar_sesion_estudiantes.php';
require '../conexion.php';

$id_usuario = $_SESSION['id_usuario'];

// Obtener ID del estudiante
$stmt_id = $conexion->prepare("SELECT id_estudiante FROM estudiantes WHERE id_usuario = ?");
$stmt_id->execute([$id_usuario]);
$id_estudiante = $stmt_id->fetchColumn();

// Consulta maestra para traer las tareas de los cursos asignados y ver si ya hay entregas
$sql_tareas = "
    SELECT 
        t.id_tarea, t.titulo, t.descripcion, t.fecha_limite, 
        c.nombre_curso, c.id_curso,
        et.id_entrega, et.archivo_ruta, et.calificacion, et.retroalimentacion_docente, et.fecha_entrega
    FROM tareas t
    INNER JOIN asignaciones_docentes ad ON t.id_curso = ad.id_curso AND t.id_docente = ad.id_docente
    INNER JOIN asignaciones a ON ad.id_asignacion = a.id_asignacion
    INNER JOIN cursos c ON t.id_curso = c.id_curso
    LEFT JOIN entregas_tareas et ON t.id_tarea = et.id_tarea AND et.id_estudiante = a.id_estudiante
    WHERE a.id_estudiante = ?
    ORDER BY t.fecha_limite ASC
";
$stmt_tareas = $conexion->prepare($sql_tareas);
$stmt_tareas->execute([$id_estudiante]);
$tareas = $stmt_tareas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Tareas - Plataforma Academica</title>
    <link rel="stylesheet" href="../css/estilos_dashboard.css">
    <style>
        /* Estilos específicos para las tarjetas de tareas */
        .assignment-grid { display: grid; gap: 1.5rem; }
        .assignment-card { border: 1px solid rgba(255,255,255,0.06); background: rgba(255,255,255,0.03); border-radius: 22px; padding: 1.5rem; }
        .assignment-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
        .pill { background: rgba(91, 140, 255, 0.12); border: 1px solid rgba(91, 140, 255, 0.28); color: #cfe0ff; padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: bold; }
        .submitted-box { background: rgba(24, 194, 156, 0.1); border-left: 4px solid #18c29c; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .graded-box { background: rgba(91, 140, 255, 0.1); border-left: 4px solid #5b8cff; padding: 1rem; border-radius: 8px; margin-top: 1rem; }
        .assignment-form { display: grid; gap: 1rem; margin-top: 1rem; }
    </style>
</head>
<body>
<div class="app-shell">
    
    <?php include 'encabezado.php'; ?>

    <main class="content">
        <section class="hero glass">
            <div>
                <span class="hero-badge">Asignaciones</span>
                <h1>Entregas y Tareas</h1>
                <p class="muted">Revisa las instrucciones de tus catedráticos, sube tus documentos y revisa tus calificaciones.</p>
            </div>
        </section>

        <?php if (isset($_GET['exito'])): ?>
            <div class="alert alert-success">¡Tu tarea se ha subido correctamente!</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger">Ocurrió un error al subir el archivo. Inténtalo de nuevo.</div>
        <?php endif; ?>

        <section class="panel glass">
            <div class="assignment-grid">
                <?php if (count($tareas) > 0): ?>
                    <?php foreach ($tareas as $tarea): ?>
                        <article class="assignment-card">
                            <div class="assignment-top">
                                <div>
                                    <h3 style="margin: 0;"><?php echo htmlspecialchars($tarea['titulo']); ?></h3>
                                    <p class="muted" style="margin: 0.3rem 0 0 0;">Curso: <?php echo htmlspecialchars($tarea['nombre_curso']); ?></p>
                                </div>
                                <span class="pill">Límite: <?php echo date("d/m/Y H:i", strtotime($tarea['fecha_limite'])); ?></span>
                            </div>

                            <p><?php echo nl2br(htmlspecialchars($tarea['descripcion'])); ?></p>

                            <?php if ($tarea['id_entrega']): ?>
                                <div class="submitted-box">
                                    <strong style="color: #18c29c;">✓ Tarea Entregada</strong><br>
                                    <small class="muted">Fecha de envío: <?php echo date("d/m/Y H:i", strtotime($tarea['fecha_entrega'])); ?></small><br>
                                    <a href="../<?php echo $tarea['archivo_ruta']; ?>" target="_blank" style="color: #cfe0ff; text-decoration: underline;">Ver mi archivo enviado</a>
                                </div>

                                <?php if ($tarea['calificacion'] !== null): ?>
                                    <div class="graded-box">
                                        <strong>Calificación Obtenida: <?php echo $tarea['calificacion']; ?> / 100</strong>
                                        <?php if (!empty($tarea['retroalimentacion_docente'])): ?>
                                            <p style="margin-top: 0.5rem; font-style: italic;">"<?php echo htmlspecialchars($tarea['retroalimentacion_docente']); ?>"</p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                            <?php else: ?>
                                <form action="procesar_tarea.php" method="POST" enctype="multipart/form-data" class="assignment-form">
                                    <input type="hidden" name="id_tarea" value="<?php echo $tarea['id_tarea']; ?>">
                                    <input type="hidden" name="id_curso" value="<?php echo $tarea['id_curso']; ?>">
                                    
                                    <textarea name="comentario" rows="2" placeholder="Comentario opcional para el catedrático..."></textarea>
                                    <input type="file" name="archivo_tarea" accept=".pdf,.doc,.docx,.zip" required style="background: rgba(255,255,255,0.05); padding: 10px; border-radius: 8px;">
                                    
                                    <button type="submit" class="btn btn-secondary">Subir Archivo Definitivo</button>
                                </form>
                            <?php endif; ?>

                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="muted" style="text-align: center;">No hay tareas publicadas en este momento.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>
</body>
</html>