<div class="table-responsive-sm">
  <table class="table table-striped">
    <thead>
      <tr>
        <th class="text-center">#</th>
        <th>Fecha</th>
        <th class="table-comment">Comentarios</th>
        <th class="text-right">Pago</th>
        <th class="text-right"></th>
      </tr>
    </thead>

    <tbody>
      <?php while ($row = mysqli_fetch_array($query_result)) :
        $payment_data   = base64_encode(json_encode($row));
        $num_row_table++;
      ?>
        <tr>
          <td class="text-center">
            <?= $num_row_table; ?>
          </td>

          <td>
            <?= $row['FechaFormat']; ?>
          </td>

          <td class="table-comment">
            <?= $row['Comentarios']; ?>
          </td>

          <td class="text-right">
            $<?= number_format($row['Pago'], 2); ?>
          </td>

          <td class="text-right">
            <div class="btn-group btn-group-sm">
              <button class="btn btn-danger btn-delete-payment" data-payment="<?= $payment_data; ?>">
                <i class="fal fa-trash-alt"></i>
              </button>

              <button class="btn btn-primary btn-edit-payment" data-toggle="modal" data-target="#modal-edit-payment" data-payment="<?= $payment_data; ?>">
                <i class="fal fa-pencil"></i>
              </button>
            </div>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php $balance = $total - $total_payments; ?>

<?php /* ?>
<div class="row">
  <div class="col-lg-4 col-sm-5">
  </div>
  <div class="col-lg-4 col-sm-5 ml-auto">
    <table class="table table-clear">
      <tbody>
        <tr>
          <td class="text-left">
            <strong class="text-dark">Total de la cuenta</strong>
          </td>
          <td class="text-right">$<?= number_format($total, 2); ?></td>
        </tr>

        <tr>
          <td class="text-left">
            <strong class="text-dark">Total abonos</strong>
          </td>
          <td class="text-right">$<?= number_format($total_payments, 2); ?></td>
        </tr>

        <tr>
          <td class="text-left">
            <strong class="text-dark">Saldo</strong>
          </td>
          <td class="text-right">
            <strong class="text-dark">$<?= number_format($balance, 2); ?></strong>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php */ ?>