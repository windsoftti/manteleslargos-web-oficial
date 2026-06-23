<?php
date_default_timezone_set('America/Mexico_City');
include '../lib/session-root.php';
include '../lib/pagination.php';

$action = $_POST['action'];

$initial_response = array(
  'status'  => 'error',
  'title'   => '¡Error!',
  'message' => 'Error inesperado, intentalo nuevamente'
);

$response = $initial_response;

$valid_extensions = array('jpeg', 'jpg', 'png', 'JPEG', 'JPG', 'PNG');

switch ($action) {
  case 'list-tips':
    $page       = cleanStr($_POST['page']);
    $page       = $page != '' ? $page : 1;

    $per_page   = cleanStr($_POST['perPage']);
    $per_page   = $per_page != '' ? $per_page : 1;

    $start_rows = ($page - 1) * $per_page;
    $stop_rows  = $per_page;

    $limit_rows = "LIMIT $start_rows, $stop_rows";

    $search     = cleanStr($_POST['search']);
    $search_by  = $search != '' ? "Tip LIKE '%$search%'" : "1=1";

    $c_from     = "FROM tips";

    $c_where    = "WHERE
        ($search_by) AND
        Eliminado = 'No'
      ORDER BY idTip
      DESC
    ";

    $query      = "SELECT COUNT(idTip) AS Total $c_from $c_where LIMIT 1";
    $num_pages  = numPages($query, $stop_rows);

    if (!$num_pages) {
      $default_message = '¡No hay tips registrados!.';

      if ($search != '') {
        $default_icon = 'fas fa-search';
        $default_message = '¡No se encontraron resultados!. "' . $search . '".';
      }

      include '../lib/default_message.php';
    }

    if ($num_pages) {
      $query = "SELECT
          idTip,
          Tip,
          DescCorta,
          Descripcion,
          Imagen,
          Fecha,
          slug
        $c_from
        $c_where
        $limit_rows
      ";

      $query_result = mysqli_query($mysqli, $query);

      include 'tips_table.php';
    }

    mysqli_close($mysqli);
    die();
    break;

  case 'add-tip':
    $current_date       = date('Y-m-d H:i:s');

    $title              = cleanStr($_POST['title']);
    $short_description  = cleanStr($_POST['shortDescription']);
    $long_description   = cleanStr($_POST['longDescription'], 'html');

    $principal_image    = $_FILES['principalImage'];
    $image_gallery      = $_FILES['imageGallery'];

    #$reference          = date('YmdHis');
    $tip_slug           = createSlug($title);

    # PICTURES QUERY
    $query_picture        = "";
    $query_insert_picture = "";

    # AGREGAR LA IMAGEN DE CEREMONIA RELIGIOSA
    if ($principal_image['name']) :
      $proccess_principal_image = processFile(
        $principal_image,
        $valid_extensions,
        TIPS_IMAGE_FOLDER,
        'tip'
      );

      if ($proccess_principal_image !== 'no-move' && $proccess_principal_image !== 'no-valid') :
        $query_picture         .= ", Imagen";
        $query_insert_picture  .= ", '$proccess_principal_image'";
      endif;
    endif;

    $query = "INSERT INTO tips (
        Tip,
        DescCorta,
        Descripcion,
        Fecha,
        slug
        $query_picture
      ) VALUES (
        '$title',
        '$short_description',
        '$long_description',
        '$current_date',
        '$tip_slug'
        $query_insert_picture
      )
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) :
      $tip_id = mysqli_insert_id($mysqli);

      /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
      //-- AGREGAR LA REFERENCIA
      :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
      $query_add_reference = "UPDATE tips SET Referencia = '$tip_id' WHERE idTip = $tip_id";
      mysqli_query($mysqli, $query_add_reference);

      # AGREGAR IMAGENES DE LA GALERÍA
      $image_of_gallery = processMultipleFiles(
        $image_gallery,
        $valid_extensions,
        TIPS_GALLERY_FOLDER,
        'tip-g'
      );

      foreach ($image_of_gallery as $key => $value) :
        $image = $image_of_gallery[$key];

        $query = "INSERT INTO tips_galeria (
            idTip,
            Imagen
          ) VALUES (
            $tip_id,
            '$image'
          )
        ";

        mysqli_query($mysqli, $query);
      endforeach;

      $response = array(
        'status'  => 'success',
        'title'   => '¡Datos guardados!',
        'message' => 'El tip ha sido creado correctamente.'
      );
    endif;
    break;

  case 'edit-tip':
    $tip_id             = cleanStr($_POST['tipId']);
    $current_date       = date('Y-m-d H:i:s');

    $title              = cleanStr($_POST['title']);
    $business_id        = cleanStr($_POST['businessId']);
    $short_description  = cleanStr($_POST['shortDescription']);
    $long_description   = cleanStr($_POST['longDescription'], 'html');

    $principal_image    = $_FILES['principalImage'];
    $image_gallery      = $_FILES['imageGallery'];

    $tip_slug  = createSlug($title);

    $principal_image_name = getTipPrincipalImageName($tip_id);

    $original_image_gallery = getTipGalleryIds($tip_id);
    $new_image_gallery      = $_POST['imageGallery-items'];

    # AGREGAR LA IMAGEN DE CEREMONIA RELIGIOSA
    if ($principal_image['name']) processFile(
      $principal_image,
      $valid_extensions,
      TIPS_IMAGE_FOLDER,
      'tip',
      $principal_image_name
    );

    $query = "UPDATE tips SET
        Tip         = '$title',
        DescCorta   = '$short_description',
        Descripcion = '$long_description',
        Fecha       = '$current_date',
        slug        = '$tip_slug'
      WHERE idTip = $tip_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) :
      # ELIMINAR LAS IMAGENES QUE SE REMOVIERON
      deleteTipImageGallery(
        $original_image_gallery,
        $new_image_gallery
      );

      # AGREGAR IMAGENES DE LA GALERÍA
      $image_of_gallery = processMultipleFiles(
        $image_gallery,
        $valid_extensions,
        TIPS_GALLERY_FOLDER,
        'tip-g'
      );

      foreach ($image_of_gallery as $key => $value) :
        $image = $image_of_gallery[$key];

        $query = "INSERT INTO tips_galeria (
            idTip,
            Imagen
          ) VALUES (
            $tip_id,
            '$image'
          )
        ";

        mysqli_query($mysqli, $query);
      endforeach;

      $response = array(
        'status'  => 'success',
        'title'   => '¡Datos guardados!',
        'message' => 'El tip ha sido actualizado correctamente.'
      );
    endif;
    break;

  case 'delete-tip':
    $tip_id = cleanStr($_POST['itemId']);
    $tip    = cleanStr($_POST['item']);

    $query        = "UPDATE tips SET Eliminado = 'Si' WHERE idTip = $tip_id";
    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) $response['message'] = '¡Error!, Intentelo nuevamente.';

    if ($query_result) $response = array(
      'status' => 'success',
      'message' => 'El tip "' . $tip . '" se eliminó correctamente.'
    );
    break;

  default:
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
