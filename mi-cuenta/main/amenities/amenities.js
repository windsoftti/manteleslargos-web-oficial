$(document).ready(function () {
  $("#search-filter-form").bind("keypress", function (e) {
    if (e.keyCode == 13) {
      return false;
    }
  });

  loadAmenities();
});

function searchAmenities() {
  useSearch(loadAmenities);
}

async function loadAmenities(page = 1) {
  showPageLoading();
  const dataSend = new FormData($('#search-filters-form')[0]);

  dataSend.append('page', page);
  dataSend.append('action', 'list_amenities');

  const response = await fetchData({
    place: 'amenities',
    data: dataSend
  });

  if (response.content) {
    const table = decodeURIComponent(escape(atob(response.content)));
    $('#list-amenities').html(table).show('slow');
  }

  hidePageLoading();
}

async function sendAmenityData() {
  showPageLoading();
  const form = '#amenities-form';

  const check = await checkInputValidate(form);

  if (!check) {
    hidePageLoading();
    return;
  }

  const dataSend = new FormData($(form)[0]);

  const response = await fetchData({
    place: 'amenities',
    data: dataSend
  });

  if (response) {
    showBigAlert({
      icon: response.state,
      title: response.title,
      subtitle: response.message
    });

    if (response.state === 'success') {
      closeModal('modal-add-edit-amenity');
      resetForm(form);
      loadAmenities();
    }
  }

  hidePageLoading();
}

$(document).on('click', '.btn-delete-amenity', async function () {
  const amenityId = $(this).attr('data-amenityId');
  const amenity = $(this).attr('data-amenity');

  const alertResponse = await showSweetConfirm({
    title: '!Cuidado!',
    subtitle: `¿Realmente desea eliminar a "${amenity}"?`
  });

  if (!alertResponse) return;

  showPageLoading();

  const dataSend = new FormData();

  dataSend.append('amenityId', amenityId);
  dataSend.append('amenity', amenity);
  dataSend.append('action', 'delete_amenity');

  const response = await fetchData({
    place: 'amenities',
    data: dataSend
  });

  if (response) {
    showSweetAlert({
      icon: response.state,
      title: response.title
    });

    if (response.state === 'success') {
      loadAmenities();
    }
  }

  hidePageLoading();
});

$('.btn-add-amenity').on('click', function () {
  changeModalTitle('Agregar amenidad');
  resetForm('#amenities-form');
  hideInputWarnings();
  $('#action-amenities').val('add_amenity');
});