$(document).ready(() => initFunctions());

var longDescriptionEditor;

const galleryPicker = new CSMultipleFilepicker({
  id: 'imageGallery'
});

const initFunctions = () => {
  initBusinessAutocomplete();
  createPrincipalImagePicker();
  createImageGalleryPicker();
  createEditor();
  loadRecentEvents();
  initSearchForm(loadRecentEvents);
}

const createPrincipalImagePicker = () => $csFilePickerCreateElement('principalImage');
const createImageGalleryPicker = () => galleryPicker.createFilePicker();
const createEditor = () => {
  longDescriptionEditor = CKEDITOR.replace('longDescription');
  longDescriptionEditor.config.height = 500;
}

const loadRecentEvents = page => useLoadTable({
  page,
  place: 'recent_events',
  action: 'list-recent-events'
});

const initBusinessAutocomplete = () => $("#business").autocomplete({
  source: 'data/autocomplete/business_data.php',
  minLength: 2,
  select: function (event, ui) {
    event.preventDefault();
    $('#business').val(ui.item.business);
    $('#businessId').val(ui.item.businessId);
  }
});

async function saveRecentEvents(e) {
  e.preventDefault();

  const longDescription = longDescriptionEditor.getData();

  if (!longDescription) {
    showSweetToast({
      icon: 'warning',
      message: '¡Completa el campo descripción larga!.'
    });

    return;
  }

  const imageGallery = galleryPicker.getCSFiles();
  const imageGalleryItems = galleryPicker.getNewInitialImagesArray();

  const parameters = new FormData($(this)[0]);
  parameters.append('longDescription', longDescription);

  imageGallery.map(image => parameters.append('imageGallery[]', image.blob, image.name));
  imageGalleryItems.map(image => parameters.append('imageGallery-items[]', image));

  const response = await fetchData({
    place: 'recent_events',
    parameters,
    showProgress: true
  });

  if (response.message) showSweetAlert({
    title: response.title,
    message: response.message
  });

  if (response.status == 'success') {
    closeModal('modal-recent-events');
    resetForm('#recent-events-form');
    loadRecentEvents();
  }
}

function deleteRecentEvent() {
  const data = JSON.parse(atob($(this).attr('data-recent-event')));

  useDeleteFromTable({
    place: 'recent_events',
    action: 'delete-recent-event',
    itemId: data.idEvento,
    item: data.Evento,
    onDeleted: loadRecentEvents,
  });
}

const handleAddRecentEvent = () => {
  changeModalTitle('Agregar evento reciente');
  resetForm('#recent-events-form');
  longDescriptionEditor.setData('');
  createPrincipalImagePicker();
  createImageGalleryPicker();

  $('#action-recent-events').val('add-recent-event');
}

function handleEditRecentEvent() {
  const data = JSON.parse(atob($(this).attr('data-recent-event')));

  changeModalTitle('Editar evento reciente');

  $('#recentEventId').val(data.idEvento);
  $('#title').val(data.Evento);
  $('#business').val(data.Salon);
  $('#businessId').val(data.idSalon);
  $('#shortDescription').val(data.DescCorta);
  longDescriptionEditor.setData(data.Descripcion);

  if (!data.image) createPrincipalImagePicker();
  if (data.image) $csFilePickerCreateImagePreview({
    id: 'principalImage',
    imageSrc: data.image,
    imageName: ''
  });

  $('#action-recent-events').val('edit-recent-event');

  createImageGalleryPicker();

  if (data.gallery) galleryPicker.addImages({
    images: data.gallery
  });
}

$('.btn-add-recent-event').on('click', handleAddRecentEvent);
$(document).on('click', '.btn-edit-recent-event', handleEditRecentEvent);
$(document).on('click', '.btn-delete-recent-event', deleteRecentEvent);

$('#recent-events-form').on('submit', saveRecentEvents);