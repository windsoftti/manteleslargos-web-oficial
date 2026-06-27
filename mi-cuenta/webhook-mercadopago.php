<?php

include 'inc/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    echo 'Webhook Mercado Pago operativo.';

    exit;
}

/*
|--------------------------------------------------------------------------
| Webhook Mercado Pago
|--------------------------------------------------------------------------
|
| Mercado Pago enviará un POST a este endpoint.
|
*/

$payload =
    file_get_contents(
        'php://input'
    );

if (empty($payload)) {

    http_response_code(400);

    exit;
}

$data =
    json_decode(
        $payload,
        true
    );

if (
    empty($data['type'])
) {

    http_response_code(200);

    exit;
}

/*
|--------------------------------------------------------------------------
| Sólo pagos
|--------------------------------------------------------------------------
*/

if (
    $data['type']
    !== 'payment'
) {

    http_response_code(200);

    exit;
}

/*
|--------------------------------------------------------------------------
| ID del pago
|--------------------------------------------------------------------------
*/

$paymentId =
    $data['data']['id'] ?? null;

if (!$paymentId) {

    http_response_code(400);

    exit;
}

/*
|--------------------------------------------------------------------------
| Procesar
|--------------------------------------------------------------------------
*/

$result =
    processMercadoPagoPayment(
        (string) $paymentId
    );

/*
|--------------------------------------------------------------------------
| Siempre responder 200
|--------------------------------------------------------------------------
|
| Mercado Pago volverá a intentar
| cuando reciba otro código.
|
*/

http_response_code(200);

echo json_encode($result);