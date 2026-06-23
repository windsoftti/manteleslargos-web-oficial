<div id="navbar" class="navbar">
  <div class="brand">
    <a class="desktop" href="<?= BASE_URL; ?>">
      <img src="<?= BASE_URL; ?>/src/assets/images/manteleslargos_logo.svg" alt="Manteles Largos">
    </a>

    <?php if ($_SESSION['session_user_id'] && $_SESSION['session_user_level'] == 'Usuario') :
      $btn_access = checkSupplierAccessStatus();
      $btn_target = $btn_access['status'] == 'logged' ? 'target="_blank"' : '';
    ?>
      <a <?= $btn_target; ?> class="im-supplier-btn mobile text-center btn-primary-light" href="<?= BASE_URL; ?>/mi-cuenta">
        ACCESO A PROVEEDORES
      </a>
    <?php endif; ?>

    <?php if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario') : ?>
      <a class="im-supplier-btn mobile text-center btn-primary-light" href="<?= BASE_URL; ?>/soy-proveedor">
        ACCESO A PROVEEDORES
      </a>
    <?php endif; ?>
  </div>

  <div id="navbar-toggle" class="toggle">
    <div>
      <div class="bar1"></div>
      <div class="bar2"></div>
      <div class="bar3"></div>
    </div>

    <h5>MENU</h5>
  </div>

  <div class="list">
    <div class="brand desktop">
      <a href="<?= BASE_URL; ?>">
        <img src="<?= BASE_URL; ?>/src/assets/images/manteleslargos_logo.svg" alt="Manteles Largos">
      </a>
    </div>

    <ul class="left">
      <li>
        <a href="<?= BASE_URL; ?>">
          Inicio
        </a>
      </li>

      <li>
        <div class="submenu">
          <a class="submenu-toggle nospace" href="javascript:void(0)">
            Tipos de eventos
          </a>

          <?= navbarEventTypes(); ?>
        </div>
      </li>

      <li>
        <a href="<?= BASE_URL; ?>/tips">
          Tips
        </a>
      </li>

      <li>
        <?php if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario Final') : ?>
          <a class="tab-toggle position-relative justify-start gap-05" data-toggle="modal" data-target="modal-login-register" data-content="tab-login" href="javascript:void(0)">
            Crear invitación

            <span class="badge badge-primary absolute-lg-badge">
              <ion-icon name="add"></ion-icon>
            </span>
          </a>
        <?php endif; ?>

        <?php if ($_SESSION['session_user_id'] && $_SESSION['session_user_level'] == 'Usuario Final') : ?>
          <a class="position-relative justify-start gap-05" href="<?= BASE_URL; ?>/crear-invitacion">
            Crear invitación
            <span class="badge badge-primary absolute-lg-badge">
              <ion-icon name="add"></ion-icon>
            </span>
          </a>
        <?php endif; ?>
      </li>
    </ul>

    <div class="navbar-divider"></div>

    <ul class="right">
      <?php if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario Final') : ?>
        <li class="desktop">
          <a id="btn-navbar-access" class="tab-toggle" data-toggle="modal" data-target="modal-login-register" data-content="tab-login" href="javascript:void(0)">
            Acceso
          </a>
        </li>

        <li class="desktop">
          <a class="tab-toggle" data-toggle="modal" data-target="modal-login-register" data-content="tab-create-account" href="javascript:void(0)">
            Registrarme
          </a>
        </li>
      <?php endif; ?>

      <?php if ($_SESSION['session_user_id'] && $_SESSION['session_user_level'] == 'Usuario Final') : ?>
        <li class="desktop">
          <!-- <a class="action" href="<?= BASE_URL ?>/mis-invitaciones">
            <div>
              <ion-icon name="person-circle-outline"></ion-icon>
            </div>

            <div class="extra-content">
              <h4>Bienvenido</h4>
              <h3 class="nospace"><?= explode(' ', $_SESSION['session_user_name'])[0]; ?></h3>
            </div>
          </a> -->

          <div class="submenu">
            <a class="submenu-toggle" href="javascript:void(0)">
              <ion-icon name="person-circle-outline" style="margin-right: 0.5rem;"></ion-icon>
              Hola <?= explode(' ', $_SESSION['session_user_name'])[0]; ?>
            </a>

            <ul class="submenu-content">
              <li>
                <a href="<?= BASE_URL; ?>/configuracion-cuenta" style="align-items: center; justify-content: flex-start;">
                  <ion-icon name="person-circle-outline"></ion-icon>
                  <p style="margin:0;margin-left:1rem;">
                    Mi cuenta
                  </p>
                </a>
              </li>

              <li>
                <a href="<?= BASE_URL; ?>/mis-invitaciones" style="align-items: center; justify-content: flex-start;">
                  <ion-icon name="newspaper-outline"></ion-icon>
                  <p style="margin:0;margin-left:1rem;">
                    Mis invitaciones
                  </p>
                </a>
              </li>

              <li>
                <a href="<?= BASE_URL; ?>/mis-cotizaciones" style="align-items: center; justify-content: flex-start;">
                  <ion-icon name="attach-outline"></ion-icon>
                  <p style="margin:0;margin-left:1rem;">
                    Mis cotizaciones
                  </p>
                </a>
              </li>

              <li>
                <a href="<?= BASE_URL; ?>/cerrar-sesion" style="align-items: center; justify-content: flex-start;">
                  <ion-icon name="log-out-outline"></ion-icon>
                  <p style="margin:0;margin-left:1rem;">
                    Cerrar sesión
                  </p>
                </a>
              </li>
            </ul>
          </div>
        </li>
      <?php endif; ?>

      <?php if ($_SESSION['session_user_id'] && $_SESSION['session_user_level'] == 'Usuario') : ?>
        <?php
        $btn_access = checkSupplierAccessStatus();
        $btn_target = $btn_access['status'] == 'logged' ? 'target="_blank"' : '';
        ?>

        <li class="desktop">
          <a class="im-supplier-btn-desktop btn-primary-light text-center" <?= $btn_target; ?> href="<?= BASE_URL; ?>/mi-cuenta">
            ACCESO A PROVEEDORES
          </a>
        </li>
      <?php endif; ?>

      <?php if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario') : ?>
        <li class="desktop">
          <a class="im-supplier-btn-desktop btn-primary-light text-center" href="<?= BASE_URL; ?>/soy-proveedor">
            ACCESO A PROVEEDORES
          </a>
        </li>
      <?php endif; ?>

      <!-- <li class="mobile">
        <a class="font-light" href="javascript:void(0)">
          DESCARGA LA APP
        </a>
      </li> -->
    </ul>
  </div>

  <?php if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario Final') : ?>
    <div class="user-icon">
      <a class="icon large tab-toggle" data-toggle="modal" data-target="modal-login-register" data-content="tab-login" href="javascript:void(0)">
        <div>
          <ion-icon name="person-circle-outline"></ion-icon>
        </div>
      </a>
    </div>
  <?php endif; ?>

  <?php if ($_SESSION['session_user_id'] && $_SESSION['session_user_level'] == 'Usuario Final') : ?>
    <div class="user-icon">
      <div class="cs-dropdown">
        <a href="javascript:void(0)">
          <ion-icon name="person-circle-outline"></ion-icon>
          Hola <?= explode(' ', $_SESSION['session_user_name'])[0]; ?>
        </a>

        <ul>
          <li>
            <a href="<?= BASE_URL; ?>/configuracion-cuenta" style="align-items: center; justify-content: flex-start;">
              <ion-icon name="person-circle-outline"></ion-icon>
              Mi cuenta
            </a>
          </li>

          <li>
            <a href="<?= BASE_URL; ?>/mis-invitaciones" style="align-items: center; justify-content: flex-start;">
              <ion-icon name="newspaper-outline"></ion-icon>
              Mis invitaciones
            </a>
          </li>

          <li>
            <a href="<?= BASE_URL; ?>/mis-invitaciones" style="align-items: center; justify-content: flex-start;">
              <ion-icon name="attach-outline"></ion-icon>
              Mis cotizaciones
            </a>
          </li>

          <li>
            <a href="<?= BASE_URL; ?>/cerrar-sesion" style="align-items: center; justify-content: flex-start;">
              <ion-icon name="log-out-outline"></ion-icon>
              Cerrar sesión
            </a>
          </li>
        </ul>
      </div>
    </div>
  <?php endif; ?>
</div>