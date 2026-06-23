<?php
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

function cleanStr(
  $str,
  $priority = 'high'
) {
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

  $str = trim($str);

  $str = str_replace("'", '`', $str);
  $str = str_replace('"', '`', $str);

  return $str;
}

function getDateWithMonthName($date, $mark = '-')
{
  $day    = date('d', strtotime($date));
  $month  = date('m', strtotime($date));
  $year   = date('Y', strtotime($date));

  $date_obj   = DateTime::createFromFormat('!m', $month);
  $month_name = strftime('%B', $date_obj->getTimestamp());

  $new_date = $day . $mark . $month_name . $mark . $year;
  //$new_date = $day . ' de ' . $month_name . ' del ' . $year;

  return $new_date;
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

function numPages($query, $stop_rows)
{
  global $mysqli;

  $query_result = mysqli_query($mysqli, $query);
  $row = mysqli_fetch_array($query_result);
  $num_pages = ceil($row['Total'] / $stop_rows);

  return $num_pages;
}

function generateVerificationCode($digits = 4, $mark = 'ML-')
{
  $i    = 0;
  $pin  = "";

  while ($i < $digits) {
    $pin .= mt_rand(0, 9);
    $i++;
  }

  return $mark . $pin;
}

function getNumBusiness(
  $user_id
) {
  global $mysqli;

  $query        = "SELECT COUNT(idSalon) AS Total FROM salones WHERE
      idUsuario = $user_id AND
      Status    = 'Activo'
    LIMIT 1
  ";
  $query_result = mysqli_query($mysqli, $query);

  $business_data  = mysqli_fetch_array($query_result);
  $num_business   = $business_data['Total'];

  return $num_business;
}

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

function getDatesFromRange($start, $end, $format = 'Y-m-d')
{
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

function generateReminderFirstDate(
  $quantity,
  $periodicity,
  $date
) {
  $new_date = null;

  if ($periodicity === 'Dia')     $new_date = date("Y-m-d", strtotime($date . "- $quantity day"));
  if ($periodicity === 'Semanal') $new_date = date("Y-m-d", strtotime($date . "- $quantity week"));
  if ($periodicity === 'Mensual') $new_date = date("Y-m-d", strtotime($date . "- $quantity month"));
  if ($periodicity === 'Anual')   $new_date = date("Y-m-d", strtotime($date . "- $quantity year"));

  return $new_date;
}

function queryCount(
  $item_id,
  $from,
  $where
) {
  global $mysqli;

  $query = "SELECT
      COUNT($item_id) AS Total
    FROM $from
    WHERE $where
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $item_data    = mysqli_fetch_array($query_result);
  $total        = $item_data['Total'];

  return intval($total);
}

function querySum(
  $item_id,
  $from,
  $where
) {
  global $mysqli;

  $query = "SELECT
      SUM($item_id) AS Total
    FROM $from
    WHERE $where
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $item_data    = mysqli_fetch_array($query_result);
  $total        = $item_data['Total'];

  return $total;
}

function addPaymentReminders(
  $user_id,
  $reservation_id,
  $payment_reminders
) {
  global $mysqli;

  foreach ($payment_reminders as $key => $value) :
    $payment_reminder = $value;

    $rc_date          = cleanStr($payment_reminder['date']);
    $rc_percentage    = cleanStr($payment_reminder['percentage']);

    $query = "INSERT INTO recordatorio_pagos (
        idUsuario,
        idReservacion,
        Porcentaje,
        Fecha
      ) VALUES (
        $user_id,
        $reservation_id,
        '$rc_percentage',
        '$rc_date'
      )
    ";

    mysqli_query($mysqli, $query);
  endforeach;

  return true;
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

function addDateAdvance(
  $user_id,
  $reservation_id,
  $advance
) {
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

function getEventReminders(
  $user_id,
  $event_calendar_id
) {
  global $mysqli;

  $event_reminders = array();

  $query = "SELECT
      idEventoCalendarioRecordatorio,
      idEventoCalendario,
      idUsuario,
      Cantidad,
      Periodicidad
    FROM eventos_calendario_recordatorios
    WHERE
      idUsuario = $user_id AND
      idEventoCalendario = $event_calendar_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) {
    while ($row = mysqli_fetch_array($query_result)) {
      array_push($event_reminders, array(
        'quantity'    => $row['Cantidad'],
        'periodicity' => $row['Periodicidad']
      ));
    }
  }

  return $event_reminders;
}

function getPaymentReminders(
  $user_id,
  $reservation_id
) {
  global $mysqli;

  $payment_reminders = array();

  $query = "SELECT
      Porcentaje,
      Fecha
    FROM recordatorio_pagos
    WHERE
      idUsuario     = $user_id AND
      idReservacion = $reservation_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) {
    while ($row = mysqli_fetch_array($query_result)) {
      array_push($payment_reminders, array(
        'percentage'  => $row['Porcentaje'],
        'date'        => $row['Fecha']
      ));
    }
  }

  return $payment_reminders;
}


function getDayStatus(
  $user_id,
  $business_id,
  $date
) {
  global $mysqli;

  $day_status = '';

  $query = "SELECT
      DateStatus
    FROM calendario_fechas
    WHERE
      idUsuario = $user_id AND
      idNegocio = $business_id AND
      Fecha     = '$date'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);

  if ($query_result) {
    $date_data    = mysqli_fetch_array($query_result);
    $day_status   = $date_data['DateStatus'];
  }

  return $day_status;
}

function getQuoteFolio($business_id, $mark = 'M-')
{
  global $mysqli;
  $today_year = date('Y');

  $query_get_code = "SELECT
      Folio
    FROM cotizaciones
    WHERE
      YEAR(FechaCreacion) = $today_year AND
      idNegocio = $business_id
    ORDER BY Folio
    DESC
    LIMIT 1
  ";

  $query_get_code_result = mysqli_query($mysqli, $query_get_code);
  $query_get_code_num_rows = mysqli_num_rows($query_get_code_result);

  if (!$query_get_code_num_rows) return $mark . '0001/' . $today_year;

  if ($query_get_code_num_rows) {
    $quote_data = mysqli_fetch_array($query_get_code_result);
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

function createSlug(
  $str,
  $max = 100
) {
  $out = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
  $out = substr(preg_replace('/[^-\/+|\w ]/', '', $out), 0, $max);
  $out = strtolower(trim($out, '-'));
  $out = preg_replace('/[\/_| -]+/', '-', $out);
  $out = str_replace('+', 'mas', $out);

  return $out;
}

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

function deleteFile(
  $file_location
) {
  $file_exist = file_exists($file_location);

  if (!$file_exist) {
    return 'not-exist';
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

function getBusinessEventTypes(
  $business_id
) {
  global $mysqli;

  $event_types = array();

  $query = "SELECT
      idTipoEvento
    FROM catalogo_salon_tipos_eventos
    WHERE idSalon = $business_id
  ";

  $query_result = mysqli_query($mysqli, $query);

  while ($event_type = mysqli_fetch_array($query_result)) :
    array_push($event_types, $event_type['idTipoEvento']);
  endwhile;

  return $event_types;
}

function getBusinesspackages(
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

  while ($package = mysqli_fetch_array($query_result)) :
    $package_id   = $package['idPaquete'];
    $event_types  = array();

    $query_event_types = "SELECT
        idTipoEvento
      FROM catalogo_paquete_tipos_eventos
      WHERE
        idPaquete = $package_id AND
        idNegocio = $business_id
    ";

    $query_event_types_result = mysqli_query($mysqli, $query_event_types);
    $num_event_types = mysqli_num_rows($query_event_types_result);

    if ($num_event_types) :
      while ($event_type = mysqli_fetch_array($query_event_types_result)) :
        array_push($event_types, $event_type['idTipoEvento']);
      endwhile;
    endif;

    array_push($packages, array(
      'packageId'       => $package['idPaquete'],
      'packageName'     => $package['Paquete'],
      'price'           => $package['Precio'],
      'modality'        => $package['Orientacion'],
      'eventTypes'      => $event_types,
      'description'     => $package['Descripcion'],
      'mostRecommended' => $package['MasContratado']
    ));
  endwhile;

  return $packages;
}

function getBusinessServices(
  $business_id
) {
  global $mysqli;

  $services = array();

  $query = "SELECT
      idServicio
    FROM catalogo_salon_servicios
    WHERE idSalon = $business_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows = mysqli_num_rows($query_result);

  if (!$num_rows) $services;

  while ($service = mysqli_fetch_array($query_result)) :
    array_push($services, $service['idServicio']);
  endwhile;

  return $services;
}

function getBusinessAmenities(
  $business_id
) {
  global $mysqli;

  $amenities = array();

  $query = "SELECT
      idAmenidad
    FROM catalogo_salon_amenidades
    WHERE idSalon = $business_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows = mysqli_num_rows($query_result);

  if (!$num_rows) $amenities;

  while ($amenity = mysqli_fetch_array($query_result)) :
    array_push($amenities, $amenity['idAmenidad']);
  endwhile;

  return $amenities;
}

function getBusinessGallery(
  $business_id
) {
  global $mysqli;

  $gallery = array();

  $query = "SELECT
      idGaleria,
      Imagen
    FROM galeria
    WHERE idSalon = $business_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows = mysqli_num_rows($query_result);

  if (!$num_rows) $gallery;

  while ($row = mysqli_fetch_array($query_result)) :
    $image_url = IMAGES_URL . 'listing/gallery/' . $row['Imagen'];

    $image = array(
      'imageId' => $row['idGaleria'],
      'uri'     => $image_url
    );

    array_push($gallery, $image);
  endwhile;

  return $gallery;
}

function getUserPermissions(
  $collaborator_id
) {
  global $mysqli;

  $query = "SELECT
      PUP.idPaginaUsuarioPermiso,
      PUP.idPagina,
      PUP.idUsuario,
      P.Slug
    FROM ml_paginas_usuarios_permisos AS PUP
      LEFT JOIN ml_paginas AS P ON (PUP.idPagina = P.idPagina)
    WHERE PUP.idUsuario = $collaborator_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return array();

  $permissions = array();

  while ($row = mysqli_fetch_array($query_result)) :
    array_push($permissions, $row['Slug']);
  endwhile;

  return $permissions;
}

function findToken(
  $user_id,
  $token
) {
  global $mysqli;

  $query = "SELECT idToken FROM app_tokens WHERE
      idUsuario = $user_id AND
      Token     = '$token'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) return true;
  if (!$num_rows) return false;
}

function checkDateStatus(
  $user_id,
  $business_id
) {
  global $mysqli;

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
