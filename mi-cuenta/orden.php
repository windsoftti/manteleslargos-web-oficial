<?php

include 'inc/session.php';

$orderId =
    (int) ($_GET['id'] ?? 0);

$order =
    getOrderById($orderId);

if (!$order) {

    header(
        'location:mis-ordenes'
    );

    die;
}

$meta_title =
    'Detalle de orden';
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

            <main id="content" class="bg-gray-01">

                <div class="p-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <h2>
                            Orden #<?= $order['id_order']; ?>
                        </h2>

                        <a
                            href="mis-ordenes"
                            class="btn btn-outline-secondary"
                        >
                            Volver
                        </a>

                    </div>

                    <div class="card">

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6">

                                    <p>

                                        <strong>Plan:</strong>

                                        <?= $order['Plan']; ?>

                                    </p>

                                    <p>

                                        <strong>Ciclo:</strong>

                                        <?= getBillingCycleLabel(
                                            $order['billing_cycle']
                                        ); ?>

                                    </p>

                                </div>

                                <div class="col-md-6">

                                    <p>

                                        <strong>Importe:</strong>

                                        $<?= number_format(
                                            $order['amount'],
                                            2
                                        ); ?>

                                    </p>

                                    <p>

                                        <strong>Estado:</strong>

                                        <?= getOrderStatusLabel(
                                            $order['status']
                                        ); ?>

                                    </p>

                                </div>

                            </div>

                            <hr>

                            <p>

                                <strong>Fecha:</strong>

                                <?= date(
                                    'd/m/Y H:i',
                                    strtotime(
                                        $order['created_at']
                                    )
                                ); ?>

                            </p>

                            <?php if (
                                $order['status'] === 'pending'
                            ) : ?>

                                <div class="mt-4">

                                    <!--<a
                                        href="confirmar-pago?id=<?= $order['id_order']; ?>"
                                        class="btn btn-success"
                                    >
                                        Pagar ahora
                                    </a>-->

                                    <a
                                        href="pagar-orden?id=<?= $order['id_order']; ?>"
                                        class="btn btn-success"
                                    >
                                        Pagar ahora
                                    </a>

                                </div>

                            <?php endif; ?>

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