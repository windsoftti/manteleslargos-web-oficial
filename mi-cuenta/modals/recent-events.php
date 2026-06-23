<div class="modal fade" id="modal-add-edit-recent-event">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form id="recent-events-form" class="modal-content" autocomplete="off">
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
            <label class="mb-0" for="recentEvent"><span class="text-danger">*</span>Tipo de evento</label>
            <div class="form-group mb-1">
              <input type="text" class="form-control" id="recentEvent" name="recentEvent" required>
            </div>
          </div>

          <div class="col-md-6">
            <label class="mb-0" for="businessId"><span class="text-danger">*</span>Negocio</label>
            <div class="form-group mb-1">
              <select class="form-control business-select" name="businessId" id="businessId" required>
                <option value="">Seleccionar</option>
              </select>
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
            <label class="mb-0">Imagen principal de la imagen</label>
            <div class="form-group mb-1">
              <div id="image" data-name="image" data-title="Imagen principal del evento"></div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <label class="mb-0">Galería de imagenes</label>
            <div id="gallery" data-name="gallery" data-idListar="list-image-gallery"></div>
          </div>
        </div>
      </div>

      <input type="hidden" name="recentEventId" id="recentEventId">
      <input type="hidden" name="action" id="action-recentEvents">

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