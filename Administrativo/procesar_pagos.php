<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../conexion.php';

$mensaje_exito = null;
$mensaje_error = null;

// 1. PROCESAR UN NUEVO PAGO MÚLTIPLE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrar_pago'])) {
    $id_estudiante = $_POST['id_estudiante'];
    $monto_total = $_POST['monto'];
    $numero_boleta = trim($_POST['numero_boleta']);
    
    // Capturamos el arreglo de meses marcados en los checkboxes
    $meses_seleccionados = isset($_POST['meses']) ? $_POST['meses'] : [];

    if (empty($meses_seleccionados)) {
        $mensaje_error = "Error: Debes seleccionar al menos un mes a pagar.";
    } else {
        // Dividimos el total entre los meses marcados
        $cantidad_meses = count($meses_seleccionados);
        $monto_por_mes = $monto_total / $cantidad_meses;

        try {
            // INICIAMOS TRANSACCIÓN: O se guardan todos los meses, o no se guarda ninguno
            $conexion->beginTransaction();

            $sql_insert = "INSERT INTO pagos (id_estudiante, mes_pagado, monto, numero_boleta) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conexion->prepare($sql_insert);

            // Un ciclo para registrar cada mes seleccionado usando la misma boleta
            foreach ($meses_seleccionados as $mes) {
                $stmt_insert->execute([$id_estudiante, $mes, $monto_por_mes, $numero_boleta]);
            }

            // Confirmamos la operación múltiple
            $conexion->commit();
            $mensaje_exito = "¡Se registraron $cantidad_meses mes(es) con la boleta $numero_boleta exitosamente!";

        } catch(PDOException $e) {
            $conexion->rollBack(); // Si falla, cancelamos todo

            if ($e->getCode() == 23000) {
                $mensaje_error = "Alerta: El estudiante ya tiene registrado el pago de uno de los meses seleccionados.";
            } else {
                $mensaje_error = "Error al guardar el pago: " . $e->getMessage();
            }
        }
    }
}

// 2. OBTENER LISTA DE ESTUDIANTES
$lista_estudiantes = [];
try {
    $stmt_est = $conexion->query("SELECT id_estudiante, nombres, apellidos FROM estudiantes ORDER BY apellidos ASC");
    $lista_estudiantes = $stmt_est->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $mensaje_error = "Error al cargar estudiantes: " . $e->getMessage();
}

// 3. CARGAR HISTORIAL DE PAGOS
$estudiante_seleccionado = isset($_GET['id_estudiante']) ? $_GET['id_estudiante'] : (isset($_POST['id_estudiante']) ? $_POST['id_estudiante'] : null);
$historial_pagos = [];

if ($estudiante_seleccionado) {
    try {
        $sql_pagos = "SELECT mes_pagado, monto, fecha_pago, numero_boleta 
                      FROM pagos 
                      WHERE id_estudiante = ? 
                      ORDER BY fecha_pago DESC";
        $stmt_pagos = $conexion->prepare($sql_pagos);
        $stmt_pagos->execute([$estudiante_seleccionado]);
        $historial_pagos = $stmt_pagos->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $mensaje_error = "Error al cargar el historial de pagos: " . $e->getMessage();
    }
}

$meses_permitidos = ['Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre'];
?>

