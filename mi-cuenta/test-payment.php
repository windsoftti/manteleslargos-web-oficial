<?php

include 'inc/session.php';

echo '<pre>';

print_r(

    getMercadoPagoPayment(
        //'PON_AQUI_UN_PAYMENT_ID_REAL'
        '165841849134'
    )

);

echo '</pre>';