<header class="main-header shadow-none shadow-lg-xs-1 bg-white position-relative d-none d-xl-block" style="width: 100vp;">
  <div class="container-fluid">
    <nav class="navbar navbar-light py-0 row no-gutters px-3 px-lg-0">
      <a href="javascript:void(0)" class="dashboar-custom-icon-menu">
        <i class="navbar-toggler-icon"></i>
      </a>

      <?php if ($_SESSION['session_user_level'] === 'Usuario') : ?>
        <?php if (!$_SESSION['session_user_children_id']) : ?>
          <div class="col-md-5 px-0 px-md-5 order-1 order-md-0 mr-auto text-right mt-2">
            <form id="desktop-business-select-form" method="POST">
              <?php
              $business_query = "SELECT
              idSalon,
              Salon
            FROM salones
            WHERE idUsuario = '$_SESSION[session_user_id]'
          ";

              $business_query_result  = mysqli_query($mysqli, $business_query);
              $count = 1;
              ?>

              <div class="input-group">
                <label>Administras: </label><select id="desktop-business" class="form-control business-select" name="s_business_id" style=" background: transparent;
                border: none;
                font-size: 16px;
                height: 30px;
                padding: 5px;
                width: 250px;
                max-width: fit-content;
                color: #DD0063;
                text-transform: uppercase;
              ">
                  <!--option value="">Seleccionar</option-->
                  <?php while ($business_data = mysqli_fetch_array($business_query_result)) :
                    $business_id    = $business_data['idSalon'];
                    $business_name  = $business_data['Salon'];

                    if (!$_SESSION['session_business_id'] && $count === 1) $_SESSION['session_business_id'] = $business_id;

                    $business_selected = (
                      ($count === 1 && !$_SESSION['session_business_id']) ||
                      $_SESSION['session_business_id'] === $business_id)
                      ? 'selected' : '';
                  ?>
                    <option <?= $business_selected; ?> value="<?= $business_id; ?>"><?= $business_name; ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
            </form>
          </div>
        <?php endif; ?>
      <?php endif; ?>

      <?php if ($_SESSION['session_user_children_id']) : ?>
        <input id="desktop-business" type="hidden" value="<?= $_SESSION['session_business_id']; ?>">
      <?php endif; ?>

      <div class="col-md-4 d-flex flex-wrap justify-content-md-end order-0 order-md-1">
        <!-- Notifications -->
        <?php if ($session_user_plan === 'Básico') : ?>
          <div class="dropdown border-0 py-3 text-right">
            <?php
            $today_date = date('Y-m-d');
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
                RP.Fecha      = '$today_date'
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

            <div class="dropdown-menu">
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
        <?php endif; ?>

        <div class="dropdown border-md-right border-0 py-3 text-right">
          <a href="#" class="dropdown-toggle text-heading pr-3 pr-sm-6 d-flex align-items-center justify-content-end" data-toggle="dropdown">
            <div class="mr-2 w-48px">
              <!-- <img src="images/testimonial-5.jpg" alt="Ronald Hunter" class="rounded-circle"> -->
              <i class="fa fa-user-circle fa-2x"></i>
            </div>
            <div class="fs-13 font-weight-500 lh-1">
              <?= $_SESSION['session_user_name']; ?>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-right w-100">
            <!-- <a class="dropdown-item" href="dashboard-my-profiles.html">Mi perfil</a> -->
            <a class="dropdown-item" href="cerrar-sesion">Cerrar sesión</a>
          </div>
        </div>
        <!-- <div class="dropdown no-caret py-3 px-3 px-sm-6 d-flex align-items-center justify-content-end notice">
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
    </nav>
  </div>
</header>