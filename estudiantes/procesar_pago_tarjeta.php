<?php
require_once 'validar_sesion_estudiantes.php';
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validamos que por lo menos se haya marcado un mes
    if (empty($_POST['meses_pagados'])) {
        header("Location: estado_cuenta.php?error=no_months");
        exit();
    }

    $meses_seleccionados = $_POST['meses_pagados'];
    $monto_por_mes = 450.00; 
    $line_items = [];

    // Mapeamos dinámicamente cada mes seleccionado como un producto en el checkout de Stripe
    foreach ($meses_seleccionados as $mes) {
        $line_items[] = [
            'price_data' => [
                'currency' => 'gtq',
                'product_data' => [
                    'name' => 'Mensualidad Universitaria - ' . $mes,
                ],
                'unit_amount' => $monto_por_mes * 100, // Stripe procesa en centavos (Q450.00 = 45000)
            ],
            'quantity' => 1,
        ];
    }

    // Convertimos el arreglo de meses a una cadena separada por comas para enviarlo en la URL de éxito
    $meses_url = implode(',', $meses_seleccionados);

    try {
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => 'http://localhost/proyecto/estudiantes/pago_exitoso.php?session_id={CHECKOUT_SESSION_ID}&meses=' . $meses_url,
            'cancel_url' => 'http://localhost/proyecto/estudiantes/estado_cuenta.php?error=cancelado',
        ]);

        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);
        exit();
        
    } catch (\Exception $e) {
        error_log("Error de Stripe al procesar grupo: " . $e->getMessage());
        header("Location: estado_cuenta.php?error=stripe_fail");
        exit();
    }
} else {
    header("Location: estado_cuenta.php");
    exit();
}
?>