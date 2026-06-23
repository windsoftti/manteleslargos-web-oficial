<?php
include 'inc/session.php';
$meta_title = 'Dashboard';
?>

<!doctype html>
<html lang="es">

<head>
  <?php include 'inc/meta-tags.php'; ?>
  <?php if ($_SESSION['session_user_level'] === 'Usuario') : ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap4-datetimepicker@5.2.3/build/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="plugins/custom/cs-calendar-v2/cs-calendar.css">

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

      /* Initai State ::::::::::::::::::::::::::::::::::::::: */
      .form-container>.event-form {
        display: none;
      }

      /* Event state :::::::::::::::::::::::::::::::::::::::: */
      .form-container.event>.actions {
        display: none;
      }

      .form-container.event>.event-form {
        display: block;
      }
    </style>
  <?php endif; ?>
</head>

<body>
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
          <?php if ($_SESSION['session_user_level'] != 'Usuario') : ?>
            <div class="px-3 px-lg-6 px-xxl-13 py-5 py-lg-10">
              <?php if ($_SESSION['session_user_level'] === 'Super Usuario' || $_SESSION['session_user_level'] === 'Administrador' || $_SESSION['session_user_level'] === 'Usuario') : ?>
                <div class="d-flex flex-wrap flex-md-nowrap mb-6">
                  <div class="mr-0 mr-md-auto">
                    <h2 class="mb-0 text-heading fs-22 lh-15">¡Bienvenido, <?= $_SESSION['session_user_name'] ?>!</h2>
                    <p>¡Haz crecer tu negocio con Manteles Largos!</p>
                  </div>
                  <div>
                    <a href="agregar-negocio" class="btn btn-primary btn-lg">
                      <span>Agregar nuevo negocio</span>
                      <span class="d-inline-block ml-1 fs-20 lh-1"><svg class="icon icon-add-new">
                          <use xlink:href="#icon-add-new"></use>
                        </svg></span>
                    </a>
                  </div>
                </div>
              <?php endif; ?>

              <?php if ($_SESSION['session_user_level'] === 'Usuario Final') : ?>
                <div class="d-flex flex-wrap flex-md-nowrap mb-6">
                  <div class="mr-0 mr-md-auto">
                    <h2 class="mb-0 text-heading fs-22 lh-15">¡Bienvenido, <?= $_SESSION['session_user_name'] ?>!</h2>
                    <p>¡Celebra en grande con Manteles Largos!</p>
                  </div>
                  <div>
                    <a href="nueva-invitacion" class="btn btn-primary btn-lg">
                      <span>Agregar invitación digital</span>

                      <span class="d-inline-block ml-1 fs-20 lh-1"><svg class="icon icon-add-new">
                          <use xlink:href="#icon-add-new"></use>
                        </svg></span>
                    </a>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>

          <?php if ($_SESSION['session_user_level'] === 'Usuario') : ?>
            <?php
            $page_slug = 'proximos-eventos';
            include 'inc/verify-user-permissions.php';
            include 'modals/seller-dashboard.php';
            ?>

            <div class="p-3">
              <div class="row">
                <div class="col-md-12 text-center">
                  <div class="card">
                    <h2 class="mb-2 mt-2 text-heading fs-22 lh-15">Calendario de eventos</h2>

                    <?php
                    /* $business_query = "SELECT
                        idSalon,
                        Salon
                      FROM salones
                      WHERE idUsuario = '$_SESSION[session_user_id]'
                    ";

                    $business_query_result  = mysqli_query($mysqli, $business_query);
                    ?>

                    <div class="row">
                      <div class="col-md-6 mx-auto">
                        <div class="form-group">
                          <label for="business"><b>Negocio/Salon</b></label>
                          <select id="business" class="form-control form-control-lg select2" name="business" required>
                            <option value="">Seleccionar</option>
                            <?php $count = 1; ?>
                            <?php while ($row = mysqli_fetch_array($business_query_result)) :
                              $business_selected = $count === 1 ? 'selected' : '';
                            ?>
                              <option <?= $business_selected; ?> value="<?= $row['idSalon']; ?>"><?= $row['Salon']; ?></option>
                              <?php $count++; ?>
                            <?php endwhile; ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <?php */ ?>

                    <div class="row">
                      <div class="col-md-12">
                        <div class="cs-calendar"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </main>
      </div>
    </div>
  </div>

  <!-- PAGE LOADING -->
  <?php include 'inc/page-loading.php' ?>

  <!-- REQUIRED SCRIPTS -->
  <?php include 'inc/required-scripts.php'; ?>

  <script src="js/functions.js"></script>

  <?php if ($_SESSION['session_user_level'] === 'Usuario') : ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js" integrity="sha512-LGXaggshOkD/at6PFNcp2V2unf9LzFq6LE+sChH7ceMTDP0g2kn6Vxwgg7wkPP7AAtX+lmPqPdxB47A0Nz0cMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap4-datetimepicker@5.2.3/src/js/bootstrap-datetimepicker.min.js"></script>

    <script src='plugins/custom/cs-calendar-v2/cs-calendar.js'></script>
    <script src="main/payment-recordatory/payment-recordatory.js"></script>
    <script src="main/seller-dashboard/seller-dashboard.js"></script>
    <script src="main/business-select/business-select.js"></script>

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
  <?php endif; ?>

  <?php include 'inc/svg.php'; ?>
</body>

</html>