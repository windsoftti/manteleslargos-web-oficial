<?php
date_default_timezone_set('America/Mexico_City');
include '../session.php';
include '../../inc/functions.inc.php';
include '../lib/pagination.php';

$action = isset($_POST['action']) && $_POST['action'] !== '' ? $_POST['action'] : '';

function parseDate($initial_date)
{
  $ant_date       = $initial_date;
  $date_replace   = str_replace('/', '-', $ant_date);
  $date           = date('Y-m-d', strtotime($date_replace));

  return $date;
}

function getDatesFromRange($start, $end, $format = 'Y-m-d')
{
  $array = array();
  $interval = new DateInterval('P1D');

  $realEnd = new DateTime($end);
  $realEnd->add($interval);

  $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

  foreach ($period as $date) {
    $array[] = $date->format($format);
  }

  return $array;
}

switch ($action) {
  case 'list_reservations':
    $user_id      = $_SESSION['session_user_id'];
    //$business_id  = cleanStr($_POST['businessId']);
    $business_id  = $_SESSION['session_business_id'];

    $query = "SELECT
        idReservacion,
        idUsuario,
        idNegocio,
        idPaquete,
        idTipoEvento,
        NombreCompleto,
        Correo,
        Telefono,
        Fecha,
        DATE_FORMAT(HoraInicio, '%h:%i %p') AS HoraInicio,
        DATE_FORMAT(HoraFinal, '%h:%i %p') AS HoraFinal,
        NPersonas,
        Extras,
        CostoTotal,
        Deposito,
        Anticipo
      FROM reservaciones
      WHERE
        idUsuario = '$user_id' AND
        idNegocio = '$business_id'
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    $events = array();

    if (!$num_rows) $response = array(
      'status'  => 'empty',
    );

    if ($num_rows) {
      while ($row = mysqli_fetch_array($query_result)) {
        $data = base64_encode(json_encode($row));

        array_push($events, array(
          'id'      => $row['idReservacion'],
          'date'    => $row['Fecha'],
          'data'    => $data,
          'title'   => $row['NombreCompleto']
        ));
      }
    }

    $query = "SELECT
        idCalendarioFecha,
        idUsuario,
        idNegocio,
        Fecha,
        DateStatus
      FROM calendario_fechas
      WHERE
        idUsuario = $user_id AND
        idNegocio = $business_id
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if (!$num_rows) $response = array(
      'status'  => 'success',
      'events'  => $events
    );

    if ($num_rows) {
      $dates = array();

      while ($row = mysqli_fetch_array($query_result)) {
        $date_status;

        if ($row['DateStatus'] == 'Libre')        $date_status = 'free';
        if ($row['DateStatus'] == 'Con espacios') $date_status = 'with-spaces';
        if ($row['DateStatus'] == 'Ocupado')      $date_status = 'occupied';

        array_push($dates, array(
          'id'      => $row['idReservacion'],
          'date'    => $row['Fecha'],
          'status'  => $date_status
        ));
      }

      $response = array(
        'status'  => 'success',
        'events'  => $events,
        'dates'   => $dates
      );
    }
    break;

  case 'add_reservation':
    $id_user_create = $_SESSION['session_user_id'];

    //$business       = cleanStr($_POST['business']);
    $business       = $_SESSION['session_business_id'];
    $package        = cleanStr($_POST['package']);
    $event_type     = cleanStr($_POST['eventType']);
    $name           = cleanStr($_POST['name']);
    $email          = cleanStr($_POST['email']);
    $phone          = cleanStr($_POST['phone']);
    $n_persons      = cleanStr($_POST['NPersons']);
    $extras         = cleanStr($_POST['extras']);
    $total_cost     = cleanStr($_POST['totalCost']);
    $deposit        = $_POST['deposit'] ? cleanStr($_POST['deposit']) : '0';
    $advance        = $_POST['advance'] ? cleanStr($_POST['advance']) : '0';
    $status         = cleanStr($_POST['status']);

    //$date           = cleanStr($_POST['date']);
    #$start_time     = (isset($_POST['startTime']) && $_POST['startTime'] != '') ? date("H:i:s", strtotime(cleanStr($_POST['startTime']))) : '';
    #$end_time       = (isset($_POST['endTime']) && $_POST['endTime'] != '') ? date("H:i:s", strtotime(cleanStr($_POST['endTime']))) : '';

    $start_time     = (isset($_POST['startTime']) && $_POST['startTime'] != '') ? "'" . date("H:i:s", strtotime(cleanStr($_POST['startTime']))) . "'" : "NULL";
    $end_time       = (isset($_POST['endTime']) && $_POST['endTime'] != '') ? "'" . date("H:i:s", strtotime(cleanStr($_POST['endTime']))) . "'" : "NULL";

    $ant_date       = $_POST['date'];
    $date_replace   = str_replace('/', '-', $ant_date);
    $date           = date('Y-m-d', strtotime($date_replace));

    $rc_percentages = $_POST['RCPercentages'];
    $rc_dates       = $_POST['RCDates'];

    $today_date     = date('Y-m-d');

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
        '$advance',
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

      if ($advance) addDateAdvance(
        $id_user_create,
        $reservation_id,
        $advance
      );

      $year = explode('-', $date);
      $year = $year[0];

      $today_events_content = '';
      $today_events = getCalendarTodayReservations();

      if ($today_events['num_reservations']) $today_events_content = '
          <p>
            <i class="fa fa-calendar-alt"></i> Hoy tienes ' . $today_events['num_reservations'] . ' ' . ($today_events['num_reservations'] > 1 ? 'eventos agendados' : 'evento agendado') . '
          </p>

          <ul class="p-0 m-0" style="list-style: none;">
            ' . $today_events['reservations'] . '
          </ul>
      ';

      $response = array(
        'status' => 'success',
        'title' => '¡Datos guardados!',
        'message' => 'El evento se agregó correctamente.',
        'calendar' => getCalendarData(null, $year),
        'year' => $year,
        'todayEvents' => $today_events_content
      );
    }
    break;

  case 'edit_reservation':
    $id_user_create     = $_SESSION['session_user_id'];
    $reservation_id  = cleanStr($_POST['reservationId']);
    $reservation_data   = getReservationData($reservation_id);

    //$business       = cleanStr($_POST['business']);
    $business       = $_SESSION['session_business_id'];
    $package        = cleanStr($_POST['package']);
    $event_type     = cleanStr($_POST['eventType']);
    $name           = cleanStr($_POST['name']);
    $email          = cleanStr($_POST['email']);
    $phone          = cleanStr($_POST['phone']);
    $n_persons      = cleanStr($_POST['NPersons']);
    $extras         = cleanStr($_POST['extras']);
    $total_cost     = cleanStr($_POST['totalCost']);
    $deposit        = $_POST['deposit'] ? cleanStr($_POST['deposit']) : '0';
    $advance        = $_POST['advance'] ? cleanStr($_POST['advance']) : '0';
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

    $query = "UPDATE reservaciones SET
        idNegocio       = '$business',
        idPaquete       = '$package',
        idTipoEvento    = '$event_type',
        NombreCompleto  = '$name',
        Correo          = '$email',
        Telefono        = '$phone',
        Fecha           = '$date',
        HoraInicio      = $start_time,
        HoraFinal       = $end_time,
        NPersonas       = '$n_persons',
        Extras          = '$extras',
        CostoTotal      = '$total_cost',
        Deposito        = '$deposit'
      WHERE
        idReservacion  = '$reservation_id' AND
        idUsuario           = '$id_user_create'
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) $response = array(
      'status' => 'error',
      'title' => '¡Error!',
      'message' => '¡Error!, Intentelo nuevamente.'
    );

    if ($query_result) {
      $rc_payments = checkArray($rc_percentages);

      $query_reminers = "DELETE FROM recordatorio_pagos WHERE
          idUsuario           = $id_user_create AND
          idReservacion  = $reservation_id
      ";

      mysqli_query($mysqli, $query_reminers);

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

      if ($reservation_data['Fecha'] !== $date) checkIfCalendarDateIsEmpty(
        $reservation_data['Fecha'],
        $id_user_create,
        $business
      );

      $year = explode('-', $date);
      $year = $year[0];

      $today_events_content = '';
      $today_events = getCalendarTodayReservations();

      if ($today_events['num_reservations']) $today_events_content = '
          <p>
            <i class="fa fa-calendar-alt"></i> Hoy tienes ' . $today_events['num_reservations'] . ' ' . ($today_events['num_reservations'] > 1 ? 'eventos agendados' : 'evento agendado') . '
          </p>

          <ul class="p-0 m-0" style="list-style: none;">
            ' . $today_events['reservations'] . '
          </ul>
      ';

      $response = array(
        'status' => 'success',
        'title' => '¡Datos guardados!',
        'message' => 'El evento se actualizó correctamente.',
        'calendar' => getCalendarData(null, $year),
        'year' => $year,
        'todayEvents' => $today_events_content
      );
    }
    break;

  case 'change_day_status':
    $user_id      = $_SESSION['session_user_id'];
    $business_id  = $_SESSION['session_business_id'];
    $date         = cleanStr($_POST['date']);
    $status       = cleanStr($_POST['status']);
    $year         = cleanStr($_POST['year']);

    $result = addDateStatus(
      $user_id,
      $business_id,
      $date,
      $status
    );

    if (!$result) $response = array(
      'status' => 'error',
      'message' => '¡Error!, Intentelo nuevamente.'
    );

    if ($result) $response = array(
      'status'    => 'success',
      'message'   => 'El estatus del día se cambió correctamente.',
      'calendar'  => getCalendarData(null, $year),
    );
    break;

  case 'block_date':
    $id_user_create = $_SESSION['session_user_id'];
    $ant_date       = $_POST['date'];
    $date_replace   = str_replace('/', '-', $ant_date);
    $date           = date('Y-m-d', strtotime($date_replace));

    //$business       = cleanStr($_POST['business']);
    $business       = $_SESSION['session_business_id'];

    /* $result = addDateStatus(
      $id_user_create,
      null,
      'Ocupado',
      $date
    ); */

    $result = addDateStatus(
      $id_user_create,
      $business,
      $date,
      'Ocupado'
    );

    if (!$result) $response = array(
      'status' => 'error',
      'message' => '¡Error!, Intentelo nuevamente.'
    );

    if ($result) $response = array(
      'status'  => 'success',
      'message' => 'El día se inhabilitó correctamente.'
    );
    break;

  case 'unlock_date':
    $id_user_create = $_SESSION['session_user_id'];
    $ant_date       = $_POST['date'];
    $date_replace   = str_replace('/', '-', $ant_date);
    $date           = date('Y-m-d', strtotime($date_replace));

    //$business       = cleanStr($_POST['business']);
    $business       = $_SESSION['session_business_id'];

    $result = addDateStatus(
      $id_user_create,
      $business,
      $date,
      'Libre'
    );

    if (!$result) $response = array(
      'status' => 'error',
      'message' => '¡Error!, Intentelo nuevamente.'
    );

    if ($result) $response = array(
      'status'  => 'success',
      'message' => 'La fecha se habilitó correctamente.'
    );
    break;

  case 'list_payment_recordatory':
    $id_user_create = $_SESSION['session_user_id'];
    $reservation_id = cleanStr($_POST['reservationId']);

    $query = "SELECT
        idRecordatorioPago,
        idUsuario,
        idReservacion,
        Porcentaje,
        DATE_FORMAT(Fecha, '%d-%m-Y') AS Fecha
      FROM recordatorio_pagos
      WHERE
        idUsuario           = $id_user_create AND
        idReservacion  = $reservation_id
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if (!$num_rows) $response = array(
      'status' => 'empty'
    );

    if ($num_rows) {
      $reminders = array();

      while ($row = mysqli_fetch_array($query_result)) {
        array_push($reminders, array(
          'percentage'  => $row['Porcentaje'],
          'date'        => $row['Fecha']
        ));
      }

      $response = array(
        'status'    => 'success',
        'reminders' => $reminders
      );
    }
    break;

  case 'add_event_calendar':
    $id_user_create = $_SESSION['session_user_id'];
    $business_id    = $_SESSION['session_business_id'];
    $title          = cleanStr($_POST['reminderTitle']);
    $color          = cleanStr($_POST['reminderColor']);
    $description    = cleanStr($_POST['reminderDescription']);
    $reminder_desde = parseDate($_POST['reminderDesde']);
    $reminder_hasta = parseDate($_POST['reminderHasta']);

    $reminder_quantitys     = $_POST['quantitys'];
    $reminder_periodicitys  = $_POST['periodicitys'];

    $check_reminders = checkArray($reminder_quantitys);

    if (!$check_reminders) {
      $response = array(
        'status' => 'warning',
        'title' => '¡Cuidado!',
        'message' => 'Tienes que agregar tus recordatorios.'
      );

      echo json_encode($response);
      mysqli_close($mysqli);
      exit();
    }

    $today_date = date('Y-m-d');

    if (strtotime($reminder_desde) < strtotime($today_date)) {
      $response = array(
        'status' => 'warning',
        'title' => '¡Cuidado!',
        'message' => '¡La fecha "desde" que ha seleccionado ya no esta disponible.'
      );

      echo json_encode($response);
      mysqli_close($mysqli);
      exit();
    }

    if (strtotime($reminder_hasta) < strtotime($reminder_desde)) {
      $response = array(
        'status' => 'warning',
        'title' => '¡Cuidado!',
        'message' => '¡La fecha "hasta" debe de ser mayor a la fecha "desde".'
      );

      echo json_encode($response);
      mysqli_close($mysqli);
      exit();
    }

    $query_add_event_calendar = "INSERT INTO eventos_calendario (
        idUsuario,
        idNegocio,
        Titulo,
        Descripcion,
        FechaDesde,
        FechaHasta,
        Color
      ) VALUES (
        $id_user_create,
        $business_id,
        '$title',
        '$description',
        '$reminder_desde',
        '$reminder_hasta',
        '$color'
      )
    ";

    $query_add_event_calendar_result = mysqli_query($mysqli, $query_add_event_calendar);

    if (!$query_add_event_calendar_result) $response = array(
      'status' => 'error',
      'title' => '¡Error!',
      'message' => '¡Error inesperado!, Intentalo nuevamente.'
    );

    if ($query_add_event_calendar_result) {
      $event_calendar_id = mysqli_insert_id($mysqli);

      foreach ($reminder_quantitys as $key => $value) {
        $reminder_quantity    = cleanStr($value);
        $reminder_periodicity = cleanStr($reminder_periodicitys[$key]);
        $first_date           = generateReminderFirstDate(
          $reminder_quantity,
          $reminder_periodicity,
          $reminder_desde
        );

        $query_add_reminder = "INSERT INTO eventos_calendario_recordatorios (
            idEventoCalendario,
            idUsuario,
            Cantidad,
            Periodicidad,
            FechaInicial
          ) VALUES (
            $event_calendar_id,
            $id_user_create,
            $reminder_quantity,
            '$reminder_periodicity',
            '$first_date'
          )
        ";

        mysqli_query($mysqli, $query_add_reminder);
      }

      $year = explode('-', $reminder_desde);
      $year = $year[0];

      $response = array(
        'status' => 'success',
        'title' => '¡Datos guardados!',
        'message' => 'El recordatorio se agregó correctamente.',
        'calendar' => getCalendarData(null, $year),
        'year' => $year
      );
    }
    break;

  case 'edit_event_calendar':
    $id_user_create = $_SESSION['session_user_id'];
    $business_id    = $_SESSION['session_business_id'];
    $event_calendar_id    = cleanStr($_POST['eventCalendarId']);
    $title          = cleanStr($_POST['reminderTitle']);
    $color          = cleanStr($_POST['reminderColor']);
    $description    = cleanStr($_POST['reminderDescription']);
    $reminder_desde = parseDate($_POST['reminderDesde']);
    $reminder_hasta = parseDate($_POST['reminderHasta']);

    $reminder_quantitys     = $_POST['quantitys'];
    $reminder_periodicitys  = $_POST['periodicitys'];

    $today_date = date('Y-m-d');

    if (strtotime($reminder_desde) < strtotime($today_date)) {
      $response = array(
        'status' => 'warning',
        'title' => '¡Cuidado!',
        'message' => '¡La fecha "desde" que ha seleccionado ya no esta disponible.'
      );

      echo json_encode($response);
      mysqli_close($mysqli);
      exit();
    }

    if (strtotime($reminder_hasta) < strtotime($reminder_desde)) {
      $response = array(
        'status' => 'warning',
        'title' => '¡Cuidado!',
        'message' => '¡La fecha "hasta" debe de ser mayor a la fecha "desde".'
      );

      echo json_encode($response);
      mysqli_close($mysqli);
      exit();
    }

    $query_update_reminder = "UPDATE eventos_calendario SET
          Titulo      = '$title',
          Descripcion = '$description',
          FechaDesde  = '$reminder_desde',
          FechaHasta  = '$reminder_hasta',
          Color       = '$color'
        WHERE
          idEventoCalendario  = $event_calendar_id    AND
          idUsuario           = $id_user_create AND
          idNegocio           = $business_id
    ";

    $query_update_reminder_result = mysqli_query($mysqli, $query_update_reminder);

    if (!$query_update_reminder_result) $response = array(
      'status' => 'error',
      'title' => '¡Error!',
      'message' => '¡Error inesperado!, Intentalo nuevamente.'
    );

    if ($query_update_reminder_result) {
      $query = "DELETE FROM eventos_calendario_recordatorios WHERE idEventoCalendario = $event_calendar_id";
      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) {
        foreach ($reminder_quantitys as $key => $value) {
          $reminder_quantity    = cleanStr($value);
          $reminder_periodicity = cleanStr($reminder_periodicitys[$key]);
          $first_date           = generateReminderFirstDate(
            $reminder_quantity,
            $reminder_periodicity,
            $reminder_desde
          );

          $query_add_reminder = "INSERT INTO eventos_calendario_recordatorios (
              idEventoCalendario,
              idUsuario,
              Cantidad,
              Periodicidad,
              FechaInicial
            ) VALUES (
              $event_calendar_id,
              $id_user_create,
              $reminder_quantity,
              '$reminder_periodicity',
              '$first_date'
            )
          ";

          mysqli_query($mysqli, $query_add_reminder);
        }
      }

      $year = explode('-', $reminder_desde);
      $year = $year[0];

      $response = array(
        'status' => 'success',
        'title' => '¡Datos guardados!',
        'message' => 'El recordatorio se actualizó correctamente.',
        'calendar' => getCalendarData(null, $year),
        'year' => $year
      );
    }
    break;

  case 'load_events_calendar':
    $id_user_create = $_SESSION['session_user_id'];
    $business_id    = $_SESSION['session_business_id'];

    $query_load_events_calendar = "SELECT
        idEventoCalendario,
        idusuario,
        idNegocio,
        Titulo,
        Descripcion,
        FechaDesde,
        FechaHasta,
        Color
      FROM eventos_calendario
      WHERE
        idUsuario = $id_user_create AND
        idNegocio = $business_id
    ";

    $query_load_events_calendar_result  = mysqli_query($mysqli, $query_load_events_calendar);
    $num_rows_reminders           = mysqli_num_rows($query_load_events_calendar_result);

    if (!$num_rows_reminders) $response = array('status' => 'empty');

    if ($num_rows_reminders) {
      $reminders = array();

      while ($row = mysqli_fetch_array($query_load_events_calendar_result)) {
        $dates = getDatesFromRange($row['FechaDesde'], $row['FechaHasta']);

        array_push($reminders, array(
          'eventCalendarId'         => $row['idEventoCalendario'],
          'businessId'              => $row['idNegocio'],
          'title'                   => $row['Titulo'],
          'description'             => $row['Descripcion'],
          'color'                   => $row['Color'],
          'dateDesde'               => $row['FechaDesde'],
          'dateHasta'               => $row['FechaHasta'],
          'dates'                   => $dates
        ));
      }

      $response = array(
        'status'    => 'success',
        'reminders' => $reminders
      );
    }
    break;

  case 'list_events_calendar_reminders':
    $event_calendar_id  = cleanStr($_POST['eventCalendarId']);
    $id_user_create     = $_SESSION['session_user_id'];

    $query = "SELECT
        idEventoCalendarioRecordatorio,
        idUsuario,
        idEventoCalendario,
        Cantidad,
        Periodicidad
      FROM eventos_calendario_recordatorios
      WHERE
        idUsuario           = $id_user_create AND
        idEventoCalendario  = $event_calendar_id
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if (!$num_rows) $response = array(
      'status' => 'empty'
    );

    if ($num_rows) {
      $reminders = array();

      while ($row = mysqli_fetch_array($query_result)) {
        array_push($reminders, array(
          'quantity'    => $row['Cantidad'],
          'periodicity' => $row['Periodicidad']
        ));
      }

      $response = array(
        'status' => 'success',
        'reminders' => $reminders
      );
    }
    break;

  case 'delete_reservation':
    $user_id            = $_SESSION['session_user_id'];
    $business_id        = $_SESSION['session_business_id'];
    $reservation_id     = cleanStr($_POST['reservationId']);
    $year               = cleanStr($_POST['year']);
    $reservation_data   = getReservationData($reservation_id);

    $query = "DELETE FROM reservaciones WHERE
        idReservacion = $reservation_id AND
        idNegocio     = $business_id    AND
        idUsuario     = $user_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) :
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

      checkIfCalendarDateIsEmpty(
        $reservation_data['Fecha'],
        $user_id,
        $business_id
      );
    endif;

    $today_events_content = '';
    $today_events = getCalendarTodayReservations();

    if ($today_events['num_reservations']) $today_events_content = '
        <p>
          <i class="fa fa-calendar-alt"></i> Hoy tienes ' . $today_events['num_reservations'] . ' ' . ($today_events['num_reservations'] > 1 ? 'eventos agendados' : 'evento agendado') . '
        </p>

        <ul class="p-0 m-0" style="list-style: none;">
          ' . $today_events['reservations'] . '
        </ul>
    ';

    $response = array(
      'status'    => 'success',
      'title'     => '¡Reservación eliminada!',
      'message'   => 'La reservación se eliminó correctamente.',
      'calendar'  => getCalendarData(null, $year),
      'todayEvents' => $today_events_content
    );
    break;

  case 'delete_reminder':
    $user_id            = $_SESSION['session_user_id'];
    $business_id        = $_SESSION['session_business_id'];
    $event_calendar_id  = cleanStr($_POST['eventCalendarId']);
    $year               = cleanStr($_POST['year']);

    $query = "DELETE FROM eventos_calendario WHERE
      idEventoCalendario  = $event_calendar_id  AND
      idUsuario           = $user_id            AND
      idNegocio           = $business_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) {
      $query = "DELETE FROM eventos_calendario_recordatorios WHERE idEventoCalendario = $event_calendar_id";
      mysqli_query($mysqli, $query);

      $response = array(
        'status'    => 'success',
        'title'     => '!Recordatorio eliminado¡',
        'message'   => 'El recordatorio se eliminó correctamente',
        'calendar'  => getCalendarData(null, $year),
      );
    }
    break;

  case 'get_calendar_data':
    $year = cleanStr($_POST['year']);
    $calendar_data = getCalendarData(null, $year);

    $response = array(
      'status'    => 'success',
      'calendar'  => $calendar_data,
    );
    break;

  case 'show-hide-calendar':
    $business_id    = $_SESSION['session_business_id'];
    $show_calendar  = cleanStr($_POST['showCalendar']);

    $query = "UPDATE salones SET
        MostrarCalendario = '$show_calendar'
      WHERE idSalon = $business_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) $response = array(
      'state' => 'error',
      'title' => '¡Error!',
      'message' => '¡Error!, Intentelo nuevamente.'
    );

    if ($query_result) $response = array(
      'state' => 'success'
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

//checkDateStatus();

echo json_encode($response);
mysqli_close($mysqli);
