<?php
ini_set('display_errors', 0);
//session_set_cookie_params(60 * 60 * 24 * 14);
//ini_set('session.cookie_lifetime', 60 * 60 * 24 * 14);
//session_start();

//$session_expire = 365 * 24 * 3600; // We choose a one year duration
//
//ini_set('session.gc_maxlifetime', $session_expire);
session_start(); //We start the session 

//setcookie(session_name(), session_id(), time() + $session_expire, '/');

include 'global-functions.inc.php';
include 'constants.inc.php';
include 'config.inc.php';
include 'specific-functions.inc.php';

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- SOCIAL AUTHENTICATIONS
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
# Facebook
include 'data/lib/facebook-php-sdk/autoload.php';
include 'facebook-api-config.php';

# Google
include 'data/lib/google-sdk/vendor/autoload.php';
include 'google-api-config.php';

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- META DATA
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
$webpage_clean_url = $_SERVER['REQUEST_URI'];
$webpage_clean_url = explode('?', $webpage_clean_url);
$webpage_clean_url = $webpage_clean_url[0];

$webpage_meta_data = array(
  'title'           => 'Manteles Largos',
  'description'     => 'Encuentra todo para tus eventos.',
  'image'           => BASE_URL . '/src/assets/images/logo.png',
  'currentURL'      => BASE_URL . $_SERVER['REQUEST_URI'],
  'cleanCurrentURL' => BASE_URL . $webpage_clean_url
);

if ($_REQUEST['cuid'] == 'acml') acceptWebPageCookies();

verifyUserSession();
