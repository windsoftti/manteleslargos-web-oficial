$(function () {
  initialFunctions();
});

var packageEditors = [];
var numPackages = 1;
// var numPackages = 2; //- Original, para que funcione, descomentar tambien el que está en el html

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

const galleryPicker = new CSMultipleFilepicker({
  id: 'imageGallery'
});

const initialFunctions = async () => {
  createEditor(1);

  initStepper({
    indentifier: '#business-form',
    onSubmit: saveBusinessData,
    editors: {
      numEditors: numPackages,
      editors: packageEditors
    }
  });

  createPrincipalImagePicker();
  createImageGallery();
  createBusinessLogoPicker();
}

const createPrincipalImagePicker = () => principalImagePicker.createFilePicker();
const createBusinessLogoPicker = () => businessLogoPicker.createFilePicker();
const createImageGallery = () => galleryPicker.createFilePicker();

const saveBusinessData = async form => {
  showPageLoading();

  const imageGallery = galleryPicker.getCSFiles();
  const principalImage = principalImagePicker.getFile();
  const businessLogo = businessLogoPicker.getFile();
  const parameters = new FormData(form[0]);

  if (!principalImage) {
    showSweetToast({
      icon: 'error',
      message: 'Adjunta la imagen principal'
    });

    hidePageLoading();
    return;
  }

  parameters.append('action', 'add_business');

  for (let index = 1; index <= numPackages; index++) {
    const editor = packageEditors[`step-packages-editor-${index}`];
    const find = $(`#step-packages-editor-${index}`).length;

    if (editor && find) {
      const packageDescription = editor.getData()
      parameters.append(`packageDescription-${index}`, packageDescription)
    }
  }

  parameters.append('principalImage', principalImage.blob, principalImage.name);
  imageGallery.map(image => parameters.append('imageGallery[]', image.blob, image.name));
  if (businessLogo?.blob) parameters.append('businessLogo', businessLogo.blob, businessLogo.name);

  const response = await fetchData({
    place: 'businesses',
    parameters,
    showProgress: true
  });

  hidePageLoading();

  if (response.message && response.status !== 'success') showSweetToast({
    icon: response.status,
    message: response.message
  });

  if (response.status === 'success') showSweetAlert({
    title: response.title,
    message: response.message
  }).then(() => location.href = BASE_URL + '/mi-cuenta');

  /* if (response.status === 'success') {
    setTimeout(() => {
      showPageLoading();
      clearTimeout();
      location.href = BASE_URL + '/mi-cuenta';
    }, 3000);
  } */
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

  $('html,body').animate({
    scrollTop: $('#btn-next').offset().top - 200
  }, 'fast');
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: 
//-- PACKAGES
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
//var packageEditors = [];

const createEditor = numEditor => {
  const id = `step-packages-editor-${numEditor}`;
  const editor = new EditorHTML(`#${id}`);
  packageEditors[id] = editor;

  //packageEditors[`step-packages-editor-${numEditor}`] = CKEDITOR.replace(`step-packages-editor-${numEditor}`);
}

const addPackageItem = async () => {
  showPageLoading();

  const parameters = new FormData();
  parameters.append('action', 'add-package');
  parameters.append('numPackage', numPackages);

  const response = await fetchData({
    place: 'businesses',
    parameters
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

const loadCitysForForm = stateId => useLoadSelect({
  select: '#city',
  action: 'citys',
  data: stateId
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