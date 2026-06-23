$(document).ready(function () {
  $("#search-filter-form").bind("keypress", function (e) {
    if (e.keyCode == 13) {
      return false;
    }
  });

  loadEventTypes();
});

function searchEventTypes() {
  useSearch(loadEventTypes);
}

async function loadEventTypes(page = 1) {
  showPageLoading();
  const dataSend = new FormData($('#search-filters-form')[0]);

  dataSend.append('page', page);
  dataSend.append('action', 'list_event_types');

  const response = await fetchData({
    place: 'event_types',
    data: dataSend
  });

  if (response.content) {
    const table = decodeURIComponent(escape(atob(response.content)));
    $('#list-event-types').html(table).show('slow');
  }

  hidePageLoading();
}

async function sendEventTypesData() {
  showPageLoading();
  const form = '#event-types-form';

  const check = await checkInputValidate(form);

  if (!check) {
    hidePageLoading();
    return;
  }

  const dataSend = new FormData($(form)[0]);

  const response = await fetchData({
    place: 'event_types',
    data: dataSend
  });

  if (response) {
    showBigAlert({
      icon: response.state,
      title: response.title,
      subtitle: response.message
    });

    if (response.state === 'success') {
      closeModal('modal-add-edit-event-type');
      resetForm(form);
      loadEventTypes();
    }
  }

  hidePageLoading();
}

$(document).on('click', '.btn-delete-event-type', async function () {
  const eventTypeId = $(this).attr('data-eventTypeId');
  const eventType = $(this).attr('data-eventType');

  const alertResponse = await showSweetConfirm({
    title: '!Cuidado!',
    subtitle: `¿Realmente desea eliminar a "${eventType}"?`,
    buttonTitle: 'Si, continuar',
    cancelButtonText: 'No, cancelar'
  });

  if (!alertResponse) return;

  showPageLoading();

  const dataSend = new FormData();

  dataSend.append('eventTypeId', eventTypeId);
  dataSend.append('eventType', eventType);
  dataSend.append('action', 'delete_event_type');

  const response = await fetchData({
    place: 'event_types',
    data: dataSend
  });

  if (response) {
    showSweetAlert({
      icon: response.state,
      title: response.title
    });

    if (response.state === 'success') {
      loadEventTypes();
    }
  }

  hidePageLoading();
});

$('.btn-add-event-type').on('click', function () {
  changeModalTitle('Agregar tipo de evento');
  resetForm('#event-types-form');
  hideInputWarnings();
  $('#action-eventTypes').val('add_event_type');
  cleanPicker('image', 'image', 'Agregar imagen');
});

$(document).on('click', '.delete-image', async function () {
  const alertResponse = await showSweetConfirm({
    title: '!Cuidado!',
    subtitle: `¿Realmente desea eliminar la imagen?`
  });

  if (alertResponse) {
    showPageLoading();
    const eventTypeId = $(this).attr('data-idFile');

    const dataSend = new FormData();

    dataSend.append('action', 'delete_image');
    dataSend.append('eventTypeId', eventTypeId);

    const response = await fetchData({
      place: 'event_types',
      data: dataSend
    });

    if (response) {
      showSweetAlert({
        icon: response.state,
        title: response.title
      });

      if (response.state === 'success') {
        cleanPicker('image', 'image', 'Agregar imagen');
        loadEventTypes();
      }
    }
    hidePageLoading();
  }
});