$(document).ready(function () {
  $("#search-filter-form").bind("keypress", function (e) {
    if (e.keyCode == 13) {
      return false;
    }
  });

  loadVendorTypes();
});

function searchVendorTypes() {
  useSearch(loadVendorTypes);
}

async function loadVendorTypes(page = 1) {
  showPageLoading();
  const dataSend = new FormData($('#search-filters-form')[0]);

  dataSend.append('page', page);
  dataSend.append('action', 'list_vendor_types');

  const response = await fetchData({
    place: 'vendor_types',
    data: dataSend
  });

  if (response.content) {
    const table = decodeURIComponent(escape(atob(response.content)));
    $('#list-vendor-types').html(table).show('slow');
  }

  hidePageLoading();
}

async function sendVendorTypesData() {
  showPageLoading();
  const form = '#vendor-types-form';

  const check = await checkInputValidate(form);

  if (!check) {
    hidePageLoading();
    return;
  }

  const dataSend = new FormData($(form)[0]);

  const response = await fetchData({
    place: 'vendor_types',
    data: dataSend
  });

  if (response) {
    showBigAlert({
      icon: response.state,
      title: response.title,
      subtitle: response.message
    });

    if (response.state === 'success') {
      closeModal('modal-add-edit-vendor-type');
      resetForm(form);
      loadVendorTypes();
    }
  }

  hidePageLoading();
}

$(document).on('click', '.btn-delete-vendor-type', async function () {
  const vendorTypeId = $(this).attr('data-vendorTypeId');
  const vendorType = $(this).attr('data-vendorType');

  const alertResponse = await showSweetConfirm({
    title: '!Cuidado!',
    subtitle: `¿Realmente desea eliminar a "${vendorType}"?`,
    buttonTitle: 'Si, continuar',
    cancelButtonText: 'No, cancelar'
  });

  if (!alertResponse) return;

  showPageLoading();

  const dataSend = new FormData();

  dataSend.append('vendorTypeId', vendorTypeId);
  dataSend.append('vendorType', vendorType);
  dataSend.append('action', 'delete_vendor_type');

  const response = await fetchData({
    place: 'vendor_types',
    data: dataSend
  });

  if (response) {
    showSweetAlert({
      icon: response.state,
      title: response.title
    });

    if (response.state === 'success') {
      loadVendorTypes();
    }
  }

  hidePageLoading();
});

$('.btn-add-vendor-type').on('click', function () {
  changeModalTitle('Agregar tipo de proveedor');
  resetForm('#vendor-types-form');
  hideInputWarnings();
  $('#action-vendorTypes').val('add_vendor_type');
  cleanPicker('image', 'image', 'Agregar imagen');
});

$(document).on('click', '.delete-image', async function () {
  const alertResponse = await showSweetConfirm({
    title: '!Cuidado!',
    subtitle: `¿Realmente desea eliminar la imagen?`
  });

  if (alertResponse) {
    showPageLoading();
    const vendorTypeId = $(this).attr('data-idFile');

    const dataSend = new FormData();

    dataSend.append('action', 'delete_image');
    dataSend.append('vendorTypeId', vendorTypeId);

    const response = await fetchData({
      place: 'vendor_types',
      data: dataSend
    });

    if (response) {
      showSweetAlert({
        icon: response.state,
        title: response.title
      });

      if (response.state === 'success') {
        cleanPicker('image', 'image', 'Agregar imagen');
        loadVendorTypes();
      }
    }
    hidePageLoading();
  }
});