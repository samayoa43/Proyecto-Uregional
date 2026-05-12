<?php
require_once __DIR__ . '/validar_sesion_admin.php'; 
require 'procesar_cardinal.php'; 



?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kardex Académico - Plataforma Académica</title>
    <link rel="stylesheet" href="estilo_administrativo.css?v=<?php echo time(); ?>">
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
        
        <div class="section-header no-print">
            <h2>Kardex Académico</h2>
            <p>Genera e imprime certificaciones oficiales de notas para los estudiantes.</p>
        </div>

        <div class="kpi-card form-card no-print" style="margin-bottom: 30px; max-width: 800px;">
            <h3 style="margin-top:0; color:#0078d4; margin-bottom: 15px;">Generador de Kardex</h3>
            
            <form action="" method="GET" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label class="form-label">Buscar Estudiante:</label>
                    <select name="id_estudiante" class="form-input" required>
                        <option value="" disabled <?= empty($id_estudiante) ? 'selected' : '' ?>>-- Busque al estudiante por sus apellidos --</option>
                        <?php foreach ($lista_estudiantes as $est): ?>
                            <option value="<?= $est['id_estudiante'] ?>" <?= ($id_estudiante == $est['id_estudiante']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($est['apellidos'] . ", " . $est['nombres'] . " (Carnet: " . $est['id_estudiante'] . ")") ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn-primario">Generar Certificado</button>
                </div>
            </form>
        </div>

        <?php if (!empty($datos_alumno)): ?>

            <div class="acciones-reporte no-print">
                <button class="btn-imprimir" onclick="window.print()">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" style="vertical-align: text-bottom; margin-right: 5px;">
                        <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
                    </svg>
                    Imprimir Kardex
                </button>
            </div>

            <div class="hoja-reporte kpi-card">

                <div class="hoja-header">
                    <img src="../img/img_01.png" alt="Logo Universidad" class="logo-oficial">
                    
                    <div class="header-titulos">
                        <h1>Universidad Regional</h1>
                        <h3>Certificación de Estudios Académicos (Kardex)</h3>
                    </div>
                </div>

                <div class="info-estudiante">
                    <div class="info-bloque">
                        <p><strong>Nombres:</strong> <?= htmlspecialchars($datos_alumno['nombres']) ?></p>
                        <p><strong>Apellidos:</strong> <?= htmlspecialchars($datos_alumno['apellidos']) ?></p>
                    </div>
                    <div class="info-bloque text-right">
                        <p><strong>Carnet / ID:</strong> <?= htmlspecialchars($datos_alumno['id_estudiante']) ?></p>
                        <p><strong>Carrera Inscrita:</strong> <?= htmlspecialchars($datos_alumno['nombre_carrera']) ?></p>
                        <p><strong>Fecha de Emisión:</strong> <?= date('d/m/Y') ?></p>
                    </div>
                </div>

                <?php if (isset($historial_notas) && count($historial_notas) > 0): ?>
                    <table class="tabla-reporte">
                        <thead>
                            <tr>
                                <th style="width: 120px;">Semestre</th>
                                <th>Nombre del Curso</th>
                                <th style="width: 100px; text-align: center;">Nota Final</th>
                                <th style="width: 120px; text-align: center;">Resultado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historial_notas as $nota): ?>
                                <tr>
                                    <td><?= $nota['semestre'] ? 'Semestre ' . $nota['semestre'] : 'Extra' ?></td>
                                    <td><strong><?= htmlspecialchars($nota['nombre_curso']) ?></strong></td>
                                    <td style="text-align: center;"><?= number_format($nota['nota_final'], 0) ?> pts</td>
                                    <td style="text-align: center;">
                                        <?php if ($nota['nota_final'] >= 61): ?>
                                            <span class="badge badge-aprobado">APROBADO</span>
                                        <?php else: ?>
                                            <span class="badge badge-reprobado">REPROBADO</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="resumen-kardex">
                        PROMEDIO GENERAL ACUMULADO: <strong><?= number_format($promedio_general, 2) ?> Pts.</strong>
                    </div>

                <?php else: ?>
                    <div class="reporte-vacio">
                        <h3 style="color: #64748b;">Sin Historial</h3>
                        <p>Este estudiante no tiene registro de notas en el sistema actual.</p>
                    </div>
                <?php endif; ?>

                <div class="area-firmas">
                    <div class="bloque-firma">
                        <div class="linea-firma"></div>
                        <p>Secretaría Académica</p>
                        <small>Firma y Sello Oficial</small>
                    </div>
                    <div class="bloque-firma">
                        <div class="linea-firma"></div>
                        <p>Director de Carrera</p>
                        <small>Vo. Bo.</small>
                    </div>
                </div>

            </div>
        <?php endif; ?>

    </main>

    <?php require 'footer.php'; ?>

    <script src="script_admin.js?v=<?php echo time(); ?>"></script>
</body>
</html>