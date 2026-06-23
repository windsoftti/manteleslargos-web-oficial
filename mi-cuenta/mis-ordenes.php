<?php

include 'inc/session.php';

$orders =
    getBusinessOrders();


$meta_title =
    'Mis órdenes';
?>

<!doctype html>
<html lang="es">

<head>
    <?php include 'inc/meta-tags.php'; ?>
</head>

<body>

<div class="wrapper dashboard-wrapper">

    <div class="d-flex flex-wrap flex-xl-nowrap">

        <div class="db-sidebar bg-white">

            <nav class="navbar navbar-expand-xl navbar-light d-block px-0 header-sticky dashboard-nav py-0">

                <div class="sticky-area shadow-xs-1 py-3">

                    <?php include 'inc/mobile-header.php'; ?>

                    <?php include 'inc/sidebar.php'; ?>

                </div>

            </nav>

        </div>

        <div class="page-content">

            <?php include 'inc/header.php'; ?>

            <main
                id="content"
                class="bg-gray-01"
            >

                <div class="p-4">

                    <h2 class="mb-4">

                        Mis órdenes

                    </h2>

                    <div class="card">

                        <div class="card-body p-0">

                            <div class="table-responsive">

                                <table class="table table-hover mb-0">

                                    <thead>

                                        <tr>

                                            <th>#</th>

                                            <th>Plan</th>

                                            <th>Ciclo</th>

                                            <th>Importe</th>

                                            <th>Estado</th>

                                            <th>Fecha</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php if (
                                            empty($orders)
                                        ) : ?>

                                            <tr>

                                                <td
                                                    colspan="6"
                                                    class="text-center py-5"
                                                >

                                                    No existen órdenes registradas.

                                                </td>

                                            </tr>

                                        <?php endif; ?>

                                        <?php foreach (
                                            $orders as $order
                                        ) : ?>

                                            <?php

                                            $badgeClass =
                                                'badge-secondary';

                                            if (
                                                $order['status']
                                                === 'pending'
                                            ) {

                                                $badgeClass =
                                                    'badge-warning';
                                            }

                                            if (
                                                $order['status']
                                                === 'paid'
                                            ) {

                                                $badgeClass =
                                                    'badge-success';
                                            }

                                            if (
                                                $order['status']
                                                === 'cancelled'
                                            ) {

                                                $badgeClass =
                                                    'badge-danger';
                                            }

                                            ?>

                                            <tr>

                                                <td>
                                                    
                                                    <a href="orden?id=<?= $order['id_order']; ?>">
                                                        #<?= $order['id_order']; ?>
                                                    </a>

                                                </td>

                                                <td>

                                                    <?= ucfirst(
                                                        $order['plan_name']
                                                    ); ?>

                                                </td>

                                                <td>

                                                    <?= getBillingCycleLabel(
                                                        $order['billing_cycle']
                                                    ); ?>

                                                </td>

                                                <td>

                                                    $<?= number_format(
                                                        $order['amount'],
                                                        2
                                                    ); ?>

                                                </td>

                                                <td>

                                                    <span
                                                        class="badge <?= $badgeClass; ?>"
                                                    >

                                                        <?= getOrderStatusLabel(
                                                            $order['status']
                                                        ); ?>

                                                    </span>

                                                </td>

                                                <td>

                                                    <?= date(
                                                        'd/m/Y H:i',
                                                        strtotime(
                                                            $order['created_at']
                                                        )
                                                    ); ?>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

            </main>

        </div>

    </div>

</div>

<?php include 'inc/required-scripts.php'; ?>
<script src="js/functions.js"></script>

</body>

</html>