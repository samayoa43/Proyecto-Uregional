<?php
require 'validar_sesion_estudiantes.php';

require '../conexion.php'; // Tu archivo de conexión PDO

// 2. Obtener el ID del estudiante real basado en el usuario logueado
$id_usuario = $_SESSION['id_usuario'];
$stmt_est = $conexion->prepare("SELECT id_estudiante, nombres, apellidos, correo FROM estudiantes WHERE id_usuario = ?");
$stmt_est->execute([$id_usuario]);
$estudiante = $stmt_est->fetch(PDO::FETCH_ASSOC);

$id_estudiante = $estudiante['id_estudiante'];

require 'funciones_dash.php'; // Aquí se ejecutan las consultas para notas, cursos, pagos y tareas
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma Académica</title>
    <link rel="stylesheet" href="../css/estilos_dashboard.css"> 
</head>
<body>
<div class="app-shell">
        <nav class="menu">
            <?php
            require 'encabezado.php';
            ?>
        </nav>
    </aside>

    <main class="content">
        <section class="hero glass">
            <div>
                <span class="hero-badge">Estudiante</span>
                <h1>Bienvenido, <?php echo htmlspecialchars($estudiante['nombres']); ?></h1>
                <p class="muted">Consulta tu progreso académico, tareas y pagos desde aquí.</p>
            </div>
            <div class="hero-meta">
                <div><strong>Correo:</strong> <?php echo htmlspecialchars($estudiante['correo']); ?></div>
            </div>
        </section>

        <section id="cursos" class="panel glass">
            <div class="panel-header"><h3>Cursos Asignados</h3></div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Curso</th><th>Docente</th><th>Día</th><th>Hora</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($cursos as $curso): ?>
                        <tr>
                            <td><?php echo $curso['nombre_curso']; ?></td>
                            <td><?php echo $curso['docente_nomb'] . ' ' . $curso['docente_ape']; ?></td>
                            <td><?php echo $curso['dia_semana'] ?? 'N/A'; ?></td>
                            <td><?php echo $curso['hora_inicio'] ?? 'N/A'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="tareas" class="panel glass">
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
</body>
</html>