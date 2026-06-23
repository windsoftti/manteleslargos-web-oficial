<?php
error_reporting(0);
/* $mysqli_host      = 'localhost';
$mysqli_database  = 'manteleslargos_01042022_manteles_db';
$mysqli_user      = 'manteleslargos_manteles_usr';
$mysqli_password  = 'N9$)Y!F6.vUs'; */
$mysqli_host      = 'localhost';
$mysqli_database  = 'dev_manteles';
$mysqli_user      = 'root';
$mysqli_password  = '';

$mysqli = new mysqli(
  $mysqli_host,
  $mysqli_user,
  $mysqli_password,
  $mysqli_database
);

if ($mysqli->connect_error) {
  $json = 'error';
  echo json_encode($json);
  die();
}

if (!$mysqli->connect_error) {
  $mysqli->set_charset('utf8');
}
