<?php
// 1. Definimos el ciclo académico completo (Febrero a Noviembre)
$meses_ciclo = [
    2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
    7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre'
];

$mes_actual_numero = (int)date('n'); // Mes actual en número (Ej. Mayo = 5)

// Solo verificamos si estamos en febrero o después
if ($mes_actual_numero >= 2) {
    
    // Si el año ya terminó (diciembre o enero), exigimos solvencia hasta noviembre
    $mes_limite = ($mes_actual_numero > 11) ? 11 : $mes_actual_numero;

    // 2. Obtener el ID del estudiante
    $id_usuario_sesion = $_SESSION['id_usuario'];
    $stmt_id = $conexion->prepare("SELECT id_estudiante FROM estudiantes WHERE id_usuario = ?");
    $stmt_id->execute([$id_usuario_sesion]);
    $id_estudiante_solvencia = $stmt_id->fetchColumn();

    // 3. Obtener TODOS los meses que el alumno ya pagó
    $stmt_pagos = $conexion->prepare("SELECT mes_pagado FROM pagos WHERE id_estudiante = ?");
    $stmt_pagos->execute([$id_estudiante_solvencia]);
    $pagos_realizados = $stmt_pagos->fetchAll(PDO::FETCH_COLUMN);

    if (!is_array($pagos_realizados)) {
        $pagos_realizados = [];
    }

    // 4. Crear la lista de meses que DEBEN estar pagados hasta el día de hoy
    $meses_requeridos = [];
    for ($i = 2; $i <= $mes_limite; $i++) {
        $meses_requeridos[] = $meses_ciclo[$i];
    }

    // 5. Comparar: ¿Hay algún mes requerido que NO esté en sus pagos?
    $meses_adeudados = array_diff($meses_requeridos, $pagos_realizados);

    // 6. Si debe al menos un mes, lo bloqueamos
    if (!empty($meses_adeudados)) {
        // Obtenemos el mes más antiguo que debe para decírselo en la alerta
        // Ej: Si debe marzo y mayo, le cobraremos primero marzo.
        $primer_mes_adeudado = reset($meses_adeudados); 
        
        header("Location: pagos.php?alerta=moroso&mes=" . urlencode($primer_mes_adeudado));
        exit();
    }
}
?>