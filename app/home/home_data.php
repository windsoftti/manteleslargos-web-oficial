<?php
include '../inc/session.php';
date_default_timezone_set('America/Mexico_City');

$action = $json['action'];

$response = array(
  'status'  => 'error',
  'title'   => '¡Error inesperado!',
  'message' => 'Intentalo nuevamente.'
);

switch ($action) {
  case 'get-supplier-data':
    $parameters = $json['parameters'];

    $user_id      = cleanStr($parameters['userId']);
    $business_id  = cleanStr($parameters['businessId']);

    # Supplier stats :::::::::::::::::::::::::::::::::::::::::::::::::::::::
    $num_business = getNumBusiness($user_id);

    $num_views = querySum(
      "Visitas",
      "salones",
      "
        idUsuario = $user_id AND
        idSalon   = $business_id
      "
    );

    $num_pendings = queryCount(
      "idCotizacion",
      "cotizaciones",
      "
        idProveedor = $user_id      AND
        idNegocio   = $business_id  AND
        Status      = 'Pendiente'
      "
    );

    $num_sales = queryCount(
      "idReservacion",
      "reservaciones",
      "
        idUsuario = $user_id AND
        idNegocio = $business_id
      "
    );

    $supplier_stats = array(
      'numBusiness' => $num_business,
      'numViews'    => $num_views,
      'numPendings' => $num_pendings,
      'numSales'    => $num_sales
    );

    # Sales made :::::::::::::::::::::::::::::::::::::::::::::::::::::
    $today_year   = date('Y');
    $today_month  = date('m');
    $array_months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

    $sales_made = array();

    foreach ($array_months as $key => $value) :
      $month = $array_months[$key];

      $num_sales_count = queryCount(
        "idReservacion",
        "reservaciones",
        "
          MONTH(FechaDeAgendado)  = $month        AND
          YEAR(FechaDeAgendado)   = $today_year   AND
          idUsuario               = $user_id      AND
          idNegocio               = $business_id
        "
      );

      if ($month <= $today_month) array_push($sales_made, $num_sales_count);
    endforeach;

    # Event types :::::::::::::::::::::::::::::::::::::::::::::::::::::
    $event_types  = array();

    $query_eventtypes = "SELECT
        idTipoEvento,
        TipoEvento
      FROM tipo_eventos
      ORDER BY TipoEvento
    ";

    $query_eventtypes_result = mysqli_query($mysqli, $query_eventtypes);

    while ($row = mysqli_fetch_array($query_eventtypes_result)) :
      array_push($event_types, array(
        '_id'   => $row['idTipoEvento'],
        'value' => $row['TipoEvento']
      ));
    endwhile;

    $response = array(
      'status'        => 'success',
      'supplierStats' => $supplier_stats,
      'salesMade'     => $sales_made,
      'todayMonth'    => $today_month,
      'eventTypes'    => $event_types
    );
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
exit();
