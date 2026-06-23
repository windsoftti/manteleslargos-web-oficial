<?php
function cleanStr(
  $str,
  $priority = 'high'
) {
  if ($str == 'null' || $str == null) return '';

  if ($priority === 'high') {
    $bad_string = array('select', 'drop', ';', '--', 'insert', 'delete', 'xp_', '%20union%20', '/', '/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<script', '<iframe', '<applet', '<meta', '<style', '<form', '<img', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'http://', 'https://', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=', '<', '>', 'href=');
  }

  if ($priority === 'medium') {
    $bad_string = array('select', 'drop', 'insert', 'delete', 'xp_', '%20union%20', '/', '/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<script', '<iframe', '<applet', '<meta', '<style', '<form', '<img', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'http://', 'https://', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=');
  }

  if ($priority === 'low') {
    $bad_string = array('<script', '<iframe', '<applet', '<', '>', 'href=', 'select', 'drop', 'insert', 'delete', 'script>');
  }

  if ($priority === 'html') {
    $bad_string = array('<script', '<iframe', '<applet', 'select', 'drop', 'insert', 'delete', 'script>');
  }

  $bad_string_size  = count($bad_string);
  $count            = 0;

  while ($count <= $bad_string_size) {
    $str = str_replace($bad_string[$count], '/', $str);
    $count++;
  }

  $str = str_replace("'", "`", $str);
  $str = str_replace('"', "`", $str);

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

function numPages(
  $query,
  $stop_rows
) {
  global $mysqli;

  $query_result = mysqli_query($mysqli, $query);
  $row = mysqli_fetch_array($query_result);
  $num_pages = ceil($row['Total'] / $stop_rows);

  return $num_pages;
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

function timeStamp(
  $start_date,
  $end_date = null
) {
  $date1 = new DateTime($start_date);
  $date2 = $end_date ? new DateTime($end_date) : new DateTime(date('Y-m-d H:i:s'));

  $date = $date1->diff($date2);

  $time = '';

  # Years
  if ($date->y > 0) {
    $time .= $date->y;

    if ($date->y == 1) $time .= ' año, ';
    if ($date->y > 1) $time .= ' años, ';
  }

  # Months
  if ($date->m > 0) {
    $time .= $date->m;

    if ($date->m == 1) $time .= ' mes, ';
    if ($date->m > 1) $time .= ' meses, ';
  }

  # Days
  if ($date->d > 0) {
    $time .= $date->d;

    if ($date->d == 1) $time .= ' dia';
    if ($date->d > 1) $time .= ' dias';

    return $time;
  }

  # Hours
  if ($date->h > 0) {
    $time .= $date->h;

    if ($date->h == 1) $time .= ' hora, ';
    if ($date->h > 1) $time .= ' horas, ';
  }

  # Minutes
  if ($date->i > 0) {
    $time .= $date->i;

    if ($date->i == 1) $time .= ' minuto';
    if ($date->i > 1) $time .= ' minutos';

    return $time;
  }

  # Seconds
  if ($date->s > 0) {
    $time .= $date->s;

    if ($date->s == 1) $time .= ' segundo';
    if ($date->s > 1) $time .= ' segundos';
  }

  return $time;
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

function isEmptyArray(
  $array = []
) {
  $array = (array)$array;

  $is_array = is_array($array);

  if (!$is_array) return true;

  $count = count($array);

  if ($count === 0) return true;

  return false;
}

function useGetFieldPriority(
  $value_to_find,
  $array
) {
  $is_empty = isEmptyArray($array);

  if ($is_empty) return false;

  foreach ($array as $field_name => $priority) :
    if ($field_name === $value_to_find) return $priority;
  endforeach;

  return false;
}

function useInsertByPost(
  $new_config = [
    'table_name'      => "",
    'extra_fields'    => [],
    'excluded_fields' => ['action', 'place', 'uid'],
    'clean_priority'  => []
  ]
) {
  global $mysqli;
  global $_POST;

  if (!$_POST) return false;

  $config = [
    'table_name'      => "",
    'excluded_fields' => ['action', 'place', 'uid'],
    'extra_fields'    => [],
    'clean_priority'  => []
  ];

  if ($new_config['table_name'])                      $config['table_name']       = $new_config['table_name'];
  if (!isEmptyArray($new_config['excluded_fields']))  $config['excluded_fields']  = array_merge($config['excluded_fields'], $new_config['excluded_fields']);
  if (!isEmptyArray($new_config['extra_fields']))     $config['extra_fields']     = $new_config['extra_fields'];
  if (!isEmptyArray($new_config['clean_priority']))   $config['clean_priority']   = $new_config['clean_priority'];

  # PREPARE POST VALUES
  $values = [];

  foreach ($_POST as $field_name => $value) :
    if (!in_array($field_name, $config['excluded_fields'])) :
      $clean_priority     = 'high';
      $new_clean_priority = useGetFieldPriority($field_name, $config['clean_priority']);

      if ($new_clean_priority) $clean_priority = $new_clean_priority;

      array_push($values, [
        'field_name' => $field_name,
        'value'      => cleanStr($value, $clean_priority)
      ]);
    endif;
  endforeach;

  # ADD EXTRA FIELDS TO VALUES V2
  foreach ($config['extra_fields'] as $field_name => $value) :
    $clean_priority     = 'high';
    $new_clean_priority = useGetFieldPriority($field_name, $config['clean_priority']);

    if ($new_clean_priority) $clean_priority = $new_clean_priority;

    array_push($values, [
      'field_name' => $field_name,
      'value'      => cleanStr($value, $clean_priority)
    ]);
  endforeach;

  # BUILD QUERY
  $counter      = 1;
  $total_fields = count($values);

  $field_names  = "";
  $field_values = "";

  foreach ($values as $key => $fields) :
    $separator = $counter === $total_fields ? "" : ",";
    $field_value = mysqli_real_escape_string($mysqli, $fields['value']);

    $field_names  .= "$fields[field_name]"  . $separator;
    $field_values .= "'$field_value'"       . $separator;

    $counter++;
  endforeach;

  $query = "INSERT INTO $config[table_name] ($field_names) VALUES ($field_values)";

  $query_result = mysqli_query($mysqli, $query);

  if ($query_result)  return true;
  if (!$query_result) return false;
};

function useUpdateByPost(
  $new_config = [
    'table_name'      => "",
    'extra_fields'    => [],
    'excluded_fields' => ['action', 'place', 'uid'],
    'clean_priority'  => [],
    "conditions"      => ""
  ]
) {
  global $mysqli;
  global $_POST;

  if (!$_POST) return false;

  $config = [
    'table_name'      => "",
    'excluded_fields' => ['action', 'place', 'uid'],
    'extra_fields'    => [],
    'clean_priority'  => [],
    'conditions'      => ""
  ];

  if ($new_config['table_name'])                      $config['table_name']       = $new_config['table_name'];
  if (!isEmptyArray($new_config['excluded_fields']))  $config['excluded_fields']  = array_merge($config['excluded_fields'], $new_config['excluded_fields']);
  if (!isEmptyArray($new_config['extra_fields']))     $config['extra_fields']     = $new_config['extra_fields'];
  if (!isEmptyArray($new_config['clean_priority']))   $config['clean_priority']   = $new_config['clean_priority'];
  if ($new_config['conditions'])                      $config['conditions']       = $new_config['conditions'];

  # PREPARE POST VALUES
  $values = [];

  foreach ($_POST as $field_name => $value) :
    if (!in_array($field_name, $config['excluded_fields'])) :
      $clean_priority     = 'high';
      $new_clean_priority = useGetFieldPriority($field_name, $config['clean_priority']);

      if ($new_clean_priority) $clean_priority = $new_clean_priority;

      array_push($values, [
        'field_name' => $field_name,
        'value'      => cleanStr($value, $clean_priority)
      ]);
    endif;
  endforeach;

  # ADD EXTRA FIELDS TO VALUES
  foreach ($config['extra_fields'] as $field_name => $value) :
    $clean_priority     = 'high';
    $new_clean_priority = useGetFieldPriority($field_name, $config['clean_priority']);

    if ($new_clean_priority) $clean_priority = $new_clean_priority;

    array_push($values, [
      'field_name' => $field_name,
      'value'      => cleanStr($value, $clean_priority)
    ]);
  endforeach;

  # BUILD QUERY
  $counter      = 1;
  $total_fields = count($values);

  $fields = "";

  foreach ($values as $key => $field) :
    $separator    = $counter === $total_fields ? "" : ", ";
    $field_value  = mysqli_real_escape_string($mysqli, $field['value']);

    $fields .= "$field[field_name] = '$field_value'" . $separator;

    $counter++;
  endforeach;

  # CONDITIONS
  # WHERE
  $c_where        = "WHERE ";
  $total_clauses  = count($config['conditions']);

  if (count($config['conditions']) > 0) :
    foreach ($config['conditions'] as $key => $value) :
      $field_name   = $value[0];
      $field_value  = $value[1];
      $field_rule   = $value[2] ? $value[2] : "=";
      $field_concat = $value[3] ? $value[3] : "AND";
      $concat       = (($total_clauses > 1) && ($key + 1 <= $total_clauses) && ($key != 0)) ? $field_concat : "";

      if ($field_rule === "=")        $c_where .= $concat . " ($field_name =        '" . mysqli_real_escape_string($mysqli, $field_value) . "'" . ") ";
      if ($field_rule === "!=")       $c_where .= $concat . " ($field_name !=       '" . mysqli_real_escape_string($mysqli, $field_value) . "'" . ") ";
      if ($field_rule === 'LIKE')     $c_where .= $concat . " ($field_name LIKE     '" . mysqli_real_escape_string($mysqli, $field_value) . "'" . ") ";
      if ($field_rule === "IN")       $c_where .= $concat . " ($field_name IN       (" . mysqli_real_escape_string($mysqli, $field_value) . ")" . ") ";
      if ($field_rule === 'BETWEEN')  $c_where .= $concat . " ($field_name BETWEEN  '" . mysqli_real_escape_string($mysqli, $field_value[0]) . "' AND '" . mysqli_real_escape_string($mysqli, $field_value[1]) . "'" . ") ";
    endforeach;
  endif;

  $query = "UPDATE $config[table_name] SET $fields $c_where";

  $query_result = mysqli_query($mysqli, $query);

  if ($query_result)  return true;
  if (!$query_result) return false;
};

function getEmptyTableMessage(
  $message = '¡No hay registros disponibles!'
) {
  return '
    <div class="col-xs-12 text-center" style="margin: 2rem;">
      ' . $message . '
    </div>
  ';
}

function useDataTable(
  $new_config = [
    'column_id' => "",
    'from'      => "",
    'where'     => [],
    'fields'    => [],
    'join'      => "",
    'order'     => "",
    'per_page'  => 15,
    'page'      => 1
  ]
) {
  global $mysqli;

  $config = [
    'column_id' => "",
    'from'      => "",
    'where'     => [],
    'fields'    => [],
    'join'      => "",
    'order'     => "",
    'per_page'  => 15,
    'page'      => 1
  ];

  if ($new_config['column_id'])   $config['column_id']  = $new_config['column_id'];
  if ($new_config['from'])        $config['from']       = $new_config['from'];
  if ($new_config['where'])       $config['where']      = $new_config['where'];
  if ($new_config['fields'])      $config['fields']     = $new_config['fields'];
  if ($new_config['join'])        $config['join']       = $new_config['join'];
  if ($new_config['order'])       $config['order']      = $new_config['order'];
  if ($new_config['per_page'])    $config['per_page']   = $new_config['per_page'];
  if ($new_config['page'])        $config['page']       = $new_config['page'];

  $column_id    = $config['column_id'];
  $per_page     = $config['per_page'];
  $page         = $config['page'];

  $fields       = $config['fields'];
  $where        = $config['where'];

  $c_from       = "FROM " . $config['from'];
  $c_join       = $config['join'];
  $c_order      = $config['order'];

  # WHERE
  $c_where        = "";
  $total_clauses  = count($where);

  if (count($where) > 0) :
    $c_where = "WHERE ";

    foreach ($where as $key => $value) :
      $is_empty_array = isEmptyArray($value);

      if (!$is_empty_array) :
        if (!is_array($value[0])) :
          $field_name   = $value[0];
          $field_value  = $value[1];
          $field_rule   = $value[2] ? $value[2] : "=";
          $field_concat = $value[3] ? $value[3] : "AND";
          $concat       = (($total_clauses > 1) && ($key + 1 <= $total_clauses) && ($key != 0)) ? $field_concat : "";

          if ($field_rule === "=")        $c_where .= $concat . " ($field_name =        '" . mysqli_real_escape_string($mysqli, $field_value) . "'" . ") ";
          if ($field_rule === "!=")       $c_where .= $concat . " ($field_name !=       '" . mysqli_real_escape_string($mysqli, $field_value) . "'" . ") ";
          if ($field_rule === 'LIKE')     $c_where .= $concat . " ($field_name LIKE     '" . mysqli_real_escape_string($mysqli, $field_value) . "'" . ") ";
          if ($field_rule === "IN")       $c_where .= $concat . " ($field_name IN       (" . mysqli_real_escape_string($mysqli, $field_value) . ")" . ") ";
          if ($field_rule === 'BETWEEN')  $c_where .= $concat . " ($field_name BETWEEN  '" . mysqli_real_escape_string($mysqli, $field_value[0]) . "' AND '" . mysqli_real_escape_string($mysqli, $field_value[1]) . "'" . ") ";
        endif;

        if (is_array($value[0])) :
          $total_clauses_2  = count($value[0]);
          $second_concat    = !empty($value[1]) ? $value[1] : "AND";
          $c_where          .= $second_concat . "(";

          foreach ($value[0] as $key => $second_value) :
            $is_empty_array = isEmptyArray($second_value);

            if (!$is_empty_array) :
              $field_name   = $second_value[0];
              $field_value  = $second_value[1];
              $field_rule   = $second_value[2] ? $second_value[2] : "=";
              $field_concat = $second_value[3] ? $second_value[3] : "AND";
              $concat       = (($total_clauses_2 > 1) && ($key + 1 <= $total_clauses_2) && ($key != 0)) ? $field_concat : "";

              if ($field_rule === "=")        $c_where .= $concat . " ($field_name =        '" . mysqli_real_escape_string($mysqli, $field_value) . "'" . ") ";
              if ($field_rule === "!=")       $c_where .= $concat . " ($field_name !=       '" . mysqli_real_escape_string($mysqli, $field_value) . "'" . ") ";
              if ($field_rule === 'LIKE')     $c_where .= $concat . " ($field_name LIKE     '" . mysqli_real_escape_string($mysqli, $field_value) . "'" . ") ";
              if ($field_rule === "IN")       $c_where .= $concat . " ($field_name IN       (" . mysqli_real_escape_string($mysqli, $field_value) . ")" . ") ";
              if ($field_rule === 'BETWEEN')  $c_where .= $concat . " ($field_name BETWEEN  '" . mysqli_real_escape_string($mysqli, $field_value[0]) . "' AND '" . mysqli_real_escape_string($mysqli, $field_value[1]) . "'" . ") ";
            endif;
          endforeach;

          $c_where .= ")";
        endif;
      endif;
    endforeach;
  endif;

  $start_rows   = ($page - 1) * $per_page;
  $stop_rows    = $per_page;

  $c_limit_rows = "LIMIT $start_rows, $stop_rows";

  $query        = "SELECT $column_id $c_from $c_join $c_where";
  # return $query;
  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows == 0) return false;

  $num_pages    = ceil($num_rows / $stop_rows);

  # FIELDS
  $c_fields     = "";
  $total_fields = count($fields);

  foreach ($fields as $key => $field) :
    $is_array = is_array($field);

    if (!$is_array) $c_fields .= $field;

    if ($is_array) :
      $field_name   = $field[0];
      $field_rename = $field[1];
      $c_fields    .= "$field_name AS $field_rename";
    endif;

    if (($key + 1) < $total_fields) $c_fields .= ",";
  endforeach;

  $query        = "SELECT $c_fields $c_from $c_join $c_where $c_order $c_limit_rows";
  $query_result = mysqli_query($mysqli, $query);

  return [
    'query_result'  => $query_result,
    'num_pages'     => $num_pages
  ];
}
