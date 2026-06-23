<?php
include '../inc/session.php';

$action = $json['action'];

$response = array(
  'status'  => 'error',
  'title'   => '¡Error inesperado!',
  'message' => 'Intentalo nuevamente.'
);

switch ($action) {
  case 'get-calendar-data':
    try {
      $parameters = $json['parameters'];

      $user_id      = cleanStr($parameters['userId']);
      $business_id  = cleanStr($parameters['businessId']);
      $year         = cleanStr($parameters['year']);
      $year         = $year != '' ? $year : date('Y');

      /* checkDateStatus(
        $user_id,
        $business_id
      ); */

      $reservations = array();
      $reminders    = array();
      $date_status  = array();

      # Reservations :::::::::::::::::::::::::::::::::::::::::::::::::::
      /* $query_reservations = "SELECT
          R.idReservacion,
          R.idUsuario,
          R.idNegocio,
          R.idPaquete,
          R.idTipoEvento,
          R.NombreCompleto,
          R.Correo,
          R.Telefono,
          R.Fecha,
          DATE_FORMAT(R.Fecha, '%Y-%m') AS DateFind,
          DATE_FORMAT(R.HoraInicio, '%h:%i %p') AS HoraInicio,
          DATE_FORMAT(R.HoraFinal, '%h:%i %p') AS HoraFinal,
          R.NPersonas,
          R.Extras,
          R.CostoTotal,
          R.Deposito,
          R.Anticipo,
          S.Salon
        FROM reservaciones AS R
          LEFT JOIN salones as S ON (R.idNegocio = S.idSalon)
        WHERE
          R.idUsuario = '$user_id' AND
          R.idNegocio = '$business_id'
      "; */

      $query_reservations = "SELECT
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
        FROM reservaciones AS R
          LEFT JOIN salones           AS S  ON (R.idNegocio = S.idSalon)
          LEFT JOIN paquetes_negocios AS PN ON (R.idPaquete = PN.idPaquete)
        WHERE
          R.idUsuario = '$user_id' AND
          R.idNegocio = '$business_id'
      ";

      $query_reservations_result  = mysqli_query($mysqli, $query_reservations);
      $num_reservations           = mysqli_num_rows($query_reservations_result);

      if ($num_reservations) {
        while ($row = mysqli_fetch_array($query_reservations_result)) :
          $data = $row;

          $day_status = getDayStatus(
            $user_id,
            $row['idNegocio'],
            $row['Fecha']
          );

          $payment_reminders = getPaymentReminders(
            $user_id,
            $row['idReservacion']
          );

          $date = getDateWithMonthName($data['Fecha']);
          $data['DateFormat'] = $date;

          $data['dayStatus'] = $day_status;
          $data['paymentReminders'] = $payment_reminders;

          $cost = '$' . number_format($row['CostoTotal'], 2);
          $data['CostoTotalFormat'] = $cost;

          $data['TelefonoFormat'] = formatPhoneNumber($data['Telefono']);
          $data['DepositoFormat'] = '$' . number_format($data['Deposito'], 2);
          $data['AnticipoFormat'] = '$' . number_format($data['Anticipo'], 2);

          array_push($reservations, array(
            'id'        => $row['idReservacion'],
            'date'      => $row['Fecha'],
            'dateFind'  => $row['DateFind'],
            'title'     => $row['NombreCompleto'],
            'data'      => $data
          ));
        endwhile;
      }

      # Reminders :::::::::::::::::::::::::::::::::::::::::::::::::::::
      $query_reminders = "SELECT
          idEventoCalendario,
          idusuario,
          idNegocio,
          Titulo,
          Descripcion,
          FechaDesde,
          FechaHasta,
          DATE_FORMAT(FechaDesde, '%Y-%m') AS DateFind1,
          DATE_FORMAT(FechaHasta, '%Y-%m') AS DateFind2,
          DATE_FORMAT(FechaDesde, '%d/%m/%Y') AS DateDesdeFormat,
          DATE_FORMAT(FechaHasta, '%d/%m/%Y') AS DateHastaFormat,
          Color
        FROM eventos_calendario
        WHERE
          idUsuario = $user_id AND
          idNegocio = $business_id
      ";

      $query_reminders_result = mysqli_query($mysqli, $query_reminders);
      $num_reminders          = mysqli_num_rows($query_reminders_result);

      if ($num_reminders) {
        while ($row = mysqli_fetch_array($query_reminders_result)) :
          $dates = getDatesFromRange($row['FechaDesde'], $row['FechaHasta']);

          $event_reminders = getEventReminders(
            $user_id,
            $row['idEventoCalendario']
          );

          array_push($reminders, array(
            'eventCalendarId'         => $row['idEventoCalendario'],
            'businessId'              => $row['idNegocio'],
            'title'                   => $row['Titulo'],
            'description'             => $row['Descripcion'],
            'color'                   => $row['Color'],
            'dateDesde'               => $row['FechaDesde'],
            'dateHasta'               => $row['FechaHasta'],
            'dateDesdeFormat'         => $row['DateDesdeFormat'],
            'dateHastaFormat'         => $row['DateHastaFormat'],
            'dateFind1'               => $row['DateFind1'],
            'dateFind2'               => $row['DateFind2'],
            'dates'                   => $dates,
            'eventReminders'          => $event_reminders
          ));
        endwhile;
      }

      # Date status :::::::::::::::::::::::::::::::::::::::::::::::::::::
      $query_dates = "SELECT
          idCalendarioFecha,
          idUsuario,
          idNegocio,
          Fecha,
          DATE_FORMAT(Fecha, '%Y-%m') AS DateFind,
          DateStatus
        FROM calendario_fechas
        WHERE
          idUsuario = $user_id AND
          idNegocio = $business_id
      ";

      $query_dates_result = mysqli_query($mysqli, $query_dates);
      $num_dates          = mysqli_num_rows($query_dates_result);

      if ($num_dates) {
        while ($row = mysqli_fetch_array($query_dates_result)) {
          $status;

          if ($row['DateStatus'] == 'Libre')        $status = 'free';
          if ($row['DateStatus'] == 'Con espacios') $status = 'with-spaces';
          if ($row['DateStatus'] == 'Ocupado')      $status = 'occupied';

          array_push($date_status, array(
            'id'        => $row['idReservacion'],
            'date'      => $row['Fecha'],
            'dateFind'  => $row['DateFind'],
            'status'    => $status
          ));
        }
      }

      # STATUS DEL CALENDARIO
      $query          = "SELECT MostrarCalendario FROM salones WHERE idSalon = $business_id LIMIT 1";
      $query_result   = mysqli_query($mysqli, $query);
      $business_data  = mysqli_fetch_array($query_result);
      $show_calendar  = $business_data['MostrarCalendario'];

      $response = array(
        'status'        => 'success',
        'reservations'  => $reservations,
        'reminders'     => $reminders,
        'dateStatus'    => $date_status,
        'showCalendar'  => $show_calendar === 'Si' ? true : false
      );
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'unlock-day':
    $parameters   = $json['parameters'];

    $user_id      = $parameters['userId'];
    $business_id  = $parameters['businessId'];
    $date         = $parameters['date'];
    $date_format  = getDateWithMonthName($date);

    $result = addDateStatus(
      $user_id,
      $business_id,
      $date,
      'Libre'
    );

    if ($result) $response = array(
      'status'  => 'success',
      'title'   => '¡Fecha habilitada!',
      'message' => 'La fecha "' . $date_format . '" se habilitó correctamente.'
    );
    break;

  case 'change-date-status':
    $parameters   = $json['parameters'];

    $user_id      = $parameters['userId'];
    $business_id  = $parameters['businessId'];
    $date         = $parameters['date'];
    $date_status  = $parameters['dateStatus'];
    $date_format  = getDateWithMonthName($date);

    $status       = 'Libre';

    if ($date_status == 'free')         $status = 'Libre';
    if ($date_status == 'with-spaces')  $status = 'Con espacios';
    if ($date_status == 'occupied')     $status = 'Ocupado';

    $result = addDateStatus(
      $user_id,
      $business_id,
      $date,
      $status
    );

    if ($result) $response = array(
      'status'  => 'success',
      'title'   => '¡Estatus cambiado!',
      'message' => 'El estatus se cambió correctamente.'
    );
    break;

  case 'change_calendar_status':
    $parameters         = $json['parameters'];
    $business_id        = $parameters['businessId'];
    $calendar_calendar  = $parameters['calendarStatus'] == true ? 'No' : 'Si';

    $query = "UPDATE salones SET
          MostrarCalendario = '$calendar_calendar'
        WHERE idSalon = $business_id
      ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) $response = array(
      'status' => 'error',
      'title' => '¡Error!',
      'message' => '¡Error!, Intentelo nuevamente.'
    );

    if ($query_result) $response = array(
      'status' => 'success'
    );
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
exit();
