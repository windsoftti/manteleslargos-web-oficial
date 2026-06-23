<?php
include 'inc/public-session.php';

if ($_SESSION['session_user_id'] && $_SESSION['session_user_level'] === 'Usuario') :
  $supplier_access = checkSupplierAccessStatus();

  if ($supplier_access['status'] == 'unverified') {
    header('location:' . BASE_URL . '/verificar-cuenta-proveedor');
  } else if ($supplier_access['status'] == 'no-business') {
    header('location:' . BASE_URL . '/agregar-negocio');
  } else if ($supplier_access['status'] == 'logged') {
    header('location:' . BASE_URL . '/mi-cuenta');
  } else {
    // header('location:' . BASE_URL);
  }
endif;
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>

  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/im-supplier.css">

  <!-- Pure css -->
  <link rel="stylesheet" href="https://unpkg.com/purecss@2.1.0/build/grids-min.css">
  <link rel="stylesheet" href="https://unpkg.com/purecss@2.1.0/build/grids-responsive-min.css">
</head>

<body class="navbar-white im-supplier">
  <!-- Preloader -->
  <?php include 'src/components/preloader.php'; ?>

  <!-- Navbar -->
  <?php include 'src/components/navbar.php'; ?>

  <!-- Main -->
  <main class="main">
    <section class="im-supplier-section">
      <div>
        <div class="pure-g">
          <div class="pure-u-1 pure-u-sm-3-5 pure-u-lg-2-3">
            <h1>¿ERES PROVEEDOR DE EVENTOS?</h1>
            <h2>¿Y QUIERES CATAPULTAR TU EMPRESA, PARA LLEGAR A MILES DE CLIENTES?</h2>

            <a class="banner-btn btn-primary" href="<?= BASE_URL; ?>/registro-proveedor">
              ¡REGISTRATE AHORA!
            </a>
          </div>

          <div class="pure-u-1 pure-u-sm-2-5 pure-u-lg-1-3">
            <form id="supplier-login-form" class="card" autocomplete="off">
              <div class="card-heading">
                <h3>Acceder</h3>

                <p>¿No tienes cuenta? <a href="<?= BASE_URL; ?>/registro-proveedor">Regístrate</a></p>
              </div>

              <div class="form-group">
                <input id="supplier-username" name="username" placeholder="Correo/Usuario" type="text" required>
              </div>

              <div class="form-group">
                <div class="input-group-append">
                  <input id="supplier-password" name="password" placeholder="Contraseña" type="password" required>

                  <div class="append">
                    <a class="icon btn-eye-password" href="javascript:void(0)">
                      <ion-icon name="eye-off-outline"></ion-icon>
                    </a>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <a href="<?= BASE_URL; ?>/recuperar-credenciales">¿Olvidaste tu contraseña?</a>
              </div>
              <div class="form-group">
                <?php include __DIR__ . '/data/lib/security/turnstile-component.php'; ?>
              </div>
              <div class="form-group">
                <button class="btn btn-block btn-black btn-large no-mt" type="submit">
                  ACCEDER
                </button>
              </div>

              <div id="supplier-login-alert" class="form-group" style="padding-bottom: 0;"></div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Modal for login and register -->
    <?php include 'src/modals/login-register.php'; ?>

    <!-- Page loading -->
    <?php include 'src/components/page-loading.php'; ?>
  </main>

  <!-- Footer -->
  <?php include 'src/components/footer.php'; ?>

  <!-- Required scripts -->
  <?php include 'src/components/required-scripts.php'; ?>

  <script src="<?= BASE_URL; ?>/src/js/im-supplier.js"></script>
</body>

</html>