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

    exit;
}

$preference =
    createMercadoPagoPreference(
        $order
    );

if (
    $preference['status']
    !== 'success'
) {

    die(
        $preference['message']
    );
}

$meta_title =
    'Pagar orden';
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

            <nav
                class="navbar navbar-expand-xl navbar-light d-block px-0 header-sticky dashboard-nav py-0"
            >

                <div
                    class="sticky-area shadow-xs-1 py-3"
                >

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

                        Confirmar pago

                    </h2>

                    <div class="card">

                        <div class="card-body">

                            <h4 class="mb-4">

                                Resumen de la orden

                            </h4>

                            <div class="row">

                                <div class="col-md-6">

                                    <p>

                                        <strong>
                                            Orden:
                                        </strong>

                                        #<?= $order['id_order']; ?>

                                    </p>

                                    <p>

                                        <strong>
                                            Plan:
                                        </strong>

                                        <?= $order['Plan']; ?>

                                    </p>

                                    <p>

                                        <strong>
                                            Ciclo:
                                        </strong>

                                        <?= getBillingCycleLabel(
                                            $order['billing_cycle']
                                        ); ?>

                                    </p>

                                </div>

                                <div class="col-md-6">

                                    <p>

                                        <strong>
                                            Estado:
                                        </strong>

                                        <?= getOrderStatusLabel(
                                            $order['status']
                                        ); ?>

                                    </p>

                                    <p>

                                        <strong>
                                            Importe:
                                        </strong>

                                        $<?= number_format(
                                            $order['amount'],
                                            2
                                        ); ?>

                                    </p>

                                </div>

                            </div>

                            <hr>

                            <div
                                class="alert alert-info"
                            >

                                Actualmente el sistema utiliza
                                un flujo temporal de pruebas.

                                Más adelante esta pantalla
                                redirigirá automáticamente a
                                Mercado Pago.

                            </div>

                            <div
                                class="d-flex gap-2"
                            >

                                <a
                                    href="<?= $preference['checkout_url']; ?>"
                                    class="btn btn-success"
                                >

                                    Continuar al pago

                                </a>

                                <a
                                    href="orden?id=<?= $order['id_order']; ?>"
                                    class="btn btn-outline-secondary"
                                >

                                    Volver

                                </a>

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