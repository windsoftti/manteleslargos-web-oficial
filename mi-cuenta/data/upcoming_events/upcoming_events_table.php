<?php
$session_user_plan = getPlan();
$session_target_free_plan = 'javascript:showUpdatePlanAlert()';
?>

<div class="row">
  <?php while ($row = mysqli_fetch_array($query_result)) :
    $query_status = "SELECT
        DateStatus
      FROM calendario_fechas
      WHERE
        idUsuario = $id_user_create AND
        idNegocio = $row[idNegocio] AND
        Fecha     = '$row[Fecha]'
      LIMIT 1
    ";

    $query_status_result  = mysqli_query($mysqli, $query_status);
    $date_data            = mysqli_fetch_array($query_status_result);

    $date_status          = $date_data['DateStatus'];

    $row['DayStatus'] = $date_status;

    $event_data = json_encode($row);
  ?>
    <div class="col-12 col-lg-6 mb-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex w-100" style="gap: 0.8rem;flex-wrap: nowrap">
            <div class="d-flex flex-column mr-auto" style="font-size: 0.9rem;width: 85%;">
              <a class="font-weight-bold fs-5 text-blue mb-1 btn-upcoming-event-info" data-btnEdit="#btn-edit-event-<?= $row['idReservacion']; ?>" data-upcoming-event='<?= $event_data; ?>' href="#" data-toggle="modal" data-target="#modal-upcoming-events-info" style="text-decoration: underline;">
                <?= $row['NombreCompleto']; ?>
                <i class="fal fa-info-circle text-danger"></i>
              </a>

              <span><b>Fecha: </b><?= $row['FechaFormat']; ?></span>
              <?php if ($row['HoraInicio']) : ?>
                <span><b>Horario: </b><?= $row['HoraInicio']; ?> <?= ($row['HoraFinal'] ? '- ' . $row['HoraFinal'] : ''); ?></span>
              <?php endif; ?>

              <span class="mb-1"></span>

              <span><b>Paquete:</b> <?= $row['Paquete']; ?></span>
              <span><b>Total:</b> $<?= number_format($row['CostoTotal'], 2) ?></span>

              <div class="d-flex align-items-center mt-3" style="gap: 0.5rem;">
                <a href="tel:+52<?= $row['Telefono']; ?>" style="font-size: 1.3rem;">
                  <i class="fa fa-phone text-dark"></i>
                </a>

                <a target="_blank" href="https://wa.me/52<?= $row['Telefono']; ?>">
                  <img src="images/whatsapp-logo.png" style="height: 26px;">
                </a>

                <a href="mailto:<?= $row['Correo']; ?>" style="font-size: 1.3rem;">
                  <i class="fa fa-envelope"></i>
                </a>
              </div>
            </div>

            <div>
              <?php if ($session_user_plan === 'Free') : ?>
                <div class="dropdown dropleft">
                  <button id="actions-dropdown" class="btn btn-primary btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-v"></i>
                  </button>

                  <div class="dropdown-menu" aria-labelledby="actions-dropdown">
                    <a id="btn-edit-event-<?= $row['idReservacion']; ?>" class="dropdown-item" href="<?= $session_target_free_plan; ?>">
                      <i class="fas fa-pencil-alt mr-1"></i>Editar
                    </a>

                    <a class="dropdown-item" href="<?= $session_target_free_plan; ?>">
                      <i class="fas fa-money-bill mr-1"></i>Ver pagos
                    </a>
                  </div>
                </div>
              <?php endif; ?>

              <?php if ($session_user_plan === 'Básico') : ?>
                <div class="dropdown dropleft">
                  <button id="actions-dropdown" class="btn btn-primary btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-v"></i>
                  </button>

                  <div class="dropdown-menu" aria-labelledby="actions-dropdown">
                    <a id="btn-edit-event-<?= $row['idReservacion']; ?>" class="dropdown-item btn-edit-event" data-toggle="modal" data-target="#modal-upcoming-events" data-event="<?= base64_encode($event_data); ?>" data-dateStatus="<?= $date_status; ?>" href="javascript:void(0)">
                      <i class="fas fa-pencil-alt mr-1"></i>Editar
                    </a>

                    <a class="dropdown-item" href="proximos-eventos-pagos?uid=<?= $row['idReservacion']; ?>">
                      <i class="fas fa-money-bill mr-1"></i>Ver pagos
                    </a>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<?php $pagination = paginate($page, $num_pages, 2, 'loadUpcomingEvents'); ?>
<?php echo $pagination; ?>

<script>
  $('.btn-upcoming-event-info').on('click', function() {
    const data = JSON.parse($(this).attr('data-upcoming-event'));

    $('#infoName span').html(data.NombreCompleto);
    $('#infoEmail span').html(data.Correo);
    $('#infoPackage span').html(data.Paquete);
    $('#infoEventType span').html(data.TipoEvento);
    $('#infoPhone span').html(data.Telefono);
    $('#infoDate span').html(data.FechaFormat);
    $('#infoNPersons span').html(data.NPersonas);

    $('#infoCost span').html(new Intl.NumberFormat('es-MX', {
      style: 'currency',
      currency: 'MXN'
    }).format(data.CostoTotal));

    $('#infoDeposit span').html(new Intl.NumberFormat('es-MX', {
      style: 'currency',
      currency: 'MXN'
    }).format(data.Deposito));

    $('#infoAnticipo span').html(new Intl.NumberFormat('es-MX', {
      style: 'currency',
      currency: 'MXN'
    }).format(data.Anticipo));

    $('#infoExtras div').html(data.Extras);

    if (data.HoraInicio) $('#infoHour span').html(`${data.HoraInicio}${data.HoraFinal ? ` - ${data.HoraFinal}`:``}`);
    if (!data.HoraInicio) $('#infoHour span').html(`Por definir`);

    if (!data.DayStatus || data.DayStatus === 'Libre') $('#infoDayStatus span').html(`<span class="badge badge-success">${data.DayStatus}</span>`);
    if (data.DayStatus === 'Con espacios') $('#infoDayStatus span').html(`<span class="badge badge-warning">${data.DayStatus}</span>`);
    if (data.DayStatus === 'Ocupado') $('#infoDayStatus span').html(`<span class="badge badge-danger">${data.DayStatus}</span>`);

    const btnEdit = $(this).attr('data-btnEdit');

    $('#btn-edit-event').on('click', function() {
      $('#modal-upcoming-events-info').modal('hide');

      $('#modal-upcoming-events-info').on('hidden.bs.modal', function() {
        $(btnEdit).click();
        $('#modal-upcoming-events-info').unbind('hidden.bs.modal');
      });
    });
  });
</script>