<?php
include '../lib/public-session.php';

$response = array(
  'status'  => 'error',
  'title'   => '¡Error!',
  'message' => 'Error inesperado, Intentalo nuevamente.'
);

$action = $_POST['action'];

switch ($action) {
  case 'citys':
    $state_id = cleanStr($_POST['data']);

    $citys = citysForSelect(
      'CIUDAD',
      $state_id
    );

    $response = array(
      'status'  => 'success',
      'content' => base64_encode($citys)
    );
    break;

  case 'business-citys':
    $state_id = cleanStr($_POST['data']);

    $citys = businessCitysForSelect(
      'Todas las ciudades',
      $state_id
    );

    $response = array(
      'status'  => 'success',
      'content' => base64_encode($citys)
    );
    break;

  case 'business-citys-by-label':
    $state = cleanStr($_POST['data']);

    $citys = businessCitysForSearch(
      'CIUDAD',
      $state
    );

    $response = array(
      'status'  => 'success',
      'content' => base64_encode($citys)
    );
    break;

  case 'business_package_event_types':
    $package_id = cleanStr($_POST['data']);

    $business = businessPackageEventTypesForSelect(
      'Tipo de evento',
      $package_id
    );

    $response = array(
      'status'  => 'success',
      'content' => base64_encode($business)
    );
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
die();
