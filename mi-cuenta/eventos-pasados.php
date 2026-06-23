<?php
include 'inc/session-proveedor.php';
$meta_title = 'Eventos pasados';

//$page_slug = 'proximos-eventos';
$page_slug = 'eventos-pasados';
include 'inc/verify-user-permissions.php';
?>

<!doctype html>
<html lang="es">

<head>
  <?php include 'inc/meta-tags.php'; ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap4-datetimepicker@5.2.3/build/css/bootstrap-datetimepicker.min.css">

  <style>
    .custom-radio-button {
      display: flex;
    }

    .custom-radio-button>input {
      display: none;
    }

    .custom-radio-button>label {
      display: flex;
      align-items: center;
      justify-content: flex-start;
    }

    .custom-radio-button>label>div {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 1.5rem;
      width: 1.5rem;
      border: 1px solid transparent;
      margin-right: 1rem;
      border-radius: 0.3rem;
      padding: 0;
      cursor: pointer;
      opacity: 0.2;
      transition: 0.3s;
    }

    .custom-radio-button>label>div>i {
      display: none;
    }

    .custom-radio-button>label>div.green {
      background-color: #45c925;
    }

    .custom-radio-button>label>div.yellow {
      background-color: #c9c925;
    }

    .custom-radio-button>label>div.red {
      background-color: #c92525;
    }

    .custom-radio-button>input:checked+label>div {
      transform: scale(1.2);
      opacity: 1;
    }

    .custom-radio-button>input:checked+label>div>i {
      display: block;
    }
  </style>
</head>

<body class="bg-gray-01">
  <div class="wrapper dashboard-wrapper">
    <div class="d-flex flex-wrap flex-xl-nowrap">
      <div class="db-sidebar bg-white" id="custom-sidebar">
        <nav class="navbar navbar-expand-xl navbar-light d-block px-0 header-sticky dashboard-nav py-0">
          <div class="sticky-area shadow-xs-1 py-3">
            <!-- MOBILE HEADER -->
            <?php include 'inc/mobile-header.php'; ?>

            <!-- SIDEBAR -->
            <?php include 'inc/sidebar.php' ?>
          </div>
        </nav>
      </div>

      <div class="page-content">
        <!-- HEADER -->
        <?php include 'inc/header.php'; ?>

        <main id="content" class="bg-gray-01">
          <div class="p-2">
            <div class="mr-md-auto">
              <h2 class="text-heading fs-22 lh-15">
                <?= $meta_title ?>
              </h2>
            </div>

            <div class="card">
              <div class="card-header">
                <div class="row align-middle">
                  <form id="search-filters-form" class="col-md-9 text-left">
                    <div class="col-md-6">
                      <div class="input-group input-group-lg bg-white border">
                        <div class="input-group-prepend">
                          <button class="btn pr-0 shadow-none" type="button" onclick="searchUpcomingEvents()"><i class="far fa-search"></i></button>
                        </div>
                        <input type="text" id="search-by-upcoming-event" class="form-control bg-transparent border-0 shadow-none text-body" placeholder="Buscar evento" name="searchByupcoming-event" onkeyup="searchUpcomingEvents()">
                        <input name="tipo" value="pasados" type="hidden">
                      </div>
                    </div>
                  </form>

                  <!-- <div class="col-md-3 text-right mt-1">
                    <button class="btn btn-primary btn-add-upcoming-event" data-toggle="modal" data-target="#modal-add-edit-upcoming-event">
                      <i class="fal fa-plus-circle mr-1"></i> Agregar nuevo
                    </button>
                  </div> -->
                </div>

                <?php include 'modals/upcoming-events.php'; ?>
              </div>

              <div class="card-body" id="list-upcoming-events"></div>
            </div>
          </div>

          <!-- PAGE LOADING -->
          <?php include 'inc/page-loading.php' ?>
        </main>
      </div>
    </div>
  </div>

  <!-- REQUIRED SCRIPTS -->
  <?php include 'inc/required-scripts.php'; ?>
  <?php include 'inc/svg.php'; ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js" integrity="sha512-LGXaggshOkD/at6PFNcp2V2unf9LzFq6LE+sChH7ceMTDP0g2kn6Vxwgg7wkPP7AAtX+lmPqPdxB47A0Nz0cMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap4-datetimepicker@5.2.3/src/js/bootstrap-datetimepicker.min.js"></script>

  <script src="js/functions.js"></script>

  <?php if ($session_user_plan === 'Básico') : ?>
    <script src="main/payment-recordatory/payment-recordatory.js"></script>
  <?php endif; ?>

  <script src="main/upcoming-events/upcoming-events.js"></script>
  <!-- <script src="main/upcoming-events/validate.js"></script> -->

  <script>
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
</body>

</html>