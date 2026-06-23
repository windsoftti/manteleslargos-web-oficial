const datepickers = $('.datepicker');
const datetimepickers = $('.datetimepicker');

$.datepicker.regional['es'] = {
  closeText: 'Cerrar',
  prevText: '< Ant',
  nextText: 'Sig >',
  currentText: 'Hoy',
  monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
  monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
  dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
  dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
  dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
  weekHeader: 'Sm',
  dateFormat: 'dd/mm/yy',
  firstDay: 1,
  isRTL: false,
  showMonthAfterYear: false,
  yearSuffix: ''
};

$.datepicker.setDefaults($.datepicker.regional['es']);

const $initDefaultDatePicker = element => $(element).datepicker({
  locale: 'es'
});

datepickers.each(function () {
  $(this).datepicker({
    locale: 'es'
  });
});

datetimepickers.each(function () {
  /* $(this).datetimepicker({
    locale: 'es',
    hours12: true,
    format: 'hh:mm'
  }); */

  $(this).datetimepicker({
    format: 'DD/MM/YYYY hh:mm a',
    useCurrent: false,
    showTodayButton: true,
    showClear: true,
    toolbarPlacement: 'bottom',
    sideBySide: true,
    /* icons: {
      time: "fal fa-clock",
      date: "fal fa-calendar",
      up: "fal fa-arrow-up",
      down: "fal fa-arrow-down",
      previous: "fal fa-chevron-left",
      next: "fal fa-chevron-right",
      today: "fal fa-clock",
      clear: "fal fa-trash",
      close: "fal fa-times"
    }, */
    locale: 'es-es'
  });
});