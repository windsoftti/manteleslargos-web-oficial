<?php

include 'inc/session.php';

$orderId =
    (int) ($_GET['id'] ?? 0);

$result =
    markOrderAsPaid(
        $orderId
    );

if (
    $result['status']
    !== 'success'
) {

    die(
        $result['message']
    );
}

header(
    'location:mi-suscripcion'
);

exit;