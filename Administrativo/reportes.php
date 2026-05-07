<?php
require 'procesar_reportes.php';  

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Reportes - Plataforma Académica</title>
    <!-- Conectamos tu CSS principal -->
    <link rel="stylesheet" href="estilo_administrativo.css?v=<?php echo time(); ?>">
</head>
<body>
    
    <!-- SCRIPT ANTI-PARPADEO PARA MODO OSCURO -->
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>

    <!-- Llamamos a tu plantilla maestra (Header Azul + Menú Lateral) -->
    <?php 
    $ruta_base = "../";
    require 'encabezado.php'; ?>

    <!-- CONTENEDOR PRINCIPAL -->
    <main class="main-container">
        
        <!-- Título principal (Se oculta al imprimir) -->
        <div class="section-header no-print">
            <h2>Centro de Reportes</h2>
            <p>Genera listados de asistencia y estados de cuenta para la Universidad Regional.</p>
        </div>

        <!-- Panel de Controles (Se oculta al imprimir) -->
        <div class="panel-controles no-print">
            
            <!-- Formulario 1: Morosos -->
            <div class="kpi-card caja-reporte">
                <h3>Alumnos Morosos</h3>
                <form action="" method="GET">
                    <input type="hidden" name="tipo" value="morosos">
                    <label class="form-label">Seleccione el mes a revisar:</label>
                    <select name="mes" class="form-select" required>
                        <option value="" disabled selected>-- Mes de adeudo --</option>
                        <!-- Simulación de variables PHP -->
                        <?php foreach ($meses_permitidos as $mes): ?>
                            <option value="<?= $mes ?>"><?= $mes ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn-generar">Generar Reporte Financiero</button>
                </form>
            </div>

            <!-- Formulario 2: Listado Docentes -->
            <div class="kpi-card caja-reporte">
                <h3>Listado para Docentes</h3>
                <form action="" method="GET">
                    <input type="hidden" name="tipo" value="curso">
                    <label class="form-label">Seleccione el curso:</label>
                    <select name="id_curso" class="form-select" required>
                        <option value="" disabled selected>-- Elige el curso --</option>
                        <!-- Simulación de variables PHP -->
                        <?php foreach ($lista_cursos as $curso): ?>
                            <option value="<?= $curso['id_curso'] ?>"><?= htmlspecialchars($curso['nombre_curso']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn-generar">Generar Listado de Asistencia</button>
                </form>
            </div>

        </div>

        <!-- ==============================================
             ÁREA DEL DOCUMENTO (Lo que se va a imprimir)
             ============================================== -->
        <?php if (isset($tipo_reporte) && $tipo_reporte !== ''): ?>
            
            <!-- Botón flotante para imprimir -->
            <div class="acciones-reporte no-print">
                <button class="btn-imprimir" onclick="window.print()">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" style="vertical-align: text-bottom; margin-right: 5px;">
                        <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
                    </svg>
                    Imprimir Documento
                </button>
            </div>

            <div class="hoja-reporte kpi-card">
                
                <div class="hoja-header">
                    <h1>Universidad Regional</h1>
                    <h3><?= htmlspecialchars($titulo_reporte) ?></h3>
                    <p>Fecha de emisión: <?= date('d/m/Y') ?></p>
                </div>

                <?php if (isset($resultados) && count($resultados) > 0): ?>
                    <table class="tabla-reporte">
                        <thead>
                            <tr>
                                <th class="col-num">No.</th>
                                <th class="col-id">Carnet</th>
                                <th>Apellidos</th>
                                <th>Nombres</th>
                                <th>Correo Electrónico</th>
                                <?php if ($tipo_reporte === 'curso'): ?>
                                    <th class="col-firma">Firma de Asistencia</th>
                                <?php else: ?>
                                    <th class="col-firma">Observaciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $contador = 1; foreach ($resultados as $fila): ?>
                                <tr>
                                    <td class="col-num"><?= $contador++ ?></td>
                                    <td class="col-id"><?= htmlspecialchars($fila['id_estudiante']) ?></td>
                                    <td><strong><?= htmlspecialchars($fila['apellidos']) ?></strong></td>
                                    <td><?= htmlspecialchars($fila['nombres']) ?></td>
                                    <td><?= htmlspecialchars($fila['correo']) ?></td>
                                    <td class="col-firma"></td> <!-- Espacio vacío para firmar/escribir -->
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p class="total-registros">Total de registros: <?= count($resultados) ?></p>
                <?php else: ?>
                    <div class="reporte-vacio">
                        <h3>No se encontraron registros</h3>
                        <p>No hay alumnos morosos para este mes, o no hay alumnos inscritos en este curso.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </main>

        <?php require 'footer.php'; ?>

    <script src="script_admin.js?v=<?php echo time(); ?>"></script>
</body>
</html>