$(function () {
  initialFunctions();
});

var packageEditors = [];
var numPackages = 1;

const galleryPicker = new CSMultipleFilepicker({
  id: 'imageGallery'
});

const principalImagePicker = new CSFilePicker({
  id: 'principalImage',
  title: 'Imagen principal',
  subtitle: 'Para un mejor rendimiento adjunta imagenes optimizadas'
});

const businessLogoPicker = new CSFilePicker({
  id: 'businessLogo',
  title: 'Logo de tu negocio (Opcional)',
  subtitle: 'Para un mejor rendimiento adjunta imagenes optimizadas'
});

const initialFunctions = async () => {
  createInitialEditors(1);

  initStepper({
    indentifier: '#business-form',
    onSubmit: saveBusinessData,
    editors: {
      numEditors: numPackages,
      editors: packageEditors
    }
  });

  //createPrincipalImagePicker();
  //createImageGallery();

  principalImagePicker.createFilePicker();
  businessLogoPicker.createFilePicker();
  galleryPicker.createFilePicker();

  galleryPicker.addImages({
    images: imagesForGallery
  });

  principalImagePicker.createImagePreview(principalImageData);
  if (businessLogoData) businessLogoPicker.createImagePreview(businessLogoData);
}

const createInitialEditors = () => {
  for (let index = 1; index <= packageCounter; index++) {
    createEditor(index);
    numPackages = numPackages + 1;
  }
}

// const createPrincipalImagePicker = () => principalImagePicker.createFilePicker();;
// const createImageGallery = () => $csMultipleFilePickerCreateElement('imageGallery');

const saveBusinessData = async form => {
  showPageLoading();

  const principalImage = principalImagePicker.getFile();
  const businessLogo = businessLogoPicker.getFile();

  const imageGallery = galleryPicker.getCSFiles();
  const imageGalleryItems = galleryPicker.getNewInitialImagesArray();

  const parameters = new FormData(form[0]);

  parameters.append('action', 'edit_business');

  for (let index = 1; index <= numPackages; index++) {
    const editor = packageEditors[`step-packages-editor-${index}`];
    const find = $(`#step-packages-editor-${index}`).length;

    if (editor && find) {
      const packageDescription = editor.getData()
      parameters.append(`packageDescription-${index}`, packageDescription)
    }
  }

  if (businessLogo?.blob) parameters.append('businessLogo', businessLogo.blob, businessLogo.name);

  if (principalImage.blob) parameters.append('principalImage', principalImage.blob, principalImage.name);

  imageGallery.map(image => parameters.append('imageGallery[]', image.blob, image.name));
  imageGalleryItems.map(image => parameters.append('imageGallery-items[]', image));

  const response = await fetchData({
    place: 'businesses',
    data: parameters,
    showProgress: true
  });

  hidePageLoading();

  /* if (response.message) showPageAlert({
    id: '#business-alert',
    status: response.status,
    message: response.message,
    inner: true
  });

  if (response.status === 'success') {
    setTimeout(() => {
      showPageLoading();
      clearTimeout();
      location.href = 'negocios';
    }, 3000);
  } */

  if (response.message && response.status != 'success') showSweetAlert({
    id: '#business-alert',
    icon: response.status,
    message: response.message,
    inner: true
  });

  if (response.status === 'success') showBigAlert({
    icon: response.status,
    title: '¡Datos Guardados!',
    subtitle: response.message
  }).then(() => {
    showPageLoading();
    location.href = 'negocios';
  });
}

async function handleSupplierType(e) {
  e.stopPropagation();
  const value = $(this).val();
  const eventTypes = $(this).attr('data-events');
  let eventTypesCheckbox = ``;

  if (value == 1) $('.salon-type').removeClass('type-hidden');
  if (value != 1) $('.salon-type').addClass('type-hidden');

  await Promise.all(allEventTypes.map(item => {
    if (eventTypes.includes(item.eventTypeId)) {
      eventTypesCheckbox += `
        <div>
          <input id="event-type-${item.eventTypeId}"
            class="eventType-checkbox"
            name="eventType[]"
            value="${item.eventTypeId}"
            type="checkbox"
            labelError="Selecciona el tipo de evento"
            validate
          >

          <label for="event-type-${item.eventTypeId}">${item.eventType}</label>
        </div>
      `;
    }
  }));

  $('#event-types-container').html(eventTypesCheckbox);
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: 
//-- PACKAGES
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
//var packageEditors = [];

const createEditor = numEditor => {
  const id = `step-packages-editor-${numEditor}`;
  const editor = new EditorHTML(`#${id}`);
  packageEditors[id] = editor;
}

const addPackageItem = async () => {
  showPageLoading();

  const parameters = new FormData();
  parameters.append('action', 'add-package');
  parameters.append('numPackage', numPackages);

  const response = await fetchData({
    place: 'businesses',
    data: parameters
  });

  hidePageLoading();

  if (response.status === 'success') {
    const content = decryptData(response.content);
    $('#package-list').append(content);
    await createEditor(numPackages);
    numPackages = numPackages + 1;
    initNumberInput();
  }
}

function handleRemovePackageItem() {
  $(this).parent().parent().parent().remove();
}
/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: 
//-- END PACKAGES
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

const loadCitysForForm = stateId => loadSelect({
  select: '#city',
  action: 'citys',
  data: stateId,
  loading: true
});

$('#state').on('change', async function () {
  const stateId = $(this).val();
  if (stateId) {
    loadCitysForForm(stateId);
    $('#city-container').show();

    const latitude = $('option:selected', this).attr('data-latitude');
    const longitude = $('option:selected', this).attr('data-longitude');

    await $('#business-map').attr('latitude', latitude);
    await $('#business-map').attr('longitude', longitude);
    await $('#business-map').attr('zoom', 8);

    initMultipleMap();
  }

  if (!stateId) $('#city-container').hide();
});

$('.supplier-type').on('click', handleSupplierType);
$('.btn-add-package').on('click', addPackageItem);
$(document).on('click', '.btn-remove-package', handleRemovePackageItem);