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
  case 'get-quotes':
    try {
      $parameters   = $json['parameters'];

      $user_id      = cleanStr($parameters['userId']);
      $business_id  = cleanStr($parameters['businessId']);
      $page         = cleanStr($parameters['page']);
      $search_term  = cleanStr($parameters['searchTerm']);

      $per_page     = 15;

      $from         = "FROM cotizaciones";

      $search_by_term = $search_term != '' ? "
          (NombreCompleto LIKE '%$search_term%') OR
          (Folio          LIKE '%$search_term%')
      " : "1=1";

      $where = "WHERE
          idProveedor = $user_id      AND
          idNegocio   = $business_id  AND
          ($search_by_term)
        ORDER BY idCotizacion DESC
      ";

      $start_rows = ($page - 1) * $per_page;
      $stop_rows  = $per_page;

      $limit_rows = "LIMIT $start_rows, $stop_rows";

      $query      = "SELECT COUNT(idCotizacion) AS Total $from $where LIMIT 1";
      $num_pages  = numPages($query, $stop_rows);

      if (!$num_pages) $response = array(
        'status' => 'empty'
      );

      if ($num_pages) {
        $query = "SELECT
            idCotizacion,
            Folio,
            idNegocio,
            idProveedor,
            idUsuarioFinal,
            idPaquete,
            idTipoEvento,
            NombreCompleto,
            Email,
            Telefono,
            FechaSolicitada,
            FechaCreacion,
            DATE_FORMAT(FechaSolicitada, '%d-%m-%Y') AS FechaSolicitadaFormat,
            Status
          $from
          $where
          $limit_rows
        ";

        $query_result = mysqli_query($mysqli, $query);

        $quotes = array();

        while ($quote_data = mysqli_fetch_array($query_result)) :
          $quote_data['TelefonoFormat'] = formatPhoneNumber($quote_data['Telefono']);

          array_push($quotes, $quote_data);
        endwhile;

        $response = array(
          'status'      => 'success',
          'totalPages'  => $num_pages,
          'quotes'      => $quotes
        );
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'add-quote':
    $parameters   = $json['parameters'];

    $user_id      = cleanStr($parameters['userId']);
    $business_id  = cleanStr($parameters['businessId']);
    $values       = $parameters['values'];

    $package      = cleanStr($values['package']);
    $event_type   = cleanStr($values['eventType']);
    $date         = cleanStr($values['date']);
    $full_name    = cleanStr($values['fullName']);
    $email        = cleanStr($values['email']);
    $phone        = cleanStr($values['phone']);

    $folio        = getQuoteFolio($business_id);

    $today_date   = date('Y-m-d');

    if (strtotime($date) < strtotime($today_date)) {
      $response = array(
        'status'  => 'error',
        'title'   => '¡Fecha antigua!',
        'message' => 'La fecha no esta disponible'
      );

      echo json_encode($response);
      mysqli_close($mysqli);
      die();
    }

    $query = "SELECT
        DateStatus
      FROM calendario_fechas
      WHERE
        idUsuario = '$user_id'      AND
        idNegocio = '$business_id'  AND
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
        mysqli_close($mysqli);
        die();
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
        '$user_id',
        '$package',
        '$event_type',
        '$full_name',
        '$email',
        '$phone',
        '$date',
        '$folio',
        '$today_date'
      )
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) $response = array(
      'status'  => 'success',
      'title'   => '¡Cotización realizada!',
      'message' => 'La cotización se realizó correctamente'
    );
    break;

  case 'update-quote':
    try {
      $parameters   = $json['parameters'];

      $user_id      = cleanStr($parameters['userId']);
      $business_id  = cleanStr($parameters['businessId']);
      $quote_id     = cleanStr($parameters['quoteId']);

      $values       = $parameters['values'];

      $package      = cleanStr($values['package']);
      $event_type   = cleanStr($values['eventType']);
      $date         = cleanStr($values['date']);
      $full_name    = cleanStr($values['fullName']);
      $email        = cleanStr($values['email']);
      $phone        = cleanStr($values['phone']);

      $today_date   = date('Y-m-d');

      $query_date         = "SELECT FechaSolicitada FROM cotizaciones WHERE idCotizacion = $quote_id LIMIT 1";
      $query_date_result  = mysqli_query($mysqli, $query_date);

      $quote_date_data    = mysqli_fetch_array($query_date_result);
      $quote_date         = $quote_date_data['FechaSolicitada'];

      if ((strtotime($date) < strtotime($today_date)) && $date != $quote_date) {
        $response = array(
          'status'  => 'error',
          'title'   => '¡Fecha antigua!',
          'message' => 'La fecha no esta disponible'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        die();
      }

      $query = "SELECT
          DateStatus
        FROM calendario_fechas
        WHERE
          idUsuario = '$user_id'      AND
          idNegocio = '$business_id'  AND
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
          mysqli_close($mysqli);
          die();
        }
      }

      $query = "UPDATE cotizaciones SET
          idPaquete       = $package,
          idTipoEvento    = $event_type,
          NombreCompleto  = '$full_name',
          Email           = '$email',
          Telefono        = '$phone',
          FechaSolicitada = '$date'
        WHERE
          idCotizacion  = $quote_id     AND
          idNegocio     = $business_id  AND
          idProveedor   = $user_id
      ";

      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) $response = array(
        'status'  => 'success',
        'title'   => '¡Cotización actualizada!',
        'message' => 'La cotización se actualizó correctamente'
      );
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'cancel-quote':
    $parameters   = $json['parameters'];

    $user_id      = cleanStr($parameters['userId']);
    $business_id  = cleanStr($parameters['businessId']);
    $quote_id     = cleanStr($parameters['quoteId']);

    $query = "UPDATE cotizaciones SET
        Status = 'Cancelado'
      WHERE
        idCotizacion  = $quote_id     AND
        idNegocio     = $business_id  AND
        idProveedor   = $user_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) $response = array(
      'status'  => 'success',
      //'title'   => '¡Cotización cancelada!',
      //'message' => 'La cotización se canceló correctamente.'
    );
    break;

  case 'answer-quote':
    $parameters   = $json['parameters'];

    $user_id      = cleanStr($parameters['userId']);
    $business_id  = cleanStr($parameters['businessId']);
    $quote_id     = cleanStr($parameters['quoteId']);

    $query = "UPDATE cotizaciones SET
        Status = 'Contestado'
      WHERE
        idCotizacion  = $quote_id     AND
        idNegocio     = $business_id  AND
        idProveedor   = $user_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) $response = array(
      'status'  => 'success',
      //'title'   => '¡Cotización cancelada!',
      //'message' => 'La cotización se canceló correctamente.'
    );
    break;

  case 'recover-quote':
    $parameters   = $json['parameters'];

    $user_id      = cleanStr($parameters['userId']);
    $business_id  = cleanStr($parameters['businessId']);
    $quote_id     = cleanStr($parameters['quoteId']);

    $query = "UPDATE cotizaciones SET
        Status = 'Pendiente'
      WHERE
        idCotizacion  = $quote_id     AND
        idNegocio     = $business_id  AND
        idProveedor   = $user_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) $response = array(
      'status'  => 'success',
      //'title'   => '¡Cotización recuperada!',
      //'message' => 'La cotización se recuperó correctamente.'
    );
    break;

  case 'schedule-quote':
    $parameters         = $json['parameters'];

    $quote_id           = cleanStr($parameters['quoteId']);
    $user_id            = cleanStr($parameters['userId']);
    $business_id        = cleanStr($parameters['businessId']);
    $values             = $parameters['values'];

    $package            = cleanStr($values['packages']);
    $event_type         = cleanStr($values['eventType']);
    $full_name          = cleanStr($values['fullName']);
    $email              = cleanStr($values['email']);
    $phone              = cleanStr($values['phone']);
    $date               = cleanStr($values['date']);
    $time_start         = $values['timeStart']  != ''   ? "'" . cleanStr($values['timeStart']) .  "'"  : 'NULL';
    $time_end           = $values['timeEnd']    != ''   ? "'" . cleanStr($values['timeEnd']) .    "'"    : 'NULL';
    $num_persons        = cleanStr($values['numPersons']);
    $total_cost         = cleanStr($values['totalCost']);
    $deposit            = $values['deposit'] != '' ? cleanStr($values['deposit']) : 0;
    $advance            = $values['advance'] != '' ? cleanStr($values['advance']) : 0;
    $day_status         = cleanStr($values['dayStatus']);
    $comments           = cleanStr($values['comments']);

    $payment_reminders  = $values['paymentReminders'];

    $today_date   = date('Y-m-d');

    if (strtotime($date) < strtotime($today_date)) {
      $response = array(
        'status'  => 'error',
        'title'   => '¡Fecha antigua!',
        'message' => 'La fecha ya no esta disponible'
      );

      echo json_encode($response);
      mysqli_close($mysqli);
      die();
    }

    try {
      # Add reservation
      $query_add_reservation = "INSERT INTO reservaciones (
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
          $user_id,
          $business_id,
          $package,
          '$event_type',
          '$full_name',
          '$email',
          '$phone',
          '$date',
          $time_start,
          $time_end,
          '$num_persons',
          '$comments',
          '$total_cost',
          '$deposit',
          '$advance',
          '$today_date'
        )
      ";

      $query_add_reservation_result = mysqli_query($mysqli, $query_add_reservation);

      if ($query_add_reservation_result) {
        $reservation_id           = mysqli_insert_id($mysqli);
        $count_payment_reminders  = count($payment_reminders);

        if ($count_payment_reminders) addPaymentReminders(
          $user_id,
          $reservation_id,
          $payment_reminders
        );

        addDateStatus(
          $user_id,
          $business_id,
          $date,
          $day_status
        );

        if ($advance) addDateAdvance(
          $user_id,
          $reservation_id,
          $advance
        );

        # Update quote
        $query_update_quote = "UPDATE cotizaciones SET
            idPaquete       = $package,
            idTipoEvento    = $event_type,
            NombreCompleto  = '$full_name',
            Email           = '$email',
            Telefono        = '$phone',
            FechaSolicitada = '$date',
            Status = 'Completado'
          WHERE
            idCotizacion  = $quote_id     AND
            idNegocio     = $business_id  AND
            idProveedor   = $user_id
        ";

        mysqli_query($mysqli, $query_update_quote);

        $response = array(
          'status' => 'success',
          'title' => '¡Datos guardados!',
          'message' => 'La reservación se agregó correctamente.'
        );
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
exit();
