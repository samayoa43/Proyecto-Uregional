<?php
require 'validar_sesion_estudiantes.php';
require '../conexion.php';

$id_usuario = $_SESSION['id_usuario'];

// 2. Obtener el id_estudiante
$stmt_id = $conexion->prepare("SELECT id_estudiante FROM estudiantes WHERE id_usuario = ?");
$stmt_id->execute([$id_usuario]);
$id_estudiante = $stmt_id->fetchColumn();

// 3. Obtener el historial de pagos del estudiante
$stmt_pagos = $conexion->prepare("
    SELECT mes_pagado, monto, fecha_pago, numero_boleta 
    FROM pagos 
    WHERE id_estudiante = ? 
    ORDER BY fecha_pago DESC
");
$stmt_pagos->execute([$id_estudiante]);
$pagos_historial = $stmt_pagos->fetchAll(PDO::FETCH_ASSOC);

// 4. Lógica inteligente: Filtrar los meses que ya pagó
$meses_ciclo = ['Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre'];
$meses_pagados = array_column($pagos_historial, 'mes_pagado');

// array_diff saca de la lista los meses que ya están en la base de datos
$meses_pendientes = array_diff($meses_ciclo, $meses_pagados); 

// Calcular el total pagado hasta ahora
$total_pagado = array_sum(array_column($pagos_historial, 'monto'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Cuenta - Campus Pro</title>
    <link rel="stylesheet" href="../css/estilos_dashboard.css">
    <style>
        .panel-grid { display: grid; gap: 1.5rem; }
        .two-cols { grid-template-columns: 1fr 1fr; }
        .form-group { display: grid; gap: 0.5rem; margin-bottom: 1rem; }
        .form-group label { color: var(--muted); font-size: 0.95rem; font-weight: 600; }
        .form-group input, .form-group select { width: 100%; border: 1px solid rgba(255,255,255,0.09); background: rgba(255,255,255,0.04); border-radius: 12px; padding: 0.95rem 1rem; color: #ecf2ff; outline: none; }
        .form-group select option { color: #111827; }
        .btn-primary { background: linear-gradient(135deg, #5b8cff, #3b6fff); color: white; border: none; border-radius: 12px; padding: 1rem 1.2rem; font-weight: bold; cursor: pointer; width: 100%; }
        @media (max-width: 900px) { .two-cols { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="app-shell">
    
    <?php include 'encabezado.php'; ?>

    <main class="content">
        <section class="hero glass">
            <div>
                <span class="hero-badge">Financiero</span>
                <h1>Estado de Cuenta</h1>
                <p class="muted">Registra tus boletas de pago y revisa tu solvencia económica del ciclo actual.</p>
            </div>
            <div class="hero-meta">
                <div><strong>Total Abonado:</strong> Q<?php echo number_format($total_pagado, 2); ?></div>
                <div><strong>Cuotas Pagadas:</strong> <?php echo count($meses_pagados); ?> de 10</div>
            </div>
        </section>

        <?php if (isset($_GET['exito'])): ?>
            <div class="alert alert-success">¡Pago registrado exitosamente!</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger">Error al procesar el pago. Revisa los datos o verifica si el mes ya fue pagado.</div>
        <?php endif; ?>

        <section class="panel-grid two-cols">
            <article class="panel glass">
                <div class="panel-header">
                    <h3>Registrar Boleta</h3>
                    <p class="muted">Ingresa los datos de tu transferencia o depósito bancario.</p>
                </div>

                <?php if (empty($meses_pendientes)): ?>
                    <div class="alert alert-success" style="margin-top: 1rem;">
                        <strong>¡Felicidades!</strong> Estás completamente solvente para todo el ciclo académico. No tienes cuotas pendientes.
                    </div>
                <?php else: ?>
                <form action="procesar_pagos.php" method="POST" onsubmit="this.querySelector('button').disabled = true;">
                        <div class="form-group">
                            <label>Meses a Pagar (Selecciona uno o varios)</label>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem; background: rgba(255,255,255,0.04); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.09);">
                            <?php foreach ($meses_pendientes as $mes): ?>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: #ecf2ff; font-weight: normal;">
                             <input type="checkbox" name="meses_pagados[]" value="<?php echo $mes; ?>" style="width: auto;"> 
                             <?php echo $mes; ?>
                            </label>
                             <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Monto (Q)</label>
                            <input type="number" name="monto" step="0.01" min="1" placeholder="Ej. 450.00" required>
                        </div>

                        <div class="form-group">
                            <label>Número de Boleta / Referencia</label>
                            <input type="text" name="numero_boleta" placeholder="Ej. TRX-123456" required>
                        </div>

                        <button type="submit" class="btn-primary">Registrar Pago</button>
                    </form>
                <?php endif; ?>
            </article>

            <article class="panel glass">
                <div class="panel-header">
                    <h3>Historial de Movimientos</h3>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Mes</th>
                                <th>Boleta</th>
                                <th>Monto</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($pagos_historial) > 0): ?>
                                <?php foreach ($pagos_historial as $pago): ?>
                                    <tr>
                                        <td><strong><?php echo $pago['mes_pagado']; ?></strong></td>
                                        <td><?php echo htmlspecialchars($pago['numero_boleta']); ?></td>
                                        <td>Q<?php echo number_format($pago['monto'], 2); ?></td>
                                        <td class="muted"><?php echo date("d/m/Y", strtotime($pago['fecha_pago'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 2rem;">Aún no tienes pagos registrados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </article>
        </section>
    </main>
</div>
</body>
</html>