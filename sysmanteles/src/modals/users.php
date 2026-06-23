<div class="modal fade" id="modal-users">
  <div class="modal-dialog modal-dialog-centered">
    <form id="users-form" class="modal-content needs-validation" autocomplete="off" novalidate>
      <div class="modal-header">
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title modal-dynamic-title"></h5>

          <button type="button" class="close" data-dismiss="modal">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>

      <div class="modal-body login-card-body pt-1">
        <h5>Datos generales</h5>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="fullName"><span class="text-danger">*</span>Nombre completo</label>
              <input type="text" class="form-control" name="fullName" id="fullName" required>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="email"><span class="text-danger">*</span>Correo</label>
              <input type="email" class="form-control" name="email" id="email" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="phone">Teléfono</label>
              <input type="text" class="form-control" name="phone" id="phone">
            </div>
          </div>
        </div>

        <div id="credentials">
          <h5 class="mt-3">Datos de cuenta</h5>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="username"><span class="text-danger">*</span>Username</label>
                <input type="text" class="form-control" name="username" id="username" required>
              </div>
            </div>

            <?php if ($_SESSION['adm_session_user_type'] === 'Root' || $_SESSION['adm_session_user_type'] === 'Administrator') : ?>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="userType"><span class="text-danger">*</span>Tipo de usuario</label>
                  <select class="form-control" name="userType" id="userType" required>
                    <option value="">Seleccionar</option>
                    <?php if ($_SESSION['adm_session_user_type'] === 'Root') : ?>
                      <option value="Root">Super Usuario</option>
                    <?php endif; ?>
                    <option value="Administrator">Administrador</option>
                  </select>
                </div>
              </div>
            <?php endif; ?>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label for="password"><span class="text-danger">*</span>Contraseña</label>
              <div class="form-group">
                <div class="input-group">
                  <input type="password" class="form-control" name="password" id="password" aria-describedby="addon-password" required>
                  <div class="input-group-append">
                    <div class="btn input-group-text" id="addon-password">
                      <span id="icon-password" class="fas fa-eye-slash"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="confirmPassword"><span class="text-danger">*</span>Confirmar contraseña</label>
              <div class="form-group">
                <div class="input-group">
                  <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" aria-describedby="addon-confirmPassword" required>
                  <div class="input-group-append">
                    <div class="btn input-group-text" id="addon-confirmPassword">
                      <span id="icon-confirmPassword" class="fas fa-eye-slash"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <input type="hidden" name="userId" id="userId">
      <input type="hidden" name="action" id="action-users">

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          <i class="fa fa-times-circle"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fa fa-check-circle"></i> Guardar
        </button>
      </div>
    </form>
  </div>
</div>