<?php
session_start();
include '../../inc/constants.inc.php';
include '../../inc/config.inc.php';
include '../../inc/global-functions.inc.php';
include '../../inc/specific-functions.inc.php';

if (!$_SESSION['adm_session_user_id']) :
  $response = array(
    'status'  => 'error',
    'title'   => '¡Error!',
    'message' => 'Error inesperado, Intentalo nuevamente.',
    'content' => 'Error inesperado, Intentalo nuevamente.'
  );

  echo json_encode($response);
  mysqli_close($mysqli);
  die;
endif;
