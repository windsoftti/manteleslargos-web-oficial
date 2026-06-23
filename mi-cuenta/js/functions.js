$(function () {
  initNumberInput();
});

const showProgressBar = () => $('#page-progressbar').addClass('active');
const hideProgressBar = () => $('#page-progressbar').removeClass('active');

const updateProgressBar = value => {
  $('#page-progressbar .progress').width(`${value}%`);
  $('#page-progressbar .percentage').html(`${value}%`);
}

const initNumberInput = () => $('.number-input').on('keyup', function () {
  this.value = (this.value + '').replace(/[^0-9*\.0-9]/g, '');
});

const HOST_URL = `${location.protocol}//${location.host}/2021/web/`;

const decryptData = data => {
  const response = decodeURIComponent(escape(atob(data)))
  return response;
}

function disableBtnSubmit() {
  $(".btn-submit").attr('disabled', true);
}

function enableBtnSubmit() {
  $(".btn-submit").attr('disabled', false);
}

function showBtnLoading(extraClass) {
  $(`.btn-loading${extraClass}`).addClass('is-loading');
}

function hideBtnLoading(extraClass) {
  $(`.btn-loading${extraClass}`).removeClass('is-loading');
}

function showPageLoading() {
  setTimeout(() => {
    $('#page-loading').show();
  }, 200);
}

function hidePageLoading() {
  setTimeout(() => {
    $('#page-loading').hide();
    clearTimeout();
  }, 200);
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
  $('input').removeClass('is-invalid');
  $('select').removeClass('is-invalid');
  $('textarea').removeClass('is-invalid');
  $('.invalid-feedback').css('display', 'none');
  enableBtnSubmit();
}

async function usePerPage(perPageValue, func) {
  await $('#per-page').val(perPageValue);
  !!func && func();
}

async function altPerPage(idPerPage = 15) {
  await $('.per-page').removeClass('bg-purple text-white');
  $(`#per-page-${idPerPage}`).addClass('bg-purple text-white');
}

function checkInputValidate(form = '') {
  const elements = $(form).find(`[required]`);
  const length = elements.length;

  for (let index = 0; index < length; index++) {
    const element = elements[index].value;

    if (!element) {
      showSweetAlert({
        icon: 'error',
        title: '¡Error, Aún hay campos vacíos!.'
      });

      return false;
    }
  }

  return true;
}

var searchTimeOut = false;
function useSearch(func) {
  if (searchTimeOut != false) {
    window.clearTimeout(searchTimeOut);
  }
  searchTimeOut = window.setTimeout(() => func(), 500);
}

/* async function fetchData({ place, customURL, data }) {
  try {
    const defaultURL = `data/${place}/${place}_data.php`;
    const url = customURL ? customURL : defaultURL;

    console.log(url);

    const timeout = 60000;
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), timeout);

    const resData = await fetch(url, {
      method: 'POST',
      body: data,
      //signal: controller.signal
    });

    //clearTimeout(timeoutId);

    const response = await resData.json();

    console.log(response)

    if (!response) {
      showBigAlert({ icon: 'error', title: '¡Error!', subtitle: 'Error del servidor, intentelo nuevamente.' });
    }

    if (response) return response;

  } catch (error) {
    if (error.name === 'AbortError') {
      showBigAlert({ icon: 'error', title: '¡Error!', subtitle: 'Verifique su conexión a internet e intentelo nuevamente.' });
    }

    if (error.name !== 'AbortError') {
      showBigAlert({ icon: 'error', title: '¡Error!', subtitle: error });
    }

    return false;
  }
} */

const fetchData = ({
  place,
  customURL,
  data,
  showProgress = false
}) => new Promise((resolve, reject) => {
  // Request
  const request = new XMLHttpRequest();

  try {
    const defaultURL = `data/${place}/${place}_data.php`;
    const url = customURL ? customURL : defaultURL;

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
    request.send(data)

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

async function loadSelect({ select, action, data, loading }) {
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
    $(`${select}`).html(dataSelect);
  }

  if (loading) hidePageLoading();
}

$('.number-input').on('keyup', function () {
  this.value = (this.value + '').replace(/[^0-9*\.0-9]/g, '');
});

function getURL() {
  //var href = window.location.href;
  //var dir = href.substring(0, href.lastIndexOf('/')) + "/";

  //return dir;
  const host = window.location.protocol + '//' + window.location.host;
  const dir = `${host}/crm/`;

  return dir;
}

$('.clean').on('keyup', function () {
  this.value = (this.value + '').replace(' ', '');
});

const initBDatePicker = element => $(element).datetimepicker({
  format: 'DD/MM/YYYY',
  locale: 'es-es',
  icons: {
    time: "fal fa-clock",
    date: "fal fa-calendar",
    up: "fal fa-arrow-up",
    down: "fal fa-arrow-down",
    previous: "fal fa-chevron-left",
    next: "fal fa-chevron-right",
    today: "fal fa-clock",
    clear: "fal fa-trash",
    close: "fal fa-times"
  }
});

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- STEPPER
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
const setStepperAlert = ({
  message = 'Completa los campos requeridos.'
}) => `
  <div>
    <ion-icon name="information-circle"></ion-icon>
  </div>
  <span>
    ${message}
  </span>
`;

const initStepper = ({
  indentifier,
  onSubmit,
  editors,
  onBeforeSubmit,
  onBeforeVisualize
}) => $(indentifier).each(function () {
  const steps = $(this).children('.stepper-header').children('a');

  let currentStep = 0;

  const numSteps = steps.length;
  const stepsContent = $(this).children('.stepper-body').children('div');
  const stepperFooter = $(this).children('.stepper-footer');
  const stepperAlert = $(this).children('.alert');

  const prevStepButton = stepperFooter.children('div').children('.prev');

  // PASO ANTERIOR
  $(prevStepButton).on('click', () => {
    const oldStep = currentStep;
    let newStep = currentStep - 1;

    const isHidden = $(steps[newStep]).hasClass('type-hidden');
    if (isHidden) newStep = newStep - 1;

    $(steps[oldStep]).removeClass('active');
    $(stepsContent[oldStep]).removeClass('active');

    $(steps[newStep]).addClass('active');
    $(stepsContent[newStep]).addClass('active');

    currentStep = newStep;

    if (newStep > 0) {
      stepperFooter.removeClass('finish');
      stepperFooter.addClass('middle');
    }

    if (newStep == 0) stepperFooter.removeClass('middle');
  });

  // SIGUIENTE PASO O HACER SUBMIT SI TODO ESTA CORRECTO
  $(this).on('submit', async function (e) {
    e.preventDefault();

    console.log(e)

    const submitter = e.originalEvent.submitter;
    const submitterType = $(submitter).attr('class');

    const form = $(this);

    const validate = await validateForm(
      currentStep,
      submitterType
    );

    console.log(validate)

    if (!validate) return false;

    if (validate === 'visualize') {
      !!onBeforeVisualize && await onBeforeVisualize();
      //e.originalEvent();
      e.currentTarget.submit();
    }

    if (validate === 'finish') {
      !!onBeforeSubmit && await onBeforeSubmit();
      !!onSubmit && onSubmit(form);
      return false;
    }
  });

  const validateForm = (step, submitterType) => new Promise((resolve, reject) => {
    const stepId = $(stepsContent[step]).attr('id');

    let hasErrors = false;

    if (editors) {
      const descEditors = editors.editors;
      const numEditors = editors.numEditors;

      console.log(editors);

      for (let index = 1; index <= numEditors; index++) {
        const editor = descEditors[`${stepId}-editor-${index}`];
        const find = $(`#${stepId}-editor-${index}`).length;

        console.log(find);

        if (editor && find) {
          const editorData = editor.getData();
          if (!editorData) {
            hasErrors = true;

            /* stepperAlert.html(setStepperAlert({
              message: 'Completa los campos requeridos.'
            }));

            stepperAlert.show(); */

            showSweetAlert({
              icon: 'error',
              title: 'Completa los campos requeridos'
            });

            //return false;
          }
        }
      }
    }

    $(`#${stepId} :input`).each(function () {
      const isVisible = $(this).is(':visible');
      const inputType = $(this).attr('type');
      const inputId = $(this).attr('id');

      if (inputId && inputId !== undefined) {
        if (isVisible || inputType == 'hidden') {
          const validate = $(this).attr('validate');
          const greaterThan = $(this).attr('greaterThan');
          const greaterThanLabel = $(this).attr('greaterThanLabel');
          const inputLabel = $(this).attr('inputLabel');

          if (validate != undefined) {
            const value = $(this).val();
            const message = $(this).attr('labelError') != undefined ? $(this).attr('labelError') : 'Completa los campos requeridos';

            if (!value) {
              hasErrors = true;

              /* stepperAlert.html(setStepperAlert({
                message
              }));
  
              stepperAlert.show(); */

              showSweetAlert({
                icon: 'error',
                title: message
              });

              console.log('inputId:::::', inputId)

              $(`#${inputId}`).focus();
              $(`#${inputId}`).addClass('error');
              $('html,body').animate({
                scrollTop: $("#" + inputId).offset().top - 200
              }, 'fast');
              //resolve(request);
              //return false;
              resolve(false);
              return false;
            }

            if (inputType === 'radio') {
              const inputName = $(this).attr('name');

              //console.log('inputId:::::', inputId)

              const isChecked = $(`input[name=${inputName}]:checked`).val();
              if (!isChecked) {
                hasErrors = true;

                /* stepperAlert.html(setStepperAlert({
                  message
                }));
  
                stepperAlert.show(); */

                showSweetAlert({
                  icon: 'error',
                  title: message
                });

                if ($(`#${inputId}`).is(':visible')) {
                  $(`#${inputId}`).focus();
                  $(`#${inputId}`).addClass('error');
                  $('html,body').animate({
                    scrollTop: $("#" + inputId).offset().top - 200
                  }, 'fast');
                }

                //return false;
                resolve(false);
                return false;
              }
            }

            if (inputType === 'checkbox') {
              const inputName = $(this).attr('name').toString();
              const inputClass = inputName.replace('[]', '') + '-checkbox';

              let isChecked = false;

              $(`.${inputClass}`).each(function () {
                const inputId = $(this).attr('id');
                const inputChecked = $(`#${inputId}`).is(':checked');
                if (inputChecked) isChecked = true;
              });

              if (!isChecked) {
                hasErrors = true;

                /* stepperAlert.html(setStepperAlert({
                  message
                }));
  
                stepperAlert.show(); */

                showSweetAlert({
                  icon: 'error',
                  title: message
                });

                $(`#${inputId}`).focus();
                $(`#${inputId}`).addClass('error');
                $('html,body').animate({
                  scrollTop: $("#" + inputId).offset().top - 200
                }, 'fast');

                resolve(false);
                return false;
              }
            }
          }

          if (greaterThan != undefined) {
            const value = parseFloat($(this).val());
            const valueCompare = parseFloat($(`#${greaterThan}`).val());

            if (value <= valueCompare) {
              hasErrors = true;

              const message = `${inputLabel} tiene que ser mayor que ${greaterThanLabel}`;

              showSweetAlert({
                icon: 'error',
                title: message
              });

              $(`#${inputId}`).focus();
              $(`#${inputId}`).addClass('error');
              $('html,body').animate({
                scrollTop: $("#" + inputId).offset().top - 200
              }, 'fast');
              //resolve(request);
              //return false;
              resolve(false);
              return false;
            }
          }
        }
      }
    });

    //console.log(editors['packageDescription-1']);

    /* const position = $(indentifier).position();
    $('html, body').animate({ scrollTop: (position.top - 100) }, 50); */

    //if (hasErrors) stepperAlert.show();

    if (!hasErrors) {
      const position = $(indentifier).position();
      $('html, body').animate({ scrollTop: (position.top - 100) }, 50);
    }

    if (!hasErrors) {
      stepperAlert.hide();

      const oldStep = currentStep;
      let newStep = currentStep + 1;

      const isHidden = $(steps[newStep]).hasClass('type-hidden');
      if (isHidden) newStep = newStep + 1;

      if (newStep < numSteps) {
        $(steps[oldStep]).removeClass('active');
        $(stepsContent[oldStep]).removeClass('active');

        $(steps[newStep]).addClass('active');
        $(stepsContent[newStep]).addClass('active');

        currentStep = newStep;
        stepperFooter.addClass('middle');
      }

      if ((newStep + 1) == numSteps) {
        stepperFooter.removeClass('middle');
        stepperFooter.addClass('finish');
      }

      if (newStep == numSteps) resolve(submitterType);
    }
  })
});

const showPageAlert = ({
  id,
  status,
  message,
  inner = false
}) => {
  icon = '';

  if (status === 'error') status = 'alert-danger';
  if (status === 'success') status = 'alert-success';
  if (status === 'warning') status = 'alert-warning';

  const alert = `
    <div class="alert ${status}" style="width: 100%;">
      ${message}
    </div>
  `;

  const innerAlert = `${message}`;

  if (!inner) $(id).hide().html(alert).fadeIn('fast');

  if (inner) {
    $(id).hide().html(innerAlert).fadeIn('fast');
    $(id).removeClass('alert-danger');
    $(id).addClass(status);
  }

  setTimeout(() => {
    $(id).fadeOut('slow');
    clearTimeout();
  }, 4000);
}

const copyLink = link => navigator.clipboard.writeText(link).then(() => {
  showSweetAlert({
    icon: 'success',
    title: 'link copiado'
  })
}).catch(err => {
  alert('Something went wrong', err);
});


function showPremiumModal(event) {
  
  event.preventDefault();

  $('#modal-premium-required').modal('show');
}