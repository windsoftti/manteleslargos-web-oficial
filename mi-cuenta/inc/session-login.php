<?php
session_start();

require_once 'inc/config.inc.php';
require_once 'inc/constants.inc.php';
require_once 'inc/functions.inc.php';

include 'url-host.php';
$return_url = $url_host;

verifyUserSession();

if (isset($_SESSION['session_user_id'])) {
    header('Location:index');
    exit();
} else {
    header('location:' . $return_url . '/soy-proveedor');
}
