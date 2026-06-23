<?php
function getWebSiteProtocol()
{
  global $_SERVER;

  $protocol = 'http://';

  if (
    isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
  ) {
    $protocol = 'https://';
  } else {
    $protocol = 'http://';
  }

  return $protocol;
}

function getServerName(
  $subdomain = ''
) {
  global $_SERVER;

  $server_name = $_SERVER['SERVER_NAME'] . $subdomain;
  return $server_name;
}

function getBasePath(
  $subdomain = ''
) {
  global $_SERVER;

  $base_path = $_SERVER['DOCUMENT_ROOT'] . $subdomain;
  return $base_path;
}


function encrypt(
  $string,
  $key
) {
  $result = '';
  for ($i = 0; $i < strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key)) - 1, 1);
    $char = chr(ord($char) + ord($keychar));
    $result .= $char;
  }
  return base64_encode($result);
}

function decrypt(
  $string,
  $key
) {
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

  while ($count < $bad_string_size) { // $count <= $bad_string_size
    $str = str_replace($bad_string[$count], '/', $str);
    $count++;
  }

  $str = str_replace("`", "", $str);
  $str = str_replace("'", "", $str);
  $str = str_replace('"', "", $str);
  $str = str_replace('¨', "", $str);

  $str = trim($str);

  ///$str = mysqli_real_escape_string($mysqli, $str);

  return $str;
}

function formatPhoneNumber(
  $phone_number
) {
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

function filterAndCleanArray(
  $items
) {
  if (!$items) return '';

  $is_array = is_array($items);
  if (!$is_array) return '';

  $items_count = count($items);
  if (!$items_count) return '';

  $items_in = "'";

  foreach ($items as $key => $value) {
    $item = cleanStr($items[$key]);

    $concat = $key == ($items_count - 1) ? "'" : "','";

    $items_in .= $item . $concat;
  }

  return $items_in;
}

function parseDate(
  $date
) {
  $initial_date = cleanStr($date);

  if ($initial_date == '') return '';

  $date_replace = str_replace('/', '-', $initial_date);
  $new_date     = date('Y-m-d', strtotime($date_replace));

  return $new_date;
}

function limitStr(
  $str,
  $limit = 39
) {
  $str_lenght = strlen($str);
  $new_str    = substr($str, 0, $limit);

  if ($str_lenght <= $limit) return $str;
  if ($str_lenght > $limit) return $new_str . '...';
}

function addAuthenticationLogHistory(
  $email,
  $message
) {
  global $mysqli;
  global $_POST;
  global $_GET;

  $query = "INSERT INTO usuarios_history (
      Usuario,
      mGET,
      mPOST,
      Comentario,
      LastUpdate
    ) VALUES (
      '" . $email . "', 
      '" . serialize($_GET) . "', 
      '" . serialize($_POST) . "', 
      '$message',
      NOW()
    )
  ";

  mysqli_query($mysqli, $query);
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

function processMultipleFiles(
  $array,
  $extensions,
  $folder,
  $name = 'image'
) {
  $files_uploaded = array();

  // VALIDAR QUE EXISTA Y SEA ARRAY
  if (
    !isset($array['tmp_name']) ||
    !is_array($array['tmp_name'])
  ) {
    return $files_uploaded;
  }

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

function generatePassword($length = 8)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ=?_-';
  $charactersLength = strlen($characters);
  $randomString = '';

  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }

  return $randomString;
}

function getDateTimeWithStrFormat(
  $date
) {
  $day      = date('d', strtotime($date));
  $month    = date('m', strtotime($date));
  $year     = date('Y', strtotime($date));
  $time     = date('h:i a', strtotime($date));

  $date_obj   = DateTime::createFromFormat('!m', $month);
  $month_name = strftime('%B', $date_obj->getTimestamp());

  $new_date = $day . ' de ' . $month_name . ' del ' . $year . ' a las ' . $time;

  return $new_date;
}

function getDateWithMonthName(
  $date,
  $mark = '/'
) {
  $day    = date('d', strtotime($date));
  $month  = date('m', strtotime($date));
  $year   = date('Y', strtotime($date));

  $date_obj   = DateTime::createFromFormat('!m', $month);
  $month_name = strftime('%B', $date_obj->getTimestamp());

  //$new_date = $day . $mark . $month_name . $mark . $year;
  $new_date = $day . ' de ' . $month_name . ' del ' . $year;

  return $new_date;
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

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- NOTIFICATIONS
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function sendDeviceNotification(
  $token,
  $title,
  $description
) {
  $notificacion_data  = array("action" => "quotes");
  $data_encode        = json_encode($notificacion_data);

  $notification = array(
    'to'    => $token,
    'sound' => 'default',
    'title' => $title,
    'body'  => $description,
    'data'  => $data_encode,
    'chanelID' => 'rabbit-notifications'
  );

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL             => "https://exp.host/--/api/v2/push/send",
    CURLOPT_RETURNTRANSFER  => true,
    CURLOPT_ENCODING        => "",
    CURLOPT_MAXREDIRS       => 10,
    CURLOPT_TIMEOUT         => 30,
    CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST   => "POST",
    CURLOPT_POSTFIELDS      => json_encode($notification),
    CURLOPT_HTTPHEADER      => array(
      "Accept: application/json",
      "Accept-Encoding: gzip, deflate",
      "Content-Type: application/json",
      "cache-control: no-cache",
      "host: exp.host"
    ),
  ));

  $response = curl_exec($curl);
  $err      = curl_error($curl);

  if ($err) return false;
  if (!$err) return true;
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- ACCESS TO SUPPLIER
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function checkSupplierAccessStatus()
{
  global $_SESSION;
  global $mysqli;

  $status = 'unlogged';

  $supplier_id = $_SESSION['session_user_id'];

  $query = "SELECT
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
  $collaborator_business_id = $user_data['idNegocio'];

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
    $num_business = getNumBusiness($supplier_id);

    if (!$num_business) $status = 'no-business';

    if ($num_business > 0) {
      $status       = 'logged';
      $business_id  = getFirstBusinessId($supplier_id);

      if ($is_collaborator) $business_id = $user_data['idNegocio'];
    }
  }

  if ($is_collaborator) :
    $status = 'logged';
    $business_id = $collaborator_business_id;
  endif;

  return array(
    'status'          => $status,
    'businessId'      => $business_id,
    'isCollaborator'  => $is_collaborator,
    'collaboratorId'  => $collaborator_id
  );
}

function closeSession()
{
  global $_SESSION;

  $_SESSION = array();

  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
      session_name(),
      '',
      time() - 42000,
      $params["path"],
      $params["domain"],
      $params["secure"],
      $params["httponly"]
    );
  }

  session_destroy();

  return true;
}

function checkArray(
  $array
) {
  if (!is_array($array)) return false;
  if (!count($array)) return false;

  return $array;
}

function acceptWebPageCookies()
{
  $time_to_remove = time() + (60 * 60 * 24 * 365);
  setcookie('webpagecookies', '1', $time_to_remove);
}

function createCookieForSession()
{
  $time_to_remove = time() + (60 * 60 * 24 * 365);
  setcookie('MLSESSCOOKIE', 'hello', $time_to_remove);
}
