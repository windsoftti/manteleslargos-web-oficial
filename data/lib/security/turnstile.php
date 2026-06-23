<?php

require_once __DIR__ . '/turnstile-config.php';

function validateTurnstile($token)
{
    if (empty($token)) {
        return false;
    }

    $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    $data = [
        'secret' => TURNSTILE_SECRET_KEY,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
            'timeout' => 10
        ]
    ];

    $context = stream_context_create($options);

    $result = file_get_contents($url, false, $context);

    if (!$result) {
        return false;
    }

    $response = json_decode($result, true);

    return !empty($response['success']);
}