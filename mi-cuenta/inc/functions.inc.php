<?php
require_once __DIR__ . '/helpers/sidebar.php';
require_once __DIR__ . '/helpers/business-plan-helper.php';
require_once __DIR__ . '/helpers/subscription-helper.php';
require_once __DIR__ . '/helpers/subscription-order-helper.php';
require_once __DIR__ . '/helpers/subscription-history-helper.php';
require_once __DIR__ . '/helpers/payment-helper.php';
require_once __DIR__ . '/helpers/settings-helper.php';
require_once __DIR__ . '/helpers/mercadopago-helper.php';

function mysqli_query_one_row($query)
{
  global $mysqli;

  $query_result = mysqli_query($mysqli, $query);
  $num_rows = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  if ($num_rows) {
    $row = mysqli_fetch_array($query_result);

    return $row;
  }
}

function query($query)
{
  global $mysqli;

  $query_result = mysqli_query($mysqli, $query);
  $num_rows = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  if ($num_rows) return $query_result;
}

function encrypt($string, $key)
{
  $result = '';
  for ($i = 0; $i < strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key)) - 1, 1);
    $char = chr(ord($char) + ord($keychar));
    $result .= $char;
  }
  return base64_encode($result);
}

function decrypt($string, $key)
{
  $result = '';
  $string = base64_decode($string);
  for ($i = 0; $i < strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key)) - 1, 1);
    $char = chr(ord($char) - ord($keychar));
    $result .= $char;
  }
  return $result;
}

function createSlug(
  $str,
  $max = 100
) {
  $out = str_replace('año', 'anio', $str);
  $out = iconv('UTF-8', 'ASCII//TRANSLIT', $out);
  $out = substr(preg_replace('/[^-\/+|\w ]/', '', $out), 0, $max);
  $out = strtolower(trim($out, '-'));
  $out = preg_replace('/[\/_| -]+/', '-', $out);
  $out = str_replace('+', 'mas', $out);

  return $out;
}

function cleanStr(
  $str,
  $priority = 'high'
) {
  global $mysqli;

  if ($str == 'null' || $str == null) return '';

  if ($priority === 'high' || $priority === 'number') {
    $bad_string = array('select', 'drop', ';', '--', 'insert', 'delete', 'xp_', '%20union%20', '/', '/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<script', '<iframe', '<applet', '<meta', '<style', '<form', '<img', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'http://', 'https://', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=', '<', '>', 'href=');
  }

  if ($priority === 'medium') {
    $bad_string = array('select', 'drop', 'insert', 'delete', 'xp_', '%20union%20', '/', '/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<script', '<iframe', '<applet', '<meta', '<style', '<form', '<img', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'http://', 'https://', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=');
  }

  if ($priority === 'low') {
    $bad_string = array('<script', '<iframe', '<applet', '<', '>', 'href=', 'select', 'drop', 'insert', 'delete');
  }

  if ($priority === 'html') {
    $bad_string = array('<script', '<iframe', '<applet', 'select', 'drop', 'insert', 'delete');
  }

  $bad_string_size  = count($bad_string);
  $count            = 0;

  while ($count <= $bad_string_size) {
    $str = str_replace($bad_string[$count], '/', $str);
    $count++;
  }

  $str = str_replace("`", "", $str);
  $str = str_replace("'", "", $str);
  $str = str_replace('"', "", $str);
  $str = str_replace('¨', "", $str);

  $str = trim($str);

  //$str = str_replace("`", "'", $str);
  //
  //$str = mysqli_real_escape_string($mysqli, $str);

  return $str;
}

function procesarArchivo($file, $extensions, $folder)
{
  $fecha_hoy = date('dmYHis');

  $file_name         = $file['name'];
  $file_tipo_archivo = $file['type'];
  $file_tmp_name     = $file['tmp_name'];

  $file_extension    = pathinfo($file_name, PATHINFO_EXTENSION);
  $file_name_text    = pathinfo($file_name, PATHINFO_FILENAME);
  $file_new_name     = $file_name_text . '-' . $fecha_hoy . '.' . $file_extension;

  $file_folder       = $folder . $file_new_name;

  if (!in_array($file_extension, $extensions)) return 'no-valid';

  $move_file = move_uploaded_file($file_tmp_name, $file_folder);

  if (!$move_file) return 'no-move';

  return $file_new_name;
}

function procesarMultiplesArchivos($file, $extensions, $folder, $key = null)
{
  $fecha_hoy = date('dmYHis');

  $file_name         = $file['name'][$key];
  $file_tipo_archivo = $file['type'][$key];
  $file_tmp_name     = $file['tmp_name'][$key];

  $file_extension    = pathinfo($file_name, PATHINFO_EXTENSION);
  $file_name_text    = pathinfo($file_name, PATHINFO_FILENAME);
  $file_new_name     = $file_name_text . '-' . $fecha_hoy . '.' . $file_extension;

  $file_folder       = $folder . $file_new_name;

  if (!in_array($file_extension, $extensions)) return 'no-valid';

  $move_file = move_uploaded_file($file_tmp_name, $file_folder);

  if (!$move_file) return 'no-move';

  return $file_new_name;
}

function verificarMultiplesArchivos($file, $extensions, $key = null)
{

  $file_name         = $file['name'][$key];

  $file_extension    = pathinfo($file_name, PATHINFO_EXTENSION);

  if (!in_array($file_extension, $extensions)) return 'no-valid';
  else return 'ok';
}

/* function processFile($file, $extensions, $folder)
{
  $today_date = date('dmYHis');

  $file_name = $file['name'];
  $file_tmp_name = $file['tmp_name'];

  $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
  $file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);

  $new_file_name = $file_name_without_extension . '-' . $today_date . '.' . $file_extension;

  $file_with_folder = $folder . $new_file_name;

  if (!in_array($file_extension, $extensions)) {
    return 'no-valid';
  }

  $move_file = move_uploaded_file($file_tmp_name, $file_with_folder);

  if (!$move_file) {
    return 'no-move';
  }

  return $new_file_name;
} */

function processFile(
  $file,
  $extensions,
  $folder,
  $name = 'image',
  $full_name = null
) {
  $today_date = date('dmYHis');

  $file_name = $file['name'];
  $file_tmp_name = $file['tmp_name'];

  $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
  $file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);

  //$new_file_name = $file_name_without_extension . '-' . $today_date . '.' . $file_extension;

  //$new_file_name = "manteleslargos$name-$today_date.$file_extension";
  $new_file_name = 'manteleslargos_' . $name . '_' . $today_date . '.' . $file_extension;

  if ($full_name) $new_file_name = $full_name;

  $file_with_folder = $folder . $new_file_name;

  if (!in_array($file_extension, $extensions)) {
    return 'no-valid';
  }

  $move_file = move_uploaded_file($file_tmp_name, $file_with_folder);

  if (!$move_file) {
    return 'no-move';
  }

  return $new_file_name;
}

/* function processMultipleFiles($file, $extensions, $folder, $key = null)
{
  $today_date = date('dmYHis');

  $file_name = $file['name'][$key];
  $file_tmp_name = $file['tmp_name'][$key];

  $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
  $file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);

  $new_file_name = $file_name_without_extension . '-' . $today_date . '.' . $file_extension;

  $file_with_folder = $folder . $new_file_name;

  if (!in_array($file_extension, $extensions)) {
    return 'no-valid';
  }

  $move_file = move_uploaded_file($file_tmp_name, $file_with_folder);

  if (!$move_file) {
    return 'no-move';
  }

  return $new_file_name;
} */

function processMultipleFiles(
  $array,
  $extensions,
  $folder,
  $name = 'image'
) {
  $files_uploaded = array();

  foreach ($array['tmp_name'] as $key => $value) :
    if ($array['name'][$key]) :
      $today_date = date('dmYHis');

      $file_name = $array['name'][$key];
      $file_tmp_name = $array['tmp_name'][$key];

      $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
      $file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);

      //$new_file_name = $file_name_without_extension . '-' . $today_date . '.' . $file_extension;
      $new_file_name = 'manteleslargos_' . $name . '_' . $key . '_' . $today_date . '.' . $file_extension;

      $file_with_folder = $folder . $new_file_name;

      $in_array = in_array($file_extension, $extensions);
      $move_file = move_uploaded_file($file_tmp_name, $file_with_folder);

      if ($in_array && $move_file) array_push($files_uploaded, $new_file_name);
    endif;
  endforeach;

  return $files_uploaded;
}

function deleteFile($file_location)
{
  $file_exist = file_exists($file_location);

  if (!$file_exist) {
    //return 'not-exist';
    return 'deleted';
  }

  if ($file_exist) {
    $file_unlink = unlink($file_location);

    if ($file_unlink) {
      return 'deleted';
    }

    if (!$file_unlink) {
      return 'not-deleted';
    }
  }
}

function formatPhoneNumber($phone_number)
{
  $phone_number = preg_replace('/[^0-9]/', '', $phone_number);

  if (strlen($phone_number) > 10) {
    $countryCode    = substr($phone_number, 0, strlen($phone_number) - 10);
    $areaCode       = substr($phone_number, -10, 3);
    $nextThree      = substr($phone_number, -7, 3);
    $lastFour       = substr($phone_number, -4, 4);

    $phone_number   = '+' . $countryCode . ' (' . $areaCode . ') ' . $nextThree . '-' . $lastFour;
  } else if (strlen($phone_number) == 10) {
    $areaCode   = substr($phone_number, 0, 3);
    $nextThree  = substr($phone_number, 3, 3);
    $lastFour   = substr($phone_number, 6, 4);

    $phone_number = '(' . $areaCode . ') ' . $nextThree . '-' . $lastFour;
  } else if (strlen($phone_number) == 7) {
    $nextThree  = substr($phone_number, 0, 3);
    $lastFour   = substr($phone_number, 3, 4);

    $phone_number = $nextThree . '-' . $lastFour;
  }

  return $phone_number;
}

function processImage($file, $extensions, $folder)
{
  $today_date = date('dmYHis');

  $file_name = $file['name'];
  $file_tmp_name = $file['tmp_name'];

  $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
  $file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);

  $new_file_name = $file_name_without_extension . '-' . $today_date . '.' . $file_extension;
  $new_name_without_extension = $file_name_without_extension . '-' . $today_date;

  $file_with_folder = $folder . $new_file_name;

  if (!in_array($file_extension, $extensions)) {
    return 'no-valid';
  }

  $move_file = move_uploaded_file($file_tmp_name, $file_with_folder);

  if (!$move_file) {
    return 'no-move';
  }

  $data_return = array(
    "name" => $new_file_name,
    "nameWithOutExtension" => $new_name_without_extension
  );

  return $data_return;
}

function processOptimizedImage(
  $file,
  $extensions,
  $folder,
  $name = 'image',
  $full_name = null
) {
  $max_width = 1280;
  $max_height = 900;

  $today_date = date('dmYHis');

  $file_name = $file['name'];
  $file_tmp_name = $file['tmp_name'];
  $file_type = $file['type'];

  $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
  $file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);

  //$new_file_name = $file_name_without_extension . '-' . $today_date . '.' . $file_extension;
  //$new_name_without_extension = $file_name_without_extension . '-' . $today_date;

  $new_file_name = 'manteleslargos_' . $name . '_' . $today_date . '.' . $file_extension;
  $new_name_without_extension = 'manteleslargos_' . $name . '_' . $today_date;

  if ($full_name) $new_file_name = $full_name;

  $file_with_folder = $folder . $new_file_name;

  if (!in_array($file_extension, $extensions)) {
    return 'no-valid';
  }

  if ($file_type === 'image/jpeg') {
    $new_image = imagecreatefromjpeg($file_tmp_name);
  } else if ($file_type === 'image/png') {
    $new_image = imagecreatefrompng($file_tmp_name);
  } else if ($file_type === 'image/gif') {
    $new_image = imagecreatefromgif($file_tmp_name);
  }

  list($width, $height) = getimagesize($file_tmp_name);

  $x_ratio = $max_width / $width;
  $y_ratio = $max_height / $height;

  if (($width <= $max_width) && ($height <= $max_height)) {
    $final_height = $height;
    $final_width = $width;
  } else if (($x_ratio * $height) < $max_height) {
    $final_height = ceil($x_ratio * $height);
    $final_width = $max_width;
  } else {
    $final_height = $max_height;
    $final_width = ceil($y_ratio * $width);
  }

  $canvas = imagecreatetruecolor($final_width, $final_height);
  imagecopyresampled($canvas, $new_image, 0, 0, 0, 0, $final_width, $final_height, $width, $height);

  $move_file = false;

  if ($file_type == 'image/jpeg' || $file_type == 'image/jpg') {
    $move_file = imagejpeg($canvas, $file_with_folder);
  } else if ($file_type == 'image/png') {
    $move_file = imagepng($canvas, $file_with_folder);
  } else if ($file_type == 'image/png') {
    $move_file = imagegif($canvas, $file_with_folder);
  }

  if (!$move_file) {
    return 'no-move';
  }

  $data_return = array(
    "name" => $new_file_name,
    "nameWithOutExtension" => $new_name_without_extension
  );

  return $data_return;
}


function processMultipleOptimizedImage($file, $extensions, $folder, $key = null)
{
  $max_width = 1280;
  $max_height = 900;

  $today_date = date('dmYHis');

  $file_name = $file['name'][$key];
  $file_tmp_name = $file['tmp_name'][$key];
  $file_type = $file['type'][$key];

  $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
  $file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);

  $new_file_name = $file_name_without_extension . '-' . $today_date . '.' . $file_extension;
  $new_name_without_extension = $file_name_without_extension . '-' . $today_date;

  $file_with_folder = $folder . $new_file_name;

  if (!in_array($file_extension, $extensions)) {
    return 'no-valid';
  }

  if ($file_type === 'image/jpeg') {
    $new_image = imagecreatefromjpeg($file_tmp_name);
  } else if ($file_type === 'image/png') {
    $new_image = imagecreatefrompng($file_tmp_name);
  } else if ($file_type === 'image/gif') {
    $new_image = imagecreatefromgif($file_tmp_name);
  }

  list($width, $height) = getimagesize($file_tmp_name);

  $x_ratio = $max_width / $width;
  $y_ratio = $max_height / $height;

  if (($width <= $max_width) && ($height <= $max_height)) {
    $final_height = $height;
    $final_width = $width;
  } else if (($x_ratio * $height) < $max_height) {
    $final_height = ceil($x_ratio * $height);
    $final_width = $max_width;
  } else {
    $final_height = $max_height;
    $final_width = ceil($y_ratio * $width);
  }

  $canvas = imagecreatetruecolor($final_width, $final_height);
  imagecopyresampled($canvas, $new_image, 0, 0, 0, 0, $final_width, $final_height, $width, $height);

  $move_file = false;

  if ($file_type == 'image/jpeg' || $file_type == 'image/jpg') {
    $move_file = imagejpeg($canvas, $file_with_folder);
  } else if ($file_type == 'image/png') {
    $move_file = imagepng($canvas, $file_with_folder);
  } else if ($file_type == 'image/png') {
    $move_file = imagegif($canvas, $file_with_folder);
  }

  if (!$move_file) {
    return 'no-move';
  }

  return $new_file_name;
}

function generateSlug($text, $divider = '-')
{
  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  $text = preg_replace('~[^-\w]+~', '', $text);
  $text = trim($text, $divider);
  $text = preg_replace('~-+~', $divider, $text);

  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

function addDateStatus(
  $user_id,
  $bussines_id,
  $date,
  $status
) {
  global $mysqli;

  $query = "SELECT
      idCalendarioFecha
    FROM calendario_fechas
    WHERE
      idUsuario = '$user_id' AND
      idNegocio = '$bussines_id' AND
      Fecha     = '$date'
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) {
    $query = "INSERT INTO calendario_fechas (
        idUsuario,
        idNegocio,
        Fecha,
        DateStatus
      ) VALUES (
        '$user_id',
        '$bussines_id',
        '$date',
        '$status'
      )
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) return false;
    if ($query_result) return true;
  }

  if ($num_rows) {
    $query = "UPDATE calendario_fechas SET
        DateStatus = '$status'
      WHERE
        idUsuario = '$user_id' AND
        idNegocio = '$bussines_id' AND
        Fecha     = '$date'
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) return false;
    if ($query_result) return true;
  }
}

/* function addDateStatus_(
  $user_id,
  $reservation_id,
  $status,
  $date
) {
  global $mysqli;

  $query = "SELECT
      idFechaCalendario
    FROM fechas_calendario
    WHERE
      idUsuario = '$user_id' AND
      Fecha     = '$date'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) {
    $query = "INSERT INTO fechas_calendario (
        idUsuario,
        Fecha,
        DateStatus
      ) VALUES (
        '$user_id',
        '$date',
        '$status'
      )
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) return false;

    if ($query_result) {
      $calendar_date_id = mysqli_insert_id($mysqli);

      if ($reservation_id) addEventCalendarDateBusiness(
        $reservation_id,
        $calendar_date_id
      );

      return true;
    }
  }

  if ($num_rows) {
    $data_date = mysqli_fetch_array($query_result);
    $calendar_date_id = $data_date['idFechaCalendario'];

    $query = "UPDATE fechas_calendario SET
        DateStatus = '$status'
      WHERE
        idUsuario = '$user_id' AND
        Fecha     = '$date'
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) return false;
    if ($query_result) {
      if ($reservation_id) addEventCalendarDateBusiness(
        $reservation_id,
        $calendar_date_id
      );

      return true;
    }
  }
}

function addEventCalendarDateBusiness(
  $reservation_id,
  $calendar_date_id
) {
  global $mysqli;

  $query = "SELECT
      idCalendarioFecha
    FROM calendario_fechas
    WHERE
      idReservacion  = '$reservation_id'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) {
    $query = "UPDATE calendario_fechas SET
        idFechaCalendario = '$calendar_date_id'
      WHERE idReservacion = '$reservation_id'
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) return false;
    if ($query_result) return true;
  }

  if (!$num_rows) {
    $query = "INSERT INTO calendario_fechas (
        idReservacion,
        idFechaCalendario
      ) VALUES (
        '$reservation_id',
        '$calendar_date_id'
      )
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) return false;
    if ($query_result) return true;
  }
} */

function addDateAdvance($user_id, $reservation_id, $advance)
{
  global $mysqli;

  $date = date('Y-m-d H:i:s');

  $query = "INSERT INTO reservaciones_pagos (
      idUsuario,
      idReservacion,
      Pago,
      Fecha,
      Comentarios
    ) VALUES (
      '$user_id',
      '$reservation_id',
      '$advance',
      '$date',
      'Anticipo'
    )
  ";

  $query_result = mysqli_query($mysqli, $query);

  if (!$query_result) return false;
  if ($query_result) return true;
}


function parseDatePicker($initial_date)
{
  $date_replace   = str_replace('/', '-', $initial_date);
  $date           = date('Y-m-d', strtotime($date_replace));

  return $date;
}

function addPaymentRecordatory(
  $id_user_create,
  $reservation_id,
  $rc_percentages,
  $rc_dates
) {
  global $mysqli;

  foreach ($rc_percentages as $key => $value) :
    $rc_percentage  = cleanStr($rc_percentages[$key]);
    $rc_date        = date('Y-m-d', strtotime($rc_dates[$key]));

    $query = "INSERT INTO recordatorio_pagos (
        idUsuario,
        idReservacion,
        Porcentaje,
        Fecha
      ) VALUES (
        $id_user_create,
        $reservation_id,
        '$rc_percentage',
        '$rc_date'
      )
    ";

    mysqli_query($mysqli, $query);
  endforeach;

  return true;
}

function showPlusIcon($page_id)
{
  global $mysqli;

  $response = null;

  $query = "SELECT
      idPagina,
      NombrePagina,
      PerteneceA
    FROM ml_paginas
    WHERE PerteneceA = '$page_id'
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return;

  $response .= "
    <span class='ml-1 p-0'>
      <a class='p-0' href='#accordion-categories-$page_id'
        class='card-link row text-dark'
        data-toggle='collapse'
      >
        <i class='fa fa-plus ml-3'></i>
      </a>
    </span>
  ";

  return $response;
}

function createPermissionsList($parent_id = 0)
{
  global $mysqli;

  $response = null;

  $query = "SELECT
      idPagina,
      NombrePagina,
      PerteneceA
    FROM ml_paginas
    WHERE PerteneceA = '$parent_id'
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return;

  $margin_left = $parent_id != 0 ? 'ml-3' : '';

  $response .= "<div id='accordion-categories-$parent_id' class='$margin_left according accordion-s3 align-middle'>";

  while ($row = mysqli_fetch_array($query_result)) :
    $response .= "<div class='custom-control custom-checkbox mb-2'>";

    $response .= "<div class='d-flex ml-1 align-items-center justify-content-start'>";
    $response .= "<input id='check-$row[idPagina]' class='custom-control-input' type='checkbox' name='idPagina[]' value='$row[idPagina]' style='width:30px;'>";
    $response .= "<label class='custom-control-label mb-0 ml-1' for='check-$row[idPagina]'>$row[NombrePagina]</label>";
    $response .= showPlusIcon($row['idPagina']);
    $response .= "</div>";

    $response .= "</div>";

    $response .= "<div id='accordion-categories-$row[idPagina]' class='collapse align-middle' data-parent='#accordion-categories-$parent_id'>";
    $response .= createPermissionsList($row['idPagina']);
    $response .= "</div>";
  endwhile;

  $response .= "</div>";

  return $response;
}

function createEditPermissionsList($parent_id = 0, $array_permissions = [])
{
  global $mysqli;

  $response = null;

  $query = "SELECT
      idPagina,
      NombrePagina,
      PerteneceA
    FROM ml_paginas
    WHERE PerteneceA = '$parent_id'
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return;

  $margin_left = $parent_id != 0 ? 'ml-3' : '';

  $response .= "<div id='accordion-categories-$parent_id' class='$margin_left according accordion-s3 align-middle'>";

  while ($row = mysqli_fetch_array($query_result)) :
    $class_checked = in_array($row['idPagina'], $array_permissions) ? 'checkbox-input-data' : '';
    $checked = in_array($row['idPagina'], $array_permissions) ? "checked" : "";

    $response .= "<div class='custom-control custom-checkbox mb-2'>";

    $response .= "<div class='d-flex ml-1 align-items-center justify-content-start'>";
    $response .= "<input id='check-$row[idPagina]' class='custom-control-input $class_checked' type='checkbox' name='idPagina[]' value='$row[idPagina]' style='width:30px;' $checked>";
    $response .= "<label class='custom-control-label mb-0 ml-1' for='check-$row[idPagina]'>$row[NombrePagina]</label>";
    $response .= showPlusIcon($row['idPagina']);
    $response .= "</div>";

    $response .= "</div>";

    $response .= "<div id='accordion-categories-$row[idPagina]' class='collapse align-middle' data-parent='#accordion-categories-$parent_id'>";
    $response .= createEditPermissionsList($row['idPagina'], $array_permissions);
    $response .= "</div>";
  endwhile;

  $response .= "</div>";

  return $response;
}

/* function verifyUserPermissions(
  $user_id,
  $page_slug
) {
  global $mysqli;

  $query = "SELECT
      PUP.idPaginaUsuarioPermiso,
      PUP.idPagina,
      PUP.idUsuario,
      P.Slug
    FROM ml_paginas_usuarios_permisos AS PUP
      LEFT JOIN ml_paginas AS P ON (PUP.idPagina = P.idPagina)
    WHERE
      PUP.idUsuario = $user_id AND
      P.Slug        = '$page_slug'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);

  if (!$query_result) return false;

  $num_rows = mysqli_num_rows($query_result);

  if (!$num_rows) return false;
  if ($num_rows)  return true;
} */

/*function getPlan()
{
  global $mysqli;
  global $_SESSION;

  $user_id = $_SESSION['session_user_id'];

  $query = "SELECT
      Plan
    FROM usuarios
    WHERE idUsuario = $user_id
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $user_data    = mysqli_fetch_array($query_result);

  $plan = $user_data['Plan'];

  return $plan;
}*/
/*function getPlan()
{
    global $mysqli;

    if (
        empty($_SESSION['session_business_id'])
    ) {
        return 'Básico';
    }

    $id_salon =
        (int) $_SESSION['session_business_id'];

    $sql = "
        SELECT
            p.Plan
        FROM ml_business_subscriptions s

        INNER JOIN ml_planes p
            ON p.idPlan = s.id_plan

        WHERE
            s.id_salon = ?
            AND s.status = 'active'

        ORDER BY s.id_subscription DESC

        LIMIT 1
    ";

    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param(
        'i',
        $id_salon
    );

    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result->num_rows) {
        return 'Básico';
    }

    $row = $result->fetch_assoc();

    return $row['Plan'];
}*/

function getNumBusiness()
{
  global $mysqli;
  global $_SESSION;

  $user_id = $_SESSION['session_user_id'];

  $query = "SELECT
      COUNT(idSalon) AS Total
    FROM salones
    WHERE idUsuario = $user_id
    LIMIT 1
  ";

  $query_result   = mysqli_query($mysqli, $query);
  $business_data  = mysqli_fetch_array($query_result);

  $num_business = $business_data['Total'];

  return $num_business;
}

function verifyUserPermissions(
  $page_slug
) {
  global $mysqli;
  global $_SESSION;

  if ($_SESSION['session_user_is_admin_supplier'] === 'Si') return true;

  $user_id = $_SESSION['session_user_children_id'];

  $query = "SELECT
      PUP.idPaginaUsuarioPermiso,
      PUP.idPagina,
      PUP.idUsuario,
      P.Slug
    FROM ml_paginas_usuarios_permisos AS PUP
      LEFT JOIN ml_paginas AS P ON (PUP.idPagina = P.idPagina)
    WHERE
      PUP.idUsuario = $user_id AND
      P.Slug        = '$page_slug'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);

  if (!$query_result) return false;

  $num_rows = mysqli_num_rows($query_result);

  if (!$num_rows) return false;
  if ($num_rows)  return true;
}

function getQuoteFolio($business_id, $mark = 'M-')
{
  global $mysqli;
  $today_year = date('Y');

  $query_get_folio = "SELECT
      Folio
    FROM cotizaciones
    WHERE
      YEAR(FechaCreacion) = $today_year AND
      idNegocio = $business_id
    ORDER BY Folio
    DESC
    LIMIT 1
  ";

  $query_get_folio_result = mysqli_query($mysqli, $query_get_folio);
  $query_get_folio_num_rows = mysqli_num_rows($query_get_folio_result);

  if (!$query_get_folio_num_rows) return $mark . '0001/' . $today_year;

  if ($query_get_folio_num_rows) {
    $quote_data = mysqli_fetch_array($query_get_folio_result);
    $folio = $quote_data['Folio'];

    $folio = str_replace($mark, '', $folio);
    $folio = str_replace('/' . $today_year, '', $folio);
    $folio = ltrim($folio, '0');

    $new_num = intval($folio) + 1;
    $num_folio_length = strlen($new_num);
    $new_num_folio = '';

    if ($num_folio_length === 1) $new_num_folio = '000' . $new_num;
    if ($num_folio_length === 2) $new_num_folio = '00' . $new_num;
    if ($num_folio_length === 3) $new_num_folio = '0' . $new_num;
    if ($num_folio_length === 4) $new_num_folio = $new_num;

    $new_folio = $mark . $new_num_folio . '/' . $today_year;

    return $new_folio;
  }
}

function generateReminderFirstDate(
  $quantity,
  $periodicity,
  $date
) {
  $new_date = null;

  if ($periodicity === 'Dia')  $new_date = date("Y-m-d", strtotime($date . "- $quantity day"));
  if ($periodicity === 'Semanal')  $new_date = date("Y-m-d", strtotime($date . "- $quantity week"));
  if ($periodicity === 'Mensual')   $new_date = date("Y-m-d", strtotime($date . "- $quantity month"));
  if ($periodicity === 'Anual')    $new_date = date("Y-m-d", strtotime($date . "- $quantity year"));

  return $new_date;
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- ACCESS TO SUPPLIER
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function getFirstBusinessId(
  $user_id
) {
  global $mysqli;

  $business_id = 0;

  $query_business = "SELECT
      idSalon,
      Salon
    FROM salones
    WHERE
      idUsuario = $user_id AND
      Status    = 'Activo'
    ORDER BY idSalon
    ASC LIMIT 1
  ";

  $query_business_result  = mysqli_query($mysqli, $query_business);
  $num_business           = mysqli_num_rows($query_business_result);

  if ($num_business) {
    $business_data  = mysqli_fetch_array($query_business_result);
    $business_id    = $business_data['idSalon'];

    $response = $business_id;
  }

  return $response;
}

function checkSupplierAccessStatus()
{
  global $_SESSION;
  global $mysqli;

  $status = 'unlogged';

  $supplier_id = $_SESSION['session_user_id'];

  $query = "SELECT
      idUsuario,
      idNegocio,
      Status,
      VerificationCodeStatus,
      PerteneceA
    FROM usuarios
    WHERE idUsuario = $supplier_id
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $user_data    = mysqli_fetch_array($query_result);

  $verification_code_status = $user_data['VerificationCodeStatus'];
  $user_status              = $user_data['Status'];
  $user_parent              = $user_data['PerteneceA'];
  $collaborator_id          = $user_data['idUsuario'];

  # VERIFICAR SI ES COLABORADOR
  $is_collaborator  = false;

  if ($user_parent) {
    $is_collaborator = true;
    $parent_id       = $user_parent;
  }

  if ($user_status === 'Descartado') $status = 'unlogged';

  if ($user_status === 'Inactivo') {
    if ($verification_code_status === 'Nuevo') $status = 'unverified';
    if ($verification_code_status === 'Usado') $status = 'unlogged';
  }

  if ($user_status === 'Activo') {
    $num_business = getFirstBusinessId($supplier_id);

    if (!$num_business || $num_business == 0) $status = 'no-business';

    if ($num_business) {
      $status       = 'logged';
      $business_id  = getFirstBusinessId($supplier_id);

      if ($is_collaborator) $business_id = $user_data['idNegocio'];
    }
  }

  return array(
    'status'          => $status,
    'businessId'      => $business_id,
    'isCollaborator'  => $is_collaborator,
    'collaboratorId'  => $collaborator_id
  );
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- AD BUSINESS
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function getSupplierTypesRadioButtons(
  $default_value = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      idTipoProveedor AS item_id,
      TipoProveedor   AS item,
      eventos
    FROM tipo_proveedores
    ORDER BY idTipoProveedor
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  while ($row = mysqli_fetch_array($query_result)) :
    $item_id = $row['item_id'];
    $item    = $row['item'];

    $selected = $item_id == $default_value ? 'checked' : '';

    $response .= '
      <div>
        <input id="supplier-type-' . $item_id . '" data-events="[' . $row['eventos'] . ']" class="supplier-type" name="supplierType" value="' . $item_id . '" type="radio" ' . $selected . ' labelError="Selecciona el tipo de proveedor" validate>
        <label for="supplier-type-' . $item_id . '">' . $item . '</label>
      </div>
    ';
  endwhile;

  return $response;
}

function getEventTypesArray()
{
  global $mysqli;

  $event_types = array();

  $query = "SELECT
      idTipoEvento,
      TipoEvento
    FROM tipo_eventos
    ORDER BY idTipoEvento
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  while ($row = mysqli_fetch_array($query_result)) :
    array_push($event_types, array(
      'eventTypeId' => $row['idTipoEvento'],
      'eventType'   => $row['TipoEvento']
    ));
  endwhile;

  return $event_types;
}

function getEventTypesCheckboxBySupplierTypeEvents(
  $supplier_type_events,
  $default_value = [],
  $tag = ''
) {
  global $mysqli;

  $response = '';

  $array = explode(',', $supplier_type_events);
  $array = implode("','", $array);

  $query = "SELECT
      idTipoEvento  AS item_id,
      TipoEvento    AS item
    FROM tipo_eventos
    WHERE idTipoEvento IN ('" . $array . "')
    ORDER BY idTipoEvento
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  while ($row = mysqli_fetch_array($query_result)) :
    $item_id = $row['item_id'];
    $item    = $row['item'];

    $selected = in_array($item_id, $default_value) ? 'checked' : '';

    $input_class = $tag . 'eventType-checkbox';
    $input_name = $tag . 'eventType[]';
    $input_id = $tag . 'event-type-' . $item_id;

    $response .= '
      <div>
        <input id="' . $input_id . '"
          class="' . $input_class . '"
          name="' . $input_name . '"
          value="' . $item_id . '"
          type="checkbox"
          ' . $selected . '
          labelError="Selecciona el tipo de evento"
          validate
        >

        <label for="' . $input_id . '">' . $item . '</label>
      </div>
    ';
  endwhile;

  return $response;
}

function getEventTypesCheckbox(
  $default_value = [],
  $tag = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      idTipoEvento  AS item_id,
      TipoEvento    AS item
    FROM tipo_eventos
    ORDER BY idTipoEvento
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  while ($row = mysqli_fetch_array($query_result)) :
    $item_id = $row['item_id'];
    $item    = $row['item'];

    $selected = in_array($item_id, $default_value) ? 'checked' : '';

    $input_class = $tag . 'eventType-checkbox';
    $input_name = $tag . 'eventType[]';
    $input_id = $tag . 'event-type-' . $item_id;

    $response .= '
      <div>
        <input id="' . $input_id . '"
          class="' . $input_class . '"
          name="' . $input_name . '"
          value="' . $item_id . '"
          type="checkbox"
          ' . $selected . '
          labelError="Selecciona el tipo de evento"
          validate
        >

        <label for="' . $input_id . '">' . $item . '</label>
      </div>
    ';
  endwhile;

  return $response;
}

function getBusinessPackageItem(
  $counter,
  $close = true,
  $package_data = []
) {
  $package_id               = $package_data['idPaquete'];
  $tipo_eventos             = $package_data['TipoEventos'] ? $package_data['TipoEventos'] : [];
  $package_name             = $package_data['Paquete'];
  $package_modality         = $package_data['Orientacion'];
  $package_price            = $package_data['Precio'];
  $package_description      = $package_data['Descripcion'];
  $package_more_contracted  = $package_data['MasContratado'];

  $per_person_checked       = $package_modality == 'Por persona'  ? 'checked' : '';
  $per_event_checked        = $package_modality == 'Por evento'   ? 'checked' : '';
  $more_contracted_checked  = $package_more_contracted === 'Si'   ? 'checked' : '';

  $event_types = getEventTypesCheckbox($tipo_eventos, 'package-' . $counter . '-');
  $btn_remove = $close ? '<span class="close btn-remove-package" style="cursor:pointer;">&times;</span>' : '<span class="close" style="cursor:pointer;"></span>';

  $response = '
    <div class="col-12 col-md-6" style="margin-bottom: 1rem;">
      <div class="card bordered">
        <div class="card-header">
          <h2 class="card-title"></h2>
          ' . $btn_remove . '
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label for="packageName-' . $counter . '">Nombre del paquete<span>*</span></label>
                <input id="packageName-' . $counter . '" name="packageName-' . $counter . '" value="' . $package_name . '" type="text" validate>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="form-group" style="padding: 0;">
                <label for="packagePrice">Precio y modalidad de tu paquete<span>*</span></label>
                <input type="hidden">
              </div>
            </div>

            <div class="col-12">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <input id="packagePrice-' . $counter . '" class="number-input" name="packagePrice-' . $counter . '" value="' . $package_price . '" type="number" validate>
                  </div>
                </div>

                <div class="col-6">
                  <div class="radiobutton-group column small">
                    <div>
                      <input id="modality-per-person-' . $counter . '" name="modality-' . $counter . '" value="Por persona" ' . $per_person_checked . ' type="radio" labelError="Selecciona la modalidad de tu paquete" validate>
                      <label for="modality-per-person-' . $counter . '">Por persona</label>
                    </div>

                    <div>
                      <input id="modality-per-event-' . $counter . '" name="modality-' . $counter . '" value="Por evento" ' . $per_event_checked . ' type="radio" labelError="Selecciona la modalidad de tu paquete" validate>
                      <label for="modality-per-event-' . $counter . '">Por evento</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row" style="margin-bottom: 1rem;">
            <div class="col-12">
              <div class="form-group">
                <label for="">Tipos de evento que tu paquete ofrece<span>*</span></label>
                <input type="hidden">
              </div>
            </div>

            <div class="col-12">
              <div class="checkbox-group small">
                ' . $event_types . '
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label for="step-packages-editor-' . $counter . '">Descripción<span>*</span></label>
                <textarea id="step-packages-editor-' . $counter . '" name="packageDescription-' . $counter . '" rows="5" validate>' . $package_description . '</textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12 mb-2">
              <div class="checkbox-group">
                <div>
                  <input
                    id="moreContracted-' . $counter . '"
                    name="moreContracted-' . $counter . '"
                    value="Si"
                    ' . $more_contracted_checked . '
                    type="checkbox"
                  >
                  <label for="moreContracted-' . $counter . '">Marcar como el mas contratado</label>
                </div>
              </div>
            </div>
          </div>

          <input name="packageCounter[]" value="' . $counter . '" type="hidden">
        </div>
      </div>

      <input name="packageId-' . $counter . '" value="' . $package_id . '" type="hidden">
      <input name="packageId[]" value="' . $package_id . '" type="hidden">
    </div>
  ';

  return $response;
}

function statesForSelect(
  $label = 'Todas',
  $value = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      idEstado,
      Estado,
      Latitud,
      Longitud
    FROM estados
    ORDER BY Estado
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  $response .= '<option value="">' . $label . '</option>';

  while ($row = mysqli_fetch_array($query_result)) :
    $selected = $value == $row['idEstado'] ? 'selected' : '';

    $response .= '<option 
        ' . $selected . '
        data-latitude="' . $row['Latitud'] . '"
        data-longitude="' . $row['Longitud'] . '"
        value="' . $row['idEstado'] . '"
      >
        ' . $row['Estado'] . '
      </option>
    ';
  endwhile;

  return $response;
}

function getServicesCheckbox(
  $default_value = [],
  $tag = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      idServicio,
      Servicio
    FROM servicios
    ORDER BY idServicio
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  while ($row = mysqli_fetch_array($query_result)) :
    $item_id = $row['idServicio'];
    $item    = $row['Servicio'];

    $selected = in_array($item_id, $default_value) ? 'checked' : '';

    $input_class = $tag . 'services-checkbox';
    $input_name = $tag . 'services[]';
    $input_id = $tag . 'service-' . $item_id;

    $response .= '
      <div>
        <input id="' . $input_id . '"
          class="' . $input_class . '"
          name="' . $input_name . '"
          value="' . $item_id . '"
          type="checkbox"
          ' . $selected . '
          labelError="Selecciona los servicios de tu negocio"
        >

        <label for="' . $input_id . '">' . $item . '</label>
      </div>
    ';
  endwhile;

  return $response;
}

function getAmenitiesCheckbox(
  $default_value = [],
  $tag = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      idAmenidad,
      Amenidad
    FROM amenidades
    ORDER BY idAmenidad
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  while ($row = mysqli_fetch_array($query_result)) :
    $item_id = $row['idAmenidad'];
    $item    = $row['Amenidad'];

    $selected = in_array($item_id, $default_value) ? 'checked' : '';

    $input_class = $tag . 'amenities-checkbox';
    $input_name = $tag . 'amenities[]';
    $input_id = $tag . 'amenity-' . $item_id;

    $response .= '
      <div>
        <input id="' . $input_id . '"
          class="' . $input_class . '"
          name="' . $input_name . '"
          value="' . $item_id . '"
          type="checkbox"
          ' . $selected . '
          labelError="Selecciona las amenidades de tu negocio"
        >

        <label for="' . $input_id . '">' . $item . '</label>
      </div>
    ';
  endwhile;

  return $response;
}

function citysForSelect(
  $label = 'Todas',
  $state_id = '',
  $value = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      ES.idEstadoCiudad,
      ES.idCiudad,
      ES.idEstado,
      C.Ciudad
    FROM estados_ciudades AS ES
      LEFT JOIN ciudades AS C ON (ES.idCiudad = C.idCiudad)
    WHERE ES.idEStado = $state_id
    ORDER BY C.Ciudad
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  $response .= '<option value="">' . $label . '</option>';

  while ($row = mysqli_fetch_array($query_result)) :
    $selected = $value == $row['idCiudad'] ? 'selected' : '';

    $response .= '<option ' . $selected . ' value="' . $row['idCiudad'] . '">' . $row['Ciudad'] . '</option>';
  endwhile;

  return $response;
}

function getBusinessGalleryById(
  $business_id
) {
  global $mysqli;

  $gallery = array();

  $query = "SELECT
      idGaleria,
      idSalon,
      Imagen
    FROM galeria
    WHERE idSalon = $business_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    while ($row = mysqli_fetch_array($query_result)) :
      array_push($gallery, array(
        'imageId'   => $row['idGaleria'],
        'imageSrc'  => BASE_URL_FRONTED . '/src/assets/images/listing/gallery/' . $row['Imagen'],
        'imageName' => $row['Imagen']
      ));
    endwhile;
  endif;

  return $gallery;
}

function getBusinessEventTypesArray(
  $business_id
) {
  global $mysqli;

  $event_types = array();

  $query = "SELECT
      idCatalogo,
      idSalon,
      idTipoEvento
    FROM catalogo_salon_tipos_eventos
    WHERE idSalon = $business_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows > 0) :
    while ($row = mysqli_fetch_array($query_result)) :
      array_push($event_types, intval($row['idTipoEvento']));
    endwhile;
  endif;

  return $event_types;
}

function getBusinessServicesAndAmenitiesArray(
  $business_id
) {
  global $mysqli;

  $services = array();
  $amenities = array();

  # SERVICIOS
  $query = "SELECT
      idCatalogo,
      idSalon,
      idServicio
    FROM catalogo_salon_servicios
    WHERE idSalon = $business_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows > 0) :
    while ($row = mysqli_fetch_array($query_result)) :
      array_push($services, $row['idServicio']);
    endwhile;
  endif;

  # AMENIDADES
  $query = "SELECT
      idCatalogo,
      idSalon,
      idAmenidad
    FROM catalogo_salon_amenidades
    WHERE idSalon = $business_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows > 0) :
    while ($row = mysqli_fetch_array($query_result)) :
      array_push($amenities, $row['idAmenidad']);
    endwhile;
  endif;

  return array(
    'services'  => $services,
    'amenities' => $amenities
  );
}

function getBusinessPackageEventTypesArray(
  $package_id
) {
  global $mysqli;

  $event_types = array();

  $query = "SELECT
      idCatalogo,
      idNegocio,
      idPaquete,
      idTipoEvento
    FROM catalogo_paquete_tipos_eventos
    WHERE idPaquete = $package_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows > 0) :
    while ($row = mysqli_fetch_array($query_result)) :
      array_push($event_types, $row['idTipoEvento']);
    endwhile;
  endif;

  return $event_types;
}

function getBusinessPackageData(
  $business_id
) {
  global $mysqli;

  $packages = array();

  $query = "SELECT
      idPaquete,
      idNegocio,
      Paquete,
      Descripcion,
      Orientacion,
      Precio,
      MasContratado
    FROM paquetes_negocios
    WHERE idNegocio = $business_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows > 0) :
    while ($row = mysqli_fetch_array($query_result)) :
      $package_id   = $row['idPaquete'];
      $event_types  = getBusinessPackageEventTypesArray($package_id);

      $row['TipoEventos'] = $event_types;

      array_push($packages, $row);
    endwhile;
  endif;

  return $packages;
}

function getBusinessDataById(
  $business_id
) {
  global $mysqli;
  global $_SESSION;

  if (!$business_id) return false;

  $user_id = $_SESSION['session_user_id'];

  $query = "SELECT
      S.idSalon,
      S.idUsuario,
      S.idEstado,
      S.idCiudad,
      S.idTipoProveedor,
      S.Salon,
      S.Descripcion,
      S.Capacidad,
      S.CapacidadMaxima,
      S.Latitud,
      S.Longitud,
      S.Direccion,
      S.Telefono,
      S.Celular,
      S.Facebook,
      S.Instagram,
      S.Imagen,
      S.Logo,
      U.Correo,
      U.Usuario,
      TP.eventos
    FROM salones AS S
      LEFT JOIN usuarios          AS U  ON (S.idUsuario       = U.idUsuario)
      LEFT JOIN tipo_proveedores  AS TP ON (S.idTipoProveedor = TP.idTipoProveedor)
    WHERE
      S.idSalon   = $business_id AND
      S.idUsuario = $user_id
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows == 0) return null;

  $business_data  = mysqli_fetch_array($query_result);

  $gallery            = getBusinessGalleryById($business_id);
  $packages           = getBusinessPackageData($business_id);
  $event_types        = getBusinessEventTypesArray($business_id);
  $services_amenities = getBusinessServicesAndAmenitiesArray($business_id);

  $business_data['Galeria']     = $gallery;
  $business_data['Paquetes']    = $packages;
  $business_data['TipoEventos'] = $event_types;
  $business_data['Servicios']   = $services_amenities['services'];
  $business_data['Amenidades']  = $services_amenities['amenities'];

  return $business_data;
}

function getBusinessImageName(
  $business_id
) {
  global $mysqli;

  $query = "SELECT
      Imagen
    FROM salones
    WHERE idSalon = $business_id
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $invitation_data = mysqli_fetch_array($query_result);

  return $invitation_data['Imagen'];
}

function getBusinessGalleryIds(
  $business_id
) {
  global $mysqli;

  $gallery = array();

  $query = "SELECT
      idGaleria,
      idSalon,
      Imagen
    FROM galeria
    WHERE idSalon = $business_id
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

function getBusinessPackageIds(
  $business_id
) {
  global $mysqli;

  $packages = array();

  $query = "SELECT idPaquete FROM paquetes_negocios WHERE idNegocio = $business_id";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    while ($row = mysqli_fetch_array($query_result)) :
      array_push($packages, $row['idPaquete']);
    endwhile;
  endif;

  return $packages;
}

function deleteBusinessPackages(
  $original_array = array(),
  $new_array      = array()
) {
  global $mysqli;

  $array_diff = array();

  if (!$new_array)  $array_diff = $original_array;
  if ($new_array)   $array_diff = array_diff($original_array, $new_array);

  if (!$array_diff) return;

  foreach ($array_diff as $key => $value) :
    $package_id = $value;

    $query = "DELETE FROM paquetes_negocios WHERE
      idPaquete = $package_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) :
      $query_delete_event_types = "DELETE FROM catalogo_paquete_tipos_eventos WHERE
        idPaquete = $package_id
      ";

      mysqli_query($mysqli, $query_delete_event_types);
    endif;
  endforeach;
}

function deleteBusinessImageGallery(
  $original_image_gallery = array(),
  $new_image_gallery = array()
) {
  global $mysqli;

  $array_diff = array();

  if (!$new_image_gallery)  $array_diff = $original_image_gallery;
  if ($new_image_gallery)   $array_diff = array_diff($original_image_gallery, $new_image_gallery);

  foreach ($array_diff as $key => $value) :
    $image_id = $value;

    $query = "SELECT Imagen FROM galeria WHERE idGaleria = $image_id LIMIT 1";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) :
      $image_data = mysqli_fetch_array($query_result);
      $image_name = $image_data['Imagen'];

      $file_location = BUSINESS_GALLERY_FOLDER . $image_name;

      $delete_file = deleteFile($file_location);

      if ($delete_file == 'deleted') :
        $query_delete = "DELETE FROM galeria WHERE idGaleria = $image_id";
        mysqli_query($mysqli, $query_delete);
      endif;
    endif;
  endforeach;
}

function checkArray(
  $array
) {
  if (!is_array($array)) return false;
  if (!count($array)) return false;

  return true;
}

function checkIfCanAddBusiness()
{
  $plan         = getPlan();
  $num_business = getNumBusiness();

  if ($plan === 'Básico') return true;
  if ($plan === 'Free' && $num_business == 0) return true;
  if ($plan === 'Free' && $num_business > 0) return false;
}

function getRecentEventsCount()
{
  global $mysqli;
  global $_SESSION;

  $sidebar_today_date = date('Y-m-d');
  $query_count_events = "SELECT
      COUNT(idReservacion) AS Total
    FROM reservaciones
    WHERE
      idUsuario = $_SESSION[session_user_id]      AND
      idNegocio = $_SESSION[session_business_id]  AND
      Fecha >= NOW()
    LIMIT 1
  ";

  $query_count_events_result  = mysqli_query($mysqli, $query_count_events);
  $data_count_events          = mysqli_fetch_array($query_count_events_result);

  $count_events = $data_count_events['Total'];

  return $count_events;
}

function getOldEventsCount()
{
  global $mysqli;
  global $_SESSION;

  $sidebar_today_date = date('Y-m-d');
  $query_count_events = "SELECT
      COUNT(idReservacion) AS Total
    FROM reservaciones
    WHERE
      idUsuario = $_SESSION[session_user_id]      AND
      idNegocio = $_SESSION[session_business_id]  AND
      Fecha < NOW()
    LIMIT 1
  ";

  $query_count_events_result  = mysqli_query($mysqli, $query_count_events);
  $data_count_events          = mysqli_fetch_array($query_count_events_result);

  $count_events = $data_count_events['Total'];

  return $count_events;
}

function getQuotesCount()
{
  global $mysqli;
  global $_SESSION;

  $query_count_quotes = "SELECT
      COUNT(C.idCotizacion) AS Total,
      P.idNegocio
    FROM cotizaciones AS C
      LEFT JOIN paquetes_negocios AS P ON (C.idPaquete = P.idPaquete)
    WHERE
      C.idProveedor = $_SESSION[session_user_id]      AND
      P.idNegocio   = $_SESSION[session_business_id]  AND
      Status        = 'Pendiente'
    LIMIT 1
  ";

  $query_count_quotes_result  = mysqli_query($mysqli, $query_count_quotes);
  $data_count_quotes          = mysqli_fetch_array($query_count_quotes_result);

  $count_quotes = $data_count_quotes['Total'];

  return $count_quotes;
}

function checkDateStatus()
{
  global $mysqli;
  global $_SESSION;

  $user_id = $_SESSION['session_user_id'];
  $business_id    = $_SESSION['session_business_id'];

  $query = "SELECT
      CF.idCalendarioFecha,
      CF.DateStatus,
      R.idNegocio,
      R.FechaDeAgendado
    FROM calendario_fechas AS CF
      LEFT JOIN reservaciones AS R ON (CF.Fecha = R.Fecha)
    WHERE
      CF.idUsuario  = $user_id AND
      CF.idNegocio  = $business_id AND
      CF.DateStatus = 'Con espacios'
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows > 0) :
    while ($row = mysqli_fetch_array($query_result)) :
      $business = $row['idNegocio'];

      if (!$business) :
        $query_date = "UPDATE calendario_fechas SET DateStatus = 'Libre' WHERE idCalendarioFecha = $row[idCalendarioFecha]";
        mysqli_query($mysqli, $query_date);
      endif;
    endwhile;
  endif;
}

function createDateRange(
  $start,
  $end,
  $format = 'Y-m-d'
) {
  $array = array();
  $interval = new DateInterval('P1D');

  $realEnd = new DateTime($end);
  $realEnd->add($interval);

  $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

  foreach ($period as $date) {
    $array[] = $date->format($format);
  }

  return $array;
}

function getCalendarData(
  $date = null,
  $year = null
) {
  global $mysqli;
  global $_SESSION;

  $user_id      = $_SESSION['session_user_id'];
  $business_id  = $_SESSION['session_business_id'];

  $reservations = [];
  $reminders    = [];
  $date_status  = [];

  $search_by_date = $date ? "R.Fecha = '$date'" : "1=1";
  $search_by_year = $year ? "DATE_FORMAT(R.Fecha, '%Y') = '$year'" : "";

  # Reservaciones
  $query = "SELECT
      R.idReservacion,
      R.idUsuario,
      R.idNegocio,
      R.idPaquete,
      R.idTipoEvento,
      R.NombreCompleto,
      R.Correo,
      R.Telefono,
      R.Fecha,
      DATE_FORMAT(R.HoraInicio, '%h:%i %p') AS HoraInicio,
      DATE_FORMAT(R.HoraFinal, '%h:%i %p') AS HoraFinal,
      R.NPersonas,
      R.Extras,
      R.CostoTotal,
      R.Deposito,
      R.Anticipo,
      P.Paquete,
      S.Salon,
      S.Imagen,
      S.idEstado,
      S.idCiudad,
      E.Estado,
      C.Ciudad
    FROM reservaciones AS R
      LEFT JOIN paquetes_negocios AS P ON (R.idPaquete  = P.idPaquete)
      LEFT JOIN salones           AS S ON (R.idNegocio  = S.idSalon)
      LEFT JOIN estados           AS E ON (S.idEstado   = E.idEstado)
      LEFT JOIN ciudades          AS C ON (S.idCiudad   = C.idCiudad)
    WHERE
      R.idUsuario = $user_id      AND
      R.idNegocio = $business_id  AND
      ($search_by_date)           AND
      ($search_by_year)
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    while ($row = mysqli_fetch_array($query_result)) :
      $data = $row;

      $data['CostoTotalFormat'] = number_format($row['CostoTotal'], 2);

      array_push($reservations, [
        'id'    => $row['idReservacion'],
        'date'  => $row['Fecha'],
        'data'  => $data,
        'title' => $row['NombreCompleto']
      ]);
    endwhile;
  endif;

  # REMINDERS
  $search_by_date = $date ? "
    FechaDesde = '$date' OR
    FechaHasta = '$date'
  " : "1=1";

  $search_by_year = $year ? "
    DATE_FORMAT(FechaDesde, '%Y') = '$year' OR
    DATE_FORMAT(FechaHasta, '%Y') = '$year'
  " : "1=1";

  $query = "SELECT
      idEventoCalendario,
      idusuario,
      idNegocio,
      Titulo,
      Descripcion,
      FechaDesde,
      FechaHasta,
      DATE_FORMAT(FechaDesde, '%d-%m-%Y') AS FechaDesdeFormat,
      DATE_FORMAT(FechaHasta, '%d-%m-%Y') AS FechaHastaFormat,
      Color
    FROM eventos_calendario
    WHERE
      idUsuario = $user_id      AND
      idNegocio = $business_id  AND
      ($search_by_date)         AND
      ($search_by_year)
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    while ($row = mysqli_fetch_array($query_result)) :
      $dates = createDateRange($row['FechaDesde'], $row['FechaHasta']);

      array_push($reminders, array(
        'eventCalendarId' => $row['idEventoCalendario'],
        'businessId'      => $row['idNegocio'],
        'title'           => $row['Titulo'],
        'description'     => $row['Descripcion'],
        'color'           => $row['Color'],
        'dateDesde'       => $row['FechaDesde'],
        'dateHasta'       => $row['FechaHasta'],
        'dateDesdeFormat'       => $row['FechaDesdeFormat'],
        'dateHastaFormat'       => $row['FechaHastaFormat'],
        'dates'           => $dates
      ));
    endwhile;
  endif;

  #DATE STATUS
  $search_by_date = $date ? "Fecha = '$date'" : "1=1";
  $search_by_year = $year ? "DATE_FORMAT(Fecha, '%Y') = '$year'" : "1=1";

  $query = "SELECT
      idCalendarioFecha,
      idUsuario,
      idNegocio,
      Fecha,
      DateStatus
    FROM calendario_fechas
    WHERE
      idUsuario = $user_id      AND
      idNegocio = $business_id  AND
      ($search_by_date)         AND
      ($search_by_year)
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    while ($row = mysqli_fetch_array($query_result)) :
      $status = '';

      if ($row['DateStatus'] == 'Libre')        $status = 'free';
      if ($row['DateStatus'] == 'Con espacios') $status = 'with-spaces';
      if ($row['DateStatus'] == 'Ocupado')      $status = 'occupied';

      array_push($date_status, array(
        'id'      => $row['idReservacion'],
        'date'    => $row['Fecha'],
        'status'  => $status
      ));
    endwhile;
  endif;

  # STATUS DEL CALENDARIO
  $query          = "SELECT MostrarCalendario FROM salones WHERE idSalon = $business_id LIMIT 1";
  $query_result   = mysqli_query($mysqli, $query);
  $business_data  = mysqli_fetch_array($query_result);
  $show_calendar  = $business_data['MostrarCalendario'];

  return [
    'reservations'  => $reservations,
    'reminders'     => $reminders,
    'dateStatus'    => $date_status,
    'showCalendar'  => $show_calendar
  ];
}

function getUserBusinesses(
  $user_id
) {
  global $mysqli;

  $businesses = [];

  $query = "SELECT
      idSalon,
      Salon,
      Referencia,
      slug
    FROM salones
    WHERE
      idUsuario = $user_id AND
      Status    = 'Activo'
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows > 0) :
    while ($row = mysqli_fetch_array($query_result)) :
      array_push($businesses, $row);
    endwhile;
  endif;

  return $businesses;
}

function getBusinessNameById(
  $business_id
) {
  global $mysqli;

  $query = "SELECT Salon FROM salones WHERE idSalon = $business_id LIMIT 1";
  $query_result = mysqli_query($mysqli, $query);

  $business_data = mysqli_fetch_array($query_result);
  $business_name = $business_data['Salon'];

  return $business_name;
}

function getCalendarTodayReservations()
{
  global $mysqli;
  global $_SESSION;

  $user_id      = $_SESSION['session_user_id'];
  $business_id  = $_SESSION['session_business_id'];
  $today_date   = date('Y-m-d');

  # Reservaciones
  $query = "SELECT
      R.idReservacion,
      R.NombreCompleto,
      DATE_FORMAT(R.HoraInicio, '%h:%i %p') AS HoraInicio,
      DATE_FORMAT(R.HoraFinal, '%h:%i %p') AS HoraFinal,
      S.Salon,
      TE.TipoEvento
    FROM reservaciones AS R
      LEFT JOIN salones       AS S  ON (R.idNegocio     = S.idSalon)
      LEFT JOIN tipo_eventos  AS TE ON (R.idTipoEvento  = TE.idTipoEvento)
    WHERE
      R.idUsuario = $user_id      AND
      R.idNegocio = $business_id  AND
      R.fecha     = '$today_date'
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    $reservations = '';

    while ($row = mysqli_fetch_array($query_result)) :
      $reservations .= '<li>
          * ' . $row['TipoEvento'] . ' en ' . $row['Salon'] . ' ' . $row['HoraInicio'] . ' - ' . $row['HoraFinal'] . '
        </li>
      ';
    endwhile;
  endif;

  return [
    'num_reservations'  => $num_rows,
    'reservations'      => $reservations
  ];
}

function createReferenceForBusinessSlug(
  $business_id
) {
  global $mysqli;

  $query = "SELECT
      S.idSalon,
      S.idUsuario,
      U.Usuario
    FROM salones AS S
      LEFT JOIN usuarios AS U ON (S.idUsuario = U.idUsuario)
    WHERE S.idSalon = $business_id
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows > 0) :
    $user_data        = mysqli_fetch_array($query_result);
    $full_name        = createSlug($user_data['Usuario']);
    $full_name        = explode('-', $full_name);
    $user_name        = $full_name[0];
    $user_id          = $user_data['idUsuario'];

    $first_character  = $user_name[0];
    $last_character   = $user_name[strlen($user_name) - 1];

    $reference        = $first_character . $last_character . $user_id[0];

    return $reference;
  endif;
}

function checkIfBusinessIsForUser(
  $business_id
) {
  global $mysqli;
  global $_SESSION;

  $user_id = $_SESSION['session_user_id'];

  $query = "SELECT idSalon FROM salones WHERE
      idSalon   = $business_id AND
      idUsuario = $user_id
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows == 0) return false;
  if ($num_rows > 0) return true;
}

function decryptUserCookie(
  $cookie
) {
  $decrypt_cookie   = decrypt($cookie, MYSQLI_PASSWORD_SECRET);
  $user_id          = '';
  $reference_length = 16;
  $chars            = str_split($decrypt_cookie);

  foreach ($chars as $key => $value) :
    if (($key + 1) > $reference_length) :
      $user_id .= $value;
    endif;
  endforeach;

  return $user_id;
}

function verifyUserSession()
{
  global $mysqli;
  global $_SESSION;
  global $_COOKIE;

  $user_id      = $_SESSION['session_user_id'];
  $cookie_name  = 'MLSESSCOOID';
  $cookie       = $_COOKIE[$cookie_name];

  if ($user_id && !$cookie) :
    $query = "SELECT
        Usuario
      FROM usuarios
      WHERE idUsuario = $user_id
      LIMIT 1
    ";

    $query_result     = mysqli_query($mysqli, $query);
    $user_data        = mysqli_fetch_array($query_result);
    $full_name        = createSlug($user_data['Usuario']);
    $full_name        = explode('-', $full_name);
    $user_name        = $full_name[0];

    $first_character  = $user_name[0];
    $last_character   = $user_name[strlen($user_name) - 1];
    $today_date       = date('YmdHis');

    $reference        = $first_character . $last_character . $today_date . $user_id;
    $reference        = encrypt($reference, MYSQLI_PASSWORD_SECRET);

    setcookie($cookie_name, $reference, time() + (60 * 60 * 24 * 365));
  endif;

  if (!$user_id && $cookie) :
    $user_id    = decryptUserCookie($cookie);
    $user_data  = getUserData($user_id);

    if ($user_data['Nivel'] === 'Usuario Final') :
      $_SESSION['session_user_id']      = $user_id;
      $_SESSION['session_user_name']    = $user_data['Usuario'];
      $_SESSION['session_user_level']   = $user_data['Nivel'];
      $_SESSION['session_user_email']   = $user_data['Correo'];
      $_SESSION['session_user_status']  = $user_data['Status'];
    endif;

    if ($user_data['Nivel'] === 'Usuario') :
      $id_negocio   = $user_data['idNegocio'];
      $plan         = $user_data['Plan'];
      $user_level   = $user_data['Nivel'];
      $user_name    = $user_data['Usuario'];
      $pertenece_a  = $user_data['PerteneceA'];

      $session_id = ($pertenece_a != NUll && $pertenece_a != '') ? $pertenece_a : $user_id;
      $is_admin_supplier = !$pertenece_a ? 'Si' : 'No';

      $_SESSION['session_user_id']                = $session_id;
      $_SESSION['session_user_name']              = $user_name;
      $_SESSION['session_user_level']             = $user_level;
      $_SESSION['session_user_is_admin_supplier'] = $is_admin_supplier;
      $_SESSION['Plan']                           = $plan;

      if ($pertenece_a) $_SESSION['session_user_parent']      = $pertenece_a;
      if ($pertenece_a) $_SESSION['session_user_children_id'] = $user_id;
      if ($pertenece_a) $_SESSION['session_business_id']      = $id_negocio;

      if (!$id_negocio) :
        $query = "SELECT idSalon from salones WHERE idUsuario = $session_id AND Status = 'Activo' LIMIT 1";
        $query_result = mysqli_query($mysqli, $query);
        $data_negocio = mysqli_fetch_array($query_result);

        $id_negocio = $data_negocio['idSalon'];
        $_SESSION['session_business_id'] = $id_negocio;
      endif;
    endif;
  endif;
}

function getUserData(
  $user_id
) {
  global $mysqli;

  $query = "SELECT
      idUsuario,
      idNegocio,
      Plan,
      Usuario,
      Username,
      Password,
      Correo,
      Nivel,
      Status,
      PerteneceA
    FROM usuarios
    WHERE idUsuario = $user_id
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $user_data    = mysqli_fetch_array($query_result);

  return $user_data;
}

function getReservationData(
  $reservation_id
) {
  global $mysqli;
  $reservation_data = [];

  $query = "SELECT
      idReservacion,
      idUsuario,
      idNegocio,
      idPaquete,
      idTipoEvento,
      NombreCompleto,
      Correo,
      Telefono,
      Fecha,
      HoraInicio,
      HoraFinal,
      NPersonas,
      Extras,
      CostoTotal,
      Deposito,
      Anticipo,
      FechaDeAgendado
    FROM reservaciones
    WHERE idReservacion = $reservation_id
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows > 0) $reservation_data = mysqli_fetch_array($query_result);

  return $reservation_data;
}

function checkIfCalendarDateIsEmpty(
  $date,
  $user_id,
  $business_id
) {
  global $mysqli;

  $query = "SELECT idReservacion FROM reservaciones WHERE
      idNegocio = $business_id  AND
      idUsuario = $user_id      AND
      Fecha     = '$date'
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows == 0) :
    $query = "UPDATE calendario_fechas SET
        DateStatus = 'Libre'
      WHERE
        idUsuario = $user_id      AND
        idNegocio = $business_id  AND
        Fecha     = '$date'
    ";

    mysqli_query($mysqli, $query);
  endif;
}

function userCan(
    string $module,
    string $action = 'view'
): bool {

    global $mysqli;

    if (
        !isset($_SESSION['session_user_plan'])
    ) {
        return false;
    }

    // SUPER ADMIN
    if (
        isset($_SESSION['session_user_is_admin_supplier']) &&
        $_SESSION['session_user_is_admin_supplier'] === 'Si'
    ) {
        //return true;
    }

    $plan = $_SESSION['session_user_plan'];

    $stmt = $mysqli->prepare("
        SELECT is_allowed
        FROM ml_plan_permissions
        WHERE
            plan_name = ?
            AND module_slug = ?
            AND action_name = ?
        LIMIT 1
    ");

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param(
        'sss',
        $plan,
        $module,
        $action
    );

    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result || !$result->num_rows) {
        return false;
    }

    $permission = $result->fetch_assoc();

    return (bool) $permission['is_allowed'];
}

function requirePermission(
    string $module,
    string $action = 'view'
): void {

    if (!userCan($module, $action)) {

        http_response_code(403);

        include __DIR__ . '/upgrade-required.php';

        exit;
    }
}

function getAppUrl(): string
{
    return rtrim(
        getSetting(
            'app_url',
            'https://manteleslargos.com'
        ),
        '/'
    );
}


//Obtener plan del negocio/// NO LA USAREMOS POR AHORA
function getBusinessPlan(
    int $idSalon
): string {

    global $mysqli;

    $sql = "
        SELECT
            p.Plan
        FROM ml_business_subscriptions s

        INNER JOIN ml_planes p
            ON p.idPlan = s.id_plan

        WHERE
            s.id_salon = ?
            AND s.status = 'active'

        ORDER BY s.id_subscription DESC

        LIMIT 1
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return 'Básico';
    }

    $stmt->bind_param(
        'i',
        $idSalon
    );

    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result->num_rows) {
        return 'Básico';
    }

    $row = $result->fetch_assoc();

    return $row['Plan'];
}