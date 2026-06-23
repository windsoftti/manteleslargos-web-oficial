<?php
$table_row_number =
(
    ($page - 1) * $per_page
) + 1;
?>

<div class="table-responsive">

<table class="table table-hover">

    <thead>

        <tr>
            <th>#</th>
            <th>Negocio</th>
            <th>Plan</th>
            <th>Estado</th>
            <th>Inicio</th>
            <th>Vencimiento</th>
            <th>Acceso restante</th>
            <th class="text-right">Acciones</th>
        </tr>

    </thead>

    <tbody>

        <?php while($row = mysqli_fetch_array($query_result)): ?>

            <?php

            $plan = $row['CurrentPlan'];

            switch(strtolower($plan)){

                case 'premium':
                    $badge = 'badge-warning';
                break;

                case 'pro':
                    $badge = 'badge-info';
                break;

                case 'enterprise':
                    $badge = 'badge-success';
                break;

                default:
                    $badge = 'badge-secondary';
                break;
            }

            /*
            |--------------------------------------------------------------------------
            | Días restantes
            |--------------------------------------------------------------------------
            */

            $diasRestantes = null;

            $fechaReferencia =
                $row['trial_expires_at']
                ?: $row['expires_at'];

            if (!empty($fechaReferencia)) {

                $diasRestantes = ceil(
                    (
                        strtotime($fechaReferencia) -
                        time()
                    ) / 86400
                );
            }

            $subscription_data = base64_encode(
                json_encode(
                    $row,
                    JSON_UNESCAPED_UNICODE
                )
            );

            ?>

            <tr>

                <td>
                    <b><?= $table_row_number; ?></b>
                </td>

                <td>
                    <?= $row['Salon']; ?>
                </td>

                <td>

                    <span class="badge <?= $badge; ?>">
                        <?= $plan; ?>
                    </span>

                </td>

                <td>

                    <?php if($row['status']): ?>

                        <?= ucfirst($row['status']); ?>

                    <?php else: ?>

                        <span class="text-muted">
                            Básico
                        </span>

                    <?php endif; ?>

                </td>

                <td>

                    <?php if($row['starts_at']): ?>

                        <?= date(
                            'd/m/Y',
                            strtotime($row['starts_at'])
                        ); ?>

                    <?php else: ?>

                        -

                    <?php endif; ?>

                </td>

                <td>

                    <?php if($row['expires_at']): ?>

                        <?= date(
                            'd/m/Y',
                            strtotime($row['expires_at'])
                        ); ?>

                    <?php else: ?>

                        -

                    <?php endif; ?>

                </td>

                <td>

                    <?php if ($diasRestantes === null): ?>

                        <span class="text-muted">
                            -
                        </span>

                    <?php elseif ($diasRestantes < 0): ?>

                        <span class="badge badge-danger">
                            Vencida
                        </span>

                    <?php elseif ($diasRestantes <= 7): ?>

                        <span class="badge badge-warning">
                            <?= $diasRestantes; ?> días
                        </span>

                    <?php else: ?>

                        <span class="badge badge-success">
                            <?= $diasRestantes; ?> días
                        </span>

                    <?php endif; ?>

                </td>

                <td class="text-right">

                    <button
                        type="button"
                        class="btn btn-primary btn-edit-subscription"
                        data-subscription="<?= $subscription_data; ?>"
                        data-toggle="modal"
                        data-target="#modal-subscriptions">

                        <i class="fas fa-pencil-alt"></i>

                    </button>

                </td>

            </tr>

            <?php $table_row_number++; ?>

        <?php endwhile; ?>

    </tbody>

</table>

</div>

<?= paginate(
    $page,
    $num_pages,
    2,
    'loadSubscriptions'
); ?>