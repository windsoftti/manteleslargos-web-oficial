<?php
include '../session.php';
include '../../inc/functions.inc.php';
include '../lib/pagination.php';

$action = $_POST['action'];

switch ($action) {
  case 'list_egresos':
    $business_id  = $_SESSION['session_business_id'];

    $page              = isset($_POST['page']) && $_POST['page'] !== '' ? $_POST['page'] : 1;
    #$per_page         = isset($_POST['perPage']) && $_POST['perPage'] !== '' ? $_POST['perPage'] : 15;
    $per_page          = 15;

    $concept           = cleanStr($_POST['concept']);
    $search_by_concept = $concept != '' ? "(Concepto LIKE '%$concept%') OR (Descripcion LIKE '%$concept%')" : "1=1";

    $date              = (isset($_POST['date']) && $_POST['date'] != '') ? date('Y-m-d', strtotime($_POST['date'])) : '';
    $search_by_date    = $date != '' ? "Fecha = '$date'" : "1=1";

    $from              = "FROM egresos";

    $where = "WHERE
        ($search_by_concept)        AND
        ($search_by_date)           AND
        (idNegocio = $business_id)  AND
        idUsuario  = $_SESSION[session_user_id]
      ORDER BY idEgreso
      DESC
    ";

    $start_rows        = ($page - 1) * $per_page;
    $stop_rows         = $per_page;

    $limit_rows        = "LIMIT $start_rows, $stop_rows";

    $query             = "SELECT COUNT(idEgreso) AS Total $from $where LIMIT 1";
    $query_result      = mysqli_query($mysqli, $query);
    $row               = mysqli_fetch_array($query_result);

    $num_pages         = ceil($row['Total'] / $stop_rows);

    if (!$num_pages) {
      $default_message = '¡No hay egresos registrados!.';

      ob_start();
      include '../default_message.php';
      $data_message = base64_encode(ob_get_clean());

      $response['content'] = $data_message;
    }

    if ($num_pages) {
      $query_egresos = "SELECT
          idEgreso,
          idUsuario,
          idNegocio,
          Concepto,
          Costo,
          Descripcion,
          Fecha,
          DATE_FORMAT(Fecha, '%d-%m-%Y') AS FormatFecha
        $from
        $where
        $limit_rows
      ";

      $query_egresos_result = mysqli_query($mysqli, $query_egresos);

      ob_start();
      include 'egresos_table.php';
      $data_table = ob_get_clean();

      $response['content'] = base64_encode($data_table);
    }
    break;

  case 'add_egreso':
    $business_id    = $_SESSION['session_business_id'];
    $id_user_create = $_SESSION['session_user_id'];
    $date           = parseDatePicker($_POST['date']);
    $concept        = cleanStr($_POST['concept']);
    $cost           = cleanStr($_POST['cost']);
    $description    = cleanStr($_POST['description']);

    $query = "INSERT INTO egresos (
        idUsuario,
        idNegocio,
        Fecha,
        Concepto,
        Costo,
        Descripcion
      ) VALUES (
        '$id_user_create',
        '$business_id',
        '$date',
        '$concept',
        '$cost',
        '$description'
      )
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
        'message' => 'El egreso se agregó correctamente.'
      );
    }
    break;

  case 'edit_egreso':
    $business_id    = $_SESSION['session_business_id'];
    $id_user_create = $_SESSION['session_user_id'];
    $id_egreso      = cleanStr($_POST['idEgreso']);
    $date           = parseDatePicker($_POST['date']);
    $concept        = cleanStr($_POST['concept']);
    $cost           = cleanStr($_POST['cost']);
    $description    = cleanStr($_POST['description']);

    $query = "UPDATE egresos SET
        Fecha       = '$date',
        Concepto    = '$concept',
        Costo       = '$cost',
        Descripcion = '$description'
      WHERE
        idEgreso  = $id_egreso AND
        idUsuario = $id_user_create AND
        idNegocio = $business_id
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
        'message' => 'El egreso se actualizó correctamente.'
      );
    }
    break;

  case 'delete_egreso':
    $id_egreso      = cleanStr($_POST['idEgreso']);
    $id_user_create = $_SESSION['session_user_id'];

    $query = "DELETE FROM egresos WHERE
      idEgreso  = $id_egreso AND
      idUsuario = $id_user_create
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) {
      $response = array(
        'state' => 'error',
        'title' => '¡Error!, Intentelo nuevamente.'
      );
    }

    if ($query_result) {
      $response = array(
        'state' => 'success',
        'title' => 'El egreso se eliminó correctamente.'
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
