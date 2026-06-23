$(document).ready(function () {
  $("#search-filter-form").bind("keypress", function (e) {
    if (e.keyCode == 13) {
      return false;
    }
  });

  loadTips();
  /* loadBusiness(); */
});

let longDescriptionEditor;

ClassicEditor.create(document.querySelector('#longDescription')).then(editor => {
  longDescriptionEditor = editor;
}).catch(error => {
  console.log(error);
});

/* function loadBusiness() {
  loadSelect({
    select: 'business-select',
    action: 'business'
  });
} */

function searchTips() {
  useSearch(loadTips);
}

async function loadTips(page = 1) {
  showPageLoading();
  const dataSend = new FormData($('#search-filters-form')[0]);

  dataSend.append('page', page);
  dataSend.append('action', 'list_tips');

  const response = await fetchData({
    place: 'tips',
    data: dataSend
  });

  if (response.content) {
    const table = decodeURIComponent(escape(atob(response.content)));
    $('#list-tips').html(table).show('slow');
  }

  hidePageLoading();
}

async function sendTipData() {
  showPageLoading();
  const form = '#tips-form';

  const check = await checkInputValidate(form);

  if (!check) {
    hidePageLoading();
    return;
  }

  const longDescription = longDescriptionEditor.getData();

  if (!longDescription) {
    showSweetAlert({
      icon: 'warning',
      title: 'Escriba la descripción larga del tip'
    });

    hidePageLoading();
    return;
  }

  const dataSend = new FormData($(form)[0]);
  dataSend.append('longDescription', longDescription);

  const response = await fetchData({
    place: 'tips',
    data: dataSend
  });

  if (response) {
    showBigAlert({
      icon: response.state,
      title: response.title,
      subtitle: response.message
    });

    if (response.state === 'success') {
      closeModal('modal-add-edit-tip');
      resetForm(form);
      loadTips();
    }
  }

  hidePageLoading();
}

$(document).on('click', '.btn-delete-tip', async function () {
  const tipId = $(this).attr('data-tipId');
  const tip = $(this).attr('data-tip');

  const alertResponse = await showSweetConfirm({
    title: '!Cuidado!',
    subtitle: `¿Realmente desea eliminar a "${tip}"?`,
    buttonTitle: 'Si, continuar',
    cancelButtonText: 'No, cancelar'
  });

  if (!alertResponse) return;

  showPageLoading();

  const dataSend = new FormData();

  dataSend.append('tipId', tipId);
  dataSend.append('tip', tip);
  dataSend.append('action', 'delete_tip');

  const response = await fetchData({
    place: 'tips',
    data: dataSend
  });

  if (response) {
    showSweetAlert({
      icon: response.state,
      title: response.title
    });

    if (response.state === 'success') {
      loadTips();
    }
  }

  hidePageLoading();
});

$('.btn-add-tip').on('click', function () {
  changeModalTitle('Agregar tip');
  resetForm('#tips-form');
  hideInputWarnings();
  $('#action-tips').val('add_tip');

  cleanPicker('image', 'image', 'Imagen principal');

  longDescriptionEditor.setData('');
});

$(document).on('click', '.btn-edit-tip', function () {
  const data = JSON.parse(atob($(this).attr('data-tip')));

  longDescriptionEditor.setData('');

  changeModalTitle('Editar tip');
  hideInputWarnings();

  $('#tipId').val(data.idTip);
  //$('#businessId').val(data.idSalon);
  $('#tip').val(data.Tip);
  $('#shortDescription').val(data.DescCorta);
  $('#action-tips').val('edit_tip');

  const longDescription = decodeURIComponent(escape(atob(data.Descripcion)));
  longDescriptionEditor.setData(longDescription);

  if (data.Imagen) createGlobalFilePreview({
    idPicker: 'image',
    idFile: data.idTip,
    fileName: data.Imagen,
    extraClass: 'delete-image',
    uriImage: `${HOST_URL}images/tips/`
  });

  if (!data.Imagen) {
    cleanPicker('image', 'image', 'Imagen principal');
  }
});

$(document).on('click', '.delete-image', async function () {
  const alertResponse = await showSweetConfirm({
    title: '!Cuidado!',
    subtitle: `¿Realmente desea eliminar la imagen?`
  });

  if (alertResponse) {
    showPageLoading();
    const tipId = $(this).attr('data-idFile');

    const dataSend = new FormData();

    dataSend.append('action', 'delete_image');
    dataSend.append('tipId', tipId);

    const response = await fetchData({
      place: 'tips',
      data: dataSend
    });

    if (response) {
      showSweetAlert({
        icon: response.state,
        title: response.title
      });

      if (response.state === 'success') {
        cleanPicker('image', 'image', 'Imagen principal del tip');
        loadTips();
      }
    }
    hidePageLoading();
  }
});