<?php
error_reporting(0);
date_default_timezone_set('America/Mexico_City');
/* $db_host      = 'localhost';
$db_data_base = 'manteleslargos_01042022_manteles_db';
$db_user      = 'manteleslargos_manteles_usr';
$db_password  = 'N9$)Y!F6.vUs'; */

/*$db_host      = 'localhost';
$db_data_base  = 'manteleslargos_webdb';
$db_user      = 'manteleslargos_usrweb';
$db_password  = 'T%7yOhoXpjcr';*/

$db_host      = 'localhost';
$db_data_base  = 'dev_manteles';
$db_user      = 'root';
$db_password  = '';

$mysqli = new mysqli(
  $db_host,
  $db_user,
  $db_password,
  $db_data_base
);

$protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === 0 ? 'https://' : 'http://';
$url_host = 'http://' . $_SERVER['HTTP_HOST'] . '/';

if ($mysqli->connect_error) {
  $json = 'no';
  echo json_encode($json);
  die();
}

if (!$mysqli->connect_error) {
  $mysqli->set_charset('utf8');

  $secret = '@sistema/_-rentas/_-salones-fiestas/_-2021/_-IEM/_-IYS_-s0f74r3';

  $images_url     = '../../src/assets/images/';
  $images_absolute_url = $url_host . 'src/assets/images/';
}
