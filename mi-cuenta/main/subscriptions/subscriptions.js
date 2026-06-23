async function activateTrial() {

    const confirmAction =
        await showSweetConfirm({
            title: 'Activar prueba Premium',
            subtitle:
                'Obtendrás acceso Premium durante 30 días.'
        });

    if (!confirmAction) {
        return;
    }

    showPageLoading();

    const data = new FormData();

    data.append(
        'action',
        'activate_trial'
    );

    const response =
        await fetchData({
            place: 'subscriptions',
            data
        });

    hidePageLoading();

    await showBigAlert({
        title: '¡Perfecto!',
        subtitle: response.message
    });

    if (
        response.status === 'success'
    ) {
        location.reload();
    }
}

async function createSubscriptionOrder(
    planId,
    billingCycle = 'monthly'
) {

    showPageLoading();

    const data = new FormData();

    data.append(
        'action',
        'create_subscription_order'
    );

    data.append(
        'plan_id',
        planId
    );

    data.append(
        'billing_cycle',
        billingCycle
    );

    const response = await fetchData({
        place: 'subscriptions',
        data
    });

    hidePageLoading();

    if (response.status !== 'success') {

        showBigAlert({
            icon: 'error',
            title: 'Error',
            subtitle: response.message
        });

        return;
    }

    showBigAlert({
        icon: 'success',
        title: 'Orden creada',
        subtitle:
            'Tu solicitud fue registrada correctamente.'
    });
}

async function createPremiumOrder() {

    const confirmResult =
        await showSweetConfirm({

            title: 'Contratar Premium',

            subtitle:
                'Se generará una orden mensual por $299 MXN.'

        });

    if (!confirmResult) {
        return;
    }

    showPageLoading();

    const data = new FormData();

    data.append(
        'action',
        'create_premium_order'
    );

    const response =
        await fetchData({

            place: 'subscriptions',

            data

        });

    hidePageLoading();

    if (
        response.status === 'success'
    ) {

        await showBigAlert({

            title: 'Orden creada',

            subtitle:
                'Tu orden fue creada correctamente.'

        });

        location.href =
            'mis-ordenes';

        return;
    }

    showBigAlert({

        icon: 'error',

        title: 'Error',

        subtitle:
            response.message

    });
}