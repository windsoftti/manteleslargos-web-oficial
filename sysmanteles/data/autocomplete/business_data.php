<?php
include '../lib/session-root.php';

$search_term = cleanStr($_GET['term']);

if ($search_term) :
  $array_return = array();

  $query        = "SELECT idSalon, Salon FROM salones WHERE Salon LIKE '%$search_term%' LIMIT 10";
  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows > 0) :
    while ($row = mysqli_fetch_array($query_result)) :
      array_push($array_return, array(
        'value'       => $row['Salon'],
        'businessId'  => $row['idSalon'],
        'business'    => $row['Salon']
      ));
    endwhile;
  endif;

  echo json_encode($array_return);
endif;
