<?php
require 'validar_sesion_estudiantes.php';
require '../conexion.php';

// 1. Recibimos la variable como un arreglo (Array) porque viene de checkboxes
$meses_pagados = $_POST['meses_pagados'] ?? [];
$monto_total = $_POST['monto'];
$numero_boleta = trim($_POST['numero_boleta']);

// Validación: Evitar que el alumno mande el formulario sin seleccionar ningún mes
if (empty($meses_pagados)) {
    header("Location: mis_pagos.php?error=sin_mes");
    exit();
}

// Obtener el ID del estudiante
$stmt_id = $conexion->prepare("SELECT id_estudiante FROM estudiantes WHERE id_usuario = ?");
$stmt_id->execute([$_SESSION['id_usuario']]);
$id_estudiante = $stmt_id->fetchColumn();

// 2. Calcular el monto que le corresponde a cada mes
$cantidad_meses = count($meses_pagados);
$monto_por_mes = $monto_total / $cantidad_meses;

try {
    // 3. Iniciamos una transacción (O se guardan todos los meses, o no se guarda ninguno)
    $conexion->beginTransaction();

    $sql_insert = "INSERT INTO pagos (id_estudiante, mes_pagado, monto, numero_boleta) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conexion->prepare($sql_insert);

    // 4. Recorremos el arreglo y ejecutamos un INSERT por cada mes que seleccionó
    foreach ($meses_pagados as $mes) {
        $stmt_insert->execute([$id_estudiante, $mes, $monto_por_mes, $numero_boleta]);
    }

    // Si el ciclo terminó sin problemas, confirmamos los cambios en MySQL
    $conexion->commit();

    header("Location: pagos.php?exito=1");
    exit();

} catch (PDOException $e) {
    // Si hubo un error (ej. saltó el duplicado), revertimos toda la transacción
    $conexion->rollBack();

    // Si la base de datos arroja el error 23000 (Integrity constraint violation)
    if ($e->getCode() == 23000) {
        header("Location: pagos.php?error=duplicado");
    } else {
        // Cualquier otro error de base de datos
        header("Location: pagos.php?error=db");
    }
    exit();
}
?>