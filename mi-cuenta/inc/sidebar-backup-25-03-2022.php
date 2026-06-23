<div class="collapse navbar-collapse bg-white" id="primaryMenuSidebar">
  <?php if ($_SESSION['session_user_level'] === 'Usuario') : ?>
    <?php if (!$_SESSION['session_user_children_id']) : ?>
      <form id="mobile-business-select-form" class="d-block d-xl-none pt-5 px-3" method="POST">
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
          <label for="mobile-business"><b>Administras:</b> </label>
          <select id="mobile-business" class="form-control business-select" name="s_business_id" style="
            border: none;
            font-size: 16px;
            height: 30px;
            padding: 5px;
            width: 250px;
            max-width: fit-content;
            color: #DD0063;
            text-transform: uppercase;
          ">
            <option value="">Seleccionar</option>
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
    <?php endif; ?>
  <?php endif; ?>

  <ul class="list-group list-group-flush w-100">
    <li class="list-group-item pt-6 pb-4">
      <h5 class="fs-13 letter-spacing-087 text-muted mb-3 text-uppercase px-3">Main</h5>
      <ul class="list-group list-group-no-border rounded-lg">

        <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
          <a href="panel" class="text-heading lh-1 sidebar-link">
            <span class="sidebar-item-icon d-inline-block mr-3 fs-20"><i class="fal fa-tachometer"></i></span>
            <span class="sidebar-item-text">Panel de control</span>
          </a>
        </li>

        <?php if ($_SESSION['session_user_level'] === 'Usuario') : ?>
          <?php if (verifyUserPermissions('calendario-de-eventos')) : ?>
            <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
              <a href="calendario-eventos" class="text-heading lh-1 sidebar-link">
                <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
                  <i class="fal fa-calendar-alt"></i>
                </span>

                <span class="sidebar-item-text">Calendario de eventos</span>
              </a>
            </li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>
    </li>

    <?php if ($_SESSION['session_user_level'] === 'Super Usuario' || $_SESSION['session_user_level'] === 'Administrador') : ?>
      <li class="list-group-item pt-6 pb-4">
        <h5 class="fs-13 letter-spacing-087 text-muted mb-3 text-uppercase px-3">Blog y catálogos</h5>

        <ul class="list-group list-group-no-border rounded-lg">
          <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
            <a href="tipos-eventos" class="text-heading lh-1 sidebar-link d-flex align-items-center">
              <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
                <i class="fal fa-check-circle"></i>
              </span>

              <span class="sidebar-item-text">Tipos de eventos</span>
            </a>
          </li>

          <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
            <a href="tipos-proveedores" class="text-heading lh-1 sidebar-link d-flex align-items-center">
              <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
                <i class="fal fa-check-circle"></i>
              </span>

              <span class="sidebar-item-text">Tipos de proveedores</span>
            </a>
          </li>

          <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
            <a href="eventos-recientes" class="text-heading lh-1 sidebar-link d-flex align-items-center">
              <span class="sidebar-item-icon d-inline-block mr-3 text-muted fs-20">
                <svg class="icon icon-save-search">
                  <use xlink:href="#icon-save-search"></use>
                </svg>
              </span>
              <span class="sidebar-item-text">Eventos recientes</span>
            </a>
          </li>

          <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
            <a href="tips" class="text-heading lh-1 sidebar-link d-flex align-items-center">
              <span class="sidebar-item-icon d-inline-block mr-3 text-muted fs-20">
                <svg class="icon icon-save-search">
                  <use xlink:href="#icon-save-search"></use>
                </svg>
              </span>
              <span class="sidebar-item-text">Tips</span>
            </a>
          </li>
        </ul>
      </li>
    <?php endif; ?>

    <?php if ($_SESSION['session_user_level'] === 'Usuario') : ?>
      <li class="list-group-item pt-6 pb-4">
        <h5 class="fs-13 letter-spacing-087 text-muted mb-3 text-uppercase px-3">Administrar negocios</h5>

        <ul class="list-group list-group-no-border rounded-lg">
          <?php if (verifyUserPermissions('agregar-negocios')) : ?>
            <?php
            $add_business_attribute = $session_target_free_plan;
            $num_business = getNumBusiness();
            if ($session_user_plan === 'Básico' || $num_business < 1) $add_business_attribute = 'agregar-negocio';
            ?>

            <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
              <a href="<?= $add_business_attribute; ?>" class="text-heading lh-1 sidebar-link">
                <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
                  <i class="fal fa-plus-circle"></i>
                </span>
                <span class="sidebar-item-text">Nuevo negocio</span>
              </a>
            </li>
          <?php endif; ?>

          <?php if (verifyUserPermissions('listar-negocios')) : ?>
            <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
              <a href="negocios" class="text-heading lh-1 sidebar-link d-flex align-items-center">
                <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
                  <i class="fal fa-building"></i>
                </span>
                <span class="sidebar-item-text">Negocios</span>
              </a>
            </li>
          <?php endif; ?>

          <?php if (verifyUserPermissions('cotizaciones')) : ?>
            <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
              <a href="cotizaciones" class="text-heading lh-1 sidebar-link d-flex align-items-center">
                <span class="sidebar-item-icon d-inline-block mr-3 text-muted fs-20">
                  <i class="fal fa-list"></i>
                </span>

                <span class="sidebar-item-text">Cotizaciones</span>

                <?php
                $query_count_quotes = "SELECT
                    COUNT(C.idCotizacion) AS Total,
                    P.idNegocio
                  FROM cotizaciones AS C
                    LEFT JOIN paquetes_negocios AS P ON (C.idPaquete = P.idPaquete)
                  WHERE
                    C.idProveedor = $_SESSION[session_user_id]      AND
                    P.idNegocio   = $_SESSION[session_business_id]  AND
                    Status        = 'Pendiente'
                  LIMIT 1
                ";

                $query_count_quotes_result  = mysqli_query($mysqli, $query_count_quotes);
                $data_count_quotes          = mysqli_fetch_array($query_count_quotes_result);

                $count_quotes = $data_count_quotes['Total'];
                ?>

                <span class="sidebar-item-number ml-auto text-primary fs-15 font-weight-bold" style="opacity: 1;"><?= $count_quotes; ?></span>
              </a>
            </li>
          <?php endif; ?>

          <?php if (verifyUserPermissions('proximos-eventos')) : ?>
            <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
              <a href="proximos-eventos" class="text-heading lh-1 sidebar-link d-flex align-items-center">
                <span class="sidebar-item-icon d-inline-block mr-3 text-muted fs-20">
                  <i class="fal fa-list"></i>
                </span>

                <span class="sidebar-item-text">Eventos proximos</span>

                <?php
                $query_count_events = "SELECT
                    COUNT(idReservacion) AS Total
                  FROM reservaciones
                  WHERE
                    idUsuario = $_SESSION[session_user_id]      AND
                    idNegocio = $_SESSION[session_business_id]  AND
                    Fecha > NOW()
                  LIMIT 1
                ";

                $query_count_events_result  = mysqli_query($mysqli, $query_count_events);
                $data_count_events          = mysqli_fetch_array($query_count_events_result);

                $count_events = $data_count_events['Total'];
                ?>

                <span class="sidebar-item-number ml-auto text-primary fs-15 font-weight-bold" style="opacity: 1;"><?= $count_events; ?></span>
              </a>
            </li>
          <?php endif; ?>

          <?php if (verifyUserPermissions('egresos')) : ?>
            <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
              <a href="egresos" class="text-heading lh-1 sidebar-link d-flex align-items-center">
                <span class="sidebar-item-icon d-inline-block mr-3 text-muted fs-20">
                  <i class="fal fa-money-bill"></i>
                </span>
                <span class="sidebar-item-text">Egresos</span>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </li>
    <?php endif; ?>
  </ul>
</div>