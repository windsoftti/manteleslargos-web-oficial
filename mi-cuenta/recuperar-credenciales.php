<?php
include 'inc/session-login.php';
include 'inc/config.inc.php';
include 'inc/functions.inc.php';

$meta_title = 'Recuperar credenciales'; ?>

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
                <h2 class="card-title fs-30 font-weight-600 text-dark lh-16 mb-2">Recuperar credenciales</h2>

                <p class="mb-4">Escribe tu correo electrónico para continuar</p>
                <form id="recover-credentials-form" class="form" autocomplete="off">
                  <div class="form-group">
                    <label for="userEmail" class="text-heading">Correo</label>
                    <input type="email" name="userEmail" class="form-control form-control-lg border-0" id="userEmail">
                  </div>

                  <input type="hidden" name="action" value="recover_credentials">

                  <button type="submit" class="btn btn-primary btn-lg btn-block rounded">
                    Recuperar
                  </button>
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
  <script src="main/authentication/recover-credentials.js"></script>

  <div class="position-fixed pos-fixed-bottom-right p-6 z-index-10">
    <a href="#" class="gtf-back-to-top bg-white text-primary hover-white bg-hover-primary shadow p-0 w-52px h-52 rounded-circle fs-20 d-flex align-items-center justify-content-center" title="Back To Top"><i class="fal fa-arrow-up"></i></a>
  </div>
</body>

</html>