<?php
include '../session.php';
include '../../inc/functions.inc.php';
include '../lib/pagination.php';

$action = isset($_POST['action']) && $_POST['action'] !== '' ? $_POST['action'] : '';

switch ($action) {
  case 'list_services':
    $page              = isset($_POST['page']) && $_POST['page'] !== '' ? $_POST['page'] : 1;
    #$per_page         = isset($_POST['perPage']) && $_POST['perPage'] !== '' ? $_POST['perPage'] : 15;
    $per_page          = 15;

    $service           = cleanStr($_POST['searchByService']);
    $search_by_service = $service != '' ? "Servicio LIKE '%$service%'" : "1=1";

    $from              = "FROM servicios";
    $where             = "WHERE ($search_by_service) ORDER BY idServicio DESC";

    $start_rows        = ($page - 1) * $per_page;
    $stop_rows         = $per_page;

    $limit_rows        = "LIMIT $start_rows, $stop_rows";

    $query             = "SELECT COUNT(idServicio) AS Total $from $where LIMIT 1";
    $query_result      = mysqli_query($mysqli, $query);
    $row               = mysqli_fetch_array($query_result);

    $num_pages         = ceil($row['Total'] / $stop_rows);

    if (!$num_pages) {
      $default_message = '¡No hay servicios registrados!.';

      if ($service != '') {
        $default_message = '¡No hay servicios que coincidan con la palabra "' . $service . '"!.';
      }

      ob_start();
      include '../default_message.php';
      $data_message = base64_encode(ob_get_clean());

      $response['content'] = $data_message;
    }

    if ($num_pages) {
      $query        = "SELECT idServicio, Servicio $from $where $limit_rows";
      $query_result = mysqli_query($mysqli, $query);

      ob_start();
      include 'services_table.php';
      $data_table = base64_encode(ob_get_clean());

      $response['content'] = $data_table;
    }
    break;

  case 'add_service':
    $id_user_create = $_SESSION['session_user_id'];
    $service        = cleanStr($_POST['service']);

    $query        = "INSERT INTO servicios (Servicio) VALUES ('$service')";
    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) {
      $response = array(
        'state' => 'error',
        'title' => '¡Error!',
        'message' => '¡Error!, Intentelo nuevamente.'
      );
    }

    if ($query_result) {
      $response = array(
        'state' => 'success',
        'title' => '¡Datos guardados!',
        'message' => 'El servicio "' . $service . '" se agregó correctamente.'
      );
    }
    break;

  case 'edit_service':
    $service_id  = cleanStr($_POST['serviceId']);
    $service     = cleanStr($_POST['service']);

    $query = "UPDATE servicios SET
        Servicio = '$service'
      WHERE idServicio = '$service_id'
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) {
      $response = array(
        'state' => 'error',
        'title' => '¡Error!',
        'message' => '¡Error!, Intentelo nuevamente.'
      );
    }

    if ($query_result) {
      $response = array(
        'state' => 'success',
        'title' => '¡Datos guardados!',
        'message' => 'El servicio se actualizó correctamente.'
      );
    }
    break;

  case 'delete_service':
    $service_id = cleanStr($_POST['serviceId']);
    $service    = cleanStr($_POST['service']);

    $query        = "DELETE FROM servicios WHERE idServicio = '$service_id'";
    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) {
      $error_number_type = mysqli_errno($mysqli);

      if ($error_number_type == 1451) {
        $response = array(
          'state' => 'warning',
          'title' => '!El servicio aun esta en uso!.'
        );
      }

      if (!$error_number_type == 1451) {
        $response = array(
          'state' => 'error',
          'title' => '¡Error!, Intentelo nuevamente.'
        );
      }
    }

    if ($query_result) {
      $response = array(
        'state' => 'success',
        'title' => 'El servicio "' . $service . '" se eliminó correctamente.'
      );
    }
    break;

  default:
    $response = array(
      'state' => 'error',
      'title' => '¡Error!',
      'message' => '¡Error!, Intentelo nuevamente.'
    );
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
