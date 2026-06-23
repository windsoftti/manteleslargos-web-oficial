<div class="modal fade" id="modal-add-edit-tip">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form id="tips-form" class="modal-content" autocomplete="off">
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
          <div class="col-md-6">
            <label class="mb-0" for="tip"><span class="text-danger">*</span>Tip</label>
            <div class="form-group mb-1">
              <input type="text" class="form-control" id="tip" name="tip" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <label class="mb-0" for="shortDescription"><span class="text-danger">*</span>Descripción corta</label>
            <div class="form-group mb-1">
              <textarea name="shortDescription" id="shortDescription" rows="2" class="form-control"></textarea>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <label class="mb-0" for="longDescription"><span class="text-danger">*</span>Descripción larga</label>
            <div class="form-group mb-1">
              <textarea name="longDescription" id="longDescription" rows="3" class="form-control"></textarea>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <label class="mb-0">Imagen principal</label>
            <div class="form-group mb-1">
              <div id="image" data-name="image" data-title="Imagen principal"></div>
            </div>
          </div>
        </div>
      </div>

      <input type="hidden" name="tipId" id="tipId">
      <input type="hidden" name="action" id="action-tips">

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