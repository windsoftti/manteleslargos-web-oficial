<?php
include 'inc/session-proveedor.php';
$meta_title = 'Calendario de eventos';

$page_slug = 'calendario-de-eventos';
include 'inc/verify-user-permissions.php';

$calendar_data = getCalendarData(null, date('Y'));
$today_events = getCalendarTodayReservations();
?>

<!doctype html>
<html lang="es">

<head>
  <?php include 'inc/meta-tags.php'; ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap4-datetimepicker@5.2.3/build/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="plugins/calendar/calendar.css">

  <style>
    .button-cover {
      height: 100px;
      margin: 20px;
      background-color: #fff;
      box-shadow: 0 10px 20px -8px #c5d6d6;
      border-radius: 4px;
    }

    .button-cover:before {
      counter-increment: button-counter;
      content: counter(button-counter);
      position: absolute;
      right: 0;
      bottom: 0;
      color: #d7e3e3;
      font-size: 12px;
      line-height: 1;
      padding: 5px;
    }

    .button-cover,
    .knobs,
    .layer {
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
    }

    .button {
      position: relative;
      /* top: 50%; */
      width: 74px;
      height: 36px;
      /* margin: 0 auto 0 auto; */
      overflow: hidden;
    }

    .button.r,
    .button.r .layer {
      border-radius: 100px;
    }

    .button.b2 {
      border-radius: 2px;
    }

    .checkbox {
      position: relative;
      width: 100%;
      height: 100%;
      padding: 0;
      margin: 0;
      opacity: 0;
      cursor: pointer;
      z-index: 3;
    }

    .knobs {
      z-index: 2;
    }

    .layer {
      width: 100%;
      /* background-color: #ebf7fc; */
      background-color: #fcebeb;
      transition: 0.3s ease all;
      z-index: 1;
    }

    /* Button 1 */
    .switch-button .knobs:before {
      content: "NO";
      position: absolute;
      top: 4px;
      left: 4px;
      width: 30px;
      height: 30px;
      color: #fff;
      font-size: 10px;
      font-weight: bold;
      text-align: center;
      line-height: 1;
      padding: 9px 4px;
      /* background-color: #03a9f4; */
      background-color: #f44336;
      border-radius: 50%;
      transition: 0.3s cubic-bezier(0.18, 0.89, 0.35, 1.15) all;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .switch-button .checkbox:checked+.knobs:before {
      content: "SI";
      left: 42px;
      /* background-color: #f44336; */
      background-color: #03a9f4;
    }

    .switch-button .checkbox:checked~.layer {
      /* background-color: #fcebeb; */
      background-color: #ebf7fc;
    }

    .switch-button .knobs,
    .switch-button .knobs:before,
    .switch-button .layer {
      transition: 0.3s ease all;
    }
  </style>
</head>

<body>
  <div class="wrapper dashboard-wrapper">
    <div class="d-flex flex-wrap flex-xl-nowrap">
      <div class="db-sidebar bg-white" id="custom-sidebar">
        <nav class="navbar navbar-expand-xl navbar-light d-block px-0 header-sticky dashboard-nav py-0">
          <div class="sticky-area shadow-xs-1 py-3">
            <!-- Mobile header -->
            <?php include 'inc/mobile-header.php'; ?>

            <!-- Sidebar -->
            <?php include 'inc/sidebar.php' ?>
          </div>
        </nav>
      </div>

      <div class="page-content">
        <!-- Desktop header -->
        <?php include 'inc/header.php'; ?>

        <main id="content" class="bg-gray-01">
          <?php if ($session_user_plan === 'Básico') : ?>
            <?php include 'modals/seller-dashboard.php'; ?>
          <?php endif; ?>

          <div class="p-3">
            <div class="row">
              <div class="col-12 col-md-6 col-lg-8">
                <div class="card">
                  <div class="card-body">
                    <div id="calendar"></div>
                  </div>

                  <div class="card-footer">
                    <div class="row">
                      <div class="col-12 col-md-6">
                        <div id="today-events-info-container">
                          <?php if ($today_events['num_reservations']) : ?>
                            <p class="m-0">
                              <i class="fa fa-calendar-alt"></i> Hoy tienes <?= $today_events['num_reservations']; ?> <?= $today_events['num_reservations'] > 1 ? 'eventos agendados' : 'evento agendado'; ?>
                            </p>

                            <ul class="p-0 m-0" style="list-style: none;" style="display: none;">
                              <!-- <?= $today_events['reservations']; ?> -->
                            </ul>
                          <?php endif; ?>
                        </div>

                        <div class="mt-2">
                          <a class="text-blue mb-2 btn-add-reservation" href="javascript:void(0)">
                            <i class="fa fa-plus-circle"></i> Agregar nuevo evento
                          </a>

                          <br>

                          <a class="text-blue btn-add-reminder mt-2" href="javascript:void(0)">
                            <i class="fa fa-plus-circle"></i> Agregar nuevo recordatorio
                          </a>
                        </div>
                      </div>

                      <div class="col-12 col-md-6 mt-3 mt-lg-0">
                        <ul id="list-day-status-container" class="pull-right mb-1" style="
                          list-style: none;
                          display: flex;
                          flex-direction: row;
                          padding: 0;
                          gap: 0.5rem;
                          font-size: 0.75rem;
                        ">
                          <li class="d-flex align-items-center mb-1">
                            <span class="d-flex align-items-center justify-content-center mr-1 bg-success rounded-circle" style="height: 1.3rem;width: 1.3rem;">
                              <i class="fa fa-check-circle text-white" style="font-size: 0.8rem;"></i>
                            </span> Disponible
                          </li>

                          <li class="d-flex align-items-center mb-1">
                            <span class="d-flex align-items-center justify-content-center mr-1 bg-warning rounded-circle" style="height: 1.3rem;width: 1.3rem;">
                            </span> Con espacios
                          </li>

                          <li class="d-flex align-items-center mb-1">
                            <span class="d-flex align-items-center justify-content-center mr-1 bg-danger rounded-circle" style="height: 1.3rem;width: 1.3rem;">
                            </span> No disponible
                          </li>
                        </ul>

                        <div>
                          <a id="btn-change-day-status" class="text-blue align-items-center pl-lg-auto" data-toggle="modal" data-target="#modal-change-day-status" href="javascript:void(0)">
                            <i class="fas fa-exchange mr-1"></i> Cambiar status del día
                          </a>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-12 text-right mt-2">
                        <div class="d-flex align-items-center justify-content-end">
                          <label class="m-0 mr-1" for="show-calendar">Mostrar el calendario</label>

                          <div class="button r switch-button">
                            <input id="show-calendar" class="checkbox" value="Si" type="checkbox" <?= $calendar_data['showCalendar'] === 'Si' ? 'checked' : '' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-6 col-lg-4 mt-3 mt-lg-0">
                <div class="card" style="overflow: hidden;">
                  <div class="card-body custom-navtabs">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="navId">
                      <li class="nav-item">
                        <a href="#tab-reservations" data-toggle="pill" class="nav-link active">Detalles de eventos</a>
                      </li>

                      <li class="nav-item">
                        <a href="#tab-reminders" data-toggle="pill" class="nav-link">Recordatorios</a>
                      </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                      <div class="tab-pane fade show active" id="tab-reservations" role="tabpanel">
                        Selecciona una fecha para ver los eventos del día.
                      </div>

                      <div class="tab-pane fade" id="tab-reminders" role="tabpanel">
                        Selecciona una fecha para ver los recordatorios del día.
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>

  <!-- PAGE LOADING -->
  <?php include 'inc/page-loading.php' ?>

  <!-- REQUIRED SCRIPTS -->
  <?php include 'inc/required-scripts.php'; ?>
  <script src='plugins/calendar/calendar.js'></script>
  <script src="js/functions.js"></script>

  <script>
    var calendar;
  </script>

  <?php if ($session_user_plan === 'Free') : ?>
    <script src="main/events-calendar/events-calendar-2.js"></script>
  <?php endif; ?>

  <?php if ($session_user_plan === 'Básico') : ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js" integrity="sha512-LGXaggshOkD/at6PFNcp2V2unf9LzFq6LE+sChH7ceMTDP0g2kn6Vxwgg7wkPP7AAtX+lmPqPdxB47A0Nz0cMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap4-datetimepicker@5.2.3/src/js/bootstrap-datetimepicker.min.js"></script>

    <script src="main/payment-recordatory/payment-recordatory.js"></script>
    <script src="main/events-calendar/events-reminder.js"></script>
    <script src="main/events-calendar/bsc-events-calendar-2.js"></script>
    <!-- 
    <script src="main/events-calendar/bsc-events-calendar.js"></script> -->
    <!--  -->

    <script>
      initBDatePicker('.reminder-datepicker');

      $('#date').datetimepicker({
        format: 'DD/MM/YYYY',
        locale: 'es-es',
        icons: {
          time: "fal fa-clock",
          date: "fal fa-calendar",
          up: "fal fa-arrow-up",
          down: "fal fa-arrow-down",
          previous: "fal fa-chevron-left",
          next: "fal fa-chevron-right",
          today: "fal fa-clock",
          clear: "fal fa-trash",
          close: "fal fa-times"
        }
      });

      $('.time').datetimepicker({
        format: 'hh:mm a',
        locale: 'es-es',
        icons: {
          time: "fal fa-clock",
          date: "fal fa-calendar",
          up: "fal fa-arrow-up",
          down: "fal fa-arrow-down",
          previous: "fal fa-chevron-left",
          next: "fal fa-chevron-right",
          today: "fal fa-clock",
          clear: "fal fa-trash",
          close: "fal fa-times"
        }
      });
    </script>
  <?php endif; ?>

  <script>
    calendar = new Calendar({
      id: 'calendar',
      events: <?= json_encode($calendar_data['reservations']); ?>,
      reminders: <?= json_encode($calendar_data['reminders']); ?>,
      dateStatus: <?= json_encode($calendar_data['dateStatus']); ?>,
      onChangeYear: year => getNewCalendarData(year),
      onPressDate: data => renderCalendarDateData(data)
    });

    calendar.createCalendar();

    const todayData = calendar.getCalendarDataByDate('<?= date('Y-m-d'); ?>');
    if (todayData.events) renderCalendarDateData(todayData);

    const getNewCalendarData = async year => {
      showPageLoading();

      const parameters = new FormData();
      parameters.append('action', 'get_calendar_data');
      parameters.append('year', year);

      const response = await fetchData({
        place: 'events_calendar',
        data: parameters
      });

      hidePageLoading();

      if (response.message) showSweetAlert({
        icon: response.status,
        title: response.message
      });

      if (response.status === 'success') {
        calendar.setCalendarData({
          events: response.calendar.reservations,
          reminders: response.calendar.reminders,
          dateStatus: response.calendar.dateStatus
        });

        const dataSelected = calendar.getCalendarDataByDate(selectedDate);
        renderCalendarDateData(dataSelected);
      }
    };

    $('#show-calendar').on('click', async function() {
      const isChecked = $(this).is(':checked');
      let showCalendar = 'Si';

      if (!isChecked) {
        const alertResponse = await showSweetConfirm({
          title: '¡Está seguro de deshabilitar su calendario?',
          subtitle: 'No podrá recibir cotizaciones directas para administrar en su plataforma'
        });

        if (!alertResponse) {
          $(this).prop('checked', true);
          return;
        }

        showCalendar = 'No';
      }

      showPageLoading();

      const parameters = new FormData();

      parameters.append('action', 'show-hide-calendar');
      parameters.append('showCalendar', showCalendar);

      const response = await fetchData({
        place: 'events_calendar',
        data: parameters
      });

      hidePageLoading();

      if (response.message) showSweetAlert({
        icon: response.state,
        title: response.message
      });

      if (response.state === 'error') $(this).prop('checked', true);
    });
  </script>

  <?php include 'inc/svg.php'; ?>
</body>

</html>