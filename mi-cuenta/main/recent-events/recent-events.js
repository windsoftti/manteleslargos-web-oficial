$(document).ready(function () {
  $("#search-filter-form").bind("keypress", function (e) {
    if (e.keyCode == 13) {
      return false;
    }
  });

  loadRecentEvents();
  loadBusiness();
});

let longDescriptionEditor;

ClassicEditor.create(document.querySelector('#longDescription')).then(editor => {
  longDescriptionEditor = editor;
}).catch(error => {
  console.log(error);
});

function loadBusiness() {
  loadSelect({
    select: 'business-select',
    action: 'business'
  });
}

function searchRecentEvents() {
  useSearch(loadRecentEvents);
}

async function loadRecentEvents(page = 1) {
  showPageLoading();
  const dataSend = new FormData($('#search-filters-form')[0]);

  dataSend.append('page', page);
  dataSend.append('action', 'list_recent_events');

  const response = await fetchData({
    place: 'recent_events',
    data: dataSend
  });

  if (response.content) {
    const table = decodeURIComponent(escape(atob(response.content)));
    $('#list-recent-events').html(table).show('slow');
  }

  hidePageLoading();
}

async function sendRecentEventData() {
  showPageLoading();
  const form = '#recent-events-form';

  const check = await checkInputValidate(form);

  if (!check) {
    hidePageLoading();
    return;
  }

  const longDescription = longDescriptionEditor.getData();

  if (!longDescription) {
    showSweetAlert({
      icon: 'warning',
      title: 'Escriba la descripción larga del evento'
    });

    hidePageLoading();
    return;
  }

  const dataSend = new FormData($(form)[0]);
  dataSend.append('longDescription', longDescription);

  const response = await fetchData({
    place: 'recent_events',
    data: dataSend
  });

  if (response) {
    showBigAlert({
      icon: response.state,
      title: response.title,
      subtitle: response.message
    });

    if (response.state === 'success') {
      closeModal('modal-add-edit-recent-event');
      resetForm(form);
      loadRecentEvents();
      $('#sidebar-recent-events-count').html(response.Total);
    }
  }

  hidePageLoading();
}

async function loadGallery(recentEventId) {
  showPageLoading();

  const dataSend = new FormData();

  dataSend.append('recentEventId', recentEventId);
  dataSend.append('action', 'list_gallery');

  const response = await fetchData({
    customURL: 'data/recent_events/recent_events_gallery_data.php',
    data: dataSend
  });

  const gallery = decodeURIComponent(escape(atob(response.content)));
  $('#list-image-gallery').html(gallery).show();

  hidePageLoading();
}

$(document).on('click', '.btn-delete-recent-event', async function () {
  const recentEventId = $(this).attr('data-recentEventId');
  const recentEvent = $(this).attr('data-recentEvent');

  const alertResponse = await showSweetConfirm({
    title: '!Cuidado!',
    subtitle: `¿Realmente desea eliminar a "${recentEvent}"?`,
    buttonTitle: 'Si, continuar',
    cancelButtonText: 'No, cancelar'
  });

  if (!alertResponse) return;

  showPageLoading();

  const dataSend = new FormData();

  dataSend.append('recentEventId', recentEventId);
  dataSend.append('recentEvent', recentEvent);
  dataSend.append('action', 'delete_recent_event');

  const response = await fetchData({
    place: 'recent_events',
    data: dataSend
  });

  if (response) {
    showSweetAlert({
      icon: response.state,
      title: response.title
    });

    if (response.state === 'success') {
      loadRecentEvents();
    }
  }

  hidePageLoading();
});

$('.btn-add-recent-event').on('click', function () {
  changeModalTitle('Agregar evento reciente');
  resetForm('#recent-events-form');
  hideInputWarnings();
  $('#action-recentEvents').val('add_recent_event');

  cleanPicker('image', 'image', 'Imagen principal del evento');

  cleanMultiplePicker({
    id: 'gallery',
    name: 'gallery',
    idListar: 'list-image-gallery'
  });

  longDescriptionEditor.setData('');
});

$(document).on('click', '.btn-edit-recent-event', function () {
  const data = JSON.parse(atob($(this).attr('data-recentEvent')));

  cleanMultiplePicker({
    id: 'gallery',
    name: 'gallery',
    idListar: 'list-image-gallery'
  });

  longDescriptionEditor.setData('');

  changeModalTitle('Editar evento reciente');
  hideInputWarnings();

  $('#recentEventId').val(data.idEvento);
  $('#businessId').val(data.idSalon);
  $('#recentEvent').val(data.Evento);
  $('#shortDescription').val(data.DescCorta);
  $('#action-recentEvents').val('edit_recent_event');

  const longDescription = decodeURIComponent(escape(atob(data.Descripcion)));
  longDescriptionEditor.setData(longDescription);

  if (data.Imagen) createGlobalFilePreview({
    idPicker: 'image',
    idFile: data.idEvento,
    fileName: data.Imagen,
    extraClass: 'delete-image',
    uriImage: `${HOST_URL}images/eventosRecientes/`
  });

  if (!data.Imagen) {
    cleanPicker('image', 'image', 'Imagen principal del evento');
  }

  loadGallery(data.idEvento);
});

$(document).on('click', '.delete-image', async function () {
  const alertResponse = await showSweetConfirm({
    title: '!Cuidado!',
    subtitle: `¿Realmente desea eliminar la imagen?`
  });

  if (alertResponse) {
    showPageLoading();
    const recentEventId = $(this).attr('data-idFile');

    const dataSend = new FormData();

    dataSend.append('action', 'delete_image');
    dataSend.append('recentEventId', recentEventId);

    const response = await fetchData({
      place: 'recent_events',
      data: dataSend
    });

    if (response) {
      showSweetAlert({
        icon: response.state,
        title: response.title
      });

      if (response.state === 'success') {
        cleanPicker('image', 'image', 'Imagen principal del evento');
        loadRecentEvents();
      }
    }
    hidePageLoading();
  }
});

$(document).on('click', '.btn-delete-image-gallery', async function () {
  const fileId = $(this).attr('data-fileId');
  const file = $(this).attr('data-file');

  const alertResponse = await showSweetConfirm({
    title: '!Cuidado!',
    subtitle: `¿Realmente desea eliminar la imagen "${file}"?`
  });

  if (!alertResponse) return;

  showPageLoading();

  const dataSend = new FormData();

  dataSend.append('fileId', fileId);
  dataSend.append('file', file);
  dataSend.append('action', 'delete_image_galery');

  const response = await fetchData({
    customURL: 'data/recent_events/recent_events_gallery_data.php',
    data: dataSend
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
});