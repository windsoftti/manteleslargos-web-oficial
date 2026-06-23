<div class="modal fade" id="modal-add-edit-user">
  <div class="modal-dialog modal-dialog-centered">
    <form id="users-form" class="modal-content" autocomplete="off">
      <div class="modal-header">
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title modal-dynamic-title"></h5>
          <button type="button" class="close" data-dismiss="modal">
            <i class="anticon anticon-close"></i>
          </button>
        </div>
      </div>

      <div class="modal-body login-card-body">
        <div class="row">
          <div class="col-md-6 mt-1">
            <label class="mb-0" for="user"><span class="text-danger">*</span>Usuario</label>
            <div class="form-group mb-1">
              <input type="text" class="form-control" name="user" id="user" required>
            </div>
          </div>

          <div class="col-md-6 mt-1">
            <label class="mb-0" for="email"><span class="text-danger">*</span>Correo</label>
            <div class="form-group mb-1">
              <input type="email" class="form-control" name="email" id="email" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mt-1">
            <label class="mb-0" for="phone">Telefono</label>
            <div class="form-group mb-1">
              <input type="text" class="form-control" name="phone" id="phone">
            </div>
          </div>

          <div class="col-md-6 mt-1">
            <label class="mb-0" for="cellPhone"><span class="text-danger">*</span>Celular</label>
            <div class="form-group mb-1">
              <input type="text" class="form-control" name="cellPhone" id="cellPhone" required>
            </div>
          </div>
        </div>

        <div class="row" id="credentials">
          <div class="col-md-6 mt-1">
            <label class="mb-0" for="username"><span class="text-danger">*</span>Username</label>
            <div class="form-group mb-1">
              <input type="text" class="form-control" name="username" id="username">
            </div>
          </div>

          <div class="col-md-6 mt-1">
            <label class="mb-0" for="password"><span class="text-danger">*</span>Password</label>
            <div class="form-group mb-1">
              <div class="input-group">
                <input type="password" class="form-control" name="password" id="password" aria-describedby="addon-password">
                <div class="input-group-append">
                  <div class="btn input-group-text" id="addon-password">
                    <span id="icon-password" class="fa fa-eye-slash"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <?php if ($_SESSION['session_user_level'] === 'Super Usuario' || $_SESSION['session_user_level'] === 'Administrador') : ?>
          <div class="row">
            <div class="col-md-12 mt-1">
              <label class="mb-0" for="level"><span class="text-danger">*</span>Nivel de usuario</label>
              <div class="form-group mb-1">
                <select class="form-control" name="level" id="level" required>
                  <option value="">Seleccionar</option>
                  <?php if ($_SESSION['session_user_level'] === 'Super Usuario') : ?>
                    <option value="Super Usuario">Super Usuario</option>
                  <?php endif; ?>
                  <option value="Administrador">Administrador</option>
                  <option value="Usuario">Proveedor</option>
                  <option value="Usuario Final">Cliente</option>
                </select>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <input type="hidden" name="userId" id="userId">
      <input type="hidden" name="action" id="action-users">

      <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">
          <i class="fa fa-times-circle"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fa fa-check-circle"></i> Guardar
        </button>
      </div>
    </form>
  </div>
</div>