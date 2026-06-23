<?php
include 'inc/user-session.php';

$uid = $_GET['uid'];

if ($uid !== md5('verificado')) :
  header('location:' . BASE_URL . '/cerrar-sesion');
  die;
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
            <form id="supplier-signup-form" class="card" autocomplete="off">
              <div class="card-heading no-margin">
                <ion-icon name="checkmark-circle" style="font-size: 6rem;color: green;"></ion-icon>
              </div>

              <div class="card-heading">
                <h3>Crear verificada</h3>
                <p>Tu cuenta de Manteles Largos se verificó correctamente, da click en el siguiente botón para continuar.</p>
              </div>

              <div class="form-group no-margin">
                <a class="btn btn-primary btn-large btn-block no-margin" href="<?= BASE_URL; ?>/mis-invitaciones">
                  Continuar
                </a>
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
</body>

</html>