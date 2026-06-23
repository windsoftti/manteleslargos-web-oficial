<nav class="main-header navbar navbar-expand navbar-primary navbar-dark border-0">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">
    <!-- <li class="nav-item">
      <a class="nav-link" data-widget="navbar-search" href="#" role="button">
        <i class="fas fa-search"></i>
      </a>

      <div class="navbar-search-block">
        <form class="form-inline">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>
              <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li> -->

    <!-- Notifications -->
    <!-- <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">15</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header">15 Notifications</span>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-envelope mr-2"></i> 4 new messages
          <span class="float-right text-muted text-sm">3 mins</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-users mr-2"></i> 8 friend requests
          <span class="float-right text-muted text-sm">12 hours</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-file mr-2"></i> 3 new reports
          <span class="float-right text-muted text-sm">2 days</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
      </div>
    </li> -->

    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="javascript:void(0)" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>

    <li class="nav-item dropdown">
      <a href="#" class="dropdown-toggle nav-link align-middle" data-widget="profile" data-toggle="dropdown">
        <?php if (strlen($_SESSION['adm_session_user_full_name']) > 11) : ?>
          <?= substr($_SESSION['adm_session_user_full_name'], 0, 11) ?>...<b class="caret"></b>
        <?php endif; ?>

        <?php if (strlen($_SESSION['adm_session_user_full_name']) <= 11) : ?>
          <?= $_SESSION['adm_session_user_full_name'] ?><b class="caret"></b>
        <?php endif; ?>
      </a>

      <ul class="dropdown-menu pt-0">
        <li class="text-center">
          <div class="mb-2 pb-2 pt-2 bg-primary">
            <div>
              <i class="fa fa-user-circle fa-4x"></i>
            </div>

            <h1 class="text-sm mt-1 mb-0"><?= $_SESSION['adm_session_user_full_name'] ?></h1>
            <span class="text-sm text-light mt-1" style="opacity: 0.8;"><?= $_SESSION['adm_session_user_type'] ?></span>
          </div>

          <a href="cerrar-sesion" class="btn btn-sm btn-default">
            <i class="fa fa-sign-out-alt mr-1"></i> Cerrar sesión
          </a>
        </li>
      </ul>
    </li>
  </ul>
</nav>