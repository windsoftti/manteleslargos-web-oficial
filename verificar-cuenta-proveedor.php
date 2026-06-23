<?php
include 'inc/public-session.php';

if (!$_SESSION['session_user_id']) :
  header('location:' . BASE_URL);
  die();
endif;

if ($_SESSION['session_user_level'] != 'Usuario') :
  header('location:' . BASE_URL);
  die();
endif;

if ($_SESSION['session_user_id'] && $_SESSION['session_user_level'] === 'Usuario') :
  $supplier_access = checkSupplierAccessStatus();

  if ($supplier_access['status'] == 'unverified') {
    //header('location:' . BASE_URL . '/verificar-cuenta-proveedor');
  } else if ($supplier_access['status'] == 'no-business') {
    header('location:' . BASE_URL . '/agregar-negocio');
  } else if ($supplier_access['status'] == 'logged') {
    header('location:' . BASE_URL . '/mi-cuenta');
  } else {
    header('location:' . BASE_URL . '/cerrar-sesion');
  }
endif;

$query = "SELECT Correo from usuarios WHERE idUsuario = $_SESSION[session_user_id] LIMIT 1";
$query_result = mysqli_query($mysqli, $query);
$user_ddta = mysqli_fetch_array($query_result);

$seconds_remaining = 0;

if (isset($_SESSION['verification_code_sent_at'])) {

    $elapsed = time() - $_SESSION['verification_code_sent_at'];

    $seconds_remaining = max(
        0,
        60 - $elapsed
    );
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>

  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/im-supplier.css">

  <!-- Pure css -->
  <link rel="stylesheet" href="https://unpkg.com/purecss@2.1.0/build/grids-min.css">
  <link rel="stylesheet" href="https://unpkg.com/purecss@2.1.0/build/grids-responsive-min.css">
  <style type="text/css">
    #resend-code-link {
      font-size: 14px;
      text-decoration: underline;
      cursor: pointer;
    }

    #resend-timer {
      font-size: 14px;
      margin-bottom: 0;
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
          <div class="pure-u-1 pure-u-sm-2-5 pure-u-lg-1-4" style="margin: 0 auto;padding: 0;">
            <form id="supplier-verify-account-form" class="card" autocomplete="off">
              <div class="card-heading">
                <h3>Verificar cuenta</h3>
                <p>Ingrese el código de verifiación que ha sido enviado a "<?= $user_ddta['Correo']; ?>"
                  No olvides revisar tu bandeja de SPAM</p>
              </div>

              <div class="form-group code">
                <div class="input-group">
                  <input id="supplier-password" class="input-number" name="code" minlength="4" maxlength="4" type="number" required>

                  <div class="prepend">
                    <p>ML-</p>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <button class="btn btn-block btn-black btn-large no-mt" type="submit">
                  VERIFICAR
                </button>
              </div>

              <div class="form-group text-center" style="display: block;">

                <p
                  id="resend-timer"
                  data-seconds="<?= $seconds_remaining; ?>"
                  <?= $seconds_remaining <= 0 ? 'style="display:none;"' : ''; ?>
                >
                  Reenviar, disponible en
                  <strong>
                    <span id="countdown">
                      <?= $seconds_remaining; ?>
                    </span>s
                  </strong>
                </p>

                <a
                  href="#"
                  id="resend-code-link"
                  <?= $seconds_remaining > 0 ? 'style="display:none;"' : ''; ?>
                >
                  Reenviar código
                </a>

              </div>

              <div id="supplier-verify-acccount-alert" class="form-group" style="padding-bottom: 0;"></div>
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