<?php
include '../session.php';
include '../../inc/functions.inc.php';
include '../lib/pagination.php';

$action = isset($_POST['action']) && $_POST['action'] !== '' ? $_POST['action'] : '';

switch ($action) {
  case 'list_amenities':
    $page              = isset($_POST['page']) && $_POST['page'] !== '' ? $_POST['page'] : 1;
    #$per_page         = isset($_POST['perPage']) && $_POST['perPage'] !== '' ? $_POST['perPage'] : 15;
    $per_page          = 15;

    $amenity           = cleanStr($_POST['searchByAmenity']);
    $search_by_amenity = $amenity != '' ? "Amenidad LIKE '%$amenity%'" : "1=1";

    $from              = "FROM amenidades";
    $where             = "WHERE ($search_by_amenity) ORDER BY idAmenidad DESC";

    $start_rows        = ($page - 1) * $per_page;
    $stop_rows         = $per_page;

    $limit_rows        = "LIMIT $start_rows, $stop_rows";

    $query             = "SELECT COUNT(idAmenidad) AS Total $from $where LIMIT 1";
    $query_result      = mysqli_query($mysqli, $query);
    $row               = mysqli_fetch_array($query_result);

    $num_pages         = ceil($row['Total'] / $stop_rows);

    if (!$num_pages) {
      $default_message = '¡No hay amenidades registrados!.';

      if ($amenity != '') {
        $default_message = '¡No hay amenidades que coincidan con la palabra "' . $amenity . '"!.';
      }

      ob_start();
      include '../default_message.php';
      $data_message = base64_encode(ob_get_clean());

      $response['content'] = $data_message;
    }

    if ($num_pages) {
      $query        = "SELECT idAmenidad, Amenidad $from $where $limit_rows";
      $query_result = mysqli_query($mysqli, $query);

      ob_start();
      include 'amenities_table.php';
      $data_table = base64_encode(ob_get_clean());

      $response['content'] = $data_table;
    }
    break;

  case 'add_amenity':
    $id_user_create = $_SESSION['session_user_id'];
    $amenity        = cleanStr($_POST['amenity']);

    $query        = "INSERT INTO amenidades (Amenidad) VALUES ('$amenity')";
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
        'message' => 'La amenidad "' . $amenity . '" se agregó correctamente.'
      );
    }
    break;

  case 'edit_amenity':
    $amenity_id  = cleanStr($_POST['amenityId']);
    $amenity     = cleanStr($_POST['amenity']);

    $query = "UPDATE amenidades SET
        Amenidad = '$amenity'
      WHERE idAmenidad = '$amenity_id'
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
        'message' => 'La amenidad se actualizó correctamente.'
      );
    }
    break;

  case 'delete_amenity':
    $amenity_id = cleanStr($_POST['amenityId']);
    $amenity    = cleanStr($_POST['amenity']);

    $query        = "DELETE FROM amenidades WHERE idAmenidad = '$amenity_id'";
    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) {
      $error_number_type = mysqli_errno($mysqli);

      if ($error_number_type == 1451) {
        $response = array(
          'state' => 'warning',
          'title' => '!La amenidad aun esta en uso!.'
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
        'title' => 'La amenidad "' . $amenity . '" se eliminó correctamente.'
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
