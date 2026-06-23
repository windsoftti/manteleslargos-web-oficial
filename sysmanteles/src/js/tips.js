$(document).ready(() => initFunctions());

var longDescriptionEditor;

const galleryPicker = new CSMultipleFilepicker({
  id: 'imageGallery'
});

const initFunctions = () => {
  createPrincipalImagePicker();
  createImageGalleryPicker();
  createEditor();
  loadTips();
  initSearchForm(loadTips);
}

const createPrincipalImagePicker = () => $csFilePickerCreateElement('principalImage');
const createImageGalleryPicker = () => galleryPicker.createFilePicker();
const createEditor = () => {
  longDescriptionEditor = CKEDITOR.replace('longDescription');
  longDescriptionEditor.config.height = 500;
}

const loadTips = page => useLoadTable({
  page,
  place: 'tips',
  action: 'list-tips'
});

async function saveTip(e) {
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
    place: 'tips',
    parameters,
    showProgress: true
  });

  if (response.message) showSweetAlert({
    title: response.title,
    message: response.message
  });

  if (response.status == 'success') {
    closeModal('modal-tips');
    resetForm('#tips-form');
    loadTips();
  }
}

function deleteTip() {
  const data = JSON.parse(atob($(this).attr('data-tip')));

  useDeleteFromTable({
    place: 'tips',
    action: 'delete-tip',
    itemId: data.idTip,
    item: data.Tip,
    onDeleted: loadTips
  });
}

const handleAddTip = () => {
  changeModalTitle('Agregar tip');
  resetForm('#tips-form');
  longDescriptionEditor.setData('');
  createPrincipalImagePicker();
  createImageGalleryPicker();

  $('#action-tips').val('add-tip');
}

function handleEditTip() {
  const data = JSON.parse(atob($(this).attr('data-tip')));

  changeModalTitle('Editar tip');

  $('#tipId').val(data.idTip);
  $('#title').val(data.Tip);
  $('#shortDescription').val(data.DescCorta);
  longDescriptionEditor.setData(data.Descripcion);

  if (!data.image) createPrincipalImagePicker();
  if (data.image) $csFilePickerCreateImagePreview({
    id: 'principalImage',
    imageSrc: data.image,
    imageName: ''
  });

  $('#action-tips').val('edit-tip');

  createImageGalleryPicker();

  if (data.gallery) galleryPicker.addImages({
    images: data.gallery
  });
}

$('.btn-add-tip').on('click', handleAddTip);
$(document).on('click', '.btn-edit-tip', handleEditTip);
$(document).on('click', '.btn-delete-tip', deleteTip);

$('#tips-form').on('submit', saveTip);