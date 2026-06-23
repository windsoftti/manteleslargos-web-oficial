<?php
require_once '../data/lib/google-sdk/vendor/autoload.php';

$google_client = new Google_Client();
$google_client->setClientId('112524213595-7gq1k5tu5srok1hkavu0767j789kg1m1.apps.googleusercontent.com');
$google_client->setClientSecret('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');
$google_client->setRedirectUri('https://manteleslargos.com/2022/index.php');

// to get the email and profile 
$google_client->addScope('email');
$google_client->addScope('profile');
