<script src="plugins/jquery.min.js"></script>
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="plugins/bootstrap/bootstrap.bundle.js"></script>
<script src="plugins/bootstrap-select/js/bootstrap-select.min.js"></script>
<script src="plugins/slick/slick.min.js"></script>
<script src="plugins/waypoints/jquery.waypoints.min.js"></script>
<script src="plugins/counter/countUp.js"></script>
<!-- <script src="plugins/magnific-popup/jquery.magnific-popup.min.js"></script> -->
<script src="plugins/chartjs/Chart.min.js"></script>
<!-- <script src="plugins/dropzone/js/dropzone.min.js"></script> -->
<!-- <script src="plugins/timepicker/bootstrap-timepicker.min.js"></script> -->
<script src="plugins/hc-sticky/hc-sticky.min.js"></script>
<!-- <script src="plugins/jparallax/TweenMax.min.js"></script> -->
<!-- <script src="plugins/mapbox-gl/mapbox-gl.js"></script> -->
<!-- Theme scripts -->
<script src="js/theme.js"></script>
<script src="js/custom-theme.js"></script>

<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="js/sweet-alerts2.js"></script>

<script src="plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="plugins/jquery-validation/additional-methods.min.js"></script>

<script>
  $('#desktop-business').on('change', () => $('#desktop-business-select-form').submit());
  $('#mobile-business').on('change', () => $('#mobile-business-select-form').submit());

  const showUpdatePlanAlert = (
    message = 'Para seguir utilizando esta acción, debes de actualizar al plan Básico.'
  ) => showBigAlert({
    icon: 'info',
    title: '¡Actualización requerida!',
    subtitle: message,
  });
</script>