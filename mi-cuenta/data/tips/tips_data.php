<?php
include '../session.php';
include '../../inc/functions.inc.php';
include '../lib/pagination.php';
include '../lib/thumb-creator.php';

date_default_timezone_set('America/Mexico_City');

$action = isset($_POST['action']) && $_POST['action'] !== '' ? $_POST['action'] : '';

$file_extensions  = array('jpeg', 'jpg', 'png', 'doc', 'docx', 'pdf');
$file_folder      = $images_url . 'tips/';
$thumb_folder     = $images_url . 'tips/thumbs/';

switch ($action) {
  case 'list_tips':
    $page                 = isset($_POST['page']) && $_POST['page'] !== '' ? $_POST['page'] : 1;
    #$per_page            = isset($_POST['perPage']) && $_POST['perPage'] !== '' ? $_POST['perPage'] : 15;
    $per_page             = 15;

    $tip           = cleanStr($_POST['searchByTip']);
    $search_by_tip = $tip != '' ? "Tip LIKE '%$tip%'" : "1=1";

    $from                 = "FROM tips";
    $where                = "WHERE ($search_by_tip) ORDER BY idTip DESC";

    $start_rows           = ($page - 1) * $per_page;
    $stop_rows            = $per_page;

    $limit_rows           = "LIMIT $start_rows, $stop_rows";

    $query                = "SELECT COUNT(idTip) AS Total $from $left_join $where LIMIT 1";
    $query_result         = mysqli_query($mysqli, $query);
    $row                  = mysqli_fetch_array($query_result);

    $num_pages            = ceil($row['Total'] / $stop_rows);

    if (!$num_pages) {
      $default_message = '¡No hay tips registrados!.';

      if ($tip != '') {
        $default_message = '¡No hay tips que coincidan con la palabra "' . $tip . '"!.';
      }

      ob_start();
      include '../default_message.php';
      $data_message = base64_encode(ob_get_clean());

      $response['content'] = $data_message;
    }

    if ($num_pages) {
      $query = "SELECT
          idTip,
          Tip,
          DescCorta,
          Descripcion,
          DATE_FORMAT(Fecha, '%d-%m-%Y %h:%i %p') AS Fecha,
          Imagen,
          slug
        $from
        $left_join
        $where
        $limit_rows
      ";

      $query_result = mysqli_query($mysqli, $query);

      ob_start();
      include 'tips_table.php';
      $data_table = base64_encode(ob_get_clean());

      $response['content'] = $data_table;
    }
    break;

  case 'add_tip':
    $id_user_create     = $_SESSION['session_user_id'];
    /* $business_id        = cleanStr($_POST['businessId']); */
    $tip                = cleanStr($_POST['tip']);
    $short_description  = cleanStr($_POST['shortDescription']);
    $long_description   = base64_encode($_POST['longDescription']);

    $date               = date('Y-m-d h:i:s');

    $image              = $_FILES['image'];

    $image_query        = "";
    $image_insert_query = "";

    if ($image['name']) {
      $tip_image = processOptimizedImage($image, $file_extensions, $file_folder);

      if ($tip_image === 'no-valid') {
        $response = array(
          'state'   => 'warning',
          'title'   => '¡Formato invalido!',
          'message' => 'El formato del archivo no es valido.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      if ($tip_image == 'no-move') {
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
      $image_insert_query .= ", '$tip_image[name]'";

      $thumb = new Thumb();
      $thumb->loadImage($file_folder . $tip_image['name']);
      $thumb->crop(100, 100, 'center');
      $thumb->save($thumb_folder . $tip_image['nameWithOutExtension']);
    }

    $query = "INSERT INTO tips (
        Tip,
        DescCorta,
        Descripcion,
        Fecha
        $image_query
      ) VALUES (
        '$tip',
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
      $response = array(
        'state'   => 'success',
        'title'   => '!Datos guardados¡',
        'message' => 'El tip "' . $tip . '" ha sido agregado correctamente.'
      );
    }
    break;

  case 'edit_tip':
    $tip_id             = cleanStr($_POST['tipId']);
    /* $business_id        = cleanStr($_POST['businessId']); */
    $tip                = cleanStr($_POST['tip']);
    $short_description  = cleanStr($_POST['shortDescription']);
    $long_description   = base64_encode($_POST['longDescription']);

    $date               = date('Y-m-d h:i:s');

    $image              = $_FILES['image'];

    $image_query        = "";

    if ($image['name']) {
      $tip_image = processOptimizedImage($image, $file_extensions, $file_folder);

      if ($tip_image === 'no-valid') {
        $response = array(
          'state'   => 'warning',
          'title'   => '¡Formato invalido!',
          'message' => 'El formato del archivo no es valido.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      if ($tip_image == 'no-move') {
        $response = array(
          'state'   => 'error',
          'title'   => '¡Error al guardar!',
          'message' => 'El archivo no pudo moverse al servidor, intentelo mas tarde.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      $image_query .= ", Imagen = '$tip_image[name]'";

      $thumb = new Thumb();
      $thumb->loadImage($file_folder . $tip_image['name']);
      $thumb->crop(100, 100, 'center');
      $thumb->save($thumb_folder . $tip_image['nameWithOutExtension']);
    }

    $query = "UPDATE tips SET
        Tip         = '$tip',
        DescCorta   = '$short_description',
        Descripcion = '$long_description'
        $image_query
      WHERE idTip = '$tip_id'
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
        'state'   => 'success',
        'title'   => '!Datos actualizados¡',
        'message' => 'El tip ha sido actualizado correctamente.'
      );
    }
    break;

  case 'delete_image':
    $tip_id = cleanStr($_POST['tipId']);

    $query = "SELECT Imagen FROM tips WHERE idTip = '$tip_id' LIMIT 1";
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

    if ($delete_image == 'deleted' || $delete_image == 'not-exist') {
      $query = "UPDATE tips SET Imagen = '' WHERE idTip = '$tip_id'";
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

  case 'delete_tip':
    $tip_id  = cleanStr($_POST['tipId']);
    $tip     = cleanStr($_POST['tip']);

    $query        = "SELECT Imagen FROM tips WHERE idTip = '$tip_id' LIMIT 1";
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

    $query = "DELETE FROM tips WHERE idTip = '$tip_id'";
    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) {
      $error_number_type = mysqli_errno($mysqli);

      if ($error_number_type == 1451) {
        $response = array('state' => 'warning', 'title' => '!El tip aun esta en uso!.');
      }

      if (!$error_number_type == 1451) {
        $response = array('state' => 'error', 'title' => '¡Error!, Intentelo nuevamente.');
      }
    }

    if ($query_result) {
      $response = array('state' => 'success', 'title' => 'El tip "' . $tip . '" se eliminó correctamente.');
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
