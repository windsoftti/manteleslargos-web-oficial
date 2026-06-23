$(document).ready(() => initFunctions());

const galleryPicker = new CSMultipleFilepicker({
  id: 'imageGallery'
});

const crPicturePicker = new CSFilePicker({
  id: 'CRPicture',
  title: 'Adjuntar imagen',
  subtitle: 'Para un mejor rendimiento adjunta imagenes optimizadas'
});

const rPicturePicker = new CSFilePicker({
  id: 'RPicture',
  title: 'Adjuntar imagen',
  subtitle: 'Para un mejor rendimiento adjunta imagenes optimizadas'
});

const individualPicturePicker = new CSFilePicker({
  id: 'individualPicture',
  title: 'Adjuntar imagen principal',
  subtitle: 'Para un mejor rendimiento adjunta imagenes optimizadas'
});

/* const familyPicturePicker = new CSFilePicker({
  id: 'familyPicture',
  title: 'Adjuntar imagen familiar',
  subtitle: 'Para un mejor rendimiento adjunta imagenes optimizadas'
}); */

const onBeforeVisualize = () => new Promise(async (resolve, reject) => {
  const imageGallery = galleryPicker.getCSFiles();
  const imageGalleryItems = galleryPicker.getNewInitialImagesArray();

  const crPicture = crPicturePicker.getFile();
  const rPicture = rPicturePicker.getFile();
  const individualPicture = individualPicturePicker.getFile();
  //const familyPicture = familyPicturePicker.getFile();

  let inputs = ``;

  await Promise.all(imageGallery.map(image => inputs += `<input type="hidden" name="imageGallery-preview[]" value="${image.imageSrc}">`));
  await Promise.all(imageGalleryItems.map(image => {
    const find = imagesForGallery.find(item => item.imageId == image);
    if (find) inputs += `<input type="hidden" name="imageGallery-preview[]" value="${find.imageSrc}">`;
  }));

  inputs += `<input type="hidden" name="CRPicture-preview" value="${crPicture?.imageSrc}">`;
  inputs += `<input type="hidden" name="RPicture-preview" value="${rPicture?.imageSrc}">`;
  inputs += `<input type="hidden" name="individualPicture-preview" value="${individualPicture?.imageSrc}">`;
  //inputs += `<input type="hidden" name="familyPicture-preview" value="${familyPicture.imageSrc}">`;

  $('#image-gallerypreview-container').html(inputs);

  resolve(true);
});

const initFunctions = () => {
  initStepper({
    indentifier: '#create-invitation-form',
    onSubmit: createInvitation,
    onBeforeVisualize: onBeforeVisualize,
    pickers: [
      {
        stepId: 'step-where-and-when',
        picker: crPicturePicker,
        messageError: 'Debes de ajuntar la imagen de la ceremonia religiosa'
      },
      {
        stepId: 'step-where-and-when',
        picker: rPicturePicker,
        messageError: 'Debes de ajuntar la imagen de la recepción'
      },
      {
        stepId: 'step-image-gallery',
        picker: individualPicturePicker,
        messageError: 'Debes de ajuntar la imagen individual'
      },
      /* {
        stepId: 'step-image-gallery',
        picker: familyPicturePicker,
        messageError: 'Debes de ajuntar la imagen familiar'
      } */
    ]
  });

  initNumberInput();

  crPicturePicker.createFilePicker();
  rPicturePicker.createFilePicker();

  individualPicturePicker.createFilePicker();
  //familyPicturePicker.createFilePicker();

  ////$csMultipleFilePickerCreateElement('imageGallery');
  createImageGallery();

  //$('.templates-slider').slick();

  $('.datetimepicker').datetimepicker({
    "allowInputToggle": true,
    "showClose": true,
    "showClear": true,
    "showTodayButton": true,
    "format": "DD/MM/YYYY hh:mm A",
    'locale': 'es'
  });

  $('.datetimepicker').on('keydown', function (e) {
    e.preventDefault();
  });
}

const createImageGallery = () => galleryPicker.createFilePicker();

const cleanReligiousCeremony = () => {
  //$csFilePickerCreateElement('CRPicture');
  crPicturePicker.clearPicker();

  $('#CRPlace').val('');
  $('#CRDateTime').val('');
  $('#search-CRMap').val('');
  $('#address-CRMap').val('');
  $('#latitude-CRMap').val('');
  $('#longitude-CRMap').val('');
}

const cleanReceptionCeremony = () => {
  //$csFilePickerCreateElement('RPicture');
  rPicturePicker.clearPicker();

  $('#RPlace').val('');
  $('#RDateTime').val('');
  $('#search-RMap').val('');
  $('#address-RMap').val('');
  $('#latitude-RMap').val('');
  $('#longitude-RMap').val('');
}

const createInvitation = async form => {
  showPageLoading();

  const imageGallery = galleryPicker.getCSFiles();
  const crPicture = crPicturePicker.getFile();
  const rPicture = rPicturePicker.getFile();
  const individualPicture = individualPicturePicker.getFile();
  //const familyPicture = familyPicturePicker.getFile();

  const parameters = new FormData(form[0]);

  parameters.append('action', 'add_invitation');

  if (crPicture?.blob) parameters.append('CRPicture', crPicture.blob, crPicture.name);
  if (rPicture?.blob) parameters.append('RPicture', rPicture.blob, rPicture.name);
  parameters.append('individualPicture', individualPicture.blob, individualPicture.name);
  //parameters.append('familyPicture', familyPicture.blob, familyPicture.name);

  imageGallery.map(image => parameters.append('imageGallery[]', image.blob, image.name));

  const response = await fetchData({
    place: 'invitations',
    parameters,
    showProgress: true
  });

  hidePageLoading();

  if (response.message && response.status !== 'success') showSweetToast({
    id: '#create-invitation-alert',
    icon: response.status,
    message: response.message,
    inner: true
  });

  if (response.status === 'success') showSweetAlert({
    title: response.title,
    message: response.message
  }).then(() => {
    showPageLoading();
    location.href = BASE_URL + '/mis-invitaciones';
  });

  /* if (response.status === 'success') {
    setTimeout(() => {
      showPageLoading();
      clearTimeout();
      location.href = BASE_URL + '/mis-invitaciones';
    }, 2000);
  } */
}

$('#btn-add-religious').on('click', cleanReligiousCeremony);
$('#btn-add-reception').on('click', cleanReceptionCeremony);