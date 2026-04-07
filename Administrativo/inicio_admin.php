<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'funciones_dash.php'; 
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php"); 
    exit();
}

if ($_SESSION['rol'] !== 'admin') {
    // Si un Docente o Admin intenta husmear aquí, le bloqueamos el paso
    echo "<div style='text-align: center; margin-top: 50px; font-family: Arial;'>";
    echo "<h3 style='color: #d9534f;'>Acceso Denegado. Esta área es exclusiva para Administradores.</h3>";
    echo "<a href='login.php' style='text-decoration: none; background: #0056b3; color: white; padding: 10px 15px; border-radius: 5px;'>Volver a mi panel</a>";
    echo "</div>";
    exit();
}

$nombre = $_SESSION['nombre_usuario'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plataforma Académica</title>
  
</head>
<body>
    <h1>Portal Académico</h1>
    <h2>Bienvenido: <?php echo htmlspecialchars($nombre); ?>!</strong></h2>

        <nav>
        <?php
        require 'encabezado.php';
        ?>
        </nav>
            <div class="navbar">
        <a href="../logout.php" class="btn-salir">Cerrar Sesión</a>
    </div>

<h2 style="margin-bottom: 5px;">Panel de Inteligencia Estratégica</h2>
    <p style="color: #666; margin-top: 0;">Indicadores clave de rendimiento (KPIs) en tiempo real.</p>

    <?php if(isset($error_bd)) echo "<p style='color:red;'>$error_bd</p>"; ?>

    <div class="dashboard-grid">
        
        <div class="kpi-card prioridad-muy-alta">
            <h3 class="kpi-title">Finanzas (Mes: <?= $mes_actual ?>)</h3>
            <p class="kpi-value text-danger"><?= $kpi_morosos ?></p>
            <p class="kpi-desc">Alumnos con <strong>riesgo de morosidad</strong>.</p>
            <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
            <p class="kpi-desc">Pagos al día: <span class="text-success"><?= $kpi_alumnos_al_dia ?> estudiantes</span></p>
        </div>

        <div class="kpi-card prioridad-alta">
            <h3 class="kpi-title">Estudiantes (Retención)</h3>
            <p class="kpi-value"><?= $kpi_estudiantes_activos ?></p>
            <p class="kpi-desc">Alumnos activos en el sistema.</p>
            <p class="kpi-desc" style="margin-top: 10px; color: #ff9800;"><em></em></p>
        </div>

        <div class="kpi-card prioridad-alta">
            <h3 class="kpi-title">Cursos (Saturación)</h3>
            <p class="kpi-value"><?= $kpi_saturacion ?> <span style="font-size: 16px; color: #888;">alumnos/clase</span></p>
            <p class="kpi-desc">Promedio de estudiantes por sección abierta.</p>
        </div>

        <div class="kpi-card prioridad-media">
            <h3 class="kpi-title">Docentes (Carga)</h3>
            <p class="kpi-value"><?= $kpi_carga_docente ?> <span style="font-size: 16px; color: #888;">clases/docente</span></p>
            <p class="kpi-desc">Promedio de carga laboral asignada.</p>
            <p class="kpi-desc" style="margin-top: 10px; color: #17a2b8;"><em>* Desempeño: Pendiente de módulo de encuestas.</em></p>
        </div>

        <div class="kpi-card prioridad-media">
            <h3 class="kpi-title">Operación (Tickets)</h3>
            <p class="kpi-value text-danger"><?= $kpi_tickets_activos ?></p>
            <p class="kpi-desc">Solicitudes pendientes de atención.</p>
            <p class="kpi-desc" style="margin-top: 10px;"><a href="gestion_tickets.php" style="color: #17a2b8; text-decoration: none;">Ver panel de soporte ➔</a></p>
        </div>

    </div>

</body>
</html>
