$(document).ready(function () {
  $("#search-filter-form").bind("keypress", function (e) {
    if (e.keyCode == 13) {
      return false;
    }
  });

  loadEgresos(1);
});

function searchEgresos() {
  useSearch(loadEgresos);
}

async function loadEgresos(page = 1) {
  showPageLoading();
  const dataSend = new FormData($('#search-filters-form')[0]);

  dataSend.append('page', page);
  dataSend.append('action', 'list_egresos');

  const response = await fetchData({
    place: 'egresos',
    data: dataSend
  });

  if (response.content) {
    const table = decodeURIComponent(escape(atob(response.content)));
    $('#list-egresos').html(table).show('slow');
  }

  hidePageLoading();
}

async function sendEgresoData(e) {
  e.preventDefault();

  showPageLoading();
  const form = '#egresos-form';

  const check = await checkInputValidate(form);

  if (!check) {
    hidePageLoading();
    return;
  }

  const dataSend = new FormData($(form)[0]);

  const response = await fetchData({
    place: 'egresos',
    data: dataSend
  });

  if (response) {
    showBigAlert({
      icon: response.state,
      title: response.title,
      subtitle: response.message
    });

    if (response.state === 'success') {
      closeModal('modal-add-edit-egreso');
      resetForm(form);
      loadEgresos();
    }
  }

  hidePageLoading();
}

$(document).on('click', '.btn-delete-egreso', async function () {
  const idEgreso = $(this).attr('data-idEgreso');
  //const egreso = $(this).attr('data-egreso');

  const alertResponse = await showSweetConfirm({
    title: '!Cuidado!',
    subtitle: `¿Realmente quiere eliminar este egreso?`
  });

  if (!alertResponse) return;

  showPageLoading();

  const dataSend = new FormData();

  dataSend.append('idEgreso', idEgreso);
  dataSend.append('action', 'delete_egreso');

  const response = await fetchData({
    place: 'egresos',
    data: dataSend
  });

  if (response) {
    showSweetAlert({
      icon: response.state,
      title: response.title
    });

    if (response.state === 'success') {
      loadEgresos();
    }
  }

  hidePageLoading();
});

$('.btn-add-egreso').on('click', function () {
  changeModalTitle('Agregar egreso');
  resetForm('#egresos-form');
  hideInputWarnings();
  $('#action-egresos').val('add_egreso');
});

$('#egresos-form').on('submit', sendEgresoData);