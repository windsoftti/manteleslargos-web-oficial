<div class="d-flex px-3 px-xl-6 w-100">
  <a class="navbar-brand" href="index.php">
    <img src="images/manteleslargos_logo.png" alt="Manteles Largos">
  </a>
  <div class="ml-auto d-flex align-items-center ">
    <div class="d-flex align-items-center d-xl-none">
      <!-- Notifications -->
      <div class="dropdown border-0 py-3 text-right">
        <?php
        $query_notifications = "SELECT
            RP.idRecordatorioPago,
            RP.idUsuario,
            RP.idReservacion,
            RP.Fecha,
            S.Salon
          FROM recordatorio_pagos AS RP
            LEFT JOIN reservaciones  AS EC ON (RP.idReservacion = EC.idReservacion)
            LEFT JOIN salones             AS S  ON (EC.idNegocio          = S.idSalon)
          WHERE
            RP.idUsuario  = $_SESSION[session_user_id] AND
            RP.Fecha      = NOW()
          GROUP BY EC.idReservacion
        ";

        $query_notifications_result = mysqli_query($mysqli, $query_notifications);
        $num_rows_notifications     = mysqli_num_rows($query_notifications_result);
        ?>

        <a href="#" class="text-heading d-flex px-2 align-items-center justify-content-end position-relative" data-toggle="dropdown">
          <i class="fa fa-bell fa-2x"></i>
          <span class="badge badge-primary badge-circle badge-absolute" style="top: -10px;right: 0;">
            <?= $num_rows_notifications; ?>
          </span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" style="right:-31vw">
          <div style="
              display: flex;
              align-items: center;
              justify-content: center;
              width: 100%;
              border-bottom: 1px solid rgb(230,230,230)
            ">
            <b>Notificaciones</b>
          </div>

          <?php if (!$num_rows_notifications) : ?>
            <div class="col-md-12 p-2 text-center">
              No tienes notificaciones
            </div>
          <?php endif; ?>

          <?php if ($num_rows_notifications) : ?>
            <?php while ($notification = mysqli_fetch_array($query_notifications_result)) : ?>
              <a class="dropdown-item" href="#" style="border-bottom: 1px solid rgb(245,245,245);">
                <i class="fa fa-check-circle mr-1"></i>
                Recordatorio de pago de "<?= $notification['Salon']; ?>"
              </a>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>
      </div>

      <div class="dropdown px-3">
        <a href="#" class="dropdown-toggle d-flex align-items-center text-heading" data-toggle="dropdown">
          <!-- <div class="w-48px">
            <img src="images/testimonial-5.jpg" alt="<?= $_SESSION['session_user_name']; ?>" class="rounded-circle">
          </div> -->
          <div>
            <i class="fa fa-user-circle fa-2x"></i>
          </div>
          <span class="fs-13 font-weight-500 d-none d-sm-inline ml-2">
            <?= $_SESSION['session_user_name']; ?>
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <!-- <a class="dropdown-item" href="#">My perfil</a> -->
          <a class="dropdown-item" href="cerrar-sesion">Cerrar sesión</a>
        </div>
      </div>
      <!-- <div class="dropdown no-caret py-4 px-3 d-flex align-items-center notice mr-3">
        <a href="#" class="dropdown-toggle text-heading fs-20 font-weight-500 lh-1" data-toggle="dropdown">
          <i class="far fa-bell"></i>
          <span class="badge badge-primary badge-circle badge-absolute font-weight-bold fs-13">1</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </div> -->
    </div>
    <button class="navbar-toggler border-0 px-0" type="button" data-toggle="collapse" data-target="#primaryMenuSidebar" aria-controls="primaryMenuSidebar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</div>