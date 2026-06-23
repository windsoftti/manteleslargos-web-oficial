<div class="modal fade" id="modal-add-edit-egreso">
  <div class="modal-dialog modal-dialog-centered">
    <form id="egresos-form" class="modal-content" autocomplete="off">
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
            <div class="form-group">
              <label for="date">Fecha<span>*</span></label>
              <input id="date" class="form-control datepicker" type="text" name="date" value="<?= date('d-m-Y'); ?>" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-8">
            <div class="form-group">
              <label for="concept">Concepto<span>*</span></label>
              <input id="concept" class="form-control" type="text" name="concept" required>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label for="cost">Costo ($)<span>*</span></label>
              <input id="cost" class="form-control number-input" type="text" name="cost" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="description">Descripción</label>
              <textarea id="description" class="form-control" rows="4" name="description"></textarea>
            </div>
          </div>
        </div>
      </div>

      <input type="hidden" name="idEgreso" id="idEgreso">
      <input type="hidden" name="action" id="action-egresos">

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