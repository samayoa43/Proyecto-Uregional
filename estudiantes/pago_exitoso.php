<?php
require_once 'validar_sesion_estudiantes.php';
require_once '../conexion.php';
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

if (isset($_GET['session_id']) && isset($_GET['meses'])) {
    $session_id = $_GET['session_id'];
    $meses_string = $_GET['meses'];
    $id_estudiante = $_SESSION['id_estudiante'];
    $monto_por_mes = 450.00;

    // Convertimos la cadena de la URL nuevamente en un arreglo almacenable
    $meses_a_registrar = explode(',', $meses_string);

    try {
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        
        if ($session->payment_status == 'paid') {
            
            // Iniciamos una transacción para asegurar que se registren todos los meses o ninguno
            $conexion->beginTransaction();

            $sql = "INSERT INTO pagos (id_estudiante, mes_pagado, monto, numero_boleta) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);

            foreach ($meses_a_registrar as $mes) {
                // Se guarda el ID de intención de pago de Stripe como el número identificador de boleta
                $stmt->execute([$id_estudiante, $mes, $monto_por_mes, $session->payment_intent]);
            }

            $conexion->commit();

            // Redirige de vuelta reflejando el mensaje de éxito estructurado en tu interfaz
            header("Location: pagos.php?exito=1");
            exit();
        }
    } catch (Exception $e) {
        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }
        error_log("Error en el registro transaccional del pago: " . $e->getMessage());
        header("Location: pagos.php?error=db_fail");
        exit();
    }
} else {
    header("Location: pagos.php");
    exit();
}
?>