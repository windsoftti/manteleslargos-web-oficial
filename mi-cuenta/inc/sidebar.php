<div class="collapse navbar-collapse bg-white border-right" id="primaryMenuSidebar">
  <?php if (!$_SESSION['session_user_children_id']) : ?>
    <div class="d-block d-xl-none pt-5 px-3">
      <button class="btn btn-custom-default btn-block" data-toggle="modal" data-target="#select-business-modal">
        <i class="fa fa-building"></i> <span class="text-secondary"><?= getBusinessNameById($_SESSION['session_business_id']); ?></span>
        <i class="fa fa-caret-down ml-2"></i>
      </button>
    </div>
  <?php endif; ?>

  <input id="mobile-business" value="<?= $_SESSION['session_business_id']; ?>" type="hidden">

  <ul class="list-group list-group-flush w-100">
    <!--li class="list-group-item pt-6 pb-4">
      <h5 class="fs-13 letter-spacing-087 text-muted mb-3 text-uppercase px-3">Main</h5>
      <ul class="list-group list-group-no-border rounded-lg">

        <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
          <a href="panel" class="text-heading lh-1 sidebar-link">
            <span class="sidebar-item-icon d-inline-block mr-3 fs-20"><i class="fal fa-tachometer"></i></span>
            <span class="sidebar-item-text">Panel de control</span>
          </a>
        </li>
      </ul>
    </li-->

    <?php if ($_SESSION['session_user_level'] === 'Super Usuario' || $_SESSION['session_user_level'] === 'Administrador') : ?>
      <li class="list-group-item pt-6 pb-4">
        <h5 class="fs-13 letter-spacing-087 text-muted mb-3 text-uppercase px-3">Blog y catálogos</h5>

        <ul class="list-group list-group-no-border rounded-lg">
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

          <?php if ($_SESSION['session_user_level'] === 'Super Usuario') : ?>
            <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
              <a href="servicios" class="text-heading lh-1 sidebar-link">
                <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
                  <i class="fal fa-star"></i>
                </span>
                <span class="sidebar-item-text">Servicios</span>
              </a>
            </li>

            <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
              <a href="amenidades" class="text-heading lh-1 sidebar-link">
                <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
                  <i class="fal fa-star"></i>
                </span>
                <span class="sidebar-item-text">Amenidades</span>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </li>
    <?php endif; ?>

    <?php if ($_SESSION['session_user_level'] === 'Usuario') : ?>
      <li class="list-group-item pt-6 pb-4">
        <?php
        $query_result_sidebar_business = mysqli_query($mysqli, "SELECT Salon, slug, Referencia FROM salones WHERE idSalon = $_SESSION[session_business_id] LIMIT 1");
        $sidebar_business_data      = mysqli_fetch_array($query_result_sidebar_business);
        $sidebar_business_name      = $sidebar_business_data['Salon'];
        $sidebar_business_slug      = $sidebar_business_data['slug'];
        $sidebar_business_reference = $sidebar_business_data['Referencia'];
        ?>

        <h5 class="fs-13 letter-spacing-087 text-muted mb-3 text-uppercase px-3" style="
          background: #b88c1c;
          padding: 10px;
          color: #fff!important;
        ">
          <?= $sidebar_business_name; ?>

          <a target="_blank" href="https://manteleslargos.com/<?= $sidebar_business_slug; ?>-<?= $sidebar_business_reference; ?>" style="
            font-size: 1.3rem;
            color: #fff !important;
          ">
            <i class="fas fa-eye"></i>
          </a>
        </h5>

        <ul class="list-group list-group-no-border rounded-lg">
          <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
            <a href="panel" class="text-heading lh-1 sidebar-link">
              <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
                <i class="fal fa-tachometer"></i>
              </span>
              <span class="sidebar-item-text">Estadísticas</span>
            </a>
          </li>
          <?php if ($_SESSION['session_user_level'] === 'Usuario') : ?>
            <?= renderSidebarLink([
                'title'  => 'Calendario de eventos',
                'url'    => 'calendario-eventos',
                'module' => 'calendario-de-eventos',
                'icon'   => 'fal fa-calendar-alt'
            ]); ?>

            <?php if (verifyUserPermissions('calendario-de-eventos')) : ?>
              <!--<li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
                <a href="calendario-eventos" class="text-heading lh-1 sidebar-link">
                  <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
                    <i class="fal fa-calendar-alt"></i>
                  </span>

                  <span class="sidebar-item-text">Calendario de eventos</span>
                </a>
              </li>-->
            <?php endif; ?>
          <?php endif; ?>

          <?= renderSidebarLink([
              'title'   => 'Cotizaciones',
              'url'     => 'cotizaciones',
              'module'  => 'cotizaciones',
              'icon'    => 'fal fa-list',
              'counter' => getQuotesCount()
          ]); ?>
          <?php if (verifyUserPermissions('cotizaciones')) : ?>
            <!--<li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
              <a href="cotizaciones" class="text-heading lh-1 sidebar-link d-flex align-items-center">
                <span class="sidebar-item-icon d-inline-block mr-3 text-muted fs-20">
                  <i class="fal fa-list"></i>
                </span>

                <span class="sidebar-item-text">Cotizaciones</span>
                <span id="sidebar-quotes-count" class="sidebar-item-number ml-auto text-primary fs-15 font-weight-bold" style="opacity: 1;"><?= getQuotesCount(); ?></span>
              </a>
            </li>-->
          <?php endif; ?>

          <?= renderSidebarLink([
              'title'   => 'Eventos próximos',
              'url'     => 'proximos-eventos',
              'module'  => 'proximos-eventos',
              'icon'    => 'fal fa-list',
              'counter' => getRecentEventsCount()
          ]); ?>
          <?= renderSidebarLink([
              'title'   => 'Eventos pasados',
              'url'     => 'eventos-pasados',
              'module'  => 'eventos-pasados',
              'icon'    => 'fal fa-list',
          ]); ?>
          <?php if (verifyUserPermissions('proximos-eventos')) : ?>
            <!--<li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
              <a href="proximos-eventos" class="text-heading lh-1 sidebar-link d-flex align-items-center">
                <span class="sidebar-item-icon d-inline-block mr-3 text-muted fs-20">
                  <i class="fal fa-list"></i>
                </span>

                <span class="sidebar-item-text">Eventos próximos</span>
                <span id="sidebar-recent-events-count" class="sidebar-item-number ml-auto text-primary fs-15 font-weight-bold" style="opacity: 1;"><?= getRecentEventsCount(); ?></span>
              </a>
            </li>-->

            <!--<li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
              <a href="eventos-pasados" class="text-heading lh-1 sidebar-link d-flex align-items-center">
                <span class="sidebar-item-icon d-inline-block mr-3 text-muted fs-20">
                  <i class="fal fa-list"></i>
                </span>

                <span class="sidebar-item-text">Eventos pasados</span>
              </a>
            </li>-->
          <?php endif; ?>

          <?php /* if (verifyUserPermissions('egresos')) : ?>
            <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
              <a href="egresos" class="text-heading lh-1 sidebar-link d-flex align-items-center">
                <span class="sidebar-item-icon d-inline-block mr-3 text-muted fs-20">
                  <i class="fal fa-money-bill"></i>
                </span>
                <span class="sidebar-item-text">Egresos</span>
              </a>
            </li>
          <?php endif; */ ?>

          <?php if (!$_SESSION['session_user_children_id']) : ?>
            <!--<li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
              <a href="<?= $session_user_plan === 'Básico' ? 'mis-usuarios' : $session_target_free_plan; ?>" class="text-heading lh-1 sidebar-link">
                <span class="sidebar-item-icon d-inline-block mr-3 fs-20"><i class="fal fa-user"></i></span>
                <span class="sidebar-item-text">Colaboradores</span>
              </a>
            </li>-->
            <?= renderSidebarLink([
              'title'   => 'Colaboradores',
              'url'     => 'mis-usuarios',
              'module'  => 'mis-usuarios',
              'icon'    => 'fal fa-user',
          ]); ?>
          <?php endif; ?>
        </ul>
      </li>
    <?php endif; ?>


    <li class="list-group-item pt-6 pb-4">
      <h5 class="fs-13 letter-spacing-087 text-muted mb-3 text-uppercase px-3">Administración</h5>
      <ul class="list-group list-group-no-border rounded-lg">
        <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
          <a href="global" class="text-heading lh-1 sidebar-link">
            <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
              <i class="fal fa-tachometer"></i>
            </span>
            <span class="sidebar-item-text">Estadísticas Globales</span>
          </a>
        </li>

        <?= renderSidebarLink([
              'title'   => 'Negocios registrados',
              'url'     => 'negocios',
              'module'  => 'listar-negocios',
              'icon'    => 'fal fa-building',
        ]); ?>
        <?php if (verifyUserPermissions('listar-negocios')) : ?>
          <!--<li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
            <a href="negocios" class="text-heading lh-1 sidebar-link d-flex align-items-center">
              <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
                <i class="fal fa-building"></i>
              </span>
              <span class="sidebar-item-text">Negocios registrados</span>
            </a>
          </li>-->
        <?php endif; ?>

        <?php if (verifyUserPermissions('agregar-negocios')) : ?>
          <?php
          $add_business_attribute = $session_target_free_plan;
          $num_business = getNumBusiness();
          if ($session_user_plan === 'Básico' || $num_business < 1) $add_business_attribute = 'agregar-negocio';
          ?>
          <!--<li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
            <a href="<?= $add_business_attribute; ?>" class="text-heading lh-1 sidebar-link">
              <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
                <i class="fal fa-plus-circle"></i>
              </span>
              <span class="sidebar-item-text">Nuevo negocio</span>
            </a>
          </li>-->
        <?php endif; ?>
        <?= renderSidebarLink([
              'title'   => 'Nuevo negocio',
              'url'     => 'agregar-negocio',
              'module'  => 'agregar-negocios',
              'icon'    => 'fal fa-plus-circle',
        ]); ?>

      </ul>
    </li>
    <?php if ($_SESSION['session_user_level'] === 'Usuario Final') : ?>
      <li class="list-group-item pt-6 pb-4">
        <h5 class="fs-13 letter-spacing-087 text-muted mb-3 text-uppercase px-3">Invitaciones digitales</h5>
        <ul class="list-group list-group-no-border rounded-lg">
          <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
            <a href="invitaciones" class="text-heading lh-1 sidebar-link">
              <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
                <i class="fal fa-list"></i>
              </span>
              <span class="sidebar-item-text">Mis Invitaciones</span>
            </a>
          </li>

          <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
            <a href="nueva-invitacion" class="text-heading lh-1 sidebar-link">
              <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
                <i class="fal fa-plus-circle"></i>
              </span>
              <span class="sidebar-item-text">Nueva invitación</span>
            </a>
          </li>

          <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
            <a href="nueva-invitacion" class="text-heading lh-1 sidebar-link">
              <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
                <i class="fal fa-phone"></i>
              </span>
              <span class="sidebar-item-text">Quiero un diseño personalizado</span>
            </a>
          </li>
        </ul>
      </li>
    <?php endif ?>

    <?php if ($_SESSION['session_user_level'] === 'Super Usuario' || $_SESSION['session_user_level'] === 'Administrador') : ?>
      <li class="list-group-item pt-6 pb-4">
        <h5 class="fs-13 letter-spacing-087 text-muted mb-3 text-uppercase px-3">Configuración</h5>
        <ul class="list-group list-group-no-border rounded-lg">
          <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
            <a href="usuarios" class="text-heading lh-1 sidebar-link">
              <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
                <i class="fal fa-user"></i>
              </span>
              <span class="sidebar-item-text">Usuarios</span>
            </a>
          </li>

          <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
            <a href="proveedores" class="text-heading lh-1 sidebar-link">
              <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
                <i class="fal fa-user"></i>
              </span>
              <span class="sidebar-item-text">Proveedores</span>
            </a>
          </li>

          <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
            <a href="clientes" class="text-heading lh-1 sidebar-link">
              <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
                <i class="fal fa-user"></i>
              </span>
              <span class="sidebar-item-text">Clientes</span>
            </a>
          </li>
        </ul>
      </li>
    <?php endif; ?>

    <li class="list-group-item pt-6 pb-4">
      <h5 class="fs-13 letter-spacing-087 text-muted mb-3 text-uppercase px-3">Mi cuenta</h5>
      <ul class="list-group list-group-no-border rounded-lg">
        <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
          <a href="configurar-cuenta" class="text-heading lh-1 sidebar-link">
            <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
              <i class="fal fa-user"></i>
            </span>
            <span class="sidebar-item-text">Mi perfil</span>
          </a>
        </li>

        <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
          <a href="mi-suscripcion" class="text-heading lh-1 sidebar-link">
            <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
              <i class="fal fa-crown"></i>
            </span>

            <span class="sidebar-item-text">
              Mi suscripción
            </span>
          </a>
        </li>

        <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
          <a href="mis-ordenes" class="text-heading lh-1 sidebar-link">
            <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
              <i class="fal fa-file-invoice-dollar"></i>
            </span>

            <span class="sidebar-item-text">
              Mis órdenes
            </span>
          </a>
        </li>

        <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">
          <a href="cerrar-sesion" class="text-heading lh-1 sidebar-link">
            <span class="sidebar-item-icon d-inline-block mr-3 fs-20">
              <i class="fal fa-sign-out"></i>
            </span>
            <span class="sidebar-item-text">Cerrar sesión</span>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</div>