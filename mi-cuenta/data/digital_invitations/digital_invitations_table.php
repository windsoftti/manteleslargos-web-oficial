<?php $table_row_number = 1; ?>

<div class="table-responsive">
  <table class="table table-hover table-striped">
    <thead>
      <th style="width: 10px;">#</th>
      <th>Nombre de la persona (Titulo)</th>
      <th>Nombre del evento (Subtitulo)</th>
      <th>Link de la invitación</th>
      <th class="text-right">Acciones</th>
    </thead>

    <tbody>
      <?php while ($row = mysqli_fetch_array($query_result)) : ?>
        <?php $event_type_data = json_encode($row); ?>
        <tr>
          <td class="align-middle"><b><?= $table_row_number ?></b></td>
          <td class="align-middle"><?= $row['NombrePersona'] ?></td>
          <td class="align-middle"><?= $row['NombreEvento'] ?></td>
          <td class="align-middle">
            <div class="row">
              <?php
               $invitation_url = $url_host.'invitaciones/'.$row['Slug'].'/'.$row['idInvitacion'];
              ?>

              <a target="_blank" href="<?= $invitation_url ?>">
                <?= substr($invitation_url, 0, 30); ?>...
              </a>              
              
              <button class="btn btn-secondary btn-generate-link btn-tooltip ml-2"
                title="Copiar link de la invitación"
                data-link="<?= $url_host; ?>invitaciones/<?= $row['Slug']; ?>/<?= $row['idInvitacion'] ?>"
              >
                <i class="fa fa-share"></i>
              </button>
            </div>
          </td>
          <td class="align-middle text-right">
            <div class="btn-group btn-group-sm dropleft">
              <button type="button"
                class="btn btn-danger btn-delete-digital-invitation"
                data-invitationId="<?= $row['idInvitacion'] ?>"
                data-invitation="<?= $row['NombrePersona'] ?>"
              >
                <i class="fa fa-trash-alt"></i>
              </button>

              <a class="btn btn-primary"
                href="editar-invitacion?uid=<?= $row['idInvitacion']; ?>"
              >
                <i class="fa fa-pencil-alt"></i>
              </a>

              <!-- <button class="btn btn-secondary btn-generate-link"
                title="Generar link de la invitación"
                data-link="<?= $url_host; ?>invitaciones/<?= $row['Slug']; ?>/<?= $row['idInvitacion'] ?>"
              >
                <i class="fa fa-paperclip"></i>
              </button> -->
            </div>
          </td>
        </tr>

        <?php $table_row_number++; ?>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php $pagination = paginate($page, $num_pages, 2, 'loadInvitations'); ?>
<?php echo $pagination; ?>

<script>
  $('.btn-tooltip').tooltip()
</script>