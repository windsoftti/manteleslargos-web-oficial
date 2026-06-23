<?php
include 'inc/session-proveedor.php';
$meta_title = 'Calendario de eventos';

$page_slug = 'calendario-de-eventos';
include 'inc/verify-user-permissions.php';
?>

<!doctype html>
<html lang="es">

<head>
  <?php include 'inc/meta-tags.php'; ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap4-datetimepicker@5.2.3/build/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="plugins/custom/cs-calendar-v2/cs-calendar.css">
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
              <div class="col-md-12 text-center">
                <div class="card">
                  <h2 class="mb-2 mt-2 text-heading fs-22 lh-15">Calendario de eventos</h2>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="cs-calendar"></div>
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
  <script src='plugins/custom/cs-calendar-v2/cs-calendar.js'></script>
  <script src="js/functions.js"></script>

  <?php if ($session_user_plan === 'Free') : ?>
    <script src="main/events-calendar/events-calendar.js"></script>
  <?php endif; ?>

  <?php if ($session_user_plan === 'Básico') : ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js" integrity="sha512-LGXaggshOkD/at6PFNcp2V2unf9LzFq6LE+sChH7ceMTDP0g2kn6Vxwgg7wkPP7AAtX+lmPqPdxB47A0Nz0cMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap4-datetimepicker@5.2.3/src/js/bootstrap-datetimepicker.min.js"></script>

    <script src="main/payment-recordatory/payment-recordatory.js"></script>
    <script src="main/events-calendar/events-reminder.js"></script>
    <script src="main/events-calendar/bsc-events-calendar.js"></script>

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

  <?php include 'inc/svg.php'; ?>
</body>

</html>