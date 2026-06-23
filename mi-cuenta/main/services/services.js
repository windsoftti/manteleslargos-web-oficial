$(document).ready(function () {
  $("#search-filter-form").bind("keypress", function (e) {
    if (e.keyCode == 13) {
      return false;
    }
  });

  loadServices();
});

function searchServices() {
  useSearch(loadServices);
}

async function loadServices(page = 1) {
  showPageLoading();
  const dataSend = new FormData($('#search-filters-form')[0]);

  dataSend.append('page', page);
  dataSend.append('action', 'list_services');

  const response = await fetchData({
    place: 'services',
    data: dataSend
  });

  if (response.content) {
    const table = decodeURIComponent(escape(atob(response.content)));
    $('#list-services').html(table).show('slow');
  }

  hidePageLoading();
}

async function sendServiceData() {
  showPageLoading();
  const form = '#services-form';

  const check = await checkInputValidate(form);

  if (!check) {
    hidePageLoading();
    return;
  }

  const dataSend = new FormData($(form)[0]);

  const response = await fetchData({
    place: 'services',
    data: dataSend
  });

  if (response) {
    showBigAlert({
      icon: response.state,
      title: response.title,
      subtitle: response.message
    });

    if (response.state === 'success') {
      closeModal('modal-add-edit-service');
      resetForm(form);
      loadServices();
    }
  }

  hidePageLoading();
}

$(document).on('click', '.btn-delete-service', async function () {
  const serviceId = $(this).attr('data-serviceId');
  const service = $(this).attr('data-service');

  const alertResponse = await showSweetConfirm({
    title: '!Cuidado!',
    subtitle: `¿Realmente desea eliminar a "${service}"?`,
    buttonTitle: 'Si, continuar',
    cancelButtonText: 'No, cancelar'
  });

  if (!alertResponse) return;

  showPageLoading();

  const dataSend = new FormData();

  dataSend.append('serviceId', serviceId);
  dataSend.append('service', service);
  dataSend.append('action', 'delete_service');

  const response = await fetchData({
    place: 'services',
    data: dataSend
  });

  if (response) {
    showSweetAlert({
      icon: response.state,
      title: response.title
    });

    if (response.state === 'success') {
      loadServices();
    }
  }

  hidePageLoading();
});

$('.btn-add-service').on('click', function () {
  changeModalTitle('Agregar servicio');
  resetForm('#services-form');
  hideInputWarnings();
  $('#action-services').val('add_service');
});