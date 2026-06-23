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
  case 'get-reservations':
    try {
      $parameters   = $json['parameters'];

      $user_id      = cleanStr($parameters['userId']);
      $business_id  = cleanStr($parameters['businessId']);
      $page         = cleanStr($parameters['page']);
      $search_term  = cleanStr($parameters['searchTerm']);
      $type         = cleanStr($parameters['type']);

      $per_page     = 15;

      $from         = "FROM reservaciones AS R";

      $search_by_term = $search_term != '' ? "
          (R.NombreCompleto LIKE '%$search_term%') OR
          (R.Correo         LIKE '%$search_term%')
      " : "1=1";

      $search_by_type = "1=1";

      if ($type === 'upcoming') $search_by_type = "R.Fecha >= NOW()";
      if ($type === 'old')      $search_by_type = "R.Fecha < NOW()";

      $left_join = "
          LEFT JOIN salones           AS S  ON (R.idNegocio = S.idSalon)
          LEFT JOIN paquetes_negocios AS PN ON (R.idPaquete = PN.idPaquete)
      ";

      $where = "WHERE
          R.idUsuario = $user_id      AND
          R.idNegocio = $business_id  AND
          ($search_by_term)           AND
          ($search_by_type)
        ORDER BY R.Fecha ASC
      ";

      $start_rows = ($page - 1) * $per_page;
      $stop_rows  = $per_page;

      $limit_rows = "LIMIT $start_rows, $stop_rows";

      $query      = "SELECT COUNT(R.idReservacion) AS Total $from $left_join $where LIMIT 1";
      $num_pages  = numPages($query, $stop_rows);

      if (!$num_pages) $response = array(
        'status' => 'empty'
      );

      if ($num_pages) {
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
            DATE_FORMAT(R.Fecha, '%Y-%m')         AS DateFind,
            DATE_FORMAT(R.HoraInicio, '%h:%i %p') AS HoraInicioFormat,
            DATE_FORMAT(R.HoraFinal, '%h:%i %p')  AS HoraFinalFormat,
            R.HoraInicio,
            R.HoraFinal,
            R.NPersonas,
            R.Extras,
            R.CostoTotal,
            R.Deposito,
            R.Anticipo,
            S.Salon,
            PN.Paquete
          $from
          $left_join
          $where
          $limit_rows
        ";

        $query_result = mysqli_query($mysqli, $query);

        $reservations = array();

        while ($reservation_data = mysqli_fetch_array($query_result)) :
          $data = $reservation_data;

          $day_status = getDayStatus(
            $user_id,
            $reservation_data['idNegocio'],
            $reservation_data['Fecha']
          );

          $payment_reminders = getPaymentReminders(
            $user_id,
            $reservation_data['idReservacion']
          );

          $date = getDateWithMonthName($data['Fecha']);
          $data['DateFormat'] = $date;

          $data['dayStatus'] = $day_status;
          $data['paymentReminders'] = $payment_reminders;

          $cost = '$' . number_format($reservation_data['CostoTotal'], 2);
          $data['CostoTotalFormat'] = $cost;

          $data['TelefonoFormat'] = formatPhoneNumber($data['Telefono']);
          $data['DepositoFormat'] = '$' . number_format($data['Deposito'], 2);
          $data['AnticipoFormat'] = '$' . number_format($data['Anticipo'], 2);

          array_push($reservations, $data);
        endwhile;

        $response = array(
          'status'        => 'success',
          'totalPages'    => $num_pages,
          'reservations'  => $reservations
        );
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'add-reservation':
    $parameters         = $json['parameters'];

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

    $today_date         = date('Y-m-d');

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

  case 'update-reservation':
    $parameters         = $json['parameters'];

    $user_id            = cleanStr($parameters['userId']);
    $business_id        = cleanStr($parameters['businessId']);
    $reservation_id     = cleanStr($parameters['reservationId']);

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
    $advance            = cleanStr($values['advance']);
    $day_status         = cleanStr($values['dayStatus']);
    $comments           = cleanStr($values['comments']);
    $payment_reminders  = $values['paymentReminders'];
    $today_date         = date('Y-m-d');
    $reservation_data   = getReservationData($reservation_id);

    try {
      # Edit reservation
      $query_edit_reservation = "UPDATE reservaciones SET
          idNegocio       = '$business_id',
          idPaquete       = '$package',
          idTipoEvento    = '$event_type',
          NombreCompleto  = '$full_name',
          Correo          = '$email',
          Telefono        = '$phone',
          Fecha           = '$date',
          HoraInicio      = $time_start,
          HoraFinal       = $time_end,
          NPersonas       = '$num_persons',
          Extras          = '$comments',
          CostoTotal      = '$total_cost',
          Deposito        = '$deposit'
        WHERE
          idReservacion = $reservation_id AND
          idUsuario     = $user_id
      ";

      $query_edit_reservation_result = mysqli_query($mysqli, $query_edit_reservation);

      if ($query_edit_reservation_result) {
        # Delete old reminders
        $query_delete_reminders = "DELETE FROM recordatorio_pagos WHERE
            idUsuario     = $user_id AND
            idReservacion = $reservation_id
        ";

        mysqli_query($mysqli, $query_delete_reminders);

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

        if ($reservation_data['Fecha'] !== $date) checkIfCalendarDateIsEmpty(
          $reservation_data['Fecha'],
          $user_id,
          $business_id
        );

        $response = array(
          'status' => 'success',
          'title' => '¡Datos guardados!',
          'message' => 'La reservación se actualizó correctamente.'
        );
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'remove-reservation':
    $parameters         = $json['parameters'];

    $user_id            = cleanStr($parameters['userId']);
    $business_id        = cleanStr($parameters['businessId']);
    $reservation_id     = cleanStr($parameters['reservationId']);
    $reservation_data   = getReservationData($reservation_id);

    $query = "DELETE FROM reservaciones WHERE
        idReservacion = $reservation_id AND
        idNegocio     = $business_id    AND
        idUsuario     = $user_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) :
      checkIfCalendarDateIsEmpty(
        $reservation_data['Fecha'],
        $user_id,
        $business_id
      );

      $query = "DELETE FROM reservaciones_pagos WHERE
          idReservacion = $reservation_id AND
          idUsuario     = $user_id
      ";

      mysqli_query($mysqli, $query);

      $query = "DELETE FROM recordatorio_pagos WHERE
          idReservacion = $reservation_id AND
          idUsuario     = $user_id
      ";

      mysqli_query($mysqli, $query);
    endif;

    $response = array(
      'status' => 'success',
      'title' => '¡Reservación eliminada!',
      'message' => 'La reservación se eliminó correctamente.'
    );
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
exit();
