<?php
require 'validar_sesion_estudiantes.php';
require '../conexion.php'; 

// 2. Obtener el ID del estudiante real basado en el usuario logueado
$id_usuario = $_SESSION['id_usuario'];
$stmt_est = $conexion->prepare("SELECT id_estudiante, nombres, apellidos FROM estudiantes WHERE id_usuario = ?");
$stmt_est->execute([$id_usuario]);
$estudiante = $stmt_est->fetch(PDO::FETCH_ASSOC);

$id_estudiante = $estudiante['id_estudiante'];

require 'funciones_dash.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma Académica</title>
    <link rel="stylesheet" href="estilos_estudiantes.css"> 
    <link rel="icon" href="../img/img_02.png" type="image/png">
    <link rel="stylesheet" href="introjs.min.css">
</head>
<body>
<div class="app-shell">
        <nav class="menu">
            <?php require 'encabezado.php'; ?>
        </nav>
    
    <main class="content">
        <section class="hero glass">
            <div>
                <span class="hero-badge">Estudiante</span>
                <h1>Bienvenido, <?php echo htmlspecialchars($estudiante['nombres']); ?></h1>
                <p class="muted">Consulta tu progreso académico, tareas y pagos desde aquí.</p>

                <button onclick="iniciarTour()" style="margin-top: 15px; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.5); color: white; padding: 8px 15px; border-radius: 5px; cursor: pointer; transition: 0.3s;"
                    data-step="9" 
                    data-title="¿Necesitas ayuda?" 
                    data-intro="Si alguna vez olvidas para qué sirve cada sección, presiona este botón para volver a ver este recorrido."
                    style="margin-top: auto; color: #80deea; font-weight: bold;">
                    ▶ Repetir Tutorial
                </button>
            </div>
            <div class="hero-meta">
            </div>
        </section>

 <section id="anuncios" class="panel glass" style="border-left: 5px solid #ffca28;"
 data-step="10" 
         data-title="Tablón de Anuncios" 
         data-intro="Aquí publicaremos comunicados oficiales, suspensiones de clases o avisos urgentes de tus catedráticos. Revísalo a diario.">
    <div class="panel-header">
        <h3 style="color: #ffffff; margin: 0;">📢 Tablón de Anuncios</h3>
    </div>
    <div style="padding: 15px;">
        <?php if(count($anuncios) > 0): ?>
            <?php foreach($anuncios as $anuncio): ?>
                <div style="border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 15px; margin-bottom: 15px;">
                    <h4 style="margin: 0 0 5px 0; color: #ffffff; font-size: 1.1em;">
                        <?php echo htmlspecialchars($anuncio['titulo']); ?>
                    </h4>
                    <small style="color: #cccccc; display: block; margin-bottom: 8px;">
                        <strong>De:</strong> <?php echo htmlspecialchars($anuncio['autor']); ?> | 
                        <strong>Fecha:</strong> <?php echo date('d/m/Y h:i A', strtotime($anuncio['fecha_publicacion'])); ?>
                        <?php if($anuncio['nombre_curso']): ?>
                            | <span style="background: rgba(255,255,255,0.1); color: #80deea; padding: 2px 6px; border-radius: 4px; font-weight: bold;">
                                Curso: <?php echo htmlspecialchars($anuncio['nombre_curso']); ?>
                            </span>
                        <?php endif; ?>
                    </small>
                    <p style="margin: 0; color: #eeeeee; line-height: 1.5;">
                        <?php echo nl2br(htmlspecialchars($anuncio['mensaje'])); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: #ffffff; text-align: center; padding: 20px; margin: 0;">No hay comunicados nuevos por el momento.</p>
        <?php endif; ?>
    </div>
</section>
<section id="cursos" class="panel glass" 
         data-step="11" 
         data-title="Tus Cursos Asignados" 
         data-intro="Aquí verás un resumen de tus cursos actuales, tus horarios y tu porcentaje de asistencia en cada uno.">
    <div class="panel-header"><h3>Cursos Asignados</h3></div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Docente</th>
                    <th>Horario</th>
                    <th style="width: 200px;">Asistencia</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($cursos as $curso): 
                    $pct = $curso['porcentaje_asistencia'];
                    // Color de la barra según el porcentaje
                    $color = ($pct >= 80) ? '#4caf50' : (($pct >= 60) ? '#ff9800' : '#f44336');
                ?>
                <tr>
                    <td style="color: #ffffff;"><?php echo htmlspecialchars($curso['nombre_curso']); ?></td>
                    <td style="color: #cccccc;"><?php echo htmlspecialchars($curso['docente_nomb'] . ' ' . $curso['docente_ape']); ?></td>
                    <td style="color: #cccccc;">
                        <?php echo ($curso['dia_semana'] ?? 'N/A') . ' ' . ($curso['hora_inicio'] ?? ''); ?>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="flex-grow: 1; background: rgba(255,255,255,0.1); height: 8px; border-radius: 4px; overflow: hidden;">
                                <div style="width: <?php echo $pct; ?>%; background: <?php echo $color; ?>; height: 100%; transition: width 0.5s;"></div>
                            </div>
                            <span style="color: #ffffff; font-weight: bold; font-size: 0.9em; min-width: 40px;">
                                <?php echo $pct; ?>%
                            </span>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
        <section id="tareas" class="panel glass" 
                 data-step="12" 
                 data-title="Tareas Pendientes" 
                 data-intro="Aquí encontrarás las tareas que tus catedráticos han asignado. Puedes subir tus archivos y agregar comentarios.">
            <div class="panel-header"><h3>Tareas Pendientes</h3></div>
            <div class="assignment-grid">
                <?php foreach($tareas as $tarea): ?>
                <article class="assignment-card">
                    <div class="assignment-top">
                        <h4><?php echo $tarea['titulo']; ?> (<?php echo $tarea['nombre_curso']; ?>)</h4>
                    </div>
                    <p class="assignment-description"><?php echo $tarea['descripcion']; ?></p>
                    <div class="assignment-meta"><span>Límite: <?php echo $tarea['fecha_limite']; ?></span></div>
                    
                    <form action="procesar_tarea_estudiante.php" method="POST" enctype="multipart/form-data" class="assignment-form">
                        <input type="hidden" name="id_tarea" value="<?php echo $tarea['id_tarea']; ?>">
                        <input type="text" name="comentario" placeholder="Comentario opcional">
                        <input type="file" name="archivo_tarea" required>
                        <button type="submit" class="btn btn-secondary">Subir Tarea</button>
                    </form>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        
    </main>
</div>
<script src="intro.min.js"></script>
<script src="tutorial.js"></script>
</body>
</html>