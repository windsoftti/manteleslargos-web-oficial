<?php

include 'inc/session.php';

$subscription =
    getCurrentSubscription();

$currentPlanSlug =
    strtolower(
        $subscription['plan_slug']
    );

$isTrial =
    $subscription['is_trial'];

$daysLeft =
    $subscription['days_left'];

$meta_title =
    'Planes y suscripciones';

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

                    <div class="mb-5">

                        <h2 class="mb-2">
                            Planes y suscripciones
                        </h2>

                        <p class="text-muted mb-0">
                            Elige el plan ideal para potenciar tu negocio.
                        </p>

                    </div>

                    <?php if ($isTrial) : ?>

                        <div class="alert alert-warning mb-4">

                            <strong>
                                Tu periodo de prueba está activo.
                            </strong>

                            <br>

                            Te quedan

                            <strong>
                                <?= $daysLeft; ?> días
                            </strong>

                            para disfrutar de Premium.

                        </div>

                    <?php endif; ?>

                    <div class="row">

                        <!-- BASICO -->

                        <div class="col-md-6 mb-4">

                            <div class="card h-100">

                                <div class="card-body">

                                    <div class="text-center mb-4">

                                        <i class="fal fa-layer-group fa-4x text-secondary mb-3"></i>

                                        <h3>
                                            Básico
                                        </h3>

                                        <h2 class="font-weight-bold">
                                            $0
                                        </h2>

                                        <small class="text-muted">
                                            Sin costo
                                        </small>

                                    </div>

                                    <ul class="list-unstyled">

                                        <li class="mb-2">
                                            ✓ Perfil del negocio
                                        </li>

                                        <li class="mb-2">
                                            ✓ Información comercial
                                        </li>

                                        <li class="mb-2">
                                            ✓ Estadísticas básicas
                                        </li>

                                        <li class="mb-2">
                                            ✓ Gestión de negocio
                                        </li>

                                    </ul>

                                    <div class="mt-4">

                                        <?php if (
                                            $currentPlanSlug === 'basico'
                                        ) : ?>

                                            <button
                                                class="btn btn-outline-secondary btn-block"
                                                disabled
                                            >
                                                Plan actual
                                            </button>

                                        <?php else : ?>

                                            <button
                                                class="btn btn-outline-secondary btn-block"
                                                disabled
                                            >
                                                Disponible
                                            </button>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- PREMIUM -->

                        <div class="col-md-6 mb-4">

                            <div class="card h-100 border-primary">

                                <div class="card-body">

                                    <div class="text-center mb-4">

                                        <i class="fal fa-crown fa-4x text-warning mb-3"></i>

                                        <h3>
                                            Premium
                                        </h3>

                                        <h2 class="font-weight-bold text-primary">
                                            $299
                                        </h2>

                                        <small class="text-muted">
                                            por mes
                                        </small>

                                    </div>

                                    <ul class="list-unstyled">

                                        <li class="mb-2">
                                            ✓ Calendario inteligente
                                        </li>

                                        <li class="mb-2">
                                            ✓ Cotizaciones avanzadas
                                        </li>

                                        <li class="mb-2">
                                            ✓ Próximos eventos
                                        </li>

                                        <li class="mb-2">
                                            ✓ Multiusuarios
                                        </li>

                                        <li class="mb-2">
                                            ✓ Herramientas premium
                                        </li>

                                    </ul>

                                    <div class="mt-4">

                                        <?php if (
                                            $currentPlanSlug === 'premium'
                                            || $isTrial
                                        ) : ?>

                                            <button
                                                class="btn btn-success btn-block"
                                                disabled
                                            >
                                                <?= $isTrial
                                                    ? 'Premium en prueba'
                                                    : 'Premium activo'; ?>
                                            </button>

                                        <?php else : ?>

                                            <!--<button
                                                type="button"
                                                class="btn btn-primary"
                                                onclick="createSubscriptionOrder(2)"
                                            >
                                                Contratar Premium
                                            </button>-->
                                            <a
                                                href="javascript:void(0)"
                                                onclick="createPremiumOrder()"
                                                class="btn btn-primary btn-block"
                                            >
                                                Contratar Premium
                                            </a>

                                        <?php endif; ?>

                                    </div>

                                </div>

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
<script src="main/subscriptions/subscriptions.js"></script>

</body>

</html>