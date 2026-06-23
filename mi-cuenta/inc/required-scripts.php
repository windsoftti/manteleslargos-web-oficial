<?php include 'modals/select-business.php'; ?>
<?php include 'inc/modals/upgrade-plan.php'; ?>
<script>
  const GOOGLE_MAPS_API_KEY = '<?= GOOGLE_MAPS_API_KEY; ?>';
</script>

<script src="plugins/jquery.min.js"></script>
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="plugins/bootstrap/bootstrap.bundle.js"></script>
<script src="plugins/bootstrap-select/js/bootstrap-select.min.js"></script>
<script src="plugins/slick/slick.min.js"></script>
<script src="plugins/waypoints/jquery.waypoints.min.js"></script>
<script src="plugins/hc-sticky/hc-sticky.min.js"></script>

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

  $('.mobile-dropdown-toggle').on('click', function() {
    const element = $(this).parent();
    const hasClass = element.hasClass('open');

    if (hasClass) element.removeClass('open');
    if (!hasClass) element.addClass('open');
  });

  $(document).on('click', '[data-toggle="modal"]', function(e) {
    const clase = $(this).attr('class');

    const isEdit = clase.search('edit');
    const isAdd = clase.search('add');

    console.log(isEdit);

    if (isEdit != -1) $('.btn-modal-title').html('<i class="fal fa-check-circle"></i> Guardar cambios');
    if (isAdd != -1) $('.btn-modal-title').html('<i class="fal fa-check-circle"></i> Guardar');
  });

  $(document).on('click', '.btn-csc-event', function() {
    $('.btn-modal-title').html('<i class="fal fa-check-circle"></i> Guardar cambios');
  });

  $(document).on('click', '.csc-add-event', function() {
    $('.btn-modal-title').html('<i class="fal fa-check-circle"></i> Guardar');
  });

  $('#change-business-btn').on('click', function() {
    showPageLoading();

    const refValue = $('#select-business-modal input[name="selectBusiness"]:checked').val();

    var loc = window.location;
    window.location = loc.protocol + '//' + loc.host + loc.pathname + `?business_ref=${refValue}`;
  });

  $('input[name="username"]').on('keyup', function() {
    const id = $(this).attr('id');
    const specialCharacters = /\W+/;
    const value = $(this).val();
    const form = $(this).closest('form');

    if (id === 'login-modal-username' || id === 'supplier-username') return;

    if (value.match(specialCharacters)) {
      $(this).closest('form').find('[type="submit"]').attr('disabled', true);
      if ($(this).parent().find('p.alert-message').length == 0) $(this).parent().append(
        `<p class="alert-message" style="
          font-size: 0.8rem;
          color: red;
          margin: 0;
        ">Lo sentimos, solo se permiten letras (a-z) y números (0-9).</p>`
      );
    };

    if (!value.match(specialCharacters)) {
      $(this).closest('form').find('[type="submit"]').removeAttr('disabled');
      $(this).parent().children('p.alert-message').remove();
    }
  });
</script>