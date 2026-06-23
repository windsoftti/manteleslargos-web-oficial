<?php
include 'inc/config.inc.php';

session_start();

$session_user_level = $_SESSION['session_user_level'];

$return_url = $url_host;

//if ($session_user_level == 'Administrador' || $session_user_level == 'Super Usuario') $return_url = 'login';

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

header("Location:$return_url");
