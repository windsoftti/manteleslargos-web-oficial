<?php

include 'inc/session.php';

echo '<pre>';

echo "Enabled:\n";

var_dump(
    isMercadoPagoEnabled()
);

echo "\n";

echo "Public Key:\n";

var_dump(
    getMercadoPagoPublicKey()
);

echo "\n";

echo "Access Token:\n";

var_dump(
    getMercadoPagoAccessToken()
);

echo "\n";

echo "Webhook Secret:\n";

var_dump(
    getMercadoPagoWebhookSecret()
);

echo '</pre>';