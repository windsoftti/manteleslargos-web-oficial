<?php

include 'inc/session.php';

$order = getSubscriptionOrder(4);

echo '<pre>';

print_r(
    createMercadoPagoPreference(
        $order
    )
);

echo '</pre>';