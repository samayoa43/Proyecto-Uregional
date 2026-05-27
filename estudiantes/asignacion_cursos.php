<?php
require 'validar_sesion_estudiantes.php';
require '../conexion.php';
require_once 'verificar_solvencia.php';

$id_usuario = $_SESSION['id_usuario'];

// 2. Obtener el id_estudiante y su id_carrera
$stmt_perfil = $conexion->prepare("SELECT id_estudiante, id_carrera FROM estudiantes WHERE id_usuario = ?");
$stmt_perfil->execute([$id_usuario]);
$perfil = $stmt_perfil->fetch(PDO::FETCH_ASSOC);

$id_estudiante = $perfil['id_estudiante'];
$id_carrera = $perfil['id_carrera'];

// 3. Verificar el "Semáforo" del Ciclo Académico
$stmt_ciclo = $conexion->query("SELECT nombre_ciclo, asignaciones_abiertas FROM ciclos_academicos WHERE estado = 'Activo' LIMIT 1");
$ciclo_actual = $stmt_ciclo->fetch(PDO::FETCH_ASSOC);

$asignaciones_abiertas = ($ciclo_actual && $ciclo_actual['asignaciones_abiertas'] == 1);

// 4. Si están abiertas, buscamos la oferta académica disponible para su carrera
$oferta_cursos = [];
if ($asignaciones_abiertas) {
    $sql_oferta = "
        SELECT 
            ad.id_asignacion, 
            c.nombre_curso, 
            d.nombres AS docente_nomb, 
            d.apellidos AS docente_ape, 
            h.dia_semana, 
            h.hora_inicio, 
            h.hora_fin
        FROM asignaciones_docentes ad
        INNER JOIN cursos c ON ad.id_curso = c.id_curso
        INNER JOIN docentes d ON ad.id_docente = d.id_docente
        LEFT JOIN horarios h ON ad.id_asignacion = h.id_asignacion
        INNER JOIN pensum p ON c.id_curso = p.id_curso
        WHERE p.id_carrera = ? 
        AND ad.id_asignacion NOT IN (
            SELECT id_asignacion FROM asignaciones WHERE id_estudiante = ?
        )
    ";
    $stmt_oferta = $conexion->prepare($sql_oferta);
    $stmt_oferta->execute([$id_carrera, $id_estudiante]);
    $oferta_cursos = $stmt_oferta->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignación de Cursos</title>
    <link rel="stylesheet" href="estilos_estudiantes.css"> 
    <style>
        .banner-alerta { padding: 1.5rem; border-radius: 16px; margin-bottom: 1.5rem; }
        .banner-cerrado { background: rgba(255, 107, 129, 0.12); border: 1px solid rgba(255, 107, 129, 0.3); color: #ff8a9d; }
        .banner-abierto { background: rgba(24, 194, 156, 0.14); border: 1px solid rgba(24, 194, 156, 0.3); color: #18c29c; }
        .btn-asignar { padding: 8px 16px; font-size: 0.9rem; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; }
        .btn-asignar:hover { opacity: 0.9; transform: scale(1.05); }
    </style>
    <link rel="icon" href="../img/img_02.png" type="image/png">
</head>
<body>
<div class="app-shell">
    
    <?php include 'encabezado.php'; ?>

    <main class="content">
        <section class="hero glass">
            <div>
                <span class="hero-badge">Inscripciones</span>
                <h1>Asignación de Cursos</h1>
                <p class="muted">Selecciona las materias que cursarás durante el <?php echo htmlspecialchars($ciclo_actual['nombre_ciclo'] ?? 'ciclo actual'); ?>.</p>
            </div>
        </section>

        <?php if (isset($_GET['exito'])): ?>
            <div class="alert alert-success">¡Te has asignado al curso exitosamente!</div>
        <?php endif; ?>

        <?php if (!$asignaciones_abiertas): ?>
            <div class="banner-alerta banner-cerrado">
                <h3 style="margin-top: 0;">Proceso Cerrado</h3>
                <p>El proceso de asignación de cursos para el ciclo actual no se encuentra disponible o ha finalizado. Si crees que esto es un error, contacta a la administración de la sede.</p>
            </div>
        <?php else: ?>
            <div class="banner-alerta banner-abierto">
                <h3 style="margin-top: 0;">Proceso Abierto</h3>
                <p>Asegúrate de revisar el horario antes de asignarte para evitar choques de clases.</p>
            </div>

            <section class="panel glass">
                <div class="panel-header">
                    <h3>Cursos Disponibles para tu Carrera</h3>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Curso</th>
                                <th>Catedrático</th>
                                <th>Horario</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($oferta_cursos) > 0): ?>
                                <?php foreach ($oferta_cursos as $curso): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($curso['nombre_curso']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($curso['docente_nomb'] . " " . $curso['docente_ape']); ?></td>
                                    <td>
                                        <?php if ($curso['dia_semana']): ?>
                                            <?php echo $curso['dia_semana'] . " (" . date("H:i", strtotime($curso['hora_inicio'])) . " - " . date("H:i", strtotime($curso['hora_fin'])) . ")"; ?>
                                        <?php else: ?>
                                            <span class="muted">A confirmar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form action="procesar_asignacion.php" method="POST">
                                            <input type="hidden" name="id_asignacion" value="<?php echo $curso['id_asignacion']; ?>">
                                            <button type="submit" class="btn btn-primary btn-asignar">+ Asignarme</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 2rem;">No hay cursos nuevos disponibles para asignarte en este momento.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php endif; ?>
    </main>
</div>
</body>
</html>