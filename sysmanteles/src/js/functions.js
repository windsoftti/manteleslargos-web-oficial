$(activeCurrentPage);

const BASE_URL = `${location.protocol}//${location.host}/`;

function showProgressBar() {
  $('#page-progressbar').show();
}

function hideProgressBar() {
  $('#page-progressbar').hide();
}

function updateProgressBar(value) {
  $('#page-progressbar').children().children().width(`${value}%`);
  $('#page-progressbar').children().children().html(`${value}%`);
}

function disableBtnSubmit() {
  $(".btn-submit").attr('disabled', true);
}

function enableBtnSubmit() {
  $(".btn-submit").attr('disabled', false);
}

function showPageLoading() {
  $('#page-loading').show();
}

function hidePageLoading() {
  $('#page-loading').hide();
}

function changeModalTitle(title, extraClass = '') {
  $(`.modal-dynamic-title${extraClass}`).html(title);
}

function closeModal(modal) {
  $(`#${modal}`).modal('hide');
}

function resetForm(form) {
  $(form).trigger("reset");
}

function hideInputWarnings() {
  $('form').removeClass('was-validated');
  enableBtnSubmit();
}

function activeCurrentPage() {
  const pathName = location.pathname;
  const hash = location.hash;
  const route = `${pathName}${hash}`;

  document.querySelectorAll(`a.nav-link`).forEach(tag => {
    const href = $(tag).attr('href');

    if (href !== '#' && href != 'javascript:void(0)') {
      const find = route.search(href);

      if (find > 0) {
        $(tag).addClass('active');

        const parent = $(tag).parent('.nav-item').parent('.nav-treeview').parent('.nav-item');

        if (parent) {
          $(parent).addClass('menu-open');
          $(parent).children('.nav-link').addClass('active');
        }
      }
    }
  });
}

function initNumberInput() {
  $('.number-input').on('keyup', function () {
    this.value = (this.value + '').replace(/[^0-9*\.0-9]/g, '');
  });
}

var searchTimeOut = false;

function initSearchForm(func, customForm) {
  const form = customForm ? customForm : '#search-form';

  $(form).on('submit', (e) => {
    e.preventDefault();
    !!func && func();
  });

  $(form).on('change', (e) => {
    e.preventDefault();
    !!func && func();
  });

  $(form).on('keyup', () => {
    if (searchTimeOut != false) {
      window.clearTimeout(searchTimeOut);
    }

    searchTimeOut = window.setTimeout(() => {
      !!func && func();
    }, 500);
  });

  $(`${form} .per-page`).on('click', function () {
    const page = $(this).attr('data-page');

    $(`${form} .perPageInput`).val(page);

    //$('#per-page').val(page);

    $(`${form} .per-page`).removeClass('bg-primary text-white');
    $(this).addClass('bg-primary text-white');

    !!func && func();
  });
}

function decryptData(data) {
  const initialDecode = decodeURIComponent(atob(data));
  const textNode = document.createTextNode(initialDecode);

  return textNode;
}

function decryptDataTable(data) {
  const textNode = document.createTextNode(data);

  return textNode;
}

function initShowHidePassword() {
  $('#addon-password').on('click', function () {
    const input = document.getElementById("password");

    if (input.type === 'password') {
      input.type = 'text';
      $('#icon-password').removeClass('fa-eye-slash');
      $('#icon-password').addClass('fa-eye');
    } else if (input.type === 'text') {
      input.type = 'password';
      $('#icon-password').removeClass('fa-eye');
      $('#icon-password').addClass('fa-eye-slash');
    }
  });

  $('#addon-confirmPassword').on('click', function () {
    const input = document.getElementById("confirmPassword");

    if (input.type === 'password') {
      input.type = 'text';
      $('#icon-confirmPassword').removeClass('fa-eye-slash');
      $('#icon-confirmPassword').addClass('fa-eye');
    } else if (input.type === 'text') {
      input.type = 'password';
      $('#icon-confirmPassword').removeClass('fa-eye');
      $('#icon-confirmPassword').addClass('fa-eye-slash');
    }
  });
}

const fetchData = ({
  place,
  customURL,
  parameters,
  showProgress = false
}) => new Promise((resolve, reject) => {
  // Request
  const request = new XMLHttpRequest();

  try {
    const defaultURL = `data/${place}/${place}_data.php`;
    const url = customURL ? customURL : defaultURL;

    console.log(url);

    // Progress
    request.upload.addEventListener('progress', event => {
      const percent = Math.round((event.loaded / event.total) * 100);
      console.log(percent);

      if (showProgress) {
        showProgressBar();
        updateProgressBar(percent);
      }
    });

    request.addEventListener('load', () => {
      if (showProgress) {
        hideProgressBar();
        updateProgressBar(0);
      }
    });

    request.addEventListener('loadend', event => {
      if (!event.target.response) {
        hideProgressBar();
        alert('¡Error!');

        resolve([]);
      }

      if (event.target.response) {
        const response = JSON.parse(event.target.response);
        resolve(response);
      }
    });

    // Enviar datos
    request.open('post', url);
    request.send(parameters)

    // Cancelar
    const cancelButton = document.getElementById('page-progressbar-cancel');

    !!cancelButton && cancelButton.addEventListener('click', () => {
      request.abort();

      hideProgressBar();
      updateProgressBar(0);
    });
  } catch (error) {
    alert(error);

    request.abort();

    hideProgressBar();
    updateProgressBar(0);

    resolve([]);
  }
});

const useLoadTable = async ({
  page = 1,
  place,
  action,
  extraData,
  searchForm = '#search-form'
}) => {
  const url = `data/${place}/${place}_data.php`;

  const parameters = new FormData($(searchForm)[0]);
  parameters.append('page', page);
  parameters.append('action', action);
  parameters.append('extraData', extraData);

  $.ajax({
    data: parameters,
    url,
    type: 'post',
    enctype: 'multipart/form-data',
    processData: false,
    contentType: false,
    cache: false,
    beforeSend: function () {
      showPageLoading();
    },
    success: response => {
      console.log(response);
      if (response) $(`#${action}`).html(response);
      hidePageLoading();
    },
    error: error => {
      alert(error.responseText);
      hidePageLoading();
      return;
    }
  });
}

/* async function _useLoadTable({
  page = 1,
  place,
  action,
  extraData,
  searchForm = '#search-form'
}) {
  showPageLoading();

  const parameters = new FormData($(searchForm)[0]);
  parameters.append('page', page);
  parameters.append('action', action);
  parameters.append('extraData', extraData);

  const response = await fetchData({
    place,
    parameters
  });

  hidePageLoading();

  console.log(response);

  if (response.content) {
    const data = decryptDataTable(response.content);
    $(`#${action}`).html(data);
  }
} */

async function useDeleteFromTable({
  itemId,
  item,
  place,
  action,
  onDeleted
}) {
  const alertResponse = await showSweetConfirm({
    message: `¿Realmente desea eliminar a "${item}"?`
  });

  if (!alertResponse) return;

  showPageLoading();

  const parameters = new FormData();

  parameters.append('itemId', itemId);
  parameters.append('item', item);
  parameters.append('action', action);

  const response = await fetchData({
    place,
    parameters
  });

  hidePageLoading();

  if (response.message) showSweetToast({
    icon: response.status,
    message: response.message
  });

  if (response.status === 'success') !!onDeleted && onDeleted();
}

async function useSimpleActionFromTable({
  itemId,
  item,
  place,
  action,
  onAction
}) {
  const alertResponse = await showSweetConfirm({
    title: `¡Cuidado!`,
    message: `¿Realmente desea realizar esta acción?`
  });

  if (!alertResponse) return;

  showPageLoading();

  const parameters = new FormData();

  parameters.append('itemId', itemId);
  parameters.append('item', item);
  parameters.append('action', action);

  const response = await fetchData({
    place,
    parameters
  });

  hidePageLoading();

  if (response.message) showSweetToast({
    icon: response.status,
    message: response.message
  });

  if (response.status === 'success') !!onAction && onAction();
}

async function useLoadSelect({
  select,
  action,
  data,
  loading,
  replace = false
}) {
  if (loading) showPageLoading();
  const dataSend = new FormData();

  dataSend.append('data', data);
  dataSend.append('action', action);

  const response = await fetchData({
    place: 'selects',
    data: dataSend
  });

  if (response.content) {
    const dataSelect = decodeURIComponent(escape(atob(response.content)));
    if (!replace) $(`.${select}`).append(dataSelect);
    if (replace) $(`.${select}`).html(dataSelect);
  }

  if (loading) hidePageLoading();
}

const callEndpoint = ({
  place,
  customURL,
  parameters,
  formData,
  showLoading = true
}) => new Promise((resolve, reject) => {
  if (showLoading) showPageLoading();

  const defaultURL = `data/${place}/${place}_data.php`;
  const url = customURL ? customURL : defaultURL;

  let data;

  if (!formData) data = new FormData();
  if (formData) data = formData;

  for (const valueName in parameters) {
    const value = parameters[valueName];
    data.append(`${valueName}`, value);
  }

  fetch(url, {
    method: 'post',
    body: data
  }).then(res => res.json()).then(response => {
    hidePageLoading();
    resolve(response);
  }).catch(error => {
    hidePageLoading();
    alert(error);
    resolve({});
  });
});