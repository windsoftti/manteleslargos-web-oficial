<header class="main-header shadow-none shadow-lg-xs-1 bg-white position-relative d-none d-xl-block border-bottom elevation-0">
  <div class="container-fluid">
    <nav class="navbar navbar-light py-0 row no-gutters px-3 px-lg-0">
      <a href="javascript:void(0)" class="dashboar-custom-icon-menu">
        <i class="navbar-toggler-icon"></i>
      </a>

      <?php if (!$_SESSION['session_user_children_id']) : ?>
        <button class="btn btn-custom-default mr-auto ml-4" data-toggle="modal" data-target="#select-business-modal">
          <i class="fa fa-building"></i> <span class="text-secondary"><?= getBusinessNameById($_SESSION['session_business_id']); ?></span>
          <i class="fa fa-caret-down ml-2"></i>
        </button>
      <?php endif; ?>

      <input id="desktop-business" value="<?= $_SESSION['session_business_id']; ?>" type="hidden">

      <div class="col-md-6 d-flex flex-wrap justify-content-md-end order-0 order-md-1">
        <div class="dropdown user-dropdown border-md-right border-0 py-3 text-right">
          <a href="#" class="dropdown-toggle text-heading pr-3 pr-sm-6 d-flex align-items-center justify-content-end" data-toggle="dropdown">
            <div class="mr-2 w-48px">
              <i class="fal fa-user-circle fa-2x my-2"></i>
            </div>

            <div class="fs-13 font-weight-bold lh-1">
              <?= 'Hola '.explode(' ', $_SESSION['session_user_name'])[0]; ?>
            </div>
          </a>

          <div class="dropdown-menu dropdown-menu-right w-100">
            <a class="dropdown-item" href="cerrar-sesion">
              <i class="fa fa-sign-out mr-1"></i>
              Cerrar sesión
            </a>
          </div>
        </div>

        <?php if ($session_user_plan == 'Básico') : ?>
          <div class="dropdown no-caret py-3 px-3 px-sm-6 d-flex align-items-center justify-content-end notice">
            <?php
            # Notificaciones de cotizaciones ::::::::::::::::::::::::::::::::::::::
            $quotes_query = "SELECT
                C.idCotizacion,
                S.Salon,
                C.NombreCompleto,
                DATE_FORMAT(C.FechaCreacion, '%d-%m-%Y') AS FechaCreacion,
                S.Salon
              FROM cotizaciones AS C
                LEFT JOIN salones AS S ON (C.idNegocio = S.idSalon)
              WHERE
                C.idProveedor = '$_SESSION[session_user_id]'  AND
                C.Status      = 'Pendiente'                   AND
                S.idSalon   = $_SESSION[session_business_id]
            ";

            $quotes_query_result  = mysqli_query($mysqli, $quotes_query);
            $quotes_num_rows      = mysqli_num_rows($quotes_query_result);

            # Notificaciones de recordatorios de pagos ::::::::::::::::::::::::::::
            $today_date = date('Y-m-d');
            $payment_reminders_query = "SELECT
                RP.idRecordatorioPago,
                RP.idReservacion,
                RP.Porcentaje,
                RP.Fecha,
                DATE_FORMAT(RP.Fecha, '%d-%m-%Y') AS FechaRecordatorio,
                S.Salon
              FROM recordatorio_pagos AS RP
                LEFT JOIN reservaciones AS R ON (RP.idReservacion = R.idReservacion)
                LEFT JOIN salones       AS S ON (R.idNegocio      = S.idSalon)
              WHERE
                RP.idUsuario  = $_SESSION[session_user_id] AND
                RP.Fecha      = '$today_date'
              GROUP BY R.idReservacion
            ";

            $payment_reminders_query_result  = mysqli_query($mysqli, $payment_reminders_query);
            $payment_reminders_num_rows      = mysqli_num_rows($payment_reminders_query_result);

            # Notificaciones de recordatorios de eventos ::::::::::::::::::::::::::
            $event_reminders_query = "SELECT
                ECR.idEventoCalendarioRecordatorio,
                ECR.idEventoCalendario,
                ECR.FechaInicial,
                EC.Titulo,
                EC.Descripcion,
                EC.FechaHasta
              FROM eventos_calendario_recordatorios AS ECR
                LEFT JOIN eventos_calendario AS EC ON (ECR.idEventoCalendario = EC.idEventoCalendario)
              WHERE
                (NOW() BETWEEN ECR.FechaInicial AND EC.FechaHasta) AND
                EC.idNegocio = $_SESSION[session_business_id]
            ";

            $event_reminders_query_result  = mysqli_query($mysqli, $event_reminders_query);
            $event_reminders_num_rows      = mysqli_num_rows($event_reminders_query_result);

            # Total de notificaciones :::::::::::::::::::::::::::::::::::::::::::::
            $num_notifications = $quotes_num_rows + $payment_reminders_num_rows + $event_reminders_num_rows;
            ?>

            <a href="#" class="dropdown-toggle text-heading fs-20 font-weight-500 lh-1" data-toggle="dropdown">
              <i class="far fa-bell"></i>
              <?php if ($num_notifications) : ?>
                <span class="badge badge-primary badge-circle badge-absolute font-weight-bold fs-13 pulsate"><?= $num_notifications; ?></span>
              <?php endif; ?>
            </a>

            <div class="dropdown-menu dropdown-menu-right" style="width: 25rem; height:80vh;">
              <div class="px-2 pt-0 pb-1 mobile-dropdown-header">
                <h4 class="m-0"><b>Notificaciones</b></h4>
              </div>

              <div class="mobile-dropdown-body">
                <?php if (!$num_notifications) : ?>
                  <div class="col-md-12 p-2 text-center">
                    No tienes notificaciones
                  </div>
                <?php endif; ?>


                <!-- Cotizaciones ::::::::::::::::::::::::::::::::::::: -->
                <?php if ($quotes_num_rows) : ?>
                  <h6 class="mx-4 my-1">Cotizaciones</h6>

                  <?php while ($row = mysqli_fetch_array($quotes_query_result)) : ?>
                    <a class="dropdown-item mb-2" href="cotizaciones" style="white-space: normal;display: flex;align-items: center;justify-content: space-between;">
                      <div style="line-height: 1.2;display: flex; align-items: center;">
                        <div>
                          <i class="fa fa-engine-warning mr-1 text-warning"></i>
                        </div>

                        <span>
                          <b>Nueva cotización</b><br>
                          Cotización pendiente con el cliente: <?= $row['NombreCompleto']; ?>
                        </span>
                      </div>

                      <span style="width: 120px;text-align: right;">
                        <small>
                          <?= $row['FechaCreacion']; ?>
                        </small>
                      </span>
                    </a>
                  <?php endwhile; ?>
                <?php endif; ?>

                <!-- Recordatorio de pagos :::::::::::::::::::::::::::: -->
                <?php if ($payment_reminders_num_rows) : ?>
                  <h6 class="mx-4 my-1">Recordatorio de pagos</h6>

                  <?php while ($row = mysqli_fetch_array($payment_reminders_query_result)) : ?>
                    <a class="dropdown-item mb-2" href="javascript:void(0)" style="white-space: normal;display: flex;align-items: center;justify-content: space-between;">
                      <div style="line-height: 1.2;display: flex; align-items: center;">
                        <div>
                          <i class="fa fa-engine-warning mr-1 text-danger"></i>
                        </div>

                        <span>
                          <b>Nuevo recordatorio</b><br>
                          Hoy se cumple el pago del <?= $row['Porcentaje']; ?>%
                        </span>
                      </div>

                      <span style="width: 120px;text-align: right;">
                        <small>
                          <?= $row['FechaRecordatorio']; ?>
                        </small>
                      </span>
                    </a>
                  <?php endwhile; ?>
                <?php endif; ?>

                <!-- Recordatorio de eventos ::::::::::::::::::::::::: -->
                <?php if ($event_reminders_num_rows) : ?>
                  <h6 class="mx-4 my-1">Recordatorio de eventos</h6>

                  <?php while ($row = mysqli_fetch_array($event_reminders_query_result)) : ?>
                    <a class="dropdown-item mb-2" href="javascript:void(0)" style="white-space: normal;display: flex;align-items: center;justify-content: space-between;">
                      <div style="line-height: 1.2;display: flex; align-items: center;">
                        <div>
                          <i class="fa fa-engine-warning mr-1" style="color: #007bff;"></i>
                        </div>

                        <span>
                          <b><?= $row['Titulo']; ?></b><br>
                          <?= $row['Descripcion']; ?>
                        </span>
                      </div>

                      <span style="width: 120px;text-align: right;">
                        <small>
                          <?= date('d-m-Y'); ?>
                        </small>
                      </span>
                    </a>
                  <?php endwhile; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </nav>
  </div>
</header>