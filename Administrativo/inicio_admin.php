<?php

require_once __DIR__ . '/validar_sesion_admin.php'; 
$ruta_base = "../";

require 'funciones_dash.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma Académica</title>
    <link rel="stylesheet" href="estilo_administrativo.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= $ruta_base ?>introjs.min.css">
</head>
<body>
    
   <?php 
   require 'encabezado.php'; ?>

    <main class="main-container">
        <div class="section-header">
            <h2>Bienvenido(a), <?= htmlspecialchars($nombre) ?></h2>
        </div>

<div class="section-header">
    <h3>Panel de Inteligencia Estratégica:</h3>
    <?php if(isset($error_bd)) echo "<p class='error-msg'>$error_bd</p>"; ?>
</div>

<div class="dashboard-grid">
    
    <div class="kpi-card prioridad-muy-alta">
        <h3 class="kpi-title">Finanzas (Mes: <?= $mes_actual ?>)</h3>
        <div style="height: 120px; position: relative;">
            <canvas id="chartFinanzas"></canvas>
        </div>
        <p class="kpi-desc" style="margin-top: 10px;">
            <strong class="text-danger"><?= $kpi_morosos ?></strong> con riesgo de morosidad.
        </p>
    </div>

    <div class="kpi-card prioridad-alta">
        <h3 class="kpi-title">Estudiantes (Retención)</h3>
        <div style="height: 120px; position: relative;">
            <canvas id="chartRetencion"></canvas>
        </div>
        <p class="kpi-desc" style="text-align: center; margin-top: 5px;">
            <strong><?= $kpi_estudiantes_activos ?></strong> activos.
        </p>
    </div>

    <div class="kpi-card prioridad-alta">
        <h3 class="kpi-title">Cursos (Saturación)</h3>
        <div style="height: 120px; position: relative;">
            <canvas id="chartSaturacion"></canvas>
        </div>
        <p class="kpi-desc" style="margin-top: 5px;">Promedio: <?= $kpi_saturacion ?> alumnos/clase.</p>
    </div>

    <div class="kpi-card prioridad-media">
        <h3 class="kpi-title">Docentes (Carga)</h3>
        <p class="kpi-value"><?= $kpi_carga_docente ?></p>
        <div class="progress-bar-container" style="background: #eee; height: 8px; border-radius: 4px;">
            <div style="background: var(--color-primario); width: <?= ($kpi_carga_docente * 20) ?>%; height: 100%; border-radius: 4px;"></div>
        </div>
        <p class="kpi-desc" style="margin-top: 10px; color: #17a2b8;"><em>* Evaluación pendiente.</em></p>
    </div>

    <div class="kpi-card prioridad-media">
        <h3 class="kpi-title">Operación (Tickets)</h3>
        <p class="kpi-value text-danger"><?= $kpi_tickets_activos ?></p>
        <p class="kpi-desc">Solicitudes pendientes.</p>
        <p class="kpi-desc" style="margin-top: 10px;"><a href="gestion_tickets.php" style="color: #17a2b8; text-decoration: none;">Ver panel de soporte ➔</a></p>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
<script>
    window.dataMorosos = <?= json_encode((int)$kpi_morosos) ?>;
    window.dataAlDia = <?= json_encode((int)$kpi_alumnos_al_dia) ?>; 
    window.dataEstudiantesActivos = <?= json_encode((int)$kpi_estudiantes_activos) ?>;
    window.dataSaturacion = <?= json_encode((float)$kpi_saturacion) ?>;
</script>
    
    <script src="graficas.js?v=<?php echo time(); ?>"></script>
        </div>
    </main>

    <?php require 'footer.php'; ?>

<script src="script_admin.js?v=<?php echo time(); ?>"></script>
<script src="<?= $ruta_base ?>intro.min.js"></script>
<script src="<?= $ruta_base ?>tutorial.js?v=<?php echo time(); ?>"></script>
</body>
</html>
