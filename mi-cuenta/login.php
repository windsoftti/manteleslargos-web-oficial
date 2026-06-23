<?php
include 'inc/session-login.php';
include 'inc/config.inc.php';
include 'inc/functions.inc.php';
?>
<?php $meta_title = 'Iniciar sesión'; ?>

<?php
$message = null;

if ($_GET) {
  $uid = cleanStr($_GET['uid']);

  if ($uid) {
    $query = "SELECT
        AccessToken,
        TokenStatus,
        Status
      FROM usuarios
      WHERE 
        AccessToken = '$uid' AND
        TokenStatus = 'Usado' AND
        Status      = 'Activo'
      LIMIT 1
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) $message = '¡Tu cuenta ha sido activada exitosamente!';
  }
}
?>

<!doctype html>
<html lang="es">

<head>
  <?php include 'inc/meta-tags.php'; ?>
</head>

<body style="background-image: linear-gradient(to right top, #e4b12f, #e7bc2e, #eac72e, #ebd22e, #ecde30);">
  <main id="content">
    <section class="py-13">
      <div class="container">
        <div class="row">
          <div class="col-lg-5 mx-auto">
            <div class="card border-0 shadow-xxs-2 login-register">
              <div class="card-body p-6">
                <h2 class="card-title fs-30 font-weight-600 text-dark lh-16 mb-2">Iniciar sesión</h2>

                <?php if ($message) : ?>
                  <div class="col-md-12 text-center">
                    <p class="mb-4 text-success" style="font-size: 1.2rem;"><?= $message; ?></p>
                  </div>
                <?php endif; ?>

                <p class="mb-4">Ingresa tus credenciales para continuar</p>
                <form id="login-form" class="form" autocomplete="off">
                  <div class="form-group">
                    <label for="userEmail" class="text-heading">Correo / Username</label>
                    <input type="text" name="userEmail" class="form-control form-control-lg border-0" id="userEmail" placeholder="Escribe tu correo o tu Username">
                  </div>

                  <div class="form-group">
                    <label for="userPassword" class="text-heading">Contraseña</label>
                    <input type="password" name="userPassword" class="form-control form-control-lg border-0" id="userPassword" placeholder="Ingresa tu contraseña">
                  </div>

                  <input type="hidden" name="action" value="logIn">

                  <button type="submit" class="btn btn-primary btn-lg btn-block rounded">
                    Iniciar sesión
                  </button>

                  <p>
                    ¿Olvidaste tus credenciales de acceso? <a href="recuperar-credenciales">Recuperar aqui.</a>
                  </p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- PAGE LOADING -->
    <?php include 'inc/page-loading.php' ?>
  </main>

  <?php include 'inc/required-scripts.php'; ?>
  <?php include 'inc/svg.php'; ?>

  <script src="js/functions.js"></script>
  <script src="main/authentication/logIn.js"></script>

  <div class="position-fixed pos-fixed-bottom-right p-6 z-index-10">
    <a href="#" class="gtf-back-to-top bg-white text-primary hover-white bg-hover-primary shadow p-0 w-52px h-52 rounded-circle fs-20 d-flex align-items-center justify-content-center" title="Back To Top"><i class="fal fa-arrow-up"></i></a>
  </div>
</body>

</html>