<?php
include '../session.php';
include '../../inc/functions.inc.php';
include '../lib/pagination.php';

$action = isset($_POST['action']) && $_POST['action'] !== '' ? $_POST['action'] : '';

$file_extensions  = array('jpeg', 'jpg', 'png', 'doc', 'docx', 'pdf');
$file_folder      = $images_url . 'tiposEventos/';

switch ($action) {
  case 'list_event_types':
    $page                 = isset($_POST['page']) && $_POST['page'] !== '' ? $_POST['page'] : 1;
    #$per_page            = isset($_POST['perPage']) && $_POST['perPage'] !== '' ? $_POST['perPage'] : 15;
    $per_page             = 15;

    $event_type           = cleanStr($_POST['searchByEventType']);
    $search_by_event_type = $event_type != '' ? "TipoEvento LIKE '%$event_type%'" : "1=1";

    $from                 = "FROM tipo_eventos";
    $where                = "WHERE ($search_by_event_type) ORDER BY idTipoEvento DESC";

    $start_rows           = ($page - 1) * $per_page;
    $stop_rows            = $per_page;
    $limit_rows           = "LIMIT $start_rows, $stop_rows";

    $query                = "SELECT COUNT(idTipoEvento) AS Total $from $where LIMIT 1";
    $query_result         = mysqli_query($mysqli, $query);
    $row                  = mysqli_fetch_array($query_result);

    $num_pages            = ceil($row['Total'] / $stop_rows);

    if (!$num_pages) {
      $default_message = '¡No hay tipos de eventos registrados!.';

      if ($event_type != '') {
        $default_message = '¡No hay tipos de eventos que coincidan con la palabra "' . $event_type . '"!.';
      }

      ob_start();
      include '../default_message.php';
      $data_message = base64_encode(ob_get_clean());

      $response['content'] = $data_message;
    }

    if ($num_pages) {
      $query        = "SELECT idTipoEvento, TipoEvento, Imagen $from $where $limit_rows";
      $query_result = mysqli_query($mysqli, $query);

      ob_start();
      include 'event_types_table.php';
      $data_table = base64_encode(ob_get_clean());

      $response['content'] = $data_table;
    }
    break;

  case 'add_event_type':
    $id_user_create = $_SESSION['session_user_id'];
    $event_type     = cleanStr($_POST['eventType']);
    $image          = $_FILES['image'];

    $image_query        = "";
    $image_insert_query = "";

    if ($image['name']) {
      $icon = processFile($image, $file_extensions, $file_folder);

      if ($icon === 'no-valid') {
        $response = array(
          'state'   => 'warning',
          'title'   => '¡Formato invalido!',
          'message' => 'El formato del archivo no es valido.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      if ($icon == 'no-move') {
        $response = array(
          'state'   => 'error',
          'title'   => '¡Error al guardar!',
          'message' => 'El archivo no pudo moverse al servidor, intentelo mas tarde.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      $image_query .= ", Imagen";
      $image_insert_query .= ", '$icon'";
    }

    $query = "INSERT INTO tipo_eventos (
        TipoEvento
        $image_query
      ) VALUES (
        '$event_type'
        $image_insert_query
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
        'message' => 'El tipo de evento "' . $event_type . '" se agregó correctamente.'
      );
    }
    break;

  case 'edit_event_type':
    $event_type_id  = cleanStr($_POST['eventTypeId']);
    $event_type     = cleanStr($_POST['eventType']);
    $image          = $_FILES['image'];

    $image_query    = "";

    if ($image['name']) {
      $icon = processFile($image, $file_extensions, $file_folder);

      if ($icon === 'no-valid') {
        $response = array(
          'state'   => 'warning',
          'title'   => '¡Formato invalido!',
          'message' => 'El formato del archivo no es valido.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      if ($icon == 'no-move') {
        $response = array(
          'state'   => 'error',
          'title'   => '¡Error al guardar!',
          'message' => 'El archivo no pudo moverse al servidor, intentelo mas tarde.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      $image_query .= ", Imagen = '$icon'";
    }

    $query = "UPDATE tipo_eventos SET
        TipoEvento = '$event_type'
        $image_query
      WHERE idTipoEvento = '$event_type_id'
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
        'message' => 'El tipo de evento se actualizó correctamente.'
      );
    }
    break;

  case 'delete_image':
    $event_type_id = cleanStr($_POST['eventTypeId']);

    $query = "SELECT Imagen FROM tipo_eventos WHERE idTipoEvento = '$event_type_id' LIMIT 1";
    $query_result = mysqli_query($mysqli, $query);

    $row = mysqli_fetch_array($query_result);

    $image = $row['Imagen'];
    $file_location    = $file_folder . $image;

    $delete_image = deleteFile($file_location);

    if ($delete_image === 'not-deleted') {
      $response = array(
        'state'   => 'error',
        'title' => 'No se puede remover la imagen "' . $image . '", intentelo nuevamente.'
      );
    }

    if ($delete_image === 'deleted' || $delete_image === 'not-exist') {
      $query = "UPDATE tipo_eventos SET Imagen = '' WHERE idTipoEvento = '$event_type_id'";
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
          'title' => 'La imagen "' . $image . '" ha sido eliminado correctamente.'
        );
      }
    }
    break;

  case 'delete_event_type':
    $event_type_id  = cleanStr($_POST['eventTypeId']);
    $event_type     = cleanStr($_POST['eventType']);

    $query        = "SELECT Imagen FROM tipo_eventos WHERE idTipoEvento = '$event_type_id' LIMIT 1";
    $query_result = mysqli_query($mysqli, $query);

    $row    = mysqli_fetch_array($query_result);
    $image  = $row['Imagen'];

    if ($image) {
      $file_location    = $file_folder . $image;

      $delete_image = deleteFile($file_location);

      if ($delete_image === 'not-deleted') {
        $response = array(
          'state'   => 'error',
          'title' => 'No se puede remover la imagen "' . $image . '", intentelo nuevamente.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }
    }

    $query = "DELETE FROM tipo_eventos WHERE idTipoEvento = '$event_type_id'";
    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) {
      $error_number_type = mysqli_errno($mysqli);

      if ($error_number_type == 1451) {
        $response = array('state' => 'warning', 'title' => '!El tipo de evento aun esta en uso!.');
      }

      if (!$error_number_type == 1451) {
        $response = array('state' => 'error', 'title' => '¡Error!, Intentelo nuevamente.');
      }
    }

    if ($query_result) {
      $response = array('state' => 'success', 'title' => 'El tipo de evento "' . $event_type . '" se eliminó correctamente.');
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
