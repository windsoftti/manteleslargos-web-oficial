<?php
include 'inc/session-proveedor.php';

$page_slug = 'proximos-eventos';
include 'inc/verify-user-permissions.php';

if ($session_user_plan === 'Free') {
  header('location:proximos-eventos');
  exit();
};

$reservation_id = cleanStr($_GET['uid']);

if (!$reservation_id) {
  header('location:proximos-eventos');
  die();
}

$query = "SELECT
    EC.idReservacion,
    EC.idUsuario,
    EC.idNegocio,
    EC.idPaquete,
    EC.idTipoEvento,
    EC.NombreCompleto,
    EC.Correo,
    EC.Telefono,
    EC.Fecha,
    DATE_FORMAT(EC.Fecha, '%d-%m-%Y') AS FechaFormat,
    DATE_FORMAT(EC.HoraInicio, '%h:%i %p') AS HoraInicio,
    DATE_FORMAT(EC.HoraFinal, '%h:%i %p') AS HoraFinal,
    EC.NPersonas,
    EC.Extras,
    EC.CostoTotal,
    EC.Deposito,
    EC.Anticipo,
    S.Salon,
    PN.Paquete,
    (EC.CostoTotal - SUM(ECP.Pago)) AS SaldoTotal
  FROM reservaciones AS EC
    LEFT JOIN salones AS S ON (EC.idNegocio = S.idSalon)
    LEFT JOIN paquetes_negocios AS PN ON (EC.idPaquete = PN.idPaquete)
    INNER JOIN reservaciones_pagos AS ECP ON (EC.idReservacion = ECP.idReservacion)
  WHERE
    EC.idReservacion  = '$reservation_id' AND
    EC.idUsuario           = '$_SESSION[session_user_id]'
  LIMIT 1
";

$query_result = mysqli_query($mysqli, $query);
$num_rows     = mysqli_num_rows($query_result);

if (!$num_rows) {
  header('location:proximos-eventos');
  die();
}

$event_data = mysqli_fetch_array($query_result);

$meta_title = 'Pagos/Abonos';
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

    .form-group label {
      margin-bottom: 0;
    }

    .form-group label span {
      color: #c92525;
    }

    @media (max-width: 480px) {
      .table-comment {
        display: none;
      }
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
              <h2 class="text-heading fs-22 lh-15 m-0">
                <?= $meta_title ?>
              </h2>
            </div>

            <div class="row">
              <div class="col-md-12 p-4">
                <div class="card">
                  <div class="card-body pt-2">
                    <div class="row">
                      <div class="col-md-12 border-bottom mb-2 p-2 align-items-center">
                        <a href="proximos-eventos">
                          <i class="fal fa-arrow-left fa-2x"></i>
                        </a>

                        <div class="float-right">
                          <h4 class="mb-0 mt-0 mb-1"><?= $event_data['Salon']; ?></h4>

                          <div class="row">
                            <div class="text-left ml-3 mr-3 mb-2">
                              <p class="mb-0">
                                <b>Cliente:</b> <?= $event_data['NombreCompleto']; ?><br>
                                <b>Fecha del evento:</b> <?= $event_data['FechaFormat']; ?><br>
                                <b>Paquete:</b> <?= $event_data['Paquete']; ?>
                              </p>
                            </div>

                            <div class="text-left ml-3 mr-2 mb-2">
                              <p class="m-0"><b>Total a pagar:</b> $<?= number_format($event_data['CostoTotal'], 2); ?><br></p>
                              <p class="m-0"><b>Total abonado:</b> <span id="txt-total-abonado"></span><br></p>
                              <p class="m-0"><b>Saldo:</b> <span id="txt-saldo"></span></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <?php
                    $display_form = $event_data['SaldoTotal'] <= 0 ? 'style="display:none"' : '';
                    ?>

                    <div id="payment-container" class="row mb-4" <?= $display_form; ?>>
                      <div class="col-12">
                        <h5 class="mb-3">Nuevo pago</h5>

                        <div class="row">
                          <form id="add-payment-form" class="col-md-12" autocomplete="off">
                            <div class="row">
                              <div class="col-6 col-sm-5 col-md-4 col-lg-3">
                                <div class="form-group">
                                  <label for="currentBalance">Saldo actual<span>*</span></label>
                                  <input id="currentBalance" class="form-control" type="text" name="currentBalance" value="<?= $event_data['SaldoTotal']; ?>" required readonly>
                                </div>
                              </div>

                              <div class="col-6 col-sm-5 col-md-4 col-lg-3">
                                <div class="form-group">
                                  <label for="newBalance">Nuevo saldo<span>*</span></label>
                                  <input id="newBalance" class="form-control" type="text" name="newBalance" required readonly>
                                </div>
                              </div>

                              <div class="col-6 col-sm-5 col-md-4 col-lg-3">
                                <div class="form-group">
                                  <label for="payment">Pago<span>*</span></label>
                                  <input id="payment" class="form-control number-input" type="text" name="payment" required>
                                </div>
                              </div>

                              <div class="col-6 col-sm-5 col-md-4 col-lg-3">
                                <div class="form-group">
                                  <label for="date">Fecha<span>*</span></label>
                                  <input id="date" class="form-control datepicker" type="text" name="date" required value="<?= date('d/m/Y'); ?>">
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-12">
                                <div class="form-group">
                                  <label for="comments">Comentarios</label>
                                  <textarea id="comments" class="form-control" name="comments" rows="2"></textarea>
                                </div>
                              </div>
                            </div>

                            <input id="reservationId" type="hidden" name="reservationId" value="<?= $reservation_id; ?>">

                            <div class="row">
                              <div class="col-12 col-sm-5 col-md-4 col-lg-3 ml-auto">
                                <button class="btn btn-primary btn-block" type="submit">
                                  <i class="fal fa-check-circle mr-1"></i> Agregar pago
                                </button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>

                    <div class="row align-middle">
                      <form id="search-filters-form" class="col-md-12">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="search-date">Buscar por fecha</label>
                            <input id="search-date" class="form-control datepicker" type="text" name="date" placeholder="Todas las fechas" value="">
                          </div>
                        </div>

                        <input type="hidden" name="reservationId" value="<?= $reservation_id; ?>">
                      </form>
                    </div>

                    <?php include 'modals/upcoming-event-payments.php'; ?>

                    <div class="row">
                      <div id="list_upcoming_event_payments" class="col-md-12"></div>
                    </div>
                  </div>

                  <!-- <div class="card-footer bg-white">
                    <p class="mb-0">BBBootstrap.com, Sounth Block, New delhi, 110034</p>
                  </div> -->
                </div>
              </div>
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

  <script src="main/upcoming-event-payments/upcoming-event-payments.js"></script>

  <script>
    $('.datepicker').datetimepicker({
      format: 'DD-MM-YYYY',
      locale: 'es-es'
    });

    $("#search-date").datetimepicker({
      format: 'DD-MM-YYYY',
      locale: 'es-es'
    }).on('dp.change', loadUpcomingEventPayments);
  </script>
</body>

</html>