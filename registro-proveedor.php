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
  <style>
    .hidden{
      display:none;
    }
  </style>
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
          <div class="pure-u-1 pure-u-sm-4-5 pure-u-lg-2-5" style="margin: 0 auto;padding: 0;">
            <form id="supplier-signup-form" class="card" autocomplete="off">
              <div class="card-heading">
                <h3>Crear cuenta</h3>
                <p>¿Ya tienes cuenta? <a href="<?= BASE_URL; ?>/soy-proveedor">Acceder</a></p>
              </div>

              <div id="supplier-signup-alert" class="form-group"></div>

              <div class="modal-heading align-start">
                <p>Datos personales</p>
              </div>

              <div class="pure-g">
                <div class="pure-u-1 pure-u-sm-1-2">
                  <div class="form-group">
                    <input id="supplier-signup-fullName" name="fullName" placeholder="Nombre completo" type="text" required>
                  </div>
                </div>

                <div class="pure-u-1 pure-u-sm-1-2">
                  <div class="form-group">
                    <input id="supplier-signup-email" name="email" value="" placeholder="Correo electrónico" type="email" required>
                  </div>
                </div>
              </div>

              <div class="pure-g">
                <div class="pure-u-1 pure-u-sm-1-2">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="prepend">
                        <p>+52</p>
                      </div>
                      <input id="supplier-signup-phone" name="phone" placeholder="Teléfono" maxlength="10" type="number" required>
                    </div>
                  </div>
                </div>
              </div>

              <div class="modal-heading align-start ">
                <p>Datos de acceso</p>
              </div>

              <div class="pure-g ">
                <div class="pure-u-1 pure-u-sm-1-2">
                  <div class="form-group">
                    <input id="supplier-signup-username" value="" class="clean" name="username" placeholder="Usuario" type="text" required>
                  </div>
                </div>
              </div>

              <div class="pure-g ">
                <div class="pure-u-1 pure-u-sm-1-2">
                  <div class="form-group">
                    <div class="input-group-append">
                      <input id="supplier-signup-password" value="" name="password" placeholder="Contraseña" type="password" required>

                      <div class="append">
                        <a class="icon btn-eye-password" href="javascript:void(0)">
                          <ion-icon name="eye-off-outline"></ion-icon>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="pure-u-1 pure-u-sm-1-2">
                  <div class="form-group">
                    <div class="input-group-append">
                      <input id="supplier-signup-confirmatePassword" value="" name="confirmatePassword" placeholder="Confirmar contraseña" type="password" required>

                      <div class="append">
                        <a class="icon btn-eye-password" href="javascript:void(0)">
                          <ion-icon name="eye-off-outline"></ion-icon>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <?php include __DIR__ . '/data/lib/security/turnstile-component.php'; ?>
              </div>
              <div class="form-group">
                <button class="btn btn-block btn-black btn-large no-mt" type="submit">
                  CREAR CUENTA
                </button>
              </div>
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