<?php
require 'procesar_ciclo.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ciclos Académicos - Plataforma Académica</title>
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
        
        <div class="section-header">
            <h2>Control de Ciclos Académicos</h2>
            <p>Apertura nuevos semestres y controla los periodos de asignación para los estudiantes.</p>
        </div>

        <?php if(!empty($mensaje_exito)): ?>
            <div class='alerta alerta-exito'><?= $mensaje_exito ?></div>
        <?php endif; ?>
        <?php if(!empty($mensaje_error)): ?>
            <div class='alerta alerta-error'><?= $mensaje_error ?></div>
        <?php endif; ?>

        <div class="kpi-card form-card" style="margin-bottom: 30px;">
            <h3 style="margin-top:0; color:#0078d4; margin-bottom: 15px;">Aperturar Nuevo Semestre</h3>

            <form action="" method="POST" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label class="form-label">Nombre del Ciclo:</label>
                    <input type="text" name="nombre_ciclo" class="form-input" placeholder="Ej. Primer Semestre <?= date('Y') ?>" required>
                </div>
                
                <div>
                    <button type="submit" name="crear_ciclo" class="btn-primario">Crear Ciclo Académico</button>
                </div>

            </form>
        </div>

        <div class="kpi-card" style="width: 100%; overflow-x: auto; padding: 25px; box-sizing: border-box;">
            <h3 style="margin-top:0; color:#334155; margin-bottom: 20px;">Historial de Ciclos</h3>
            
            <?php if (count($lista_ciclos) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Ciclo Académico</th>
                            <th style="width: 150px;">Estado Semestre</th>
                            <th style="width: 220px;">Periodo de Asignaciones</th>
                            <th style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista_ciclos as $ciclo): ?>
                            <tr>
                                <td><?= $ciclo['id_ciclo'] ?></td>
                                <td><strong><?= htmlspecialchars($ciclo['nombre_ciclo']) ?></strong></td>
                                
                                <td>
                                    <?php if($ciclo['estado'] == 'Activo'): ?>
                                        <span class="badge activo">En Curso</span>
                                    <?php else: ?>
                                        <span class="badge inactivo">Cerrado</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if($ciclo['estado'] == 'Activo'): ?>
                                        <?php if($ciclo['asignaciones_abiertas'] == 1): ?>
                                            <a href="?accion=toggle_asignacion&id=<?= $ciclo['id_ciclo'] ?>&estado_actual=1" class="btn-switch on" title="Click para cerrar asignaciones">✅ ABIERTAS</a>
                                        <?php else: ?>
                                            <a href="?accion=toggle_asignacion&id=<?= $ciclo['id_ciclo'] ?>&estado_actual=0" class="btn-switch off" title="Click para abrir asignaciones">❌ CERRADAS</a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="texto-secundario">No disponible (Semestre cerrado)</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if($ciclo['estado'] == 'Activo'): ?>
                                        <a href="?accion=cerrar_ciclo&id=<?= $ciclo['id_ciclo'] ?>" class="btn-peligro" onclick="return confirm('¿Estás seguro de cerrar este semestre? Las notas quedarán congeladas y no se podrá reabrir.');">Terminar Semestre</a>
                                    <?php else: ?>
                                        <span class="texto-secundario">Sin acciones</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="reporte-vacio">
                    <h3 style="color: #64748b;">Sin Historial</h3>
                    <p>No hay ciclos registrados. Crea el primer semestre en el formulario de arriba.</p>
                </div>
            <?php endif; ?>
        </div>

    </main>

    <?php require 'footer.php'; ?>

    <script src="script_admin.js?v=<?php echo time(); ?>"></script>
</body>
</html>