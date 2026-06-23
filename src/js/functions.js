$(function () {
  initNumberInput();
});

const initNumberInput = () => $('.number-input').on('keyup', function () {
  this.value = (this.value + '').replace(/[^0-9*\.0-9]/g, '');
});

const decryptData = data => {
  const response = decodeURIComponent(escape(atob(data)))
  return response;
}

const resetForm = form => $(form).trigger('reset');

/* const fetchData = async ({
  place,
  customURL,
  parameters
}) => {
  try {
    const defaultURL = `data/${place}/${place}_data.php`;
    const url = BASE_URL + '/' + (customURL ? customURL : defaultURL);

    console.log(url);

    const timeout = 60000;
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), timeout);

    const resData = await fetch(url, {
      method: 'POST',
      body: parameters,
      signal: controller.signal
    });

    clearTimeout(timeoutId);

    const response = await resData.json();

    if (!response) alert('Error del servidor, intentelo nuevamente.');

    if (response) {
      console.log(response);
      return response;
    }

  } catch (error) {
    if (error.name === 'AbortError') alert('Verifique su conexión a internet e intentelo nuevamente.');

    if (error.name !== 'AbortError') alert(error);

    console.log(error)

    return false;
  }
} */

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
    const url = BASE_URL + '/' + (customURL ? customURL : defaultURL);

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
    /* const cancelButton = document.getElementById('page-progressbar-cancel');

    !!cancelButton && cancelButton.addEventListener('click', () => {
      request.abort();

      hideProgressBar();
      updateProgressBar(0);
    }); */
  } catch (error) {
    alert(error);

    request.abort();

    hideProgressBar();
    updateProgressBar(0);

    resolve([]);
  }
});

const useLoadSelect = async ({
  select,
  action,
  data,
  label
}) => {
  showPageLoading();

  const parameters = new FormData();

  parameters.append('data', data);
  parameters.append('label', label);
  parameters.append('action', action);

  const response = await fetchData({
    place: 'selects',
    parameters
  });

  hidePageLoading();

  if (response.status === 'success') {
    const content = decryptData(response.content);
    $(select).html(content);
  }
}

var searchTimeOut = false;
function initSearchForm(func, customForm) {
  const form = customForm ? customForm : '#filters-form';

  $(form).on('submit', (e) => {
    e.preventDefault();
    !!func && func();
  });

  $(`${form} select`).on('change', (e) => {
    e.preventDefault();
    !!func && func();
  });

  $(`${form} input`).on('keyup', () => {
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

async function useLoadTable({
  page = 1,
  place,
  action,
  extraData,
  searchForm = '#filters-form'
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

  if (response.content) {
    const data = decryptData(response.content);
    $(`#${action}`).html(data);
  }
}

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

  //const alertResponse = confirm('¿Realmente desea realizar esta acción?');
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

const callEndpoint = ({
  place,
  customURL,
  parameters,
  showLoading = true
}) => new Promise((resolve, reject) => {
  if (showLoading) showPageLoading();

  const defaultURL = `data/${place}/${place}_data.php`;
  const url = BASE_URL + '/' + (customURL ? customURL : defaultURL);

  const data = new FormData();

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