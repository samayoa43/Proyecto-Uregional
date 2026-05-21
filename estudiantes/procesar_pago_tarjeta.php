<?php
require_once 'validar_sesion_estudiantes.php';
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validamos que por lo menos se haya marcado un mes o inscripción
    if (empty($_POST['meses_pagados'])) {
        header("Location: pagos.php?error=no_months");
        exit();
    }

    $meses_seleccionados = $_POST['meses_pagados'];
    $monto_base = 400.00; // Tu cuota base establecida
    $dia_actual = (int)date('j'); // Obtenemos el día del mes actual (1 al 31)
    $line_items = [];

    // 1. Contar cuántos meses regulares se seleccionaron (excluyendo inscripciones)
    $meses_regulares = 0;
    foreach ($meses_seleccionados as $mes) {
        if ($mes !== 'Inscripción S1' && $mes !== 'Inscripción S2') {
            $meses_regulares++;
        }
    }

    // 2. Determinar si cumple con alguna de las condiciones para el 10% de descuento
    $aplica_descuento = ($dia_actual <= 5 || $meses_regulares >= 5);

    // 3. Mapeamos dinámicamente cada concepto aplicando las reglas financieras
    foreach ($meses_seleccionados as $mes) {
        $monto_final = $monto_base;
        $nombre_item = 'Mensualidad Universitaria - ' . $mes;

        // Si el ítem es una inscripción, se cobra neto sin importar la fecha o volumen
        if ($mes === 'Inscripción S1' || $mes === 'Inscripción S2') {
            $monto_final = $monto_base; 
            $nombre_item = 'Inscripción Semestral - ' . $mes;
        } else {
            // Si es un mes regular y aplica el descuento, restamos el 10%
            if ($aplica_descuento) {
                $monto_final = $monto_base * 0.90; // Q360.00
                
                if ($dia_actual <= 5) {
                    $nombre_item .= ' (10% Descuento Pronto Pago)';
                } else {
                    $nombre_item .= ' (10% Descuento Semestre Completo)';
                }
            }
        }

        $line_items[] = [
            'price_data' => [
                'currency' => 'gtq',
                'product_data' => [
                    'name' => $nombre_item,
                ],
                'unit_amount' => round($monto_final * 100), // Stripe procesa en centavos
            ],
            'quantity' => 1,
        ];
    }

// Convertimos el arreglo y lo CODIFICAMOS para que Stripe acepte los espacios y tildes en la URL
    $meses_url = urlencode(implode(',', $meses_seleccionados));

    try {
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => 'http://localhost/proyecto/estudiantes/pago_exitoso.php?session_id={CHECKOUT_SESSION_ID}&meses=' . $meses_url,
            'cancel_url' => 'http://localhost/proyecto/estudiantes/pagos.php?error=cancelado',
        ]);

        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);
        exit();
        
    } catch (\Exception $e) {
        error_log("Error de Stripe al procesar grupo: " . $e->getMessage());
        header("Location: pagos.php?error=stripe_fail");
        exit();
    }
} else {
    header("Location: pagos.php");
    exit();
}
?>