<?php
include '../session.php';
include '../../inc/functions.inc.php';
include '../lib/pagination.php';

date_default_timezone_set('America/Mexico_City');

$action = isset($_POST['action']) && $_POST['action'] !== '' ? $_POST['action'] : '';

switch ($action) {
  case 'list_quotes':
    $id_user_create = $_SESSION['session_user_id'];

    $page              = isset($_POST['page']) && $_POST['page'] !== '' ? $_POST['page'] : 1;
    #$per_page         = isset($_POST['perPage']) && $_POST['perPage'] !== '' ? $_POST['perPage'] : 15;
    $per_page          = 15;

    $name               = cleanStr($_POST['searchByQuote']);
    $search_by_name     = $name != '' ? "(C.NombreCompleto LIKE '%$name%') OR C.Folio LIKE '%$name%'" : "1=1";

    $from              = "FROM cotizaciones AS C";

    /* $left_join = "
        LEFT JOIN paquetes_negocios AS PN ON (C.idPaquete = PN.idPaquete)
        LEFT JOIN salones           AS S  ON (PN.idNegocio = S.idSalon)
        LEFT JOIN usuarios          AS U  ON (C.idProveedor = U.idUsuario)
    "; */

    $left_join = "
        LEFT JOIN usuarios          AS U    ON (C.idProveedor   = U.idUsuario)
        LEFT JOIN paquetes_negocios AS PN   ON (C.idPaquete     = PN.idPaquete)
        LEFT JOIN tipo_eventos      AS TE   ON (C.idTipoEvento  = TE.idTipoEvento)
        LEFT JOIN salones           AS S    ON (PN.idNegocio    = S.idSalon)
        LEFT JOIN estados           AS E    ON (S.idEstado      = E.idEstado)
        LEFT JOIN ciudades          AS Ciu  ON (S.idCiudad      = Ciu.idCiudad)
    ";

    $where             = "WHERE
        ($search_by_name) AND
        (C.idProveedor  = '$id_user_create') AND
        (S.idSalon      = $_SESSION[session_business_id])
      ORDER BY C.idCotizacion DESC
    ";

    $start_rows        = ($page - 1) * $per_page;
    $stop_rows         = $per_page;

    $limit_rows        = "LIMIT $start_rows, $stop_rows";

    $query             = "SELECT COUNT(C.idCotizacion) AS Total $from $left_join $where LIMIT 1";
    $query_result      = mysqli_query($mysqli, $query);
    $row               = mysqli_fetch_array($query_result);

    $num_pages         = ceil($row['Total'] / $stop_rows);

    if (!$num_pages) {
      $default_message = '¡No hay cotizaciones solicitadas!.';

      if ($name != '') {
        $default_message = '¡No hay cotizaciones que coincidan con la palabra "' . $name . '"!.';
      }

      ob_start();
      include '../default_message.php';
      $data_message = base64_encode(ob_get_clean());

      $response['content'] = $data_message;
    }

    if ($num_pages) {
      $query = "SELECT
          C.idCotizacion,
          C.idProveedor,
          C.idUsuarioFinal,
          C.idPaquete,
          C.idTipoEvento,
          C.NombreCompleto,
          C.Email,
          C.Telefono,
          C.FechaSolicitada,
          DATE_FORMAT(C.FechaSolicitada, '%d/%m/%Y') AS FechaSolicitadaFormat,
          C.Folio,
          DATE_FORMAT(C.FechaCreacion, '%d/%m/%Y') AS FechaCotizacion,
          C.Status,
          U.Usuario AS Proveedor,
          U.Telefono AS TelefonoProveedor,
          U.Celular AS CelularProveedor,
          PN.idNegocio,
          PN.Paquete,
          PN.Precio AS PrecioPaquete,
          PN.Orientacion AS ModalidadPaquete,
          TE.TipoEvento,
          S.idEstado,
          S.idCiudad,
          S.Salon,
          S.Direccion,
          S.Facebook,
          S.Instagram,
          CONCAT(S.Direccion, ' ', E.Estado, ' ', Ciu.Ciudad) AS DireccionCompleta
        $from
        $left_join
        $where
        $limit_rows
      ";

      /* $query_________ = "SELECT
          C.idCotizacion,
          C.idPaquete,
          C.idTipoEvento,
          C.NombreCompleto,
          C.Email,
          C.Telefono,
          C.Fecha,
          C.Status,
          PN.Paquete,
          PN.idNegocio,
          S.Salon,
          U.Usuario AS Proveedor
        $from $left_join $where $limit_rows
      "; */

      $query_result = mysqli_query($mysqli, $query);

      ob_start();
      include 'quotes_table.php';
      $data_table = base64_encode(ob_get_clean());

      $response['content'] = $data_table;
      $response['recentEventsTotal'] = getRecentEventsCount();
      $response['total'] = getQuotesCount();
    }
    break;

  case 'schedule_date':
    $id_user_create = $_SESSION['session_user_id'];

    $quote_id       = cleanStr($_POST['quoteId']);

    $business       = cleanStr($_POST['business']);
    $package        = cleanStr($_POST['package']);
    $event_type     = cleanStr($_POST['eventType']);
    $name           = cleanStr($_POST['name']);
    $email          = cleanStr($_POST['email']);
    $phone          = cleanStr($_POST['phone']);
    $n_persons      = cleanStr($_POST['NPersons']);
    $extras         = cleanStr($_POST['extras']);
    $total_cost     = cleanStr($_POST['totalCost']);
    $deposit        = $_POST['deposit'] ? cleanStr($_POST['deposit']) : '0';
    $adavnce        = $_POST['advance'] ? cleanStr($_POST['advance']) : '0';
    $status         = cleanStr($_POST['status']);

    //$date           = cleanStr($_POST['date']);
    #$start_time     = date("H:i:s", strtotime(cleanStr($_POST['startTime'])));
    #$end_time       = date("H:i:s", strtotime(cleanStr($_POST['endTime'])));

    $start_time     = (isset($_POST['startTime']) && $_POST['startTime'] != '') ? "'" . date("H:i:s", strtotime(cleanStr($_POST['startTime']))) . "'" : "NULL";
    $end_time       = (isset($_POST['endTime']) && $_POST['endTime'] != '') ? "'" . date("H:i:s", strtotime(cleanStr($_POST['endTime']))) . "'" : "NULL";

    $ant_date       = $_POST['date'];
    $date_replace   = str_replace('/', '-', $ant_date);
    $date           = date('Y-m-d', strtotime($date_replace));

    $rc_percentages = $_POST['RCPercentages'];
    $rc_dates       = $_POST['RCDates'];

    $today_date   = date('Y-m-d');

    if (strtotime($date) < strtotime($today_date)) {
      $response = array(
        'status'  => 'error',
        'title'   => '¡Fecha antigua!',
        'message' => 'La fecha no esta disponible'
      );

      echo json_encode($response);
      return;
    }

    $query = "SELECT
        DateStatus
      FROM calendario_fechas
      WHERE
        idUsuario = '$id_user_create' AND
        idNegocio = '$business' AND
        Fecha     = '$date'
      LIMIT 1
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) {
      $data_date    = mysqli_fetch_array($query_result);
      $date_status  = $data_date['DateStatus'];

      if ($date_status == 'Ocupado') {
        $response = array(
          'status'  => 'error',
          'title'   => '¡Error!',
          'message' => 'La fecha ya está ocupada'
        );

        echo json_encode($response);
        return;
      }
    }

    $query = "INSERT INTO reservaciones (
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
      ) VALUES (
        '$id_user_create',
        '$business',
        '$package',
        '$event_type',
        '$name',
        '$email',
        '$phone',
        '$date',
        $start_time,
        $end_time,
        '$n_persons',
        '$extras',
        '$total_cost',
        '$deposit',
        '$adavnce',
        '$today_date'
      )
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) $response = array(
      'status' => 'error',
      'title' => '¡Error!',
      'message' => '¡Error!, Intentelo nuevamente.'
    );

    if ($query_result) {
      $reservation_id = mysqli_insert_id($mysqli);

      $rc_payments = checkArray($rc_percentages);

      if ($rc_payments) addPaymentRecordatory(
        $id_user_create,
        $reservation_id,
        $rc_percentages,
        $rc_dates
      );

      addDateStatus(
        $id_user_create,
        $business,
        $date,
        $status
      );

      if ($adavnce) addDateAdvance(
        $id_user_create,
        $reservation_id,
        $adavnce
      );

      $query = "UPDATE cotizaciones SET
          idPaquete       = '$package',
          idTipoEvento    = '$event_type',
          NombreCompleto  = '$name',
          Email           = '$email',
          Telefono        = '$phone',
          FechaSolicitada = '$date',
          Status          = 'Completado'
        WHERE idCotizacion = '$quote_id'
      ";

      mysqli_query($mysqli, $query);

      $response = array(
        'status' => 'success',
        'title' => '¡Datos guardados!',
        'message' => 'El evento se agregó correctamente.',
        'recentEventsTotal' => getRecentEventsCount()
      );
    }
    break;

  case 'add_quote':
    $seller_id    = $_SESSION['session_user_id'];
    $business_id  = $_SESSION['session_business_id'];
    $package      = cleanStr($_POST['package']);
    $name         = cleanStr($_POST['name']);
    $email        = cleanStr($_POST['email']);
    $phone        = cleanStr($_POST['phone']);
    $event_type   = cleanStr($_POST['eventType']);
    $folio        = getQuoteFolio($business_id);

    $ant_date     = $_POST['date'];
    $date_replace = str_replace('/', '-', $ant_date);
    $date         = date('Y-m-d', strtotime($date_replace));

    $today_date   = date('Y-m-d');

    if (strtotime($date) < strtotime($today_date)) {
      $response = array(
        'status'  => 'error',
        'title'   => '¡Fecha antigua!',
        'message' => 'La fecha no esta disponible'
      );

      echo json_encode($response);
      return;
    }

    $query = "SELECT
        DateStatus
      FROM calendario_fechas
      WHERE
        idUsuario = '$seller_id' AND
        idNegocio = '$business_id' AND
        Fecha     = '$date'
      LIMIT 1
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) {
      $data_date    = mysqli_fetch_array($query_result);
      $date_status  = $data_date['DateStatus'];

      if ($date_status == 'Ocupado') {
        $response = array(
          'status'  => 'error',
          'title'   => '¡Error!',
          'message' => 'La fecha ya está ocupada'
        );

        echo json_encode($response);
        return;
      }
    }

    $query = "INSERT INTO cotizaciones (
        idNegocio,
        idProveedor,
        idPaquete,
        idTipoEvento,
        NombreCompleto,
        Email,
        Telefono,
        FechaSolicitada,
        Folio,
        FechaCreacion
      ) VALUES (
        '$business_id',
        '$seller_id',
        '$package',
        '$event_type',
        '$name',
        '$email',
        '$phone',
        '$date',
        '$folio',
        '$today_date'
      )
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) $response = array(
      'status'  => 'error',
      'title'   => '¡Error!',
      'message' => 'Error inesperado, intentalo nuevamente'
    );

    if ($query_result) $response = array(
      'status'  => 'success',
      'title'   => '¡Cotización realizada!',
      'message' => 'La cotización se realizó correctamente',
      'recentEventsTotal' => getRecentEventsCount()
    );
    break;

  case 'edit_quote':
    $seller_id    = $_SESSION['session_user_id'];
    $quote_id     = cleanStr($_POST['quoteId']);
    $package      = cleanStr($_POST['package']);
    $name         = cleanStr($_POST['name']);
    $email        = cleanStr($_POST['email']);
    $phone        = cleanStr($_POST['phone']);
    $event_type   = cleanStr($_POST['eventType']);

    $ant_date     = $_POST['date'];
    $date_replace = str_replace('/', '-', $ant_date);
    $date         = date('Y-m-d', strtotime($date_replace));

    $today_date   = date('Y-m-d');

    if (strtotime($date) < strtotime($today_date)) {
      $response = array(
        'status'  => 'error',
        'title'   => '¡Fecha antigua!',
        'message' => 'La fecha no esta disponible'
      );

      echo json_encode($response);
      return;
    }

    $query = "SELECT
        idNegocio
      FROM catalogo_paquete_tipos_eventos
      WHERE idPaquete = '$package'
      LIMIT 1
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if (!$num_rows) {
      $response = array(
        'status'  => 'error',
        'title'   => '¡Error inesperado!',
        'message' => 'Intentalo nuevamente.'
      );

      echo json_encode($response);
      return;
    }

    $data_business  = mysqli_fetch_array($query_result);
    $business_id    = $data_business['idNegocio'];

    $query = "SELECT
        DateStatus,
        Fecha
      FROM calendario_fechas
      WHERE
        idUsuario = '$seller_id' AND
        idNegocio = '$business_id' AND
        Fecha     = '$date'
      LIMIT 1
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) {
      $data_date      = mysqli_fetch_array($query_result);
      $date_status    = $data_date['DateStatus'];
      $original_date  = $data_date['Fecha'];

      if ($date_status == 'Ocupado' && $original_date != $date) {
        $response = array(
          'status'  => 'error',
          'title'   => '¡Error!',
          'message' => 'La fecha ya está ocupada'
        );

        echo json_encode($response);
        return;
      }
    }

    $query = "UPDATE cotizaciones SET
          idPaquete       = '$package',
          idTipoEvento    = '$event_type',
          NombreCompleto  = '$name',
          Email           = '$email',
          Telefono        = '$phone',
          FechaSolicitada = '$date'
        WHERE
          idCotizacion  = $quote_id AND
          idProveedor   = $seller_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) $response = array(
      'status'  => 'error',
      'title'   => '¡Error!',
      'message' => 'Error inesperado, intentalo nuevamente'
    );

    if ($query_result) $response = array(
      'status'  => 'success',
      'title'   => '¡Cotización actualizada!',
      'message' => 'La cotización se actualizó correctamente',
      'recentEventsTotal' => getRecentEventsCount()
    );
    break;

  case 'cancel_quote':
    $seller_id  = $_SESSION['session_user_id'];
    $quote_id   = cleanStr($_POST['quoteId']);

    $query = "UPDATE cotizaciones SET
        Status = 'Cancelado'
      WHERE
        idCotizacion  = $quote_id AND
        idProveedor   = $seller_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) $response = array(
      'status'  => 'error',
      'message' => 'Error inesperado, intentalo nuevamente'
    );

    if ($query_result) $response = array(
      'status'  => 'success',
      'message' => 'La cotización se canceló correctamente'
    );
    break;

  case 'contact_quote':
    $seller_id  = $_SESSION['session_user_id'];
    $quote_id   = cleanStr($_POST['quoteId']);

    $query = "UPDATE cotizaciones SET
        Status = 'Contestado'
      WHERE
        idCotizacion  = $quote_id AND
        idProveedor   = $seller_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) $response = array(
      'status'  => 'error',
      'message' => 'Error inesperado, intentalo nuevamente'
    );

    if ($query_result) $response = array(
      'status'  => 'success',
      'message' => 'La cotización se ha marcado como contestado'
    );
    break;

  case 'resume_quote':
    $seller_id  = $_SESSION['session_user_id'];
    $quote_id   = cleanStr($_POST['quoteId']);

    $query = "UPDATE cotizaciones SET
        Status = 'Pendiente'
      WHERE
        idCotizacion  = $quote_id AND
        idProveedor   = $seller_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) $response = array(
      'status'  => 'error',
      'message' => 'Error inesperado, intentalo nuevamente'
    );

    if ($query_result) $response = array(
      'status'  => 'success',
      'message' => 'La cotización se recuperó correctamente'
    );
    break;

  default:
    $response = array(
      'state' => 'error',
      'title' => '¡Error!',
      'message' => '¡Error!, Intentelo nuevamente.'
    );
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
