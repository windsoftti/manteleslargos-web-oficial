<?php
$navbar_autentication_google_pulse = $_GET['uid'] === 'googlelogin' ? 'pulse' : '';
$navbar_autentication_facebook_pulse = $_GET['uid'] === 'facebooklogin' ? 'pulse' : '';
?>


<?php if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario Final') : ?>
  <div id="modal-login-register" class="modal">
    <div class="modal-content modal-sm" autocomplete="off">
      <div class="modal-header-secondary">
        <span data-toggle="dismiss" class="close">&times;</span>
      </div>

      <div class="modal-body">
        <div class="tabs">
          <div class="tabs-header">
            <a class="active" href="javascript:void(0)" data-content="tab-login">
              INICIAR SESIÓN
            </a>

            <a href="javascript:void(0)" data-content="tab-create-account">
              CREAR CUENTA
            </a>
          </div>

          <div class="tabs-body">
            <div id="tab-login" class="tab-padding active">
              <form id="navbar-login-form" autocomplete="off">
                <div class="modal-heading">
                  <h3>Acceder</h3>
                </div>

                <div class="form-group">
                  <input id="login-modal-username" class="clean" name="username" placeholder="Correo/Usuario" type="text" required>
                </div>

                <div class="form-group">
                  <div class="input-group-append">
                    <input id="login-modal-password" name="password" placeholder="Contraseña" type="password" required>

                    <div class="append">
                      <a class="icon btn-eye-password" href="javascript:void(0)">
                        <ion-icon name="eye-off-outline"></ion-icon>
                      </a>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <a class="tab-toggle" data-content="tab-recover-password" href="javascript:vid(0)">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="form-group">
                  <?php include __DIR__ . '/../../data/lib/security/turnstile-component.php'; ?>
                </div>
                <div class="form-group">
                  <button class="btn btn-block btn-black btn-large no-mt" type="submit">
                    ACCEDER
                  </button>
                </div>

                <div id="navbar-login-alert" class="form-group"></div>

                <div class="modal-heading align-start">
                  <p>¿No tienes cuenta? <a class="tab-toggle" data-content="tab-create-account" href="javascript:void(0)">Regístrate</a></p>
                </div>

                <div class="form-divider">Ó</div>

                <div class="form-group">
                  <a href="<?= $google_client->createAuthUrl(); ?>" class="btn btn-block btn-large no-mt social btn-google <?= $navbar_autentication_google_pulse; ?>">
                    <img src="<?= BASE_URL; ?>/src/assets/images/google.png">
                    Continuar con google
                  </a>
                </div>

                <div class="form-group">
                  <a href="<?= $facebook_helper->getLoginUrl($facebook_redirect_URL, $facebook_permissions); ?>" class="btn btn-block btn-large no-mt social btn-facebook <?= $navbar_autentication_facebook_pulse; ?>" type="button">
                    <ion-icon name="logo-facebook"></ion-icon>
                    Continuar con facebook
                  </a>
                </div>
              </form>
            </div>

            <div id="tab-create-account" class="tab-padding">
              <form id="navbar-signup-form" autocomplete="off">
                <div class="modal-heading">
                  <h3>Crear cuenta</h3>
                  <p>¿Ya tienes cuenta? <a class="tab-toggle" data-content="tab-login" href="javascript:void(0)">Acceder</a></p>
                </div>

                <div id="navbar-signup-alert-top" class="form-group"></div>

                <div class="form-group">
                  <input id="register-modal-fullName" name="fullName" placeholder="Nombre completo" type="text" required>
                </div>

                <div class="form-group">
                  <input id="register-modal-email" name="email" placeholder="Email" type="email" required>
                </div>

                <div class="form-group">
                  <div class="input-group">
                    <div class="prepend">
                      <p>+52</p>
                    </div>
                    <input id="register-modal-cellPhone" class="input-number" name="cellPhone" placeholder="Whatsapp/Celular" maxlength="10" type="text" required>
                  </div>
                </div>

                <div class="form-group">
                  <input id="register-modal-country" name="country" placeholder="País" type="text" value="México" required readonly>
                </div>

                <div class="form-group">
                  <select id="register-modal-state" name="state" required>
                    <?= statesForSelect('Estado'); ?>
                  </select>
                </div>

                <div class="form-group">
                  <input id="register-modal-username" name="username" placeholder="Usuario" type="text" required>
                </div>

                <div class="form-group">
                  <div class="input-group-append">
                    <input id="register-modal-password" name="password" placeholder="Contraseña" type="password" required>

                    <div class="append">
                      <a class="icon btn-eye-password" href="javascript:void(0)">
                        <ion-icon name="eye-off-outline"></ion-icon>
                      </a>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group-append">
                    <input id="register-modal-confirmatePassword" name="confirmatePassword" placeholder="Confirmar contraseña" type="password" required>

                    <div class="append">
                      <a class="icon btn-eye-password" href="javascript:void(0)">
                        <ion-icon name="eye-off-outline"></ion-icon>
                      </a>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <?php include __DIR__ . '/../../data/lib/security/turnstile-component.php'; ?>
                </div>
                <div class="form-group">
                  <button class="btn btn-block btn-black btn-large no-mt" type="submit">
                    CREAR CUENTA
                  </button>
                </div>

                <div id="navbar-signup-alert" class="form-group"></div>

                <div class="form-divider">Ó</div>

                <div class="form-group">
                  <a href="<?= $google_client->createAuthUrl(); ?>" class="btn btn-block btn-large no-mt social btn-google <?= $navbar_autentication_google_pulse; ?>">
                    <img src="<?= BASE_URL; ?>/src/assets/images/google.png">
                    Continuar con google
                  </a>
                </div>

                <div class="form-group">
                  <a href="<?= $facebook_helper->getLoginUrl($facebook_redirect_URL, $facebook_permissions); ?>" class="btn btn-block btn-large no-mt social btn-facebook <?= $navbar_autentication_facebook_pulse; ?>" type="button">
                    <ion-icon name="logo-facebook"></ion-icon>
                    Continuar con facebook
                  </a>
                </div>
              </form>
            </div>

            <div id="tab-recover-password" class="tab-padding">
              <form id="navbar-recover-password-form" autocomplete="off">
                <div class="modal-heading">
                  <h3>Recuperar credenciales</h3>
                  <p>¿Ya tienes cuenta? <a class="tab-toggle" data-content="tab-login" href="javascript:void(0)">Acceder</a></p>
                  <p>¿No tienes cuenta? <a class="tab-toggle" data-content="tab-create-account" href="javascript:void(0)">Regístrate</a></p>
                </div>

                <div class="form-group">
                  <div class="input-group">
                    <input id="recover-password-modal-email" name="email" placeholder="Correo" type="email" required>

                    <div class="prepend">
                      <ion-icon name="mail"></ion-icon>
                    </div>
                  </div>
                </div>

                <!-- <div class="form-group">
                  <a class="tab-toggle" data-content="tab-recover-password" href="javascript:vid(0)">¿Olvidaste tu contraseña?</a>
                </div> -->

                <div class="form-group">
                  <button class="btn btn-block btn-black btn-large no-mt" type="submit">
                    RECUPERAR
                  </button>
                </div>

                <div id="navbar-recover-password-alert" class="form-group"></div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>