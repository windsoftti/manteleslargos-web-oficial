<?php
session_start();

$time_to_remove = time() + (60 * 60 * 24 * 365);
$cookie = setcookie('webpagecookies', '1', $time_to_remove, '/');

echo json_encode(array(
    'status' => 'success',
    'message' => $_COOKIE['webpagecookies']
));
