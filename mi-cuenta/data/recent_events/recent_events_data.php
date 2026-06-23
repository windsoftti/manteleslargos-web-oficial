<?php
include '../session.php';
include '../../inc/functions.inc.php';
include '../lib/pagination.php';
include '../lib/thumb-creator.php';

date_default_timezone_set('America/Mexico_City');

$action = isset($_POST['action']) && $_POST['action'] !== '' ? $_POST['action'] : '';

$file_extensions  = array('jpeg', 'jpg', 'png', 'doc', 'docx', 'pdf');
$file_folder      = $images_url . 'eventosRecientes/';
$thumb_folder     = $images_url . 'eventosRecientes/thumbs/';

switch ($action) {
  case 'list_recent_events':
    $page                 = isset($_POST['page']) && $_POST['page'] !== '' ? $_POST['page'] : 1;
    #$per_page            = isset($_POST['perPage']) && $_POST['perPage'] !== '' ? $_POST['perPage'] : 15;
    $per_page             = 15;

    $recent_event           = cleanStr($_POST['searchByRecentEvent']);
    $search_by_recent_event = $recent_event != '' ? "ER.Evento LIKE '%$recent_event%'" : "1=1";

    $from                 = "FROM eventos_recientes AS ER";
    $left_join            = "LEFT JOIN salones AS S ON (ER.idSalon = S.idSalon)";
    $where                = "WHERE ($search_by_recent_event) ORDER BY ER.idEvento DESC";

    $start_rows           = ($page - 1) * $per_page;
    $stop_rows            = $per_page;

    $limit_rows           = "LIMIT $start_rows, $stop_rows";

    $query                = "SELECT COUNT(ER.idEvento) AS Total $from $left_join $where LIMIT 1";
    $query_result         = mysqli_query($mysqli, $query);
    $row                  = mysqli_fetch_array($query_result);

    $num_pages            = ceil($row['Total'] / $stop_rows);
    $total_recent_events  = $row['Total'];

    if (!$num_pages) {
      $default_message = '¡No hay eventos recientes registrados!.';

      if ($recent_event != '') {
        $default_message = '¡No hay eventos recientes que coincidan con la palabra "' . $recent_event . '"!.';
      }

      ob_start();
      include '../default_message.php';
      $data_message = base64_encode(ob_get_clean());

      $response['content'] = $data_message;
    }

    if ($num_pages) {
      $query = "SELECT
          ER.idEvento,
          ER.idSalon,
          ER.Evento,
          ER.DescCorta,
          ER.Descripcion,
          DATE_FORMAT(ER.Fecha, '%d-%m-%Y %h:%i %p') AS Fecha,
          ER.Imagen,
          ER.slug,
          S.Salon
        $from
        $left_join
        $where
        $limit_rows
      ";

      $query_result = mysqli_query($mysqli, $query);

      ob_start();
      include 'recent_events_table.php';
      $data_table = base64_encode(ob_get_clean());

      $response['content']  = $data_table;
      $response['Total']    = getRecentEventsCount();
    }
    break;

  case 'add_recent_event':
    $id_user_create     = $_SESSION['session_user_id'];
    $business_id        = cleanStr($_POST['businessId']);
    $recent_event       = cleanStr($_POST['recentEvent']);
    $short_description  = cleanStr($_POST['shortDescription']);
    $long_description   = base64_encode($_POST['longDescription']);

    $date               = date('Y-m-d h:i:s');

    $image              = $_FILES['image'];
    $gallery      = $_FILES['gallery'];

    $image_query        = "";
    $image_insert_query = "";

    if ($image['name']) {
      $event_image = processOptimizedImage($image, $file_extensions, $file_folder);

      if ($event_image === 'no-valid') {
        $response = array(
          'state'   => 'warning',
          'title'   => '¡Formato invalido!',
          'message' => 'El formato del archivo no es valido.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      if ($event_image == 'no-move') {
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
      $image_insert_query .= ", '$event_image[name]'";

      $thumb = new Thumb();
      $thumb->loadImage($file_folder . $event_image['name']);
      $thumb->crop(100, 100, 'center');
      $thumb->save($thumb_folder . $event_image['nameWithOutExtension']);
    }

    $query = "INSERT INTO eventos_recientes (
        idSalon,
        Evento,
        DescCorta,
        Descripcion,
        Fecha
        $image_query
      ) VALUES (
        '$business_id',
        '$recent_event',
        '$short_description',
        '$long_description',
        '$date'
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
      $recent_event_id  = mysqli_insert_id($mysqli);

      $folder_gallery   = $file_folder . 'galeria/';
      $move_files       = true;
      $valid_files      = true;

      foreach ($gallery['tmp_name'] as $key => $value) {
        if ($gallery['name'][$key]) {
          $image_gallery = processMultipleOptimizedImage($gallery, $file_extensions, $folder_gallery, $key);

          if ($image_gallery == 'no-move') {
            $move_files = false;
          }

          if ($image_gallery == 'no-valid') {
            $valid_files = false;
          }

          if ($image_gallery != 'no-move' && $image_gallery != 'no-valid') {
            $query = "INSERT INTO galeria_eventos_recientes (idEvento, Imagen) VALUES (
              '$recent_event_id', '$image_gallery'
            )";

            mysqli_query($mysqli, $query);
          }
        }
      }

      if (!$move_files) {
        $response = array(
          'state'   => 'success',
          'title'   => '!Aviso¡',
          'message' => "Algunas imagenes no pudieron moverse, verifique en el apartado de editar evento."
        );
      }
      if (!$valid_files) {
        $response = array(
          'state'   => 'success',
          'title'   => '!Aviso¡',
          'message' => "Algunas imagenes no son validos, verifique en el apartado de editar evento."
        );
      }

      if ($move_files && $valid_files) {
        $response = array(
          'state'   => 'success',
          'title'   => '!Datos guardados¡',
          'message' => 'El evento "' . $recent_event . '" ha sido agregado correctamente.'
        );
      }
    }
    break;

  case 'edit_recent_event':
    $recent_event_id    = cleanStr($_POST['recentEventId']);
    $business_id        = cleanStr($_POST['businessId']);
    $recent_event       = cleanStr($_POST['recentEvent']);
    $short_description  = cleanStr($_POST['shortDescription']);
    $long_description   = base64_encode($_POST['longDescription']);

    $date               = date('Y-m-d h:i:s');

    $image              = $_FILES['image'];
    $gallery            = $_FILES['gallery'];

    $image_query        = "";

    if ($image['name']) {
      $event_image = processOptimizedImage($image, $file_extensions, $file_folder);

      if ($event_image === 'no-valid') {
        $response = array(
          'state'   => 'warning',
          'title'   => '¡Formato invalido!',
          'message' => 'El formato del archivo no es valido.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      if ($event_image == 'no-move') {
        $response = array(
          'state'   => 'error',
          'title'   => '¡Error al guardar!',
          'message' => 'El archivo no pudo moverse al servidor, intentelo mas tarde.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      $image_query .= ", Imagen = '$event_image[name]'";

      $thumb = new Thumb();
      $thumb->loadImage($file_folder . $event_image['name']);
      $thumb->crop(100, 100, 'center');
      $thumb->save($thumb_folder . $event_image['nameWithOutExtension']);
    }

    $query = "UPDATE eventos_recientes SET
        idSalon     = '$business_id',
        Evento      = '$recent_event',
        DescCorta   = '$short_description',
        Descripcion = '$long_description'
        $image_query
      WHERE idEvento = '$recent_event_id'
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
      $folder_gallery   = $file_folder . 'galeria/';
      $move_files       = true;
      $valid_files      = true;

      foreach ($gallery['tmp_name'] as $key => $value) {
        if ($gallery['name'][$key]) {
          $image_gallery = processMultipleOptimizedImage($gallery, $file_extensions, $folder_gallery, $key);

          if ($image_gallery == 'no-move') {
            $move_files = false;
          }

          if ($image_gallery == 'no-valid') {
            $valid_files = false;
          }

          if ($image_gallery != 'no-move' && $image_gallery != 'no-valid') {
            $query = "INSERT INTO galeria_eventos_recientes (idEvento, Imagen) VALUES (
              '$recent_event_id', '$image_gallery'
            )";

            mysqli_query($mysqli, $query);
          }
        }
      }

      if (!$move_files) {
        $response = array(
          'state'   => 'success',
          'title'   => '!Aviso¡',
          'message' => "Algunas imagenes no pudieron moverse, verifique en el apartado de editar evento."
        );
      }
      if (!$valid_files) {
        $response = array(
          'state'   => 'success',
          'title'   => '!Aviso¡',
          'message' => "Algunas imagenes no son validos, verifique en el apartado de editar evento."
        );
      }

      if ($move_files && $valid_files) {
        $response = array(
          'state'   => 'success',
          'title'   => '!Datos actualizados¡',
          'message' => 'El evento ha sido actualizado correctamente.'
        );
      }
    }
    break;

  case 'delete_image':
    $recent_event_id = cleanStr($_POST['recentEventId']);

    $query = "SELECT Imagen FROM eventos_recientes WHERE idEvento = '$recent_event_id' LIMIT 1";
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
      $query = "UPDATE eventos_recientes SET Imagen = '' WHERE idEvento = '$recent_event_id'";
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

  case 'delete_recent_event':
    $recent_event_id  = cleanStr($_POST['recentEventId']);
    $recent_event     = cleanStr($_POST['recentEvent']);

    $query        = "SELECT Imagen FROM eventos_recientes WHERE idEvento = '$recent_event_id' LIMIT 1";
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

    $query        = "SELECT Imagen FROM galeria_eventos_recientes WHERE idEvento = '$recent_event_id'";
    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) {
      while ($row = mysqli_fetch_array($query_result)) {
        $image  = $row['Imagen'];
        $file_location = $file_folder . 'galeria/' . $image;

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
    }

    $query = "DELETE FROM eventos_recientes WHERE idEvento = '$recent_event_id'";
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
      $response = array('state' => 'success', 'title' => 'El tipo de evento "' . $recent_event . '" se eliminó correctamente.');
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
