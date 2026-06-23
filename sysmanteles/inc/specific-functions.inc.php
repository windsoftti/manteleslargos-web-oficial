<?php
/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- RECENT EVENTS
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function getRecentEventGallery(
  $recent_event_id
) {
  global $mysqli;

  $gallery = array();

  $query = "SELECT
      idGaleria,
      idEvento,
      Imagen
    FROM galeria_eventos_recientes
    WHERE idEvento = $recent_event_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return;

  if ($num_rows) :
    while ($row = mysqli_fetch_array($query_result)) :
      array_push($gallery, array(
        'imageId'   => $row['idGaleria'],
        'imageSrc'  => BASE_URL_FRONTED . '/src/assets/images/recent-events/gallery/' . $row['Imagen'],
        'imageName' => $row['Imagen']
      ));
    endwhile;
  endif;

  return $gallery;
}

function setRecentEventImage(
  $img,
  $gallery = false
) {
  if (!$img) return BASE_URL_FRONTED . '/src/assets/images/500x500.png';

  $img_location = BASE_PATH_FRONTED . '/src/assets/images/recent-events/';
  $image = BASE_URL_FRONTED . '/src/assets/images/recent-events/';

  if (!$gallery) {
    $img_location   .= $img;
    $image .= $img;
  }

  if ($gallery) {
    $img_location   .= 'gallery/' . $img;
    $image .= 'gallery/' . $img;
  }

  $img_exist = realpath($img_location);

  if ($img_exist) return $image;
  if (!$img_exist) return BASE_URL_FRONTED . '/src/assets/images/500x500.png';
}

function getRecentEventPrincipalImageName(
  $recent_event_id
) {
  global $mysqli;

  $query = "SELECT Imagen FROM eventos_recientes WHERE
      idEvento  = $recent_event_id AND
      Eliminado = 'No'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $data = mysqli_fetch_array($query_result);

  return $data['Imagen'];
}

function getRecentEventGalleryIds(
  $recent_event_id
) {
  global $mysqli;

  $gallery = array();

  $query = "SELECT
      idGaleria,
      idEvento,
      Imagen
    FROM galeria_eventos_recientes
    WHERE idEvento = $recent_event_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    while ($row = mysqli_fetch_array($query_result)) :
      array_push($gallery, $row['idGaleria']);
    endwhile;
  endif;

  return $gallery;
}

function deleteRecentEventImageGallery(
  $original_image_gallery = array(),
  $new_image_gallery = array()
) {
  global $mysqli;

  $array_diff = array();

  if (!$new_image_gallery)  $array_diff = $original_image_gallery;
  if ($new_image_gallery)   $array_diff = array_diff($original_image_gallery, $new_image_gallery);

  foreach ($array_diff as $key => $value) :
    $image_id = $value;

    $query = "SELECT Imagen FROM galeria_eventos_recientes WHERE idGaleria = $image_id LIMIT 1";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) :
      $image_data = mysqli_fetch_array($query_result);
      $image_name = $image_data['Imagen'];

      $file_location = RECENT_EVENTS_GALLERY_FOLDER . $image_name;

      $delete_file = deleteFile($file_location);

      if ($delete_file == 'deleted' || $delete_file == 'not-exist') :
        $query_delete = "DELETE FROM galeria_eventos_recientes WHERE idGaleria = $image_id";
        mysqli_query($mysqli, $query_delete);
      endif;
    endif;
  endforeach;
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- TIPS
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function getTipGallery(
  $tip_id
) {
  global $mysqli;

  $gallery = array();

  $query = "SELECT
      idGaleria,
      Imagen
    FROM tips_galeria
    WHERE idTip = $tip_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return;

  if ($num_rows) :
    while ($row = mysqli_fetch_array($query_result)) :
      array_push($gallery, array(
        'imageId'   => $row['idGaleria'],
        'imageSrc'  => setTipImage($row['Imagen'], true),
        'imageName' => $row['Imagen']
      ));
    endwhile;
  endif;

  return $gallery;
}

function setTipImage(
  $img,
  $gallery = false
) {
  if (!$img) return BASE_URL_FRONTED . '/src/assets/images/500x500.png';

  $img_location = BASE_PATH_FRONTED . '/src/assets/images/tips/';
  $image        = BASE_URL_FRONTED . '/src/assets/images/tips/';

  if (!$gallery) {
    $img_location .= $img;
    $image        .= $img;
  }

  if ($gallery) {
    $img_location .= 'gallery/' . $img;
    $image        .= 'gallery/' . $img;
  }

  $img_exist = realpath($img_location);

  if ($img_exist) return $image;
  if (!$img_exist) return BASE_URL_FRONTED . '/src/assets/images/500x500.png';
}

function getTipPrincipalImageName(
  $tip_id
) {
  global $mysqli;

  $query = "SELECT Imagen FROM tips WHERE
      idTip     = $tip_id AND
      Eliminado = 'No'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $data = mysqli_fetch_array($query_result);

  return $data['Imagen'];
}

function getTipGalleryIds(
  $tip_id
) {
  global $mysqli;

  $gallery = array();

  $query = "SELECT
      idGaleria,
      Imagen
    FROM tips_galeria
    WHERE idTip = $tip_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    while ($row = mysqli_fetch_array($query_result)) :
      array_push($gallery, $row['idGaleria']);
    endwhile;
  endif;

  return $gallery;
}

function deleteTipImageGallery(
  $original_image_gallery = array(),
  $new_image_gallery      = array()
) {
  global $mysqli;

  $array_diff = array();

  if (!$new_image_gallery)  $array_diff = $original_image_gallery;
  if ($new_image_gallery)   $array_diff = array_diff($original_image_gallery, $new_image_gallery);

  foreach ($array_diff as $key => $value) :
    $image_id = $value;

    $query = "SELECT Imagen FROM tips_galeria WHERE idGaleria = $image_id LIMIT 1";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) :
      $image_data = mysqli_fetch_array($query_result);
      $image_name = $image_data['Imagen'];

      $file_location = TIPS_GALLERY_FOLDER . $image_name;

      $delete_file = deleteFile($file_location);

      if ($delete_file == 'deleted' || $delete_file == 'not-exist') :
        $query_delete = "DELETE FROM tips_galeria WHERE idGaleria = $image_id";
        mysqli_query($mysqli, $query_delete);
      endif;
    endif;
  endforeach;
}
