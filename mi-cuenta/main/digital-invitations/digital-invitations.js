window.onload = function () {
  $createMapComponent('CR');
  $createMapComponent('Recepcion');
}

$(function () {
  createPicker('cr-image');
  createPicker('r-image');
  createPicker('individual-picture');
  createPicker('family-picture');
  createMultiplePicker('image-gallery');

  $('#digital-invitations-form').bind("keypress", function (e) {
    if (e.keyCode == 13) {
      return false;
    }
  });
});

function showAlert({
  title,
  tabHref
}) {
  $('#tab-alert').html(title);
  $('#tab-alert').show();

  $(`[href="#${tabHref}"]`).tab('show');

  $("html, body").animate({ scrollTop: 0 }, "slow");
}

async function validateForm(e) {
  if (e) e.preventDefault();

  var formdata = $('#digital-invitations-form').serializeArray();

  var data = {};

  $(formdata).each(function (index, obj) {
    data[obj.name] = obj.value;
  });

  if (!data.invitationType) {
    showAlert({
      title: 'Selecciona el tipo de invitación',
      tabHref: 'general-data'
    });

    return;
  }

  if (!data.personName) {
    showAlert({
      title: 'Ingresa el titulo de tu invitación',
      tabHref: 'general-data'
    });

    return;
  }

  if (!data.eventName) {
    showAlert({
      title: 'Ingresa el subtitulo de tu invitación',
      tabHref: 'general-data'
    });

    return;
  }

  if (!data.template) {
    showAlert({
      title: 'Selecciona la plantilla de tu invitación',
      tabHref: 'general-data'
    });

    return;
  }

  if (!data.principalColor) {
    showAlert({
      title: 'Selecciona el color principal para tu invitación',
      tabHref: 'general-data'
    });

    return;
  }

  if (!data.secondaryColor) {
    showAlert({
      title: 'Selecciona el color secundario para tu invitación',
      tabHref: 'general-data'
    });

    return;
  }

  if (data.CRPlace || data.CRDate || data.addressCR) {
    if (!data.CRPlace) {
      showAlert({
        title: 'Ingresa el nombre del lugar de la ceremonia religiosa',
        tabHref: 'who-and-where'
      });

      return;
    }

    if (!data.CRDate) {
      showAlert({
        title: 'Ingresa la fecha de la ceremonia',
        tabHref: 'who-and-where'
      });

      return;
    }

    if (!data.addressCR) {
      showAlert({
        title: 'Ingresa la dirección de la ceremonia',
        tabHref: 'who-and-where'
      });

      return;
    }
  }

  if (data.RPlace || data.RDate || data.addressRecepcion) {
    if (!data.RPlace) {
      showAlert({
        title: 'Ingresa el nombre del lugar de la recepción',
        tabHref: 'who-and-where'
      });

      return;
    }

    if (!data.RDate) {
      showAlert({
        title: 'Ingresa la fecha de la ceremonia',
        tabHref: 'who-and-where'
      });

      return;
    }

    if (!data.addressRecepcion) {
      showAlert({
        title: 'Ingresa la dirección de la ceremonia',
        tabHref: 'who-and-where'
      });

      return;
    }
  }

  $('#tab-alert').hide();

  return 'ok';
}

async function sendDigitalInvitationsData() {

  const validate = await validateForm();

  if (!validate) return;

  showPageLoading();

  const parameters = new FormData($('#digital-invitations-form')[0]);

  const response = await fetchData({
    place: 'digital_invitations',
    data: parameters
  });

  console.log(response);

  showBigAlert({
    icon: response.status,
    title: response.title,
    subtitle: response.message
  }).then(() => {
    if (response.status === 'success') {
      window.location.href = "invitaciones";
    }
  });

  hidePageLoading();
}

async function loadImageGallery() {
  showPageLoading();

  const invitationId = $('#invitation-id').val();

  const parameters = new FormData();

  parameters.append('invitationId', invitationId);
  parameters.append('action', 'list_image_gallery');

  const response = await fetchData({
    customURL: 'data/digital_invitations/digital_invitations_gallery_data.php',
    data: parameters
  });

  console.log(response);

  const images = decodeURIComponent(escape(atob(response.content)));
  $('#list-image-gallery').html(images).show();

  hidePageLoading();
}

async function deleteImagePicker({ action, target }) {
  const alertResponse = await showSweetConfirm({
    icon: 'warning',
    title: '¡Cuidado!',
    subtitle: '¿Realmente desea eliminar la imagen?'
  });

  if (alertResponse) {
    showPageLoading();

    const invitationId = target.attr('data-idFile');

    const parameters = new FormData();

    parameters.append('invitationId', invitationId);
    parameters.append('action', action);

    const response = await fetchData({
      place: 'digital_invitations',
      data: parameters
    });

    if (response) {
      showSweetAlert({
        icon: response.status,
        title: response.title
      });

      if (response.status === 'success') {
        if (action == 'delete_individual_picture') {
          cleanPicker('individual-picture', 'individualPicture', 'Agregar imagen');
        }

        if (action == 'delete_family_picture') {
          cleanPicker('family-picture', 'familyPicture', 'Agregar imagen');
        }

        if (action == 'delete_cr_image') {
          cleanPicker('cr-image', 'crImage', 'Agregar imagen');
        }

        if (action == 'delete_r_image') {
          cleanPicker('r-image', 'rImage', 'Agregar imagen');
        }
      }
    }

    hidePageLoading();
  }
}

async function deleteImageGallery() {
  const fileId = $(this).attr('data-fileId');
  const file = $(this).attr('data-file');

  const alertResponse = await showSweetConfirm({
    icon: 'warning',
    title: '¡Cuidado!',
    subtitle: `¿Realmente desea eliminar a "${file}"`
  });

  if (!alertResponse) return;

  showPageLoading();

  const parameters = new FormData();

  parameters.append('fileId', fileId);
  parameters.append('file', file);
  parameters.append('action', 'delete_image_gallery');

  const response = await fetchData({
    customURL: 'data/digital_invitations/digital_invitations_gallery_data.php',
    data: parameters
  });

  if (response) {
    showSweetAlert({
      icon: response.state,
      title: response.title
    });

    if (response.state === 'success') {
      $(this).remove();
    }
  }

  hidePageLoading();
}

$(document).on('click', '.delete-individual-picture', function () {
  deleteImagePicker({
    action: 'delete_individual_picture',
    target: $(this)
  });
});

$(document).on('click', '.delete-family-picture', function () {
  deleteImagePicker({
    action: 'delete_family_picture',
    target: $(this)
  });
});

$(document).on('click', '.delete-cr-image', function () {
  deleteImagePicker({
    action: 'delete_cr_image',
    target: $(this)
  });
});

$(document).on('click', '.delete-r-image', function () {
  deleteImagePicker({
    action: 'delete_r_image',
    target: $(this)
  });
});

function showTemplate() {
  const image = $(this).attr('data-image');
  const title = $(this).attr('data-title');

  const img = `<img class="img-fluid" src="${image}">`;

  changeModalTitle(title);

  $('#template-container').html(img);
}

function removeCRCard() {
  const isDisplay = $('#cr-card').hasClass('ni-card-open');

  if (isDisplay) {
    $('#cr-card').removeClass('ni-card-open');
    cleanPicker('cr-image', 'crImage', 'Agregar imagen');
    $('#CRPlace').val('');
    $('#CRDate').val('');
    $('#latitude-CR').val('');
    $('#longitude-CR').val('');
    $('#address-CR').val('');
  }

  if (!isDisplay) $('#cr-card').addClass('ni-card-open');
}

function removeRCard() {
  const isDisplay = $('#r-card').hasClass('ni-card-open');

  if (isDisplay) {
    $('#r-card').removeClass('ni-card-open');
    cleanPicker('r-image', 'rImage', 'Agregar imagen');
    $('#RPlace').val('');
    $('#RDate').val('');
    $('#latitude-Recepcion').val('');
    $('#longitude-Recepcion').val('');
    $('#address-Recepcion').val('');
  }

  if (!isDisplay) $('#r-card').addClass('ni-card-open');
}

function showCards() {
  $(this).parent().parent().addClass('ni-card-open');
}

$(document).on('click', '.btn-delete-image-gallery', deleteImageGallery);

$('.img-template').on('click', showTemplate);

$('#btn-remove-rc-card').on('click', removeCRCard);
$('#btn-remove-r-card').on('click', removeRCard);
$('.btn-add-card').on('click', showCards);

$('.btn-show-preview').on('click', async (e) => {
  e.preventDefault();

  const validate = await validateForm();
  if (!validate) return;

  $('#digital-invitations-form').submit();
});

$('#btn-send-data').on('click', sendDigitalInvitationsData);