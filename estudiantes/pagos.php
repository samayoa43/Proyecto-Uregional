<?php
require 'validar_sesion_estudiantes.php';
require '../conexion.php';

$id_usuario = $_SESSION['id_usuario'];

// 1. Obtener el id_estudiante
$stmt_id = $conexion->prepare("SELECT id_estudiante FROM estudiantes WHERE id_usuario = ?");
$stmt_id->execute([$id_usuario]);
$id_estudiante = $stmt_id->fetchColumn();

// 2. Obtener el historial de pagos
$stmt_pagos = $conexion->prepare("
    SELECT mes_pagado, monto, fecha_pago, numero_boleta 
    FROM pagos 
    WHERE id_estudiante = ? 
    ORDER BY fecha_pago DESC
");
$stmt_pagos->execute([$id_estudiante]);
$pagos_historial = $stmt_pagos->fetchAll(PDO::FETCH_ASSOC);

// SEGURO 1: Forzamos a que siempre sea un arreglo, aunque la base de datos devuelva falso
if (!is_array($pagos_historial)) {
    $pagos_historial = [];
}

$meses_ciclo = ['Inscripción S1', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Inscripción S2', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre'];

// SEGURO 2: Extraemos los datos con un ciclo manual, lo que evita por completo el error bool
$meses_pagados = [];
$total_pagado = 0;

foreach ($pagos_historial as $pago) {
    // Llenamos la lista de meses que ya pagó
    $meses_pagados[] = $pago['mes_pagado'];
    // Sumamos el dinero
    $total_pagado += (float)$pago['monto'];
}

// 3. Ahora array_diff recibe dos arreglos garantizados, ¡adiós error!
$meses_pendientes = array_diff($meses_ciclo, $meses_pagados); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Cuenta</title>
    <link rel="stylesheet" href="estilos_estudiantes.css">
    <link rel="icon" href="../img/img_02.png" type="image/png">
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
                <div><strong>Cuotas Pagadas:</strong> <?php echo count($meses_pagados); ?> de 12</div>
            </div>
        </section>

        <?php if (isset($_GET['exito'])): ?>
            <div class="alert alert-success">¡Pago registrado exitosamente!</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger">Error al procesar el pago. Revisa los datos o verifica si el mes ya fue pagado.</div>
        <?php endif; ?>
        <?php if (isset($_GET['exito'])): ?>
            <div class="alert alert-success">¡Pago registrado exitosamente!</div>
        <?php elseif (isset($_GET['error']) && $_GET['error'] == 'cancelado'): ?>
            <div class="alert alert-danger">El pago fue cancelado o no se completó.</div>
        
        <?php elseif (isset($_GET['alerta']) && $_GET['alerta'] == 'moroso'): ?>
            <div class="alert" style="background: rgba(244, 67, 54, 0.1); border-left: 5px solid #f44336; padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px;">
                <h4 style="color: #ffcdd2; margin-top: 0; margin-bottom: 0.5rem;">⚠️ Funcionalidad Bloqueada</h4>
                <p style="color: #ecf2ff; margin: 0;">
                    Por políticas de la universidad, tu acceso a ciertas áreas está restringido porque no registras el pago de la cuota correspondiente a <strong><?php echo htmlspecialchars($_GET['mes']); ?></strong>. 
                    Por favor, realiza tu pago para liberar el sistema.
                </p>
            </div>
        <?php endif; ?>

        <section class="panel-grid two-cols">
<article class="panel glass">
    <div class="panel-header">
        <h3>Realizar Pago Académico</h3>
        <p class="muted">Selecciona las cuotas pendientes y el método de pago de tu preferencia.</p>
    </div>

    <?php if (empty($meses_pendientes)): ?>
        <div class="alert alert-success" style="margin-top: 1rem;">
            <strong>¡Felicidades!</strong> Estás completamente solvente para todo el ciclo académico. No tienes cuotas pendientes.
        </div>
    <?php else: ?>
        <?php if (date('j') <= 5): ?>
    <div class="alert" style="background: rgba(24, 194, 156, 0.14); border: 1px solid #18c29c; color: #18c29c; margin-bottom: 1rem;">
        ⭐ <strong>¡Aprovecha el Pronto Pago!</strong> Por pagar en los primeros 5 días del mes, tienes un <strong>10% de descuento</strong> en todas tus cuotas regulares.
    </div>
<?php endif; ?>
<div class="alert" style="background: rgba(91, 140, 255, 0.1); border: 1px solid #5b8cff; color: #8cb0ff; margin-bottom: 1rem;">
    💡 <strong>Tip:</strong> Si pagas un semestre completo (5 cuotas regulares juntas), también recibes el 10% de descuento sin importar la fecha.
</div>
        <form id="form-pago" action="procesar_pagos.php" method="POST" onsubmit="this.querySelector('button[type=submit]').disabled = true;">
            
            <div class="form-group">
                <label>Meses a Pagar (Selecciona uno o varios)</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem; background: rgba(255,255,255,0.04); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.09);">
                    <?php foreach ($meses_pendientes as $mes): ?>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: #ecf2ff; font-weight: normal;">
                            <input type="checkbox" name="meses_pagados[]" value="<?php echo $mes; ?>" class="chk-mes" style="width: auto;"> 
                            <?php echo $mes; ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Método de Pago</label>
                <div style="display: flex; gap: 1.5rem; background: rgba(255,255,255,0.02); padding: 0.8rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: #ecf2ff;">
                 <input type="radio" name="metodo_pago" value="boleta" checked onclick="cambiarMetodoPago('boleta')" style="width:auto;"> 🏛️ Depósito / Boleta
                </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: #ecf2ff;">
            <input type="radio" name="metodo_pago" value="tarjeta" onclick="cambiarMetodoPago('tarjeta')" style="width:auto;"> 💳 Tarjeta (Stripe)
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: #ecf2ff;">
            <input type="radio" name="metodo_pago" value="paypal" onclick="cambiarMetodoPago('paypal')" style="width:auto;"> 🅿️ PayPal
            </label>
             </div>
            </div>

            <div class="form-group">
                <label>Monto Total (Q)</label>
                <input type="number" id="monto_total" name="monto" step="0.01" placeholder="0.00" required disabled>
            </div>

            <div class="form-group" id="grupo-boleta">
                <label>Número de Boleta / Referencia</label>
                <input type="text" id="numero_boleta" name="numero_boleta" placeholder="Ej. TRX-123456" required>
            </div>

            <button type="submit" id="btn-submit-pago" class="btn-primary">Registrar Pago con Boleta</button>
        </form>
    <?php endif; ?>
</article>
<script>
const costoBase = 400.00;
const diaActual = <?php echo (int)date('j'); ?>; // PHP inyecta el día exacto del servidor
const form = document.getElementById('form-pago');
const grupoBoleta = document.getElementById('grupo-boleta');
const inputBoleta = document.getElementById('numero_boleta');
const inputMonto = document.getElementById('monto_total');
const btnSubmit = document.getElementById('btn-submit-pago');
const checkboxes = document.querySelectorAll('.chk-mes');

function cambiarMetodoPago(metodo) {
    if (metodo === 'boleta') {
        form.action = 'procesar_pagos.php';
        grupoBoleta.style.display = 'grid';
        inputBoleta.required = true;
        inputMonto.readOnly = false;
        btnSubmit.textContent = 'Registrar Pago con Boleta';
        btnSubmit.style.background = 'linear-gradient(135deg, #5b8cff, #3b6fff)';
    } else if (metodo === 'tarjeta') {
        form.action = 'procesar_pago_tarjeta.php';
        grupoBoleta.style.display = 'none';
        inputBoleta.required = false;
        inputMonto.readOnly = true; 
        btnSubmit.textContent = 'Proceder al Pago con Tarjeta';
        btnSubmit.style.background = 'linear-gradient(135deg, #00b0ff, #0081cb)';
    } else if (metodo === 'paypal') {
        form.action = 'procesar_pago_paypal.php';
        grupoBoleta.style.display = 'none';
        inputBoleta.required = false;
        inputMonto.readOnly = true; 
        btnSubmit.textContent = 'Pagar con PayPal';
        btnSubmit.style.background = 'linear-gradient(135deg, #003087, #009cde)';
    }
}

function calcularTotal() {
    let total = 0;
    let mesesRegulares = 0;
    let aplicaDescuento = false;

    // 1. Contar cuántas cuotas regulares se seleccionaron (excluyendo inscripciones)
    checkboxes.forEach(cb => {
        if (cb.checked && cb.value !== 'Inscripción S1' && cb.value !== 'Inscripción S2') {
            mesesRegulares++;
        }
    });

    // 2. Validar si es acreedor al descuento del 10%
    if (diaActual <= 5 || mesesRegulares >= 5) {
        aplicaDescuento = true;
    }

    // 3. Sumar el dinero
    checkboxes.forEach(cb => {
        if (cb.checked) {
            if (cb.value === 'Inscripción S1' || cb.value === 'Inscripción S2') {
                total += costoBase; // Las inscripciones se cobran netas
            } else {
                total += aplicaDescuento ? (costoBase * 0.90) : costoBase; // Aplica 10% si cumple
            }
        }
    });

    inputMonto.value = total.toFixed(2);
}

checkboxes.forEach(cb => {
    cb.addEventListener('change', calcularTotal);
});
</script>

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