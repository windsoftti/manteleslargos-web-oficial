<?php
error_reporting(0);
setlocale(LC_ALL, "es_MX");
date_default_timezone_set('America/Mexico_City');

/*$db_host        = 'localhost';
$db_data_base   = 'manteleslargos_web@2022';
$db_user        = 'manteleslargos_manteles_usr';
$db_password    = 'N9$)Y!F6.vUs';*/

$db_host      = 'localhost';
$db_data_base  = 'manteleslargos_webdb';
$db_user      = 'manteleslargos_usrweb';
$db_password  = 'T%7yOhoXpjcr';


$mysqli = new mysqli(
  $db_host,
  $db_user,
  $db_password,
  $db_data_base
);

if ($mysqli->connect_error) {
  echo json_encode(array(
    'status'  => 'no-connection',
    'title'   => '¡Error inesperado!',
    'message' => 'No se pudo establecer conexión con el servidor'
  ));
  die();
}

if (!$mysqli->connect_error) {
  $mysqli->set_charset('utf8');

  $mysqli_secret  = '@sistema/_-rentas/_-salones-fiestas/_-2021/_-IEM/_-IYS_-s0f74r3';
  $json           = json_decode(file_get_contents('php://input'), true);
}
