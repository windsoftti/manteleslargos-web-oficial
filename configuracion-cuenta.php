<?php

use Google\Service\DriveActivity\Edit;

include 'inc/user-session.php';
$user_data = getFinalUserDataById($_SESSION['session_user_id']);

if (!$user_data) :
  header('location:' . BASE_URL . '/cerrar-sesion');
  die();
endif;
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>

  <!-- Pure css -->
  <link rel="stylesheet" href="https://unpkg.com/purecss@2.1.0/build/grids-min.css">
  <link rel="stylesheet" href="https://unpkg.com/purecss@2.1.0/build/grids-responsive-min.css">

  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/invitations.css">
</head>

<body class="navbar-white">
  <!-- Preloader -->
  <?php include 'src/components/preloader.php'; ?>

  <!-- Navbar -->
  <?php include 'src/components/navbar.php'; ?>

  <!-- Main -->
  <main class="main">
    <!-- Invitation navbar -->
    <?php /* include 'src/components/invitations-navbar.php'; */ ?>

    <section class="pure-g" style="margin-top: 1rem;">
      <div class="pure-u-1 pure-u-md-3-5 pure-u-lg-3-5 mx-auto">
        <div class="card">
          <div class="card-heading">
            <h3>Actualizar mi perfil</h3>
          </div>

          <div class="pure-g">
            <div class="pure-u-1 pure-u-lg-1-3" style="text-align: center;">
              <ion-icon name="person-circle-outline" style="
                font-size: 5rem;
                color:var(--primary-color);
              "></ion-icon>

              <div class="card-heading">
                <h3><small><?= $user_data['Usuario']; ?></small></h3>
                <p>
                  <strong>Correo</strong><br><?= $user_data['Correo']; ?>
                  <?php if ($user_data['Telefono']) : ?>
                    <br>
                    <strong>Teléfono</strong><br><?= $user_data['Telefono']; ?><br>
                  <?php endif; ?>
                </p>
              </div>
            </div>

            <div class="pure-u-1 pure-u-lg-2-3">
              <form id="my-account-form" method="POST" autocomplete="off">
                <div class="pure-g">
                  <div class="pure-u-1 pure-u-md-1-2">
                    <div class="form-group">
                      <label for="my-account-fullname">Nombre completo<span>*</span></label>
                      <input id="my-account-fullname" name="fullname" value="<?= $user_data['Usuario']; ?>" type="text" required>
                    </div>
                  </div>

                  <?php if ($user_data['AccessType'] === 'Manteles Largos') : ?>
                    <div class="pure-u-1 pure-u-md-1-2">
                      <div class="form-group">
                        <label for="my-account-email">Correo electrónico<span>*</span></label>
                        <input id="my-account-email" name="email" value="<?= $user_data['Correo']; ?>" type="email" required>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>

                <div class="pure-g">
                  <div class="pure-u-1 pure-u-md-1-2">
                    <div class="form-group">
                      <label for="my-account-phone">Teléfono<span>*</span></label>
                      <input id="my-account-phone input-number" name="phone" value="<?= $user_data['Telefono']; ?>" type="number" required>
                    </div>
                  </div>
                </div>

                <div class="pure-g">
                  <div class="pure-u-1 pure-u-md-1-2">
                    <div class="form-group">
                      <label for="my-account-country">País<span>*</span></label>
                      <input id="my-account-country" name="country" value="México" type="text" required readonly>
                    </div>
                  </div>

                  <div class="pure-u-1 pure-u-md-1-2">
                    <div class="form-group">
                      <label for="my-account-state">Estado<span>*</span></label>
                      <select id="my-account-state" name="state" required>
                        <?= statesForSelect('--Seleccionar--', $user_data['idEstado']); ?>
                      </select>
                    </div>
                  </div>
                </div>

                <?php if ($user_data['AccessType'] === 'Google') : ?>
                  <div class="form-group">
                    <div class="alert error w100">
                      <div>
                        <ion-icon name="logo-google"></ion-icon>
                      </div>
                      <p>Esta cuenta fué creada con la red social: <strong>Google</strong></p>
                    </div>
                  </div>
                <?php endif; ?>

                <?php if ($user_data['AccessType'] === 'Facebook') : ?>
                  <div class="form-group">
                    <div class="alert info w100">
                      <div>
                        <ion-icon name="logo-facebook"></ion-icon>
                      </div>
                      <p>Esta cuenta fué creada con la red social: <strong>Facebook</strong></p>
                    </div>
                  </div>
                <?php endif; ?>

                <?php if ($user_data['AccessType'] === 'Manteles Largos') : ?>
                  <div class="pure-g">
                    <div class="pure-u-1 pure-u-md-1-2">
                      <div class="form-group">
                        <label for="my-account-username">Nombre de usuario<span>*</span></label>
                        <input id="my-account-username" name="username" type="text" value="<?= $user_data['Username']; ?>" required readonly>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="checkbox-group">
                      <div>
                        <input id="change-password" name="changePassword" value="si" type="checkbox">
                        <label for="change-password">Cambiar contraseña</label>
                      </div>
                    </div>
                  </div>

                  <div id="password-fields" class="pure-g" style="display: none;">
                    <div class="pure-u-1 pure-u-md-1-2">
                      <div class="form-group">
                        <label for="my-account-password">Contraseña<span>*</span></label>
                        <div class="input-group-append">
                          <input id="my-account-password" name="password" type="password">

                          <div class="append">
                            <a class="icon btn-eye-password" href="javascript:void(0)">
                              <ion-icon name="eye-off-outline"></ion-icon>
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="pure-u-1 pure-u-md-1-2">
                      <div class="form-group">
                        <label for="my-account-confirmPassword">Confirmar contraseña<span>*</span></label>
                        <div class="input-group-append">
                          <input id="my-account-confirmPassword" name="confirmPassword" type="password">

                          <div class="append">
                            <a class="icon btn-eye-password" href="javascript:void(0)">
                              <ion-icon name="eye-off-outline"></ion-icon>
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>

                <div class="form-group">
                  <button class="btn btn-primary btn-block" type="submit">
                    Guardar cambios
                  </button>
                </div>

                <div id="my-account-alert" class="form-group"></div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Page loading -->
    <?php include 'src/components/page-loading.php'; ?>
  </main>

  <!-- Footer -->
  <?php include 'src/components/footer.php'; ?>

  <!-- Required scripts -->
  <?php include 'src/components/required-scripts.php'; ?>

  <script>
    $('#change-password').on('change', function() {
      const isChecked = $(this).is(':checked');

      if (isChecked) {
        $('#password-fields').slideDown();
        $('#my-account-password').attr('required', true);
        $('#my-account-confirmPassword').attr('required', true);
      }

      if (!isChecked) {
        $('#password-fields').slideUp();
        $('#my-account-password').removeAttr('required');
        $('#my-account-confirmPassword').removeAttr('required');
      }
    });
  </script>
</body>

</html>