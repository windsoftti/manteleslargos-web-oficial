<div class="modal fade" id="modal-recent-events">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form id="recent-events-form" class="modal-content needs-validation" autocomplete="off">
      <div class="modal-header">
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title modal-dynamic-title"></h5>

          <button type="button" class="close" data-dismiss="modal">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="title"><span class="text-danger">*</span>Título del evento</label>
              <input id="title" class="form-control" name="title" type="text" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="business"><span class="text-danger">*</span>Negocio/Salón</label>
              <input id="business" class="form-control" name="business" type="text" required>
              <input id="businessId" name="businessId" type="hidden" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="shortDescription"><span class="text-danger">*</span>Descripción corta</label>
              <textarea id="shortDescription" class="form-control" name="shortDescription" rows="4" required></textarea>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="longDescription"><span class="text-danger">*</span>Descripción larga</label>
              <textarea id="longDescription" class="form-control" name="longDescription" rows="6" required></textarea>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div id="principalImage" data-name="principalImage" data-title="<span style='color:red'>*</span>Adjuntar imagen principal" data-subtitle="" data-labelError="Ajunta la imagen principal" data-required="true"></div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div id="imageGallery" data-name="imageGallery"></div>
            </div>
          </div>
        </div>
      </div>

      <input type="hidden" name="recentEventId" id="recentEventId">
      <input type="hidden" name="action" id="action-recent-events">

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