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
            <label class="mb-0" for="user"><span class="text-danger">*</span>Nombre completo</label>
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
            <div class="form-group mb-1">
              <label class="mb-0" for="cellPhone"><span class="text-danger">*</span>Celular</label>
              <div class="input-group">
                <span class="input-group-addon position-absolute" style="z-index: 10;" id="prefixCellPhone">+52</span>

                <input type="text" class="form-control" name="cellPhone" id="cellPhone" maxlength="10" aria-describedby="prefixCellPhone" required>
              </div>
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
                <input type="password" class="form-control" name="password" id="password" aria-describedby="addon-password" style="padding-left: 0.5rem;">
                <div class="input-group-addon">
                  <div class="btn input-group-text" id="addon-password" style="height: 2.5rem;">
                    <span id="icon-password" class="fa fa-eye-slash"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- <div class="row mt-2">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-12 text-center">
                <h5 class="text-center login-title">Permisos del usuario</h5>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="card p-1">
                  <div class="row">
                    <div class="col-md-12">
                      <table class="table-sm">
                        <tbody>
                          <tr>
                            <td class="align-middle">
                              <label class="m-0" for="permisoNuevoNegocio">NUEVO NEGOCIO</label>
                            </td>
                            <td class="align-midle">
                              <div class="custom-control custom-checkbox">
                                <input id="permisoNuevoNegocio" type="checkbox" name="permisoNuevoNegocio" value="Si">
                              </div>
                            </td>
                          </tr>

                          <tr>
                            <td class="align-middle">
                              <label class="m-0" for="permisoListarNegocios">LISTAR NEGOCIOS</label>
                            </td>
                            <td class="align-midle">
                              <div class="custom-control custom-checkbox">
                                <input id="permisoListarNegocios" type="checkbox" name="permisoListarNegocios" value="Si">
                              </div>
                            </td>
                          </tr>

                          <tr>
                            <td class="align-middle">
                              <label class="m-0" for="permisoCotizaciones">COTIZACIONES</label>
                            </td>
                            <td class="align-midle">
                              <div class="custom-control custom-checkbox">
                                <input id="permisoCotizaciones" type="checkbox" name="permisoCotizaciones" value="Si">
                              </div>
                            </td>
                          </tr>

                          <tr>
                            <td class="align-middle">
                              <label class="m-0" for="permisoEventosProximos">EVENTOS PROXIMOS</label>
                            </td>
                            <td class="align-midle">
                              <div class="custom-control custom-checkbox">
                                <input id="permisoEventosProximos" type="checkbox" name="permisoEventosProximos" value="Si">
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> -->
      </div>

      <input type="hidden" name="userId" id="userId">
      <input type="hidden" name="action" id="action-users">

      <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">
          <i class="fa fa-times-circle"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-primary btn-modal-title">
          <i class="fa fa-check-circle"></i> Guardar
        </button>
      </div>
    </form>
  </div>
</div>