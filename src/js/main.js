$(document).ready(() => {
  activeCurrentPageInvitationNavbar();

  $('select:not(.not-select2)').each(function (e) {
    $(this).select2();

    $(this).on('select2:open', function (e) {
      document.querySelector(`[aria-controls="select2-${e.target.id}-results"]`).focus();
    });
  });

  $('.clean').on('keyup', function () {
    this.value = (this.value + '').replace(' ', '');
  });
});

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- INDICE
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
/*
//-- PRELOADER
//-- PAGE LOADING
//-- NAVBAR
//-- INPUT RANGE
//-- LISTING FILTERS
//-- LISTING MAP MODE
//-- LISTING MAP MODE
//-- BUSINESS PACKAGES
//-- TABS
//-- TIPS SLIDER
//-- STEPPER
//-- INVITATIONS CONTENT
//-- INVITATIONS NAVBAR CURRENT PAGE
//-- MODAL
//-- PAGE ALERTS
//-- PASSWORD EYE
*/

//const BASE_URL = `${location.protocol}//${location.host}/manteleslargos`;
//const BASE_URL = `${location.protocol}//${location.host}/manteleslargos`;
//console.log(BASE_URL);

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- PRELOADER
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
const hidePreloader = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });

  $('.preloader').fadeOut();
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- PAGE LOADING
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
const showPageLoading = () => {
  $('[type="submit"]').attr('disabled', true);

  setTimeout(() => {
    $('.page-loading').addClass('show');
    clearTimeout();
  }, 200);
}

const hidePageLoading = () => {
  $('[type="submit"]').attr('disabled', false);

  setTimeout(() => {
    $('.page-loading').removeClass('show');
    clearTimeout();
  }, 200);
}

/* const showPageLoading = () => $('.page-loading').addClass('show');
const hidePageLoading = () => $('.page-loading').removeClass('show'); */

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- PAGE LOADING
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
const showProgressBar = () => $('#page-progressbar').addClass('active');
const hideProgressBar = () => $('#page-progressbar').removeClass('active');

const updateProgressBar = value => {
  $('#page-progressbar .progress').width(`${value}%`);
  $('#page-progressbar .percentage').html(`${value}%`);
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- NAVBAR
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
const handleToggleNavbar = () => {
  const navbar = $('#navbar');
  const isOpen = navbar.hasClass('open');

  if (!isOpen) navbar.addClass('open');
  if (isOpen) navbar.removeClass('open');

  const list = navbar.children('ul');
  list.scrollTop({ position: 0 })
}

function handleSubmenuToggle() {
  const submenu = $(this).parent('.submenu');
  const isOpen = $(submenu).hasClass('open');

  if (!isOpen) {
    $(submenu).addClass('open');

    $(submenu).children('.submenu-content').slideDown({
      duration: 200
    });
  }

  if (isOpen) {
    $(submenu).removeClass('open');

    $(submenu).children('.submenu-content').slideUp({
      duration: 200
    });
  }
}

$('.navbar .toggle').on('click', handleToggleNavbar);
$('.submenu .submenu-toggle').on('click', handleSubmenuToggle);

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- INPUT RANGE SLIDER
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
$('.range-slider').each(function () {
  const moneyLocale = Intl.NumberFormat('es-MX', {
    style: "currency",
    currency: "MXN"
  });

  const symbol = $(this).attr('symbol');
  const symbolPosition = $(this).attr('symbolPosition');
  const format = $(this).attr('format');

  const inputMin = $(this).children('input').attr('min');
  const inputVal = $(this).children('input').val();

  const leftSymbol = symbolPosition == 'left' ? symbol : '';
  const rightSymbol = symbolPosition == 'right' ? symbol : '';

  let leftLabel;
  let rightLabel;

  if (format === 'money') {
    leftLabel = moneyLocale.format(parseFloat(inputMin));
    rightLabel = moneyLocale.format(parseFloat(inputVal));
  }

  if (format === 'text') {
    leftLabel = `${leftSymbol}${inputMin}${rightSymbol}`;
    rightLabel = `${leftSymbol}${inputVal}${rightSymbol}`;
  }

  const element = `
    <div class='range-content'>
      ${leftLabel}
      hasta
      ${rightLabel}
    </div>
  `;

  $(this).append(element);
  $(this).on('input', sliderSetter);
});

function sliderSetter() {
  const moneyLocale = Intl.NumberFormat('es-MX', {
    style: "currency",
    currency: "MXN"
  });

  const symbol = $(this).attr('symbol');
  const symbolPosition = $(this).attr('symbolPosition');
  const format = $(this).attr('format');

  const inputMin = $(this).children('input').attr('min');
  const inputVal = $(this).children('input').val();

  const leftSymbol = symbolPosition == 'left' ? symbol : '';
  const rightSymbol = symbolPosition == 'right' ? symbol : '';

  let leftLabel;
  let rightLabel;

  if (format === 'money') {
    leftLabel = moneyLocale.format(parseFloat(inputMin));
    rightLabel = moneyLocale.format(parseFloat(inputVal));
  }

  if (format === 'text') {
    leftLabel = `${leftSymbol}${inputMin}${rightSymbol}`;
    rightLabel = `${leftSymbol}${inputVal}${rightSymbol}`;
  }

  const element = `
    <div class='range-content'>
      ${leftLabel}
      hasta
      ${rightLabel}
    </div>
  `;

  $(this).children('.range-content').remove();
  $(this).append(element);
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- LISTING FILTERS
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
const listingFilters = $('#listing-filters');

$('.btn-filters').on('click', function () {
  const isOpen = listingFilters.hasClass('open');

  if (isOpen) listingFilters.removeClass('open');
  if (!isOpen) listingFilters.addClass('open');
});

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- LISTING MAP MODE
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
const listing = $('#listing');

$('#map-mode').on('click', function () {
  const isChecked = $(this).is(':checked');

  if (isChecked) listing.addClass('map-mode');
  if (!isChecked) listing.removeClass('map-mode');
});

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- BUSINESS PACKAGES
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
const packagesOpen = $('.business-packages li.open');
packagesOpen.children('.package-body').css('display', 'flex');

$('.business-packages li').children('a').on('click', function () {
  const package = $(this).parent('li');
  const isOpen = package.hasClass('open');

  if (!isOpen) {
    package.addClass('open');
    package.children('.package-body').slideDown({
      duration: 200,
      start: function () {
        $(this).css({
          display: "flex"
        })
      }
    });
  }

  if (isOpen) {
    package.removeClass('open');
    package.children('.package-body').slideUp({
      duration: 200,
      end: function () {
        $(this).css({
          display: "none"
        })
      }
    });
  }
});

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- TABS
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function handleToggleTab() {
  const content = $(this).attr('data-content');
  const parent = $(`#${content}`).parent().parent();

  $(parent).children('.tabs-header').children('a').removeClass('active');
  parent.children('.tabs-body').children().removeClass('active');

  $(`#${content}`).addClass('active');
  $(`.tabs .tabs-header a[data-content="${content}"]`).addClass('active');
}

const handleToggleOuterTab = id => {
  const content = id;
  const parent = $(`#${content}`).parent().parent();

  $(parent).children('.tabs-header').children('a').removeClass('active');
  parent.children('.tabs-body').children().removeClass('active');

  $(`#${content}`).addClass('active');
  $(`.tabs .tabs-header a[data-content="${content}"]`).addClass('active');
}

$('.tabs .tabs-header').children('a').on('click', handleToggleTab);
$('.tab-toggle').on('click', handleToggleTab);

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- TIPS SLIDER
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
/* $('.tips-slider').each(function () {
  const mobileItems = $(this).children('.tips').children('.tip-row').children();
  const mobileItemsLength = mobileItems.length;
  let mobileActiveItem = 0;

  const mobileArrowLeft = $(this).children('.arrow.left.mobile');
  const mobileArrowRight = $(this).children('.arrow.right.mobile');

  $(mobileArrowLeft).on('click', () => {
    if ((mobileActiveItem + 1) === 1) return;

    const currentTipIndexActive = mobileActiveItem;
    const newTipIndexActive = mobileActiveItem - 1;

    mobileActiveItem = mobileActiveItem - 1;

    $(mobileItems[currentTipIndexActive]).removeClass('active');
    $(mobileItems[newTipIndexActive]).addClass('active');
  });

  $(mobileArrowRight).on('click', () => {
    if ((mobileActiveItem + 1) === mobileItemsLength) return;

    const currentTipIndexActive = mobileActiveItem;
    const newTipIndexActive = mobileActiveItem + 1;

    mobileActiveItem = mobileActiveItem + 1;

    $(mobileItems[currentTipIndexActive]).removeClass('active');
    $(mobileItems[newTipIndexActive]).addClass('active');
  });

  // :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
  const desktopItems = $(this).children('.tips').children('.tip-row');
  const desktopItemsLength = desktopItems.length;
  let desktopActiveItem = 0;

  const desktopArrowLeft = $(this).children('.arrow.left.desktop');
  const desktopArrowRight = $(this).children('.arrow.right.desktop');

  $(desktopArrowLeft).on('click', () => {
    if ((desktopActiveItem + 1) === 1) return;

    const currentTipIndexActive = desktopActiveItem;
    const newTipIndexActive = desktopActiveItem - 1;

    desktopActiveItem = desktopActiveItem - 1;

    $(desktopItems[currentTipIndexActive]).removeClass('active');
    $(desktopItems[newTipIndexActive]).addClass('active');
  });

  $(desktopArrowRight).on('click', () => {
    if ((desktopActiveItem + 1) === desktopItemsLength) return;

    const currentTipIndexActive = desktopActiveItem;
    const newTipIndexActive = desktopActiveItem + 1;

    desktopActiveItem = desktopActiveItem + 1;

    $(desktopItems[currentTipIndexActive]).removeClass('active');
    $(desktopItems[newTipIndexActive]).addClass('active');
  });
}); */

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
  pickers,
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

            showSweetToast({
              icon: 'error',
              message: 'Completa los campos requeridos'
            });

            //return false;
          }
        }
      }
    }

    let errors = false;

    if (pickers) {
      pickers.map(item => {
        const pickerStepId = item.stepId;

        if (pickerStepId == stepId && !errors) {
          const picker = item.picker;
          const messageError = item.messageError;

          const image = picker.getFile();
          const pickerId = picker.getPickerId();

          const isVisible = $(`#${pickerId}`).is(':visible');

          if (!image && isVisible) {
            showSweetToast({
              icon: 'error',
              message: messageError
            });

            $(`#${pickerId}`).focus();
            //$(`#${pickerId}`).addClass('error');
            $('html,body').animate({
              scrollTop: $("#" + pickerId).offset().top - 200
            }, 'fast');

            errors = true;

            //resolve(false);
            //return false;

            //return false;
          }
        }
      });

      if (errors) {
        resolve(false);
        return false;
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

              showSweetToast({
                icon: 'error',
                message
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

                showSweetToast({
                  icon: 'error',
                  message
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

                showSweetToast({
                  icon: 'error',
                  message
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

              showSweetToast({
                icon: 'error',
                message
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

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- INVITATIONS CONTENT
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
$('.btn-add-invitation-content').on('click', function () {
  const cardContent = $(this).parent();
  cardContent.addClass('active');
});

$('.btn-remove-invitation-content').on('click', function () {
  const cardContent = $(this).parent().parent().parent().parent();
  cardContent.removeClass('active');
});

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- INVITATIONS NAVBAR CURRENT PAGE
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function activeCurrentPageInvitationNavbar() {
  const pathName = location.pathname;
  const hash = location.hash;
  const route = `${location.protocol}//${location.host}${pathName}${hash}`;

  //console.log(route);

  document.querySelectorAll(`.invitations-navbar ul li a`).forEach(tag => {
    const href = $(tag).attr('href');

    //console.log(href)

    if (href !== '#' && href != 'javascript:void(0)') {
      const find = route.search(href);

      //console.log(find);

      if (find != -1) $(tag).parent('li').addClass('active');
    }
  });
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- MODAL
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function openModal() {
  const target = this.getAttribute('data-target');
  const modal = document.getElementById(target);

  if (modal) modal.style = 'display: flex';
}

const handleOpenModal = id => {
  const target = id;
  const modal = document.getElementById(target);

  if (modal) modal.style = 'display: flex';
}

const closeModal = () => document.querySelectorAll('.modal').forEach(modal => {
  modal.style = 'diplay: none';
});

$('[data-toggle="modal"]').on('click', openModal);
$('[data-toggle="dismiss"]').on('click', closeModal);

$(document).on('keyup', function (e) {
  if (e.key == "Escape") $('.modal').css('display', 'none');
});

/* $('.modal').on('click', function () {
  $(this).css('display', 'none');
});

$('.modal-content').on('click', function (e) {
  e.stopPropagation();
}); */

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- PAGE ALERTS
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
const showPageAlert = ({
  id,
  status,
  message,
  inner = false,
  timeToClose = 4000
}) => {
  icon = '';

  if (status === 'success') icon = '<ion-icon name="checkmark-outline"></ion-icon>';
  if (status === 'error') icon = '<ion-icon name="information-circle"></ion-icon>';
  if (status === 'warning') icon = '<ion-icon name="information-circle"></ion-icon>';

  const alert = `
    <div class="alert ${status}" style="width: 100%;">
      <div>
        ${icon}
      </div>
      <span>
        ${message}
      </span>
    </div>
  `;

  const innerAlert = `
    <div>
      ${icon}
    </div>
    <span>
      ${message}
    </span>
  `;

  if (!inner) $(id).hide().html(alert).fadeIn('fast');

  if (inner) {
    $(id).hide().html(innerAlert).fadeIn('fast');
    $(id).removeClass('error');
    $(id).addClass(status);
  }

  setTimeout(() => {
    $(id).fadeOut('slow');
    clearTimeout();
  }, timeToClose);
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- PASSWORD EYE
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
$('.btn-eye-password').on('click', function () {
  const input = $(this).parent().parent().children('input');
  const type = input.attr('type');

  if (type === 'password') {
    $(this).html('<ion-icon name="eye-outline"></ion-icon>');
    input.attr('type', 'text');
  }

  if (type === 'text') {
    $(this).html('<ion-icon name="eye-off-outline"></ion-icon>');
    input.attr('type', 'password');
  }
});

window.onload = hidePreloader();