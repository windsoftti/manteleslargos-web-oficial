<?php
include '../session.php';
include '../../inc/functions.inc.php';
include '../lib/pagination.php';

$action = isset($_POST['action']) && $_POST['action'] !== '' ? $_POST['action'] : '';

$file_extensions  = array('jpeg', 'jpg', 'png', 'doc', 'docx', 'pdf');
$file_folder      = $images_url . 'tiposProveedores/';

switch ($action) {
  case 'list_vendor_types':
    $page                 = isset($_POST['page']) && $_POST['page'] !== '' ? $_POST['page'] : 1;
    #$per_page            = isset($_POST['perPage']) && $_POST['perPage'] !== '' ? $_POST['perPage'] : 15;
    $per_page             = 15;

    $vendor_type           = cleanStr($_POST['searchByVendorType']);
    $search_by_vendor_type = $vendor_type != '' ? "TipoProveedor LIKE '%$vendor_type%'" : "1=1";

    $from                 = "FROM tipo_proveedores";
    $where                = "WHERE ($search_by_vendor_type) ORDER BY idTipoProveedor DESC";

    $start_rows           = ($page - 1) * $per_page;
    $stop_rows            = $per_page;

    $limit_rows           = "LIMIT $start_rows, $stop_rows";

    $query                = "SELECT COUNT(idTipoProveedor) AS Total $from $where LIMIT 1";
    $query_result         = mysqli_query($mysqli, $query);
    $row                  = mysqli_fetch_array($query_result);

    $num_pages            = ceil($row['Total'] / $stop_rows);

    if (!$num_pages) {
      $default_message = '¡No hay tipos de proveedores registrados!.';

      if ($vendor_type != '') {
        $default_message = '¡No hay tipos de proveedores que coincidan con la palabra "' . $vendor_type . '"!.';
      }

      ob_start();
      include '../default_message.php';
      $data_message = base64_encode(ob_get_clean());

      $response['content'] = $data_message;
    }

    if ($num_pages) {
      $query        = "SELECT idTipoProveedor, TipoProveedor, Imagen $from $where $limit_rows";
      $query_result = mysqli_query($mysqli, $query);

      ob_start();
      include 'vendor_types_table.php';
      $data_table = base64_encode(ob_get_clean());

      $response['content'] = $data_table;
    }
    break;

  case 'add_vendor_type':
    $id_user_create = $_SESSION['session_user_id'];
    $vendor_type     = cleanStr($_POST['vendorType']);
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

    $query = "INSERT INTO tipo_proveedores (
        TipoProveedor
        $image_query
      ) VALUES (
        '$vendor_type'
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
        'message' => 'El tipo de proveedor "' . $vendor_type . '" se agregó correctamente.'
      );
    }
    break;

  case 'edit_vendor_type':
    $vendor_type_id  = cleanStr($_POST['vendorTypeId']);
    $vendor_type     = cleanStr($_POST['vendorType']);
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

    $query = "UPDATE tipo_proveedores SET
        TipoProveedor = '$vendor_type'
        $image_query
      WHERE idTipoProveedor = '$vendor_type_id'
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
        'message' => 'El tipo de proveedor se actualizó correctamente.'
      );
    }
    break;

  case 'delete_image':
    $vendor_type_id = cleanStr($_POST['vendorTypeId']);

    $query = "SELECT Imagen FROM tipo_proveedores WHERE idTipoProveedor = '$vendor_type_id' LIMIT 1";
    $query_result = mysqli_query($mysqli, $query);

    $row = mysqli_fetch_array($query_result);

    $image          = $row['Imagen'];
    $file_location  = $file_folder . $image;

    $delete_image = deleteFile($file_location);

    if ($delete_image === 'not-deleted') {
      $response = array(
        'state'   => 'error',
        'title' => 'No se puede remover la imagen "' . $image . '", intentelo nuevamente.'
      );
    }

    if ($delete_image === 'deleted' || $delete_image === 'not-exist') {
      $query = "UPDATE tipo_proveedores SET Imagen = '' WHERE idTipoProveedor = '$vendor_type_id'";
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

  case 'delete_vendor_type':
    $vendor_type_id = cleanStr($_POST['vendorTypeId']);
    $vendor_type    = cleanStr($_POST['vendorType']);

    $query        = "SELECT Imagen FROM tipo_proveedores WHERE idTipoProveedor = '$vendor_type_id' LIMIT 1";
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

    $query = "DELETE FROM tipo_proveedores WHERE idTipoProveedor = '$vendor_type_id'";
    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) {
      $error_number_type = mysqli_errno($mysqli);

      if ($error_number_type == 1451) {
        $response = array('state' => 'warning', 'title' => '!El tipo de proveedor aun esta en uso!.');
      }

      if (!$error_number_type == 1451) {
        $response = array('state' => 'error', 'title' => '¡Error!, Intentelo nuevamente.');
      }
    }

    if ($query_result) {
      $response = array('state' => 'success', 'title' => 'El tipo de proveedor "' . $vendor_type . '" se eliminó correctamente.');
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
