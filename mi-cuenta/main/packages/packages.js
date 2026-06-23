function item(count) {
  return `
  <div class="col-md-6" id="col-paquete-${count}">
    <div class="col-md-12 card">
      <div class="modal-header">
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title">Paquete ${count + 1}</h5>
          <button type="button" class="close btn-delete-paquete" data-id="${count}">
            <i class="fal fa-times"></i>
          </button>
        </div>
      </div>

      <div class="row text-left">
        <div class="col-sm-12 col-md-12">
          <div class="form-group">
            <label for="nombrePaquete${count}" class="text-heading"><span class="text-danger">*</span>Nombre del paquete</label>
            <input type="text" name="nombrePaquete[]" class="form-control form-control-lg" id="nombrePaquete${count}">
          </div>
        </div>
      </div>

      <div class="row text-left">
        <div class="col-xs-12 col-sm-6 col-md-8">
          <div class="form-group">
            <label for="orientacionPaquete${count}" class="text-heading"><span class="text-danger">*</span>Modalidad</label>
            <select name="orientacionPaquete[]" id="orientacionPaquete${count}" class="form-control form-control-lg">
              <option value="">Seleccionar</option>
              <option value="Por persona">Por persona</option>
              <option value="Por evento">Por evento</option>
            </select>
          </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4">
          <div class="form-group">
            <label for="precioPaquete${count}" class="text-heading"><span class="text-danger">*</span>Precio</label>
            <input type="text" name="precioPaquete[]" class="form-control form-control-lg" id="precioPaquete${count}">
          </div>
        </div>
      </div>

      <div class="row text-left">  
        <div class="col-sm-12 col-md-12">
          <div class="form-group">
            <label for="descripcionPaquete${count}" class="text-heading"><span class="text-danger">*</span>Descripción <i class="fal fa-question-circle pointer m-0 text-info tooltip-icon" style="font-size:18px" data-toggle="tooltip" data-placement="right" title="Describe de manera detallada las características del servicio que proporcionas para que tus posibles clientes conozcan de manera correcta el producto que van a adquirir."></i></label>
            <textarea rows="3" id="descripcionPaquete${count}" class="form-control form-control-lg"></textarea>
          </div>
        </div>
      </div>

      <div class="row text-left">    
        <div class="col-sm-12 col-md-12">
          <div class="form-group">
            <label for="tipoEventoPaquete${count}">Tipo de evento</label>
            <select id="tipoEventoPaquete${count}" name="tipoEventoPaquete${count}[]" class="form-control form-control-lg select2" multiple="multiple" data-placeholder="Seleccionar los tipos de eventos" style="width: 100%;">
            </select>
          </div>
        </div>

        <input id="counter${count}" type="hidden" name="counter[]" value="${count}" />
      </div>
    </div>
  </div>
`;
}

var packageEditors = [];
var packageEditorCount = 0;
var eventTypes = '';

async function loadEventTypes() {
  const dataSend = new FormData();

  dataSend.append('action', 'list_event_types');

  const response = await fetchData({
    place: 'selects',
    data: dataSend
  });

  eventTypes = decodeURIComponent(escape(atob(response.content)));
}

function createNewEditor() {
  console.log(packageEditorCount)
  ClassicEditor.create(document.querySelector(`#descripcionPaquete${packageEditorCount}`)).then(editor => {
    packageEditors[`editor-${packageEditorCount}`] = editor;

    $(`#tipoEventoPaquete${packageEditorCount}`).append(eventTypes);

    packageEditorCount = packageEditorCount + 1;
  }).catch(error => {
    console.error(error);
  });

  $('.select2').select2({
    theme: 'bootstrap4'
  });

  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
}

function createNewEditEditor(count) {
  ClassicEditor.create(document.querySelector(`#descripcionPaquete${count}`)).then(editor => {
    packageEditors[`editor-${count}`] = editor;
    packageEditorCount = count + 1;
  }).catch(error => {
    console.error(error);
  });

  $('.select2').select2({
    theme: 'bootstrap4'
  })

  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
}

$('.btn-add-paquete').on('click', async function () {
  const newItem = item(packageEditorCount);
  await $('#listar-paquetes').append(newItem).show('slow');

  createNewEditor();
});

$(document).on('click', '.btn-delete-paquete', async function () {
  const resAlert = await showSweetConfirm({
    title: '¡Cuidado!',
    subtitle: '¿Realmente desea quitar este paquete?'
  });

  if (resAlert) {
    const idPaquete = $(this).attr('data-id');

    $(`#col-paquete-${idPaquete}`).remove();
    packageEditors[`editor-${idPaquete}`] = null;
  }
});

async function loadPackages() {
  showPageLoading();

  const businessId = $('#idSalon').val();

  const dataSend = new FormData();

  dataSend.append('businessId', businessId);
  dataSend.append('action', 'list_packages');

  const response = await fetchData({
    place: 'packages',
    data: dataSend
  });

  const packages = atob(response.content);
  $('#listar-paquetes').append(packages).show();

  hidePageLoading();
}

$(document).on('click', '.btn-remove-paquete', async function () {
  const packageId = $(this).attr('data-packageId');
  const package = $(this).attr('data-package');
  const idCol = $(this).attr('data-id');

  const alertResponse = await showSweetConfirm({
    title: '¡Cuidado!',
    subtitle: `¿Realmente desea eliminar el paquete "${package}"?`
  });

  if (!alertResponse) return;

  showPageLoading();

  const dataSend = new FormData();

  dataSend.append('packageId', packageId);
  dataSend.append('package', package);
  dataSend.append('action', 'delete_package');

  const response = await fetchData({
    place: 'packages',
    data: dataSend
  });

  if (response) {
    showSweetAlert({
      icon: response.state,
      title: response.title
    });

    if (response.state === 'success') {
      $(`#col-paquete-${idCol}`).remove();
      packageEditors[`editor-${idCol}`] = null;
    }
  }

  hidePageLoading();
});