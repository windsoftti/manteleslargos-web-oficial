<div class="d-flex px-3 px-xl-6 w-100 border-right">
  <a class="navbar-brand" href="index.php">
    <img src="images/manteleslargos_logo.png" alt="Manteles Largos">
  </a>

  <div class="ml-auto d-flex align-items-center ">
    <div class="d-flex align-items-center d-xl-none">
      <?php if ($session_user_plan === 'Básico') : ?>
        <?php
        $quotes_query = "SELECT
            C.idCotizacion,
            S.Salon,
            C.NombreCompleto,
            DATE_FORMAT(C.FechaCreacion, '%d-%m-%Y') AS FechaCreacion
          FROM cotizaciones AS C
            LEFT JOIN salones AS S ON (C.idNegocio = S.idSalon)
          WHERE
            C.idProveedor = '$_SESSION[session_user_id]'  AND
            C.Status      = 'Pendiente'                   AND
            S.idSalon   = $_SESSION[session_business_id]

            ORDER BY C.FechaCreacion DESC
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
            RP.idUsuario  = $_SESSION[session_user_id]  AND
            RP.Fecha      = '$today_date'               AND
            S.idSalon     = $_SESSION[session_business_id]
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

        <div class="mobile-dropdown">
          <a class="mobile-dropdown-toggle" href="javascript:void(0)">
            <i class="far fa-bell text-dark" style="font-size: 1.8rem;"></i>
            <?php if ($num_notifications) : ?>
              <span class="badge badge-primary badge-circle badge-absolute font-weight-bold fs-13 pulsate" style="top: -10px;right:-10px;">
                <?= $num_notifications; ?>
              </span>
            <?php endif; ?>
          </a>

          <ul class="mobile-dropdown-content">
            <div class="mobile-dropdown-card">
              <div class="p-2 mobile-dropdown-header">
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
                          <?= $row['NombreCompleto']; ?>
                        </span>
                      </div>

                      <span style="width: 100px;text-align: right;">
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
                          Hoy se comple el pago del <?= $row['Porcentaje']; ?>%
                        </span>
                      </div>

                      <span style="width: 100px;text-align: right;">
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
          </ul>
        </div>
      <?php endif; ?>

      <div class="dropdown px-3 ml-4">
        <a href="#" class="dropdown-toggle d-flex align-items-center text-heading" data-toggle="dropdown">
          <div>
            <i class="fal fa-user-circle fa-2x my-2"></i>
          </div>
          <span class="fs-13 font-weight-500 d-none d-sm-inline">
            <?= $_SESSION['session_user_name']; ?>
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a class="dropdown-item mb-2" href="cerrar-sesion">
            <i class="fa fa-sign-out mr-1"></i>
            Cerrar sesión
          </a>
        </div>
      </div>
    </div>
    <button class="navbar-toggler border-0 px-0" type="button" data-toggle="collapse" data-target="#primaryMenuSidebar" aria-controls="primaryMenuSidebar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</div>