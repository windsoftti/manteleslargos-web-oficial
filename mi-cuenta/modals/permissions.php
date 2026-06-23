<div class="modal fade" id="modal-permissions">
  <div class="modal-dialog modal-dialog-centered">
    <form id="permissions-form" class="modal-content" autocomplete="off">
      <div class="modal-header">
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title">Permisos del usuario</h5>
          <button type="button" class="close" data-dismiss="modal">
            <i class="fal fa-times"></i>
          </button>
        </div>
      </div>

      <div class="modal-body">
        <div class="row">
          <div id="list-permissions" class="col-md-12"></div>
        </div>
      </div>

      <input type="hidden" name="userId" id="permissionsUserId">
      <input type="hidden" name="action" id="action-permissions" value="add_user_permissions">

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