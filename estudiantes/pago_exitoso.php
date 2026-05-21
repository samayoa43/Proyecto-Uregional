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
    
    // Cuota base actual
    $monto_base = 400.00; 
    $dia_actual = (int)date('j');

    $meses_a_registrar = explode(',', $meses_string);

    $meses_regulares = 0;
    foreach ($meses_a_registrar as $mes) {
        if ($mes !== 'Inscripción S1' && $mes !== 'Inscripción S2') {
            $meses_regulares++;
        }
    }

    $aplica_descuento = ($dia_actual <= 5 || $meses_regulares >= 5);

    try {
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        
        if ($session->payment_status == 'paid') {
            
            // Iniciamos una transacción para asegurar que se registren todos los meses o ninguno
            $conexion->beginTransaction();

            $sql = "INSERT INTO pagos (id_estudiante, mes_pagado, monto, numero_boleta) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);

            foreach ($meses_a_registrar as $mes) {
                $monto_final = $monto_base;
                
                // Si es un mes normal y ganó el descuento, le quitamos el 10%
                if ($mes !== 'Inscripción S1' && $mes !== 'Inscripción S2' && $aplica_descuento) {
                    $monto_final = $monto_base * 0.90; // Si la base es 400, guardará 360.
                }
                $stmt->execute([$id_estudiante, $mes, $monto_final, $session->payment_intent]);
            }

            $conexion->commit();

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