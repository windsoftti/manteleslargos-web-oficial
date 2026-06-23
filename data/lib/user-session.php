<?php
session_set_cookie_params(60 * 60 * 24 * 14);
session_start();
include '../../inc/global-functions.inc.php';
include '../../inc/constants.inc.php';
include '../../inc/config.inc.php';
include '../../inc/specific-functions.inc.php';

if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario Final') :
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
