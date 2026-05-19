<?php
require_once 'validar_sesion_estudiantes.php';
require_once '../conexion.php';
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if (isset($_GET['token']) && isset($_GET['meses'])) {
    $order_id = $_GET['token']; // PayPal envia el ID en el parámetro 'token'
    $meses_a_registrar = explode(',', $_GET['meses']);
    $id_estudiante = $_SESSION['id_estudiante'];
    $monto_por_mes = 450.00; // Lo guardamos en su valor original en Quetzales

    $client_id = $_ENV['PAYPAL_CLIENT_ID'];
    $secret = $_ENV['PAYPAL_SECRET'];

    // 1. Obtener Token de Acceso
    $ch = curl_init("https://api-m.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_USERPWD, $client_id . ":" . $secret);
    $result = curl_exec($ch);
    $access_token = json_decode($result)->access_token;
    curl_close($ch);

    // 2. Capturar el pago (Extraer el dinero)
    $ch = curl_init("https://api-m.sandbox.paypal.com/v2/checkout/orders/" . $order_id . "/capture");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $access_token
    ]);
    $capture_result = curl_exec($ch);
    $capture = json_decode($capture_result);
    curl_close($ch);

    // 3. Validar y Guardar en la Base de Datos
    if (isset($capture->status) && $capture->status == 'COMPLETED') {
        try {
            $conexion->beginTransaction();
            $sql = "INSERT INTO pagos (id_estudiante, mes_pagado, monto, numero_boleta) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);

            // Obtener el ID de la transacción final de PayPal
            $transaccion_id = $capture->purchase_units[0]->payments->captures[0]->id;

            foreach ($meses_a_registrar as $mes) {
                $stmt->execute([$id_estudiante, $mes, $monto_por_mes, $transaccion_id]);
            }

            $conexion->commit();
            header("Location: pagos.php?exito=1");
            exit();
        } catch (Exception $e) {
            $conexion->rollBack();
            header("Location: pagos.php?error=db_fail");
            exit();
        }
    } else {
        header("Location: pagos.php?error=paypal_not_completed");
        exit();
    }
} else {
    header("Location: pagos.php");
    exit();
}
?>