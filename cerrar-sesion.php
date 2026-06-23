<?php
include 'inc/public-session.php';

$_SESSION = array();

if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(
    session_name(),
    '',
    time() - 42000,
    $params["path"],
    $params["domain"],
    $params["secure"],
    $params["httponly"]
  );
}
unset($_COOKIE['MLSESSCOOID']);
setcookie('MLSESSCOOID', null, -1, '/');
session_destroy();
header("Location:" . BASE_URL);
die();
