<?php
require 'validar_sesion_estudiantes.php';
require '../conexion.php';

// 1. Escudo de seguridad (Validación por nombre de rol)
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== "estudiante") {
    header("Location: ../login.php?error=acceso_denegado");
    exit();
}
 require 'procesar_cursos.php'; // Aquí se ejecutan las consultas para notas, cursos, pagos y tareas
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Cursos</title>
    <link rel="stylesheet" href="estilos_estudiantes.css">
    <link rel="icon" href="../img/img_02.png" type="image/png">
</head>
<body>
<div class="app-shell">
    
    <?php include 'encabezado.php'; ?>

    <main class="content">
        <section class="hero glass">
            <div>
                <span class="hero-badge">Inscripciones Activas</span>
                <h1>Mis Cursos</h1>
                <p class="muted">Listado detallado de tus materias asignadas para el ciclo actual en la sede de Barberena.</p>
            </div>
        </section>

        <section id="cursos" class="panel glass">
            <div class="panel-header">
                <h3>Horario y Catedráticos</h3>
                <p class="muted">Verifica los salones y horarios de tus clases.</p>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Catedrático</th>
                            <th>Día</th>
                            <th>Horario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($cursos) > 0): ?>
                            <?php foreach ($cursos as $curso): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($curso['nombre_curso']); ?></strong></td>
                                <td><?php echo htmlspecialchars($curso['docente_nomb'] . " " . $curso['docente_ape']); ?></td>
                                <td><?php echo $curso['dia_semana'] ?? '<span class="muted">No definido</span>'; ?></td>
                                <td>
                                    <?php if ($curso['hora_inicio']): ?>
                                        <?php echo date("H:i", strtotime($curso['hora_inicio'])) . " - " . date("H:i", strtotime($curso['hora_fin'])); ?>
                                    <?php else: ?>
                                        <span class="muted">Pendiente</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 2rem;">No tienes cursos asignados todavía.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
</body>
</html>