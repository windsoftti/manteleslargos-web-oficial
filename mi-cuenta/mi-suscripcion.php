<?php

include 'inc/session.php';

$subscription =
    getCurrentSubscription();

$canActivateTrial =
    businessCanActivateTrial();

$planName =
    $subscription['plan'];

$status =
    $subscription['status'];

$daysLeft =
    $subscription['days_left'];

$expiresAt =
    $subscription['expires_at'];

$statusLabel = 'Activo';

if ($status === 'trial') {
    $statusLabel = 'Periodo de prueba';
}

if ($status === 'expired') {
    $statusLabel = 'Expirado';
}

if ($status === 'cancelled') {
    $statusLabel = 'Cancelado';
}

$isBasic =
    strtolower($planName) === 'básico';

$isPremium =
    strtolower($planName) === 'premium';

$buttonLabel =
    $isBasic
        ? 'Actualizar a Premium'
        : 'Administrar suscripción';

$statusBadgeClass = 'badge-success';

if ($status === 'trial') {
    $statusBadgeClass = 'badge-warning';
}

if (
    $status === 'expired' ||
    $status === 'cancelled'
) {
    $statusBadgeClass = 'badge-danger';
}

$meta_title =
    'Mi suscripción';
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

                    <h2 class="mb-4">
                        Mi suscripción
                    </h2>

                    <?php if (
                        $subscription['is_trial']
                    ) : ?>

                        <div class="alert alert-warning mb-4">

                            <strong>
                                Periodo de prueba activo
                            </strong>

                            <br>

                            Tu prueba gratuita vence en

                            <strong>
                                <?= $daysLeft; ?> días
                            </strong>

                            y actualmente tienes acceso a todas las funcionalidades Premium.

                        </div>

                    <?php endif; ?>

                    <div class="row">

                        <div class="col-md-5 mb-4">

                            <div class="card h-100">

                                <div class="card-body text-center py-5">

                                    <?php if ($isPremium) : ?>

                                        <i
                                            class="fal fa-crown fa-4x text-warning mb-4">
                                        </i>

                                    <?php else : ?>

                                        <i
                                            class="fal fa-store fa-4x text-secondary mb-4">
                                        </i>

                                    <?php endif; ?>

                                    <h2 class="mb-3">
                                        <?= $planName; ?>
                                    </h2>

                                    <div class="mb-3">

                                        <span class="badge <?= $statusBadgeClass; ?> px-3 py-2">

                                            <?= $statusLabel; ?>

                                        </span>

                                    </div>

                                    <?php if ($expiresAt) : ?>

                                        <p class="mb-2">

                                            <strong>
                                                Vence:
                                            </strong>

                                            <?= date(
                                                'd/m/Y',
                                                strtotime(
                                                    $expiresAt
                                                )
                                            ); ?>

                                        </p>

                                    <?php endif; ?>

                                    <?php if (
                                        $daysLeft !== null
                                    ) : ?>

                                        <p class="mb-3">

                                            <strong>
                                                Días restantes:
                                            </strong>

                                            <?= $daysLeft; ?>

                                        </p>

                                    <?php endif; ?>

                                    <?php if ($isBasic) : ?>

                                        <p
                                            class="text-muted small mb-4 px-3"
                                        >
                                            Tu negocio utiliza actualmente el
                                            plan Básico. Actualiza a Premium
                                            para desbloquear herramientas
                                            avanzadas de gestión y ventas.
                                        </p>

                                        <?php if ($canActivateTrial) : ?>

                                            <div class="alert alert-success text-left mb-4">

                                                <strong>
                                                    🎁 Prueba Premium disponible
                                                </strong>

                                                <br>

                                                Activa gratis todas las funciones
                                                Premium durante 30 días.

                                            </div>

                                            <!--<button
                                                type="button"
                                                id="activate-trial-button"
                                                class="btn btn-success btn-block mb-3"
                                            >
                                                Activar prueba gratuita
                                            </button>-->

                                            <a
                                                href="javascript:void(0)"
                                                onclick="activateTrial()"
                                                class="btn btn-success btn-block mb-3"
                                            >
                                                Activar prueba gratuita
                                            </a>

                                        <?php else : ?>

                                            <div class="alert alert-secondary text-left mb-4">

                                                La prueba gratuita ya fue utilizada
                                                para este negocio.

                                            </div>

                                        <?php endif; ?>

                                    <?php endif; ?>

                                    <?php if ($isPremium) : ?>

                                        <p
                                            class="text-muted small mb-4 px-3"
                                        >
                                            Tu negocio tiene acceso a las
                                            funcionalidades Premium
                                            disponibles actualmente.
                                        </p>

                                    <?php endif; ?>

                                    <a
                                        href="planes"
                                        class="btn btn-primary btn-block"
                                    >
                                        <?= $buttonLabel; ?>
                                    </a>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-7 mb-4">

                            <div class="card h-100">

                                <div class="card-body">

                                    <?php if ($isBasic) : ?>

                                        <h4 class="mb-4">
                                            Beneficios Premium
                                        </h4>

                                        <p class="text-muted mb-4">
                                            Actualiza tu plan para desbloquear:
                                        </p>

                                    <?php else : ?>

                                        <h4 class="mb-4">
                                            Beneficios activos
                                        </h4>

                                    <?php endif; ?>

                                    <ul class="list-unstyled">

                                        <li class="mb-3">
                                            ✅ Calendario inteligente
                                        </li>

                                        <li class="mb-3">
                                            ✅ Cotizaciones avanzadas
                                        </li>

                                        <li class="mb-3">
                                            ✅ Próximos eventos
                                        </li>

                                        <li class="mb-3">
                                            ✅ Gestión de eventos
                                        </li>

                                        <li class="mb-3">
                                            ✅ Multiusuarios
                                        </li>

                                        <li class="mb-3">
                                            ✅ Estadísticas avanzadas
                                        </li>

                                    </ul>

                                </div>

                            </div>

                        </div>

                    </div>

                    <?php if ($isBasic) : ?>

                        <div class="row">

                            <div class="col-12">

                                <div class="card">

                                    <div class="card-body">

                                        <h4 class="mb-3">

                                            ¿Por qué actualizar a Premium?

                                        </h4>

                                        <div class="row">

                                            <div class="col-md-3 mb-3">

                                                ✓ Más cotizaciones

                                            </div>

                                            <div class="col-md-3 mb-3">

                                                ✓ Multiusuarios

                                            </div>

                                            <div class="col-md-3 mb-3">

                                                ✓ Calendario inteligente

                                            </div>

                                            <div class="col-md-3 mb-3">

                                                ✓ Gestión de eventos

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endif; ?>

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