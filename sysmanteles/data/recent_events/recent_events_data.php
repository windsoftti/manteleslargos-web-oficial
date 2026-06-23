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
  case 'list-recent-events':
    $page       = cleanStr($_POST['page']);
    $page       = $page != '' ? $page : 1;

    $per_page   = cleanStr($_POST['perPage']);
    $per_page   = $per_page != '' ? $per_page : 1;

    $start_rows = ($page - 1) * $per_page;
    $stop_rows  = $per_page;

    $limit_rows = "LIMIT $start_rows, $stop_rows";

    $search     = cleanStr($_POST['search']);
    $search_by  = $search != '' ? "Evento LIKE '%$search%'" : "1=1";

    $c_from     = "FROM eventos_recientes AS ER";

    $c_left_join = "
        LEFT JOIN salones AS S ON (ER.idSalon = S.idSalon)
    ";

    $c_where    = "WHERE
        ($search_by) AND
        Eliminado = 'No'
      ORDER BY idEvento
      DESC
    ";

    $query      = "SELECT COUNT(ER.idEvento) AS Total $c_from $c_left_join $c_where LIMIT 1";
    $num_pages  = numPages($query, $stop_rows);

    if (!$num_pages) {
      $default_message = '¡No hay eventos recientes registrados!.';

      if ($search != '') {
        $default_icon = 'fas fa-search';
        $default_message = '¡No se encontraron resultados!. "' . $search . '".';
      }

      include '../lib/default_message.php';
    }

    if ($num_pages) {
      $query = "SELECT
          ER.idEvento,
          ER.idSalon,
          ER.Evento,
          ER.DescCorta,
          ER.Descripcion,
          ER.Imagen,
          ER.Fecha,
          ER.slug,
          S.Salon
        $c_from
        $c_left_join
        $c_where
        $limit_rows
      ";

      $query_result = mysqli_query($mysqli, $query);

      include 'recent_events_table.php';
    }

    mysqli_close($mysqli);
    die();
    break;

  case 'add-recent-event':
    $current_date       = date('Y-m-d H:i:s');

    $title              = cleanStr($_POST['title']);
    $business_id        = cleanStr($_POST['businessId']);
    $short_description  = cleanStr($_POST['shortDescription']);
    $long_description   = cleanStr($_POST['longDescription'], 'html');

    $principal_image    = $_FILES['principalImage'];
    $image_gallery      = $_FILES['imageGallery'];

    $reference          = date('YmdHis');
    $recent_event_slug  = createSlug($title);

    # PICTURES QUERY
    $query_picture        = "";
    $query_insert_picture = "";

    # AGREGAR LA IMAGEN DE CEREMONIA RELIGIOSA
    if ($principal_image['name']) :
      $proccess_principal_image = processFile(
        $principal_image,
        $valid_extensions,
        RECENT_EVENTS_IMAGE_FOLDER,
        'recent-events'
      );

      if ($proccess_principal_image !== 'no-move' && $proccess_principal_image !== 'no-valid') :
        $query_picture         .= ", Imagen";
        $query_insert_picture  .= ", '$proccess_principal_image'";
      endif;
    endif;

    $query = "INSERT INTO eventos_recientes (
        idSalon,
        Evento,
        DescCorta,
        Descripcion,
        Fecha,
        slug
        $query_picture
      ) VALUES (
        $business_id,
        '$title',
        '$short_description',
        '$long_description',
        '$current_date',
        '$recent_event_slug'
        $query_insert_picture
      )
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) :
      $recent_event_id = mysqli_insert_id($mysqli);

      /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
      //-- AGREGAR LA REFERENCIA
      :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
      $query_add_reference = "UPDATE eventos_recientes SET Referencia = '$recent_event_id' WHERE idEvento = $recent_event_id";
      mysqli_query($mysqli, $query_add_reference);

      # AGREGAR IMAGENES DE LA GALERÍA
      $image_of_gallery = processMultipleFiles(
        $image_gallery,
        $valid_extensions,
        RECENT_EVENTS_GALLERY_FOLDER,
        'recent-event-g'
      );

      foreach ($image_of_gallery as $key => $value) :
        $image = $image_of_gallery[$key];

        $query = "INSERT INTO galeria_eventos_recientes (
            idEvento,
            Imagen
          ) VALUES (
            $recent_event_id,
            '$image'
          )
        ";

        mysqli_query($mysqli, $query);
      endforeach;

      $response = array(
        'status'  => 'success',
        'title'   => '¡Datos guardados!',
        'message' => 'El evento reciente ha sido creado correctamente.'
      );
    endif;
    break;

  case 'edit-recent-event':
    $recent_event_id    = cleanStr($_POST['recentEventId']);
    $current_date       = date('Y-m-d H:i:s');

    $title              = cleanStr($_POST['title']);
    $business_id        = cleanStr($_POST['businessId']);
    $short_description  = cleanStr($_POST['shortDescription']);
    $long_description   = cleanStr($_POST['longDescription'], 'html');

    $principal_image    = $_FILES['principalImage'];
    $image_gallery      = $_FILES['imageGallery'];

    $recent_event_slug  = createSlug($title);

    $principal_image_name = getRecentEventPrincipalImageName($recent_event_id);

    $original_image_gallery = getRecentEventGalleryIds($recent_event_id);
    $new_image_gallery      = $_POST['imageGallery-items'];

    # AGREGAR LA IMAGEN DE CEREMONIA RELIGIOSA
    if ($principal_image['name']) processFile(
      $principal_image,
      $valid_extensions,
      RECENT_EVENTS_IMAGE_FOLDER,
      'recent-events',
      $principal_image_name
    );

    $query = "UPDATE eventos_recientes SET
        idSalon     = $business_id,
        Evento      = '$title',
        DescCorta   = '$short_description',
        Descripcion = '$long_description',
        Fecha       = '$current_date',
        slug        = '$recent_event_slug'
      WHERE idEvento = $recent_event_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) :
      # ELIMINAR LAS IMAGENES QUE SE REMOVIERON
      deleteRecentEventImageGallery(
        $original_image_gallery,
        $new_image_gallery
      );

      # AGREGAR IMAGENES DE LA GALERÍA
      $image_of_gallery = processMultipleFiles(
        $image_gallery,
        $valid_extensions,
        RECENT_EVENTS_GALLERY_FOLDER,
        'recent-event-g'
      );

      foreach ($image_of_gallery as $key => $value) :
        $image = $image_of_gallery[$key];

        $query = "INSERT INTO galeria_eventos_recientes (
            idEvento,
            Imagen
          ) VALUES (
            $recent_event_id,
            '$image'
          )
        ";

        mysqli_query($mysqli, $query);
      endforeach;

      $response = array(
        'status'  => 'success',
        'title'   => '¡Datos guardados!',
        'message' => 'El evento reciente ha sido actualizado correctamente.'
      );
    endif;
    break;

  case 'delete-recent-event':
    $recent_event_id = cleanStr($_POST['itemId']);
    $recent_event    = cleanStr($_POST['item']);

    $query        = "UPDATE eventos_recientes SET Eliminado = 'Si' WHERE idEvento = $recent_event_id";
    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) $response['message'] = '¡Error!, Intentelo nuevamente.';

    if ($query_result) $response = array(
      'status' => 'success',
      'message' => 'El evento reciente "' . $recent_event . '" se eliminó correctamente.'
    );
    break;

  default:
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
