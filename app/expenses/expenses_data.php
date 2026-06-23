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
  case 'get-expenses':
    try {
      $parameters   = $json['parameters'];

      $user_id      = cleanStr($parameters['userId']);
      $business_id  = cleanStr($parameters['businessId']);
      $page         = cleanStr($parameters['page']);
      $search_term  = cleanStr($parameters['searchTerm']);
      $search_date  = cleanStr($parameters['searchDate']);

      $per_page     = 15;

      $from         = "FROM egresos";

      $search_by_term = $search_term != '' ? "
          (Concepto     LIKE '%$search_term%') OR
          (Descripcion  LIKE '%$search_term%')
      " : "1=1";

      $search_by_date = $search_date != '' ? "Fecha = '$search_date'" : "1=1";

      $where = "WHERE
          idUsuario   = $user_id      AND
          idNegocio   = $business_id  AND
          ($search_by_term)           AND
          ($search_by_date)
        ORDER BY idEgreso DESC
      ";

      $start_rows = ($page - 1) * $per_page;
      $stop_rows  = $per_page;

      $limit_rows = "LIMIT $start_rows, $stop_rows";

      $query      = "SELECT COUNT(idEgreso) AS Total $from $where LIMIT 1";
      $num_pages  = numPages($query, $stop_rows);

      if (!$num_pages) $response = array(
        'status' => 'empty'
      );

      if ($num_pages) {
        $query = "SELECT
            idEgreso,
            idUsuario,
            idNegocio,
            Concepto,
            Descripcion,
            Costo,
            Fecha,
            DATE_FORMAT(Fecha, '%d-%m-%Y') AS FechaFormat
          $from
          $where
          $limit_rows
        ";

        $query_result = mysqli_query($mysqli, $query);

        $expenses = array();

        while ($egress_data = mysqli_fetch_array($query_result)) :
          $cost_with_format = '$' . number_format($egress_data['Costo'], 2);

          $egress_data['CostWithFormat'] = $cost_with_format;

          array_push($expenses, $egress_data);
        endwhile;

        $response = array(
          'status'      => 'success',
          'totalPages'  => $num_pages,
          'expenses'    => $expenses
        );
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'add-egress':
    $parameters   = $json['parameters'];

    $user_id      = cleanStr($parameters['userId']);
    $business_id  = cleanStr($parameters['businessId']);
    $values       = $parameters['values'];

    $concept      = cleanStr($values['concept']);
    $cost         = cleanStr($values['cost']);
    $date         = cleanStr($values['date']);
    $description  = cleanStr($values['description']);

    $query = "INSERT INTO egresos (
        idUsuario,
        idNegocio,
        Concepto,
        Costo,
        Descripcion,
        Fecha
      ) VALUES (
        $user_id,
        $business_id,
        '$concept',
        '$cost',
        '$description',
        '$date'
      )
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) $response = array(
      'status'  => 'success',
      'title'   => '¡Ereso agregado!',
      'message' => 'El egreso se agregó correctamente'
    );
    break;

  case 'update-egress':
    try {
      $parameters   = $json['parameters'];

      $user_id      = cleanStr($parameters['userId']);
      $business_id  = cleanStr($parameters['businessId']);
      $egress_id    = cleanStr($parameters['egressId']);

      $values       = $parameters['values'];

      $concept      = cleanStr($values['concept']);
      $cost         = cleanStr($values['cost']);
      $date         = cleanStr($values['date']);
      $description  = cleanStr($values['description']);

      $query = "UPDATE egresos SET
          Concepto    = '$concept',
          Costo       = '$cost',
          Descripcion = '$description',
          Fecha       = '$date'
        WHERE
          idEgreso  = $egress_id     AND
          idNegocio = $business_id  AND
          idUsuario = $user_id
      ";

      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) $response = array(
        'status'  => 'success',
        'title'   => '¡Egreso actualizado!',
        'message' => 'La egreso se actualizó correctamente'
      );
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'remove-egress':
    $parameters   = $json['parameters'];

    $user_id      = cleanStr($parameters['userId']);
    $business_id  = cleanStr($parameters['businessId']);
    $egress_id    = cleanStr($parameters['egressId']);

    $query = "DELETE FROM egresos WHERE
        idEgreso  = $egress_id    AND
        idNegocio = $business_id  AND
        idUsuario = $user_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) $response = array(
      'status'  => 'success',
      'title'   => '¡Egreso eliminado!',
      'message' => 'El egreso se eliminó correctamente.'
    );
    break;

  case 'recover-quote':
    $parameters   = $json['parameters'];

    $user_id      = cleanStr($parameters['userId']);
    $business_id  = cleanStr($parameters['businessId']);
    $egress_id     = cleanStr($parameters['egressId']);

    $query = "UPDATE cotizaciones SET
        Status = 'Pendiente'
      WHERE
        idEgreso  = $egress_id     AND
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

    $egress_id           = cleanStr($parameters['egressId']);
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
            idEgreso  = $egress_id     AND
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
