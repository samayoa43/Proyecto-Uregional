<?php
require_once 'validar_sesion_estudiantes.php';
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['meses_pagados'])) {
        header("Location: estado_cuenta.php?error=no_months");
        exit();
    }

    $meses_seleccionados = $_POST['meses_pagados'];
    $meses_url = implode(',', $meses_seleccionados);
    
    // CONVERSIÓN DE MONEDA (Ejemplo: 1 USD = 7.80 GTQ)
    $monto_gtq = count($meses_seleccionados) * 450.00;
    $tasa_cambio = 7.80; 
    $monto_usd = round($monto_gtq / $tasa_cambio, 2);

    $client_id = $_ENV['PAYPAL_CLIENT_ID'];
    $secret = $_ENV['PAYPAL_SECRET'];

    // 1. Obtener Token de Acceso
    $ch = curl_init("https://api-m.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_USERPWD, $client_id . ":" . $secret);
    $result = curl_exec($ch);
    $token = json_decode($result)->access_token;
    curl_close($ch);

    // 2. Crear la Orden de Pago
    $data = [
        "intent" => "CAPTURE",
        "purchase_units" => [[
            "amount" => [
                "currency_code" => "USD",
                "value" => number_format($monto_usd, 2, '.', '')
            ],
            "description" => "Mensualidad - " . implode(", ", $meses_seleccionados)
        ]],
        "application_context" => [
            "return_url" => "http://localhost/proyecto/estudiantes/pago_exitoso_paypal.php?meses=" . $meses_url,
            "cancel_url" => "http://localhost/proyecto/estudiantes/pagos.php?error=cancelado"
        ]
    ];

    $ch = curl_init("https://api-m.sandbox.paypal.com/v2/checkout/orders");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $token
    ]);
    $order_result = curl_exec($ch);
    $order = json_decode($order_result);
    curl_close($ch);

    // 3. Redirigir al usuario a la pantalla de PayPal
    foreach ($order->links as $link) {
        if ($link->rel == 'approve') {
            header("Location: " . $link->href);
            exit();
        }
    }
}
?>