<?php include 'inc/session-auth.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>
</head>

<body class="hold-transition content-wrapper">
  <div class="d-flex full-height pl-2 pr-2 flex-column justify-content-between">
    <div class="d-none d-md-flex pl-3 pr-3 pt-3">
      <img src="src/assets/images/logo-with-text.svg" alt="logo-sistema">
    </div>
  </div>

  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-5">
        <div class="card mt-3">
          <div class="card-body login-card-body">
            <h4 class="mt-4 mb-2">Iniciar sesión</h4>
            <p class="mb-3 mb-4">Auntentícate para entrar al sistema.</p>

            <form id="login-form" class="needs-validation" autocomplete="off" novalidate>
              <div class="form-group mb-4">
                <label for="user">Correo / Usuario:</label>

                <div class="input-group">
                  <input type="text" id="user" name="user" class="form-control" placeholder="Escribe tu correo o tu Usuario" required>
                  <div class="input-group-append">
                    <span class="input-group-text">
                      <i class="fas fa-user"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="form-group mb-4">
                <label for="password">Contraseña:</label>

                <div class="input-group">
                  <input type="password" id="password" name="password" class="form-control" placeholder="Escribe tu contraseña" required>
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-lock"></span>
                    </div>
                  </div>
                </div>
              </div>

              <input type="hidden" name="action" value="login">

              <div class="form-group mt-4">
                <div class="d-flex align-items-center justify-content-between">
                  <span class="font-size-13 text-muted">
                    ¿Olvidaste tu contraseña?
                    <a href="recuperar-credenciales"> Recuperar aqui</a>
                  </span>
                  <button type="submit" class="btn btn-primary">
                    Iniciar sesión
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="offset-md-1 col-md-6 d-none d-md-block">
        <img class="img-fluid" src="src/assets/images/bg-login.png" alt="background">
      </div>
    </div>
  </div>

  <div class="position-fixed" style="bottom: 0; width:95%">
    <div class="d-none d-md-flex pb-0 px-5 justify-content-between">
      <span class="">© <?= date('Y'); ?> Manteles Largos</span>
      <ul class="list-inline">
        <li class="list-inline-item">
          <a class="text-dark text-link" href="javascript:void(0)">Legal</a>
        </li>
        <li class="list-inline-item">
          <a class="text-dark text-link" href="javascript:void(0)">Privacy</a>
        </li>
      </ul>
    </div>
  </div>

  <!-- Page loading -->
  <?php include 'src/components/page-loading.php'; ?>

  <!-- Required scripts -->
  <?php include 'src/components/required-scripts.php'; ?>

  <script src="src/plugins/bs-validator/bs-validator.js"></script>
  <script src="src/plugins/sweetalert/sweetalert.min.js"></script>
  <script src="src/plugins/sweetalert/sweetalert-functions.js"></script>

  <script src="src/js/login.js"></script>
</body>

</html>