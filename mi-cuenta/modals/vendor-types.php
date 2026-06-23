<div class="modal fade" id="modal-add-edit-vendor-type">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <form id="vendor-types-form" class="modal-content" autocomplete="off">
      <div class="modal-header">
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title modal-dynamic-title"></h5>
          <button type="button" class="close" data-dismiss="modal">
            <i class="fal fa-times"></i>
          </button>
        </div>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <label class="mb-0" for="vendorType"><span class="text-danger">*</span>Tipo de proveedor</label>
            <div class="form-group mb-1">
              <input type="text" class="form-control" id="vendorType" name="vendorType" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <label class="mb-0">Imagen / Icono</label>
            <div class="form-group mb-1">
              <div id="image" data-name="image" data-title="Agregar imagen"></div>
            </div>
          </div>
        </div>
      </div>

      <input type="hidden" name="vendorTypeId" id="vendorTypeId">
      <input type="hidden" name="action" id="action-vendorTypes">

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