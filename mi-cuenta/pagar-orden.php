<?php

include 'inc/session.php';

$orderId =
    (int) ($_GET['id'] ?? 0);

$order =
    getOrderById($orderId);

if (!$order) {

    header(
        'location:mis-ordenes'
    );

    exit;
}

if (
    $order['status'] !== 'pending'
) {

    header(
        'location:orden?id=' .
        $orderId
    );

    exit;
}

$preference =
    createMercadoPagoPreference(
        $order
    );

if (
    $preference['status']
    !== 'success'
) {

    die(
        $preference['message']
    );
}

header(
    'Location: ' .
    $preference['checkout_url']
);

exit;