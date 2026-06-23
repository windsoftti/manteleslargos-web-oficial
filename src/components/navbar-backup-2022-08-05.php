<nav id="navbar" class="navbar">
  <div>
    <?php if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario Final') : ?>
      <div class="user-icon">
        <a class="icon white large tab-toggle" data-toggle="modal" data-target="modal-login-register" data-content="tab-login" href="javascript:void(0)">
          <div>
            <ion-icon name="person-circle-outline"></ion-icon>
          </div>
        </a>
      </div>
    <?php endif; ?>

    <?php if ($_SESSION['session_user_id'] && $_SESSION['session_user_level'] == 'Usuario Final') : ?>
      <div class="user-icon">
        <a class="icon white large" href="<?= BASE_URL ?>/mis-invitaciones">
          <div>
            <ion-icon name="person-circle-outline"></ion-icon>
          </div>
        </a>
      </div>
    <?php endif; ?>

    <div class="toggle">
      <div>
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div>
      </div>

      <h5>MENU</h5>
    </div>

    <!-- <div class="notification-icon">
      <a class="icon" href="javascript:void(0)">
        <div>
          <ion-icon name="notifications-outline"></ion-icon>
          <span class="badge">4</span>
        </div>
      </a>
    </div> -->

    <div class="brand">
      <img src="<?= BASE_URL; ?>/src/assets/images/manteleslargos_logo.svg" alt="Manteles Largos">
    </div>
  </div>

  <ul>
    <li>
      <a href="<?= BASE_URL; ?>">
        INICIO
      </a>
    </li>

    <li>
      <div class="submenu">
        <a class="submenu-toggle nospace" href="javascript:void(0)">TIPOS DE EVENTOS</a>

        <?= navbarEventTypes(); ?>
      </div>
    </li>

    <li>
      <a href="<?= BASE_URL; ?>/tips">
        TIPS
      </a>
    </li>

    <li>
      <div class="submenu">
        <a class="submenu-toggle" href="javascript:void(0)">HERRAMIENTAS</a>

        <ul class="submenu-content">
          <li>
            <?php if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario Final') : ?>
              <a class="tab-toggle" data-toggle="modal" data-target="modal-login-register" data-content="tab-login" href="javascript:void(0)" style="align-items: center;">
                <ion-icon name="newspaper-outline"></ion-icon>
                <p style="margin:0;margin-left:1rem;">
                  Crear invitación<br>
                  <small>¡Es completamente gratis!</small>
                </p>
              </a>
            <?php endif; ?>

            <?php if ($_SESSION['session_user_id'] && $_SESSION['session_user_level'] == 'Usuario Final') : ?>
              <a href="<?= BASE_URL; ?>/crear-invitacion" style="align-items: center;">
                <ion-icon name="newspaper-outline"></ion-icon>
                <p style="margin:0;margin-left:1rem;">
                  Crear invitación<br>
                  <small>¡Es completamente gratis!</small>
                </p>
              </a>
            <?php endif; ?>
          </li>
        </ul>
      </div>
    </li>

    <li class="desktop logo">
      <a href="<?= BASE_URL; ?>">
        <img src="<?= BASE_URL; ?>/src/assets/images/manteleslargos_logo.svg" alt="Manteles Largos Logo">
      </a>
    </li>

    <?php if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario Final') : ?>
      <li class="desktop">
        <a class="tab-toggle" data-toggle="modal" data-target="modal-login-register" data-content="tab-login" href="javascript:void(0)">
          ACCESO
        </a>
      </li>

      <li class="desktop">
        <a class="tab-toggle" data-toggle="modal" data-target="modal-login-register" data-content="tab-create-account" href="javascript:void(0)">
          REGISTRARME
        </a>
      </li>
    <?php endif; ?>

    <?php if ($_SESSION['session_user_id'] && $_SESSION['session_user_level'] == 'Usuario Final') : ?>
      <li class="desktop" style="margin-right: 2rem;">
        <a class="action" href="<?= BASE_URL ?>/mis-invitaciones">
          <div>
            <ion-icon name="person-circle-outline"></ion-icon>
          </div>

          <div class="extra-content">
            <h4>Mi cuenta</h4>
            <h3 class="nospace"><?= $_SESSION['session_user_name']; ?></h3>
          </div>
        </a>
      </li>
    <?php endif; ?>

    <?php if ($_SESSION['session_user_id'] && $_SESSION['session_user_level'] == 'Usuario') : ?>
      <li class="desktop">
        <a class="action" href="<?= BASE_URL ?>/mi-cuenta">
          <div>
            <ion-icon name="person-circle-outline"></ion-icon>
          </div>

          <div class="extra-content">
            <h4>Mi cuenta</h4>
            <h3 class="nospace"><?= $_SESSION['session_user_name']; ?></h3>
          </div>
        </a>
      </li>
    <?php endif; ?>

    <?php if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario') : ?>
      <li class="desktop">
        <a href="<?= BASE_URL; ?>/soy-proveedor">
          SOY PROVEEDOR
        </a>
      </li>

      <li class="white">
        <a href="javascript:void(0)">
          ACCESO EMPRESAS
        </a>
      </li>
    <?php endif; ?>

    <li class="white">
      <a href="javascript:void(0)">
        DESCARGA LA APP
      </a>
    </li>

    <!-- <li class="desktop">
      <a class="icon" href="javascript:void(0)">
        <div>
          <ion-icon name="notifications-outline"></ion-icon>
          <span class="badge">4</span>
        </div>
      </a>
    </li> -->
  </ul>
</nav>