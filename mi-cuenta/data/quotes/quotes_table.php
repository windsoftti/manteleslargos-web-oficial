<?php
$table_row_number = 1;
$session_user_plan = getPlan();
$session_target_free_plan = 'javascript:showUpdatePlanAlert()';
?>
<div class="row">
  <?php while ($row = mysqli_fetch_array($query_result)) :
    $quote_data = json_encode($row);
  ?>
    <div class="col-12 col-lg-6 mb-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex w-100" style="gap: 0.8rem;flex-wrap: nowrap">
            <div class="d-flex flex-column mr-auto" style="font-size: 0.9rem;width: 85%;">
              <a class="font-weight-bold fs-5 text-blue btn-show-quote-info" href="javascript:void(0)" data-quote='<?= $quote_data; ?>' data-toggle="modal" data-target="#modal-quote-info" style="text-decoration: underline;">
                <?= $row['NombreCompleto']; ?>
                <i class="fal fa-info-circle text-danger"></i>
              </a>

              <span class="text-justify"><?= $row['Email']; ?></span>
              <span class="mb-2"><?= formatPhoneNumber($row['Telefono']); ?></span>

              <span><b>Fecha solicitada:</b> <?= $row['FechaSolicitadaFormat']; ?></span>

              <?php if ($row['Status'] == 'Cancelado') : ?>
                <span class="badge badge-danger" style="font-size: 0.6rem;padding:3px;width:5rem;"><?= $row['Status']; ?></span>
              <?php endif; ?>

              <?php if ($row['Status'] == 'Pendiente') : ?>
                <span class="badge badge-warning" style="font-size: 0.6rem;padding:3px;width:5rem;"><?= $row['Status']; ?></span>
              <?php endif; ?>

              <?php if ($row['Status'] == 'Contestado') : ?>
                <span class="badge badge-info" style="font-size: 0.6rem;padding:3px;width:5rem;"><?= $row['Status']; ?></span>
              <?php endif; ?>

              <?php if ($row['Status'] == 'Completado') : ?>
                <span class="badge badge-success" style="font-size: 0.6rem;padding:3px;width:5rem;">
                  <?= $row['Status']; ?><br>
                  <small><b>Agendado</b></small>
                </span>
              <?php endif; ?>

              <div class="d-flex align-items-center mt-3" style="gap: 0.5rem;">
                <?php
                $call           = $session_target_free_plan;
                $pdf            = $session_target_free_plan;
                $send_wahtsapp  = $session_target_free_plan;

                if ($session_user_plan === 'Básico') {
                  $call           = 'tel:+52' . $row['Telefono'];
                  $send_wahtsapp  = 'https://wa.me/52' . $row['Telefono'];
                  $pdf            = 'generar-cotizacion-pdf.php?uid=' . base64_encode($quote_data);
                }
                ?>

                <a href="<?= $call; ?>" style="font-size: 1.3rem;">
                  <i class="fa fa-phone text-dark"></i>
                </a>

                <a <?= $session_user_plan === 'Básico' ? 'target="_blank"' : ''; ?> href="<?= $send_wahtsapp; ?>">
                  <img src="images/whatsapp-logo.png" style="height: 26px;">
                </a>

                <a href="mailto:<?= $row['Email']; ?>" style="font-size: 1.3rem;">
                  <i class="fa fa-envelope"></i>
                </a>
              </div>
            </div>

            <div>
              <?php if ($row['Status'] !== 'Completado') : ?>
                <div class="dropdown dropleft">
                  <button id="actions-dropdown" class="btn btn-primary btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-v"></i>
                  </button>

                  <div class="dropdown-menu" aria-labelledby="actions-dropdown">
                    <?php if ($row['Status'] === 'Pendiente' || $row['Status'] == 'Contestado') : ?>
                      <a class="dropdown-item btn-schedule-date" data-quote="<?= base64_encode($quote_data) ?>" data-toggle="modal" data-target="#modal-schedule-date" href="javascript:void(0)">
                        <i class="fas fa-calendar mr-1"></i>Agendar
                      </a>
                    <?php endif; ?>

                    <?php if ($session_user_plan === 'Free') : ?>
                      <?php if ($row['Status'] == 'Pendiente') : ?>
                        <a class="dropdown-item" onclick="<?= $session_target_free_plan; ?>" href="javascript:void(0)">
                          <i class="fas fa-pencil-alt mr-1"></i>Editar
                        </a>

                        <a class="dropdown-item btn-cancel-quote" onclick="<?= $session_target_free_plan; ?>" href="javascript:void(0)">
                          <i class="fas fa-times mr-1"></i>Cancelar
                        </a>
                      <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($session_user_plan === 'Básico') : ?>
                      <?php if ($row['Status'] == 'Pendiente' || $row['Status'] == 'Contestado') : ?>
                        <a class="dropdown-item btn-edit-quote" data-quote="<?= base64_encode($quote_data) ?>" data-toggle="modal" data-target="#modal-add-edit-quote" href="javascript:void(0)">
                          <i class="fas fa-pencil-alt mr-1"></i>Editar
                        </a>

                        <?php if ($row['Status'] !== 'Contestado') : ?>
                          <a class="dropdown-item btn-contact-quote" data-quote="<?= base64_encode($quote_data) ?>" href="javascript:void(0)">
                            <i class="fas fa-check mr-1"></i>Marcar como contestado
                          </a>
                        <?php endif; ?>

                        <a class="dropdown-item btn-cancel-quote" data-quote="<?= base64_encode($quote_data) ?>" href="javascript:void(0)">
                          <i class="fas fa-times mr-1"></i>Cancelar
                        </a>
                      <?php endif; ?>

                      <?php if ($row['Status'] == 'Cancelado') : ?>
                        <a class="dropdown-item btn-resume-quote" data-quote="<?= base64_encode($quote_data) ?>" href="javascript:void(0)">
                          <i class="fas fa-check mr-1"></i>Recuperar
                        </a>
                      <?php endif; ?>
                    <?php endif; ?>
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

<?php $pagination = paginate($page, $num_pages, 2, 'loadQuotes'); ?>
<?php echo $pagination; ?>

<script>
  /* $('.btn-edit-amenity').on('click', function () {
    const data = JSON.parse(atob($(this).attr('data-amenity')));

    changeModalTitle('Editar amenidad');
    hideInputWarnings();

    $('#amenityId').val(data.idAmenidad);
    $('#amenity').val(data.Amenidad);
    $('#action-amenities').val('edit_amenity');
  }); */

  $('.btn-show-quote-info').on('click', function() {
    const data = JSON.parse($(this).attr('data-quote'));

    $('#infoName span').html(data.NombreCompleto);
    $('#infoEmail span').html(data.Email);
    $('#infoPhone span').html(data.Telefono);
    $('#infoEventDay span').html(data.FechaSolicitadaFormat);
    $('#infoStatus span').html(data.Status);

    if (data.Status === 'Cancelado') $('#infoStatus span').html(`<span class="badge badge-danger">${data.Status}</span>`);
    if (data.Status === 'Pendiente') $('#infoStatus span').html(`<span class="badge badge-warning">${data.Status}</span>`);
    if (data.Status === 'Completado') $('#infoStatus span').html(`<span class="badge badge-success">Agendado</span>`);
  });
</script>