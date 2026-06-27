<?php

include 'inc/session.php';

$order = getSubscriptionOrder(1);

echo '<pre>';

print_r(
    createMercadoPagoPreference(
        $order
    )
);

echo '</pre>';