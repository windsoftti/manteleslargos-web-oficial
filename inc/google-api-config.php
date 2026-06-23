<?php
$google_client = new Google_Client();
# $google_client->setClientId('112524213595-7gq1k5tu5srok1hkavu0767j789kg1m1.apps.googleusercontent.com');
# $google_client->setClientSecret('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');
$google_client->setClientId('831680601670-ke06l5n34jj0solbc5vivv72ggja1jff.apps.googleusercontent.com');
$google_client->setClientSecret('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');
//$google_client->setRedirectUri('https://manteleslargos.com/2021/web/google-session.php');
$google_client->setRedirectUri(BASE_URL . '/google-session.php');

// to get the email and profile 
$google_client->addScope('email');
$google_client->addScope('profile');
