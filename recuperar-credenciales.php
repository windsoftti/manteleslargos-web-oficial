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
          <div class="pure-u-1 pure-u-sm-4-5 pure-u-lg-2-5" style="margin: 0 auto;padding: 0;">
            <form id="supplier-recover-password-form" class="card" autocomplete="off">
              <div class="card-heading">
                <h3>¿Olvidaste tu contraseña?</h3>
                <p>Escribe el correo electrónico que registraste para reestablecer tu contraseña</p>
              </div>

              <div id="supplier-recover-password-alert" class="form-group"></div>

              <div class="pure-g">
                <div class="pure-u-1">
                  <div class="form-group">
                    <div class="input-group">
                      <input id="supplier-recover-password-email" name="email" placeholder="Correo electrónico" type="email" required>

                      <div class="prepend">
                        <ion-icon name="mail"></ion-icon>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group flex-row justify-end align-center gap-1" style="margin-top: 1rem;">
                <a class="btn btn-large no-margin" href="<?= BASE_URL; ?>">
                  CANCELAR
                </a>

                <button class="btn btn-black btn-large no-margin" type="submit">
                  RECUPERAR
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