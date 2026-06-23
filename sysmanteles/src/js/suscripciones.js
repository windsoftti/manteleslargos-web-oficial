$(initFunctions);

function initFunctions() {

    loadSubscriptions();

    initSearchForm(
        loadSubscriptions
    );

    initTrialToggle();

}

const loadSubscriptions = page => useLoadTable({
    page,
    place: 'suscripciones',
    action: 'list_subscriptions'
});

/* =====================================================
 * PLANES
 * ===================================================== */

async function loadPlans() {

    const parameters =
        new FormData();

    parameters.append(
        'action',
        'load_plans'
    );

    const response =
        await fetchData({
            place: 'suscripciones',
            parameters
        });

    if (!response.plans) {
        return;
    }

    $('#idPlan').html(
        '<option value="">Seleccionar</option>'
    );

    response.plans.forEach(
        plan => {

            $('#idPlan').append(`
                <option value="${plan.idPlan}">
                    ${plan.Plan}
                </option>
            `);

        }
    );

}

/* =====================================================
 * NEGOCIOS
 * ===================================================== */

async function loadBusinesses() {

    const parameters =
        new FormData();

    parameters.append(
        'action',
        'load_businesses'
    );

    const response =
        await fetchData({
            place: 'suscripciones',
            parameters
        });

    if (!response.businesses) {
        return;
    }

    $('#businessId').html(
        '<option value="">Seleccionar</option>'
    );

    response.businesses.forEach(
        business => {

            $('#businessId').append(`
                <option value="${business.idSalon}">
                    ${business.Salon}
                </option>
            `);

        }
    );

}

/* =====================================================
 * NUEVA SUSCRIPCIÓN
 * ===================================================== */

async function addSubscription() {

    await loadPlans();

    await loadBusinesses();

    changeModalTitle(
        'Nueva suscripción'
    );

    resetForm(
        '#subscriptions-form'
    );

    /*
    |--------------------------------------------------
    | Valores por defecto
    |--------------------------------------------------
    */

    const today =
        new Date();

    const startDate =
        today
            .toISOString()
            .split('T')[0];

    $('#startsAt').val(
        startDate
    );

    calculateExpirationDate();

    $('#businessId').prop(
        'disabled',
        false
    );

    $('#businessId').val('');

    $('#selectedSalon').val('');

    $('#action-subscription').val(
        'add_subscription'
    );

    $('#idSubscription').val('');

    $('#status').val(
        'pending'
    );

    $('#billingCycle').val(
        'monthly'
    );

    $('#isRecurring').val(
        'no'
    );

    $('#trialEnabled').val(
        '0'
    );

    $('#trialDays').val(
        '30'
    );

    $('#trialDaysContainer')
        .hide();

}

$(document).on(
    'click',
    '.btn-add-subscription',
    addSubscription
);

/* =====================================================
 * EDITAR SUSCRIPCIÓN
 * ===================================================== */

async function editSubscription() {

    await loadPlans();

    await loadBusinesses();

    const data = JSON.parse(
        atob(
            $(this).attr(
                'data-subscription'
            )
        )
    );

    changeModalTitle(
        'Editar suscripción'
    );

    $('#action-subscription').val(
        'edit_subscription'
    );

    $('#idSubscription').val(
        data.id_subscription
    );

    $('#selectedSalon').val(
        data.idSalon
    );

    $('#businessId').val(
        data.idSalon
    );

    $('#businessId').prop(
        'disabled',
        true
    );

    $('#idPlan').val(
        data.idPlan
    );

    $('#status').val(
        data.status
    );

    $('#billingCycle').val(
        data.billing_cycle
    );

    $('#isRecurring').val(
        data.is_recurring
    );

    $('#startsAt').val(
        data.starts_at
            ? data.starts_at.substring(0, 10)
            : ''
    );

    $('#expiresAt').val(
        data.expires_at
            ? data.expires_at.substring(0, 10)
            : ''
    );

    /*
    |--------------------------------------------------
    | Trial
    |--------------------------------------------------
    */

    if (
        data.trial_starts_at &&
        data.trial_expires_at
    ) {

        const start =
            new Date(
                data.trial_starts_at
            );

        const end =
            new Date(
                data.trial_expires_at
            );

        const diffTime =
            Math.abs(
                end.getTime() -
                start.getTime()
            );

        const diffDays =
            Math.round(
                diffTime /
                (1000 * 60 * 60 * 24)
            );

        $('#trialEnabled').val(
            '1'
        );

        $('#trialDays').val(
            diffDays
        );

        $('#trialDaysContainer')
            .show();

    } else {

        $('#trialEnabled').val(
            '0'
        );

        $('#trialDays').val(
            30
        );

        $('#trialDaysContainer')
            .hide();

    }

}

$(document).on(
    'click',
    '.btn-edit-subscription',
    editSubscription
);

/* =====================================================
 * TRIAL
 * ===================================================== */

function initTrialToggle() {

    $(document).on(
        'change',
        '#trialEnabled',
        function () {

            const enabled =
                $(this).val();

            if (enabled === '1') {

                $('#trialDaysContainer')
                    .show();

            } else {

                $('#trialDaysContainer')
                    .hide();

            }

        }
    );

}

/* =====================================================
 * GUARDAR
 * ===================================================== */

async function saveSubscription(e) {

    e.preventDefault();

    showPageLoading();

    const businessSelect =
        $('#businessId');

    const wasDisabled =
        businessSelect.prop(
            'disabled'
        );

    /*
    |--------------------------------------------------
    | Si está deshabilitado (edición)
    | FormData no lo envía
    |--------------------------------------------------
    */

    if (wasDisabled) {

        businessSelect.prop(
            'disabled',
            false
        );

    }

    const parameters =
        new FormData(
            $('#subscriptions-form')[0]
        );

    if (wasDisabled) {

        businessSelect.prop(
            'disabled',
            true
        );

    }

    const response =
        await fetchData({
            place: 'suscripciones',
            parameters
        });

    hidePageLoading();

    if (response.message) {

        showSweetToast({
            icon: response.status,
            message: response.message
        });

    }

    if (
        response.status === 'success'
    ) {

        $('#modal-subscriptions')
            .modal('hide');

        loadSubscriptions();

    }

}

$('#subscriptions-form').on(
    'submit',
    saveSubscription
);

$(document).on(
    'change',
    '#startsAt',
    calculateExpirationDate
);

$(document).on(
    'change',
    '#billingCycle',
    calculateExpirationDate
);

function calculateExpirationDate() {

    const startsAt =
        $('#startsAt').val();

    if (!startsAt) {
        return;
    }

    const billingCycle =
        $('#billingCycle').val();

    const startDate =
        new Date(startsAt);

    let days = 30;

    switch (billingCycle) {

        case 'monthly':
            days = 30;
        break;

        case 'semiannual':
            days = 180;
        break;

        case 'annual':
            days = 365;
        break;

    }

    startDate.setDate(
        startDate.getDate() + days
    );

    const expiresAt =
        startDate
            .toISOString()
            .split('T')[0];

    $('#expiresAt').val(
        expiresAt
    );

}

/*$(initFunctions);

function initFunctions() {

    loadSubscriptions();

    initSearchForm(loadSubscriptions);

    initTrialToggle();

}

const loadSubscriptions = page => useLoadTable({
    page,
    place: 'suscripciones',
    action: 'list_subscriptions'
});

async function loadPlans() {

    const parameters = new FormData();
    parameters.append('action', 'load_plans');

    const response = await fetchData({
        place: 'suscripciones',
        parameters
    });

    if (!response.plans) {
        return;
    }

    $('#idPlan').html(
        '<option value="">Seleccionar</option>'
    );

    response.plans.forEach(plan => {

        $('#idPlan').append(`
            <option value="${plan.idPlan}">
                ${plan.Plan}
            </option>
        `);

    });

}

async function addSubscription() {

    await loadPlans();

    await loadBusinesses();

    changeModalTitle(
        'Nueva suscripción'
    );

    resetForm(
        '#subscriptions-form'
    );

    $('#businessId').prop(
        'disabled',
        false
    );

    $('#selectedSalon').val('');

    $('#action-subscription').val(
        'add_subscription'
    );

    $('#idSubscription').val('');

    $('#trialEnabled').val('0');

    $('#trialDaysContainer').hide();

}

$(document).on(
    'click',
    '.btn-add-subscription',
    addSubscription
);

async function loadBusinesses() {

    const parameters =
        new FormData();

    parameters.append(
        'action',
        'load_businesses'
    );

    const response =
        await fetchData({
            place: 'suscripciones',
            parameters
        });

    if (!response.businesses) {
        return;
    }

    $('#businessId').html(
        '<option value="">Seleccionar</option>'
    );

    response.businesses.forEach(
        business => {

            $('#businessId').append(`
                <option value="${business.idSalon}">
                    ${business.Salon}
                </option>
            `);

        }
    );
}

$(document).on(
    'change',
    '#businessId',
    function(){

        $('#selectedSalon').val(
            $(this).val()
        );

    }
);

async function editSubscription() {

    await loadPlans();

    const data = JSON.parse(
        atob(
            $(this).attr(
                'data-subscription'
            )
        )
    );

    changeModalTitle(
        'Editar suscripción'
    );

    $('#action-subscription').val(
        'edit_subscription'
    );


    $('#selectedSalon').val(
	    data.idSalon
	);

    $('#idSubscription').val(
        data.id_subscription
    );


    $('#businessId').html(`
	    <option value="${data.idSalon}">
	        ${data.Salon}
	    </option>
	`);

	$('#businessId').val(
	    data.idSalon
	);

	$('#businessId').prop(
	    'disabled',
	    true
	);

    $('#status').val(
        data.status
    );

    $('#billingCycle').val(
        data.billing_cycle
    );

    $('#startsAt').val(
        data.starts_at
            ? data.starts_at.substring(0,10)
            : ''
    );

    $('#expiresAt').val(
        data.expires_at
            ? data.expires_at.substring(0,10)
            : ''
    );

}

$(document).on(
    'click',
    '.btn-edit-subscription',
    editSubscription
);

function initTrialToggle() {

    $(document).on(
        'change',
        '#trialEnabled',
        function () {

            const enabled =
                $(this).val();

            if (enabled === '1') {

                $('#trialDaysContainer')
                    .show();

            } else {

                $('#trialDaysContainer')
                    .hide();

            }

        }
    );

}

async function saveSubscription(e) {

    e.preventDefault();

    showPageLoading();

    const parameters =
        new FormData(
            $('#subscriptions-form')[0]
        );

    const response =
        await fetchData({
            place: 'suscripciones',
            parameters
        });

    hidePageLoading();

    if (response.message) {

        showSweetToast({
            icon: response.status,
            message: response.message
        });

    }

    if (
        response.status === 'success'
    ) {

        $('#modal-subscriptions')
            .modal('hide');

        loadSubscriptions();

    }

}

$('#subscriptions-form').on(
    'submit',
    saveSubscription
);*/