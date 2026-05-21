<?php
require_once 'validar_sesion_estudiantes.php';
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['meses_pagados'])) {
        // Asegúrate de que el redireccionamiento vaya al archivo correcto de pagos
        header("Location: pagos.php?error=no_months");
        exit();
    }

    $meses_seleccionados = $_POST['meses_pagados'];
    
    // 1. CODIFICACIÓN SEGURA: urlencode evita que PayPal y el servidor se rompan por el espacio e tilde de "Inscripción S1"
    $meses_url = urlencode(implode(',', $meses_seleccionados));
    
    // Configuramos la cuota base en 400.00 para hacer match con tu archivo de éxito
    $monto_base = 400.00; 
    $dia_actual = (int)date('j');
    
    // 2. Contar cuántos meses regulares se seleccionaron (excluyendo inscripciones)
    $meses_regulares = 0;
    foreach ($meses_seleccionados as $mes) {
        if ($mes !== 'Inscripción S1' && $mes !== 'Inscripción S2') {
            $meses_regulares++;
        }
    }

    // 3. Determinar si cumple con la promoción del 10%
    $aplica_descuento = ($dia_actual <= 5 || $meses_regulares >= 5);

    // 4. Calcular el monto total exacto en Quetzales ítem por ítem
    $monto_total_gtq = 0;
    foreach ($meses_seleccionados as $mes) {
        // Las inscripciones siempre se cobran sin descuento
        if ($mes === 'Inscripción S1' || $mes === 'Inscripción S2') {
            $monto_total_gtq += $monto_base; 
        } else {
            // Meses regulares reciben el descuento si la bandera está activa
            if ($aplica_descuento) {
                $monto_total_gtq += ($monto_base * 0.90); // 360.00
            } else {
                $monto_total_gtq += $monto_base; // 400.00
            }
        }
    }
    
    // 5. CONVERSIÓN DE MONEDA (Ejemplo: 1 USD = 7.80 GTQ)
    $tasa_cambio = 7.80; 
    $monto_usd = round($monto_total_gtq / $tasa_cambio, 2);

    $client_id = $_ENV['PAYPAL_CLIENT_ID'];
    $secret = $_ENV['PAYPAL_SECRET'];

    // Obtener Token de Acceso
    $ch = curl_init("https://api-m.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_USERPWD, $client_id . ":" . $secret);
    $result = curl_exec($ch);
    $token = json_decode($result)->access_token;
    curl_close($ch);

    // Crear la Orden de Pago
    $data = [
        "intent" => "CAPTURE",
        "purchase_units" => [[
            "amount" => [
                "currency_code" => "USD",
                "value" => number_format($monto_usd, 2, '.', '')
            ],
            "description" => "Pago Universitario - " . implode(", ", $meses_seleccionados)
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

    // Redirigir al usuario a la pantalla de PayPal
    foreach ($order->links as $link) {
        if ($link->rel == 'approve') {
            header("Location: " . $link->href);
            exit();
        }
    }
}
?>