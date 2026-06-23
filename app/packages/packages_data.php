<?php
include '../inc/session.php';
date_default_timezone_set('America/Mexico_City');

$action = $json['action'];

$response = array(
  'status'  => 'error',
  'title'   => '¡Error inesperado!',
  'message' => 'Intentalo nuevamente.'
);

switch ($action) {
  case 'select-free-package':
    try {
      $user_id = cleanStr($json['parameters']);

      $query = "UPDATE usuarios SET
          Plan = 'Free'
        WHERE idUsuario = $user_id
      ";

      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) $response = array(
        'status' => 'success'
      );
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'select-basic-package':
    try {
      $user_id = cleanStr($json['parameters']);

      $query = "UPDATE usuarios SET
          Plan = 'Basico'
        WHERE idUsuario = $user_id
      ";

      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) $response = array(
        'status' => 'success'
      );
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
exit();
