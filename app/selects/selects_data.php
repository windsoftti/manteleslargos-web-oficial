<?php
include '../inc/session.php';

$action = $json['action'];

$response = array(
  'status'  => 'error',
  'title'   => '¡Error inesperado!',
  'message' => 'Intentalo nuevamente.'
);

switch ($action) {
  case 'get-businessId':
    $user_id = cleanStr($json['parameters']);

    $business_id = getFirstBusinessId($user_id);

    $response = array(
      'status'      => 'success',
      'businessId'  => $business_id
    );
    break;

  case 'get-businesses':
    $user_id    = cleanStr($json['parameters']);
    $businesses = array();

    $query = "SELECT
        idSalon,
        Salon,
        Direccion
      FROM salones
      WHERE
        idUsuario = $user_id AND
        Status    = 'Activo'
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) {
      while ($row = mysqli_fetch_array($query_result)) :
        array_push($businesses, array(
          'businessId'  => $row['idSalon'],
          'business'    => $row['Salon'],
          'address'      => $row['Direccion']
        ));
      endwhile;

      $response = array(
        'status'      => 'success',
        'businesses'  => $businesses
      );
    }
    break;

  case 'get-packages':
    $parameters = $json['parameters'];

    $user_id      = cleanStr($parameters['userId']);
    $business_id  = cleanStr($parameters['businessId']);

    $packages = array();

    $query_packages = "SELECT
        idPaquete,
        Paquete
      FROM paquetes_negocios
      WHERE
        idNegocio = $business_id
    ";

    $query_packages_result = mysqli_query($mysqli, $query_packages);

    while ($row = mysqli_fetch_array($query_packages_result)) :
      array_push($packages, array(
        '_id'   => $row['idPaquete'],
        'value' => $row['Paquete']
      ));
    endwhile;

    $response = array(
      'status'    => 'success',
      'packages'  => $packages
    );
    break;

  case 'get-event-types':
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
      'status'      => 'success',
      'eventTypes'  => $event_types
    );
    break;

  case 'get-supplier-types':
    $supplier_types  = array();

    $query_suppliertypes = "SELECT
        idTipoProveedor,
        TipoProveedor,
        eventos
      FROM tipo_proveedores
      ORDER BY idTipoProveedor
    ";

    $query_suppliertypes_result = mysqli_query($mysqli, $query_suppliertypes);

    while ($row = mysqli_fetch_array($query_suppliertypes_result)) :
      $events = $row['eventos'];
      $events = explode(',', $events);

      array_push($supplier_types, array(
        '_id'     => $row['idTipoProveedor'],
        'value'   => $row['TipoProveedor'],
        'events'  => $events
      ));
    endwhile;

    $response = array(
      'status'        => 'success',
      'supplierTypes' => $supplier_types
    );
    break;

  case 'get-countries':
    $countries = array();

    $query = "SELECT
        id,
        name,
        phonecode
      FROM countries
      ORDER BY name
    ";

    $query_result = mysqli_query($mysqli, $query);

    while ($row = mysqli_fetch_array($query_result)) :
      array_push($countries, array(
        '_id'       => $row['id'],
        'value'     => $row['name'],
        'phoneCode' => $row['phonecode']
      ));
    endwhile;

    $response = array(
      'status'    => 'success',
      'countries' => $countries
    );
    break;

  case 'get-states':
    $states = array();

    $query_states = "SELECT
        idEstado,
        Estado,
        Latitud,
        Longitud
      FROM estados
      ORDER BY Estado
    ";

    $query_states_result = mysqli_query($mysqli, $query_states);

    while ($row = mysqli_fetch_array($query_states_result)) :
      array_push($states, array(
        '_id'       => $row['idEstado'],
        'value'     => $row['Estado'],
        'latitude'  => $row['Latitud'],
        'longitude' => $row['Longitud']
      ));
    endwhile;

    $response = array(
      'status' => 'success',
      'states' => $states
    );
    break;

  case 'get-citys':
    $state_id = cleanStr($json['parameters']);
    $citys    = array();

    $query = "SELECT
        EC.idEstadoCiudad,
        EC.idCiudad,
        C.Ciudad
      FROM estados_ciudades AS EC
        LEFT JOIN ciudades AS C ON (EC.idCiudad = C.idCiudad)
      WHERE EC.idEstado = $state_id
      ORDER BY C.Ciudad
    ";

    $query_result = mysqli_query($mysqli, $query);

    while ($row = mysqli_fetch_array($query_result)) :
      array_push($citys, array(
        '_id'   => $row['idCiudad'],
        'value' => $row['Ciudad']
      ));
    endwhile;

    $response = array(
      'status'  => 'success',
      'citys'   => $citys
    );
    break;

  case 'get-services':
    $services = array();

    $query = "SELECT
        idServicio,
        Servicio
      FROM servicios
    ";

    $query_result = mysqli_query($mysqli, $query);

    while ($row = mysqli_fetch_array($query_result)) :
      array_push($services, array(
        '_id'   => $row['idServicio'],
        'value' => $row['Servicio']
      ));
    endwhile;

    $response = array(
      'status'    => 'success',
      'services'  => $services
    );
    break;

  case 'get-amenities':
    $amenities = array();

    $query = "SELECT
        idAmenidad,
        Amenidad
      FROM amenidades
    ";

    $query_result = mysqli_query($mysqli, $query);

    while ($row = mysqli_fetch_array($query_result)) :
      array_push($amenities, array(
        '_id'   => $row['idAmenidad'],
        'value' => $row['Amenidad']
      ));
    endwhile;

    $response = array(
      'status'    => 'success',
      'amenities' => $amenities
    );
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
exit();
