<div id="modal-request-quote" class="modal">
  <div class="modal-content modal-sm" autocomplete="off">
    <div class="modal-header">
      <h2 class="modal-title">Solicitar Cotización</h2>
      <span data-toggle="dismiss" class="close">&times;</span>
    </div>

    <div class="modal-body" style="padding-top: 0;">
      <div class="tabs">
        <div class="tabs-body">
          <?php if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario Final') : ?>
            <div id="tab-quote-login" class="tab-padding active for-hide" style="padding-top: 0;">
              <form id="quote-login-form" autocomplete="off">
                <div class="modal-heading">
                  <h3>Acceder</h3>
                  <p>Accede a tu cuenta para solicitar una cotización</p>
                </div>

                <div class="form-group">
                  <input id="login-quote-username" class="clean" name="username" placeholder="Correo/Usuario" type="text" required>
                </div>

                <div class="form-group">
                  <div class="input-group-append">
                    <input id="login-quote-password" name="password" placeholder="Contraseña" type="password" required>

                    <div class="append">
                      <a class="icon btn-eye-password" href="javascript:void(0)">
                        <ion-icon name="eye-off-outline"></ion-icon>
                      </a>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <a href="#">¿Olvidaste tu contraseña?</a>
                </div>

                <div class="form-group">
                  <button class="btn btn-block btn-black btn-large no-mt" type="submit">
                    ACCEDER
                  </button>
                </div>

                <div id="quote-login-alert" class="form-group" style="padding-bottom: 0;"></div>

                <div class="modal-heading align-start">
                  <p>¿No tienes cuenta? <a class="tab-toggle" data-content="tab-quote-create-account" href="javascript:void(0)">Registrate</a></p>
                </div>

                <div class="form-divider">Ó</div>

                <div class="form-group">
                  <a class="btn btn-block btn-large no-mt tab-toggle" data-content="tab-quote-request-quote" href="javascript:void(0)">
                    SOLICITAR COMO INVITADO
                  </a>
                </div>
              </form>
            </div>

            <div id="tab-quote-create-account" class="tab-padding for-hide" style="padding-top: 0;">
              <form id="quote-signup-form" autocomplete="off">
                <div class="modal-heading">
                  <h3>Crear cuenta</h3>
                  <p>¿Ya tienes cuenta? <a class="tab-toggle" data-content="tab-quote-login" href="javascript:void(0)">Acceder</a></p>
                </div>

                <div class="form-group">
                  <input id="register-quote-fullName" name="fullName" placeholder="Nombre completo" type="text" required>
                </div>

                <div class="form-group">
                  <input id="register-quote-email" name="email" placeholder="Email" type="email" required>
                </div>

                <div class="form-group">
                  <div class="input-group">
                    <div class="prepend">
                      <p>+52</p>
                    </div>

                    <input id="register-quote-cellPhone" class="input-number" name="cellPhone" placeholder="Whatsapp/Celular" maxlength="10" type="text" required>
                  </div>
                </div>

                <div class="form-group">
                  <input id="register-quote-country" name="country" placeholder="Estado" type="text" value="México" required readonly>
                </div>

                <div class="form-group">
                  <select id="register-quote-state" name="state" required>
                    <?= statesForSelect('Estado'); ?>
                  </select>
                </div>

                <div class="form-group">
                  <input id="register-quote-username" name="username" placeholder="Usuario" type="text" required>
                </div>

                <div class="form-group">
                  <div class="input-group-append">
                    <input id="register-quote-password" name="password" placeholder="Contraseña" type="password" required>

                    <div class="append">
                      <a class="icon btn-eye-password" href="javascript:void(0)">
                        <ion-icon name="eye-off-outline"></ion-icon>
                      </a>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group-append">
                    <input id="register-quote-confirmatePassword" name="confirmatePassword" placeholder="Confirmar contraseña" type="password" required>

                    <div class="append">
                      <a class="icon btn-eye-password" href="javascript:void(0)">
                        <ion-icon name="eye-off-outline"></ion-icon>
                      </a>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <button class="btn btn-block btn-black btn-large no-mt" type="submit">
                    CREAR CUENTA
                  </button>
                </div>

                <div id="quote-signup-alert" class="form-group"></div>
              </form>
            </div>
          <?php endif; ?>

          <?php $id_for_tab = $_SESSION['session_user_id'] && $_SESSION['session_user_level'] == 'Usuario Final' ? 'tab-quote-login' : 'tab-quote-request-quote'; ?>

          <div id="<?= $id_for_tab; ?>" class="tab-padding" style="padding-top: 0;">
            <form id="request-quote-form" autocomplete="off">
              <?php if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario Final') : ?>
                <div class="modal-heading for-hide">
                  <h3>Solicitar cotización como invitado</h3>
                  <p>¿Ya tienes cuenta? <a class="tab-toggle" data-content="tab-quote-login" href="javascript:void(0)">Acceder</a></p>
                </div>
              <?php endif; ?>

              <div class="modal-heading">
                <p>Cotización para: <a href="javascript:void(0)"><?= $business_data['Salon']; ?></a></p>
              </div>

              <div class="form-group">
                <select id="quote-package" class="not-select2" name="package" required>
                  <?= businessPackagesForSelect('Selecciona tu paquete', $business_id); ?>
                </select>
              </div>

              <div class="form-group">
                <input id="quote-requestedDate" class="datepicker" name="requestedDate" placeholder="Fecha solicitada" type="text" required>
              </div>

              <div class="form-group">
                <select id="quote-eventType" class="not-select2" name="eventType" required>
                  <option value="">Tipo de evento</option>
                </select>
              </div>

              <div class="form-group">
                <input id="quote-fullName" name="fullName" placeholder="Nombre completo" type="text" value="<?= $_SESSION['session_user_name']; ?>" required>
              </div>

              <div class="form-group">
                <input id="quote-email" name="email" placeholder="Correo" type="email" value="<?= $_SESSION['session_user_email']; ?>" required>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <div class="prepend">
                    <p>+52</p>
                  </div>

                  <input id="quote-phone" class="number-input" name="phone" placeholder="Teléfono/Celular/Whatsapp" maxlength="10" type="text" required>
                </div>
              </div>

              <div class="form-group">
                <button class="btn btn-block btn-black btn-large no-mt" type="submit">
                  SOLICITAR COTIZACIÓN
                </button>
              </div>

              <div id="request-quote-alert" class="w-100"></div>
            </form>
          </div>

          <div id="tab-busy-hours" class="tab-padding" style="padding-top: 0;">
            <div class="modal-heading">
              <h3>Horarios ocupados</h3>
              <p id="busy-hours" class="text-left"></p>
            </div>

            <button class="btn btn-black btn-block btn-large tab-toggle mb-0" data-content="tab-quote-login" type="button">
              CONTINUAR
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="show-phone-modal" class="modal">
  <div class="modal-content" autocomplete="off">
    <div class="modal-header">
      <h2 class="modal-title">Contactar con el proveedor</h2>
      <span data-toggle="dismiss" class="close">&times;</span>
    </div>

    <div class="modal-body">
      <div class="modal-heading">
        <p>Al llamar, recuerda decir que has visto el anuncio en <b>Manteles Largos</b></p>
        <h3><b>+52 <?= formatPhoneNumber($business_data['Celular']); ?></b></h3>
      </div>

      <div class="form-group flex-row gap-1">
        <a class="btn btn-primary btn-sm flex-column flex-lg-row flex-1 h-sm-5 event-counter" data-event="click-llamar" href="tel:52<?= $business_data['Celular']; ?>">
          <ion-icon class="fs-sm-15" name="call-outline"></ion-icon>
          Pulsa para llamar
        </a>

        <a class="btn btn-success btn-sm flex-column flex-lg-row flex-1 h-sm-5 event-counter" data-event="click-whatsapp" target="_blank" href="https://wa.me/52<?= $business_data['Celular']; ?>?text=Vi%20tu%20sal%C3%B3n%20en%20Manteles%20Largos">
          <ion-icon class="fs-sm-15" name="logo-whatsapp"></ion-icon>
          Whatsapp
        </a>
      </div>
    </div>
  </div>
</div>