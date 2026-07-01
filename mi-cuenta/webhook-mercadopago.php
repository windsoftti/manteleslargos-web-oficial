<?php

include 'inc/bootstrap.php';

/*if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    echo 'Webhook Mercado Pago operativo.';

    exit;
}*/

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

writeMercadoPagoLog(
    'Webhook recibido',
    [
        'payload' => $payload
    ]
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

writeMercadoPagoLog(
    'Webhook decodificado',
    $data
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

writeMercadoPagoLog(
    'Payment ID recibido',
    [
        'payment_id' => $paymentId
    ]
);

if (!$paymentId) {

    http_response_code(400);

    exit;
}

/*
|--------------------------------------------------------------------------
| Procesar
|--------------------------------------------------------------------------
*/

writeMercadoPagoLog(
    'Procesando pago'
);

$result =
    processMercadoPagoPayment(
        (string) $paymentId
    );

writeMercadoPagoLog(
    'Resultado del procesamiento',
    $result
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