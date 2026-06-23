<div class="modal fade" id="modal-subscriptions">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <form
            id="subscriptions-form"
            class="modal-content needs-validation"
            novalidate
        >

            <div class="modal-header">

                <h5 class="modal-title modal-dynamic-title">
                    Suscripción
                </h5>

                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                >
                    <i class="fas fa-times"></i>
                </button>

            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                Negocio
                            </label>

                            <select
                                id="businessId"
                                name="idSalon"
                                class="form-control"
                                required
                            >

                                <option value="">
                                    Seleccionar
                                </option>

                            </select>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                Plan
                            </label>

                            <select
                                id="idPlan"
                                name="idPlan"
                                class="form-control"
                                required
                            >

                                <option value="">
                                    Seleccionar
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                Estado
                            </label>

                            <select
                                name="status"
                                id="status"
                                class="form-control"
                                required
                            >

                                <option value="pending">
                                    Pendiente
                                </option>

                                <option value="trial">
                                    Trial
                                </option>

                                <option value="active">
                                    Activo
                                </option>

                                <option value="grace">
                                    Grace
                                </option>

                                <option value="expired">
                                    Expirado
                                </option>

                                <option value="cancelled">
                                    Cancelado
                                </option>

                            </select>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                Ciclo de facturación
                            </label>

                            <select
                                name="billingCycle"
                                id="billingCycle"
                                class="form-control"
                            >

                                <option value="monthly">
                                    Mensual
                                </option>

                            </select>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                Renovación automática
                            </label>

                            <select
                                name="isRecurring"
                                id="isRecurring"
                                class="form-control"
                            >

                                <option value="no">
                                    No
                                </option>

                                <option value="yes">
                                    Sí
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                <hr>

                <h5>
                    Prueba gratuita
                </h5>

                <div class="row">

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                Activar prueba
                            </label>

                            <select
                                name="trialEnabled"
                                id="trialEnabled"
                                class="form-control"
                            >

                                <option value="0">
                                    No
                                </option>

                                <option value="1">
                                    Sí
                                </option>

                            </select>

                        </div>

                    </div>

                    <div
                        class="col-md-6"
                        id="trialDaysContainer"
                        style="display:none;"
                    >

                        <div class="form-group">

                            <label>
                                Días de prueba
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                name="trialDays"
                                id="trialDays"
                                value="30"
                                min="1"
                                max="90"
                            >

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                Fecha inicio
                            </label>

                            <input
                                type="date"
                                id="startsAt"
                                name="startsAt"
                                class="form-control"
                                required
                            >

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                Fecha vencimiento
                            </label>

                            <input
                                type="date"
                                id="expiresAt"
                                name="expiresAt"
                                class="form-control"
                                required
                            >

                        </div>

                    </div>

                </div>

            </div>

            <input
                type="hidden"
                id="idSubscription"
                name="idSubscription"
            >

            <input
                type="hidden"
                id="action-subscription"
                name="action"
            >

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-default"
                    data-dismiss="modal"
                >
                    Cancelar
                </button>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Guardar
                </button>

            </div>

        </form>

    </div>

</div>


<!--<div class="modal fade" id="modal-subscriptions">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <form
            id="subscriptions-form"
            class="modal-content needs-validation"
            novalidate
        >

            <div class="modal-header">

                <h5 class="modal-title modal-dynamic-title">
                    Suscripción
                </h5>

                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                >
                    <i class="fas fa-times"></i>
                </button>

            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                Negocio
                            </label>

                            <select
                                id="businessId"
                                name="idSalon"
                                class="form-control"
                                required
                            >
                            </select>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                Plan
                            </label>

                            <select
                                id="idPlan"
                                name="idPlan"
                                class="form-control"
                                required
                            >
                                <option value="">
                                    Seleccionar
                                </option>
                            </select>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                Estado
                            </label>

                            <select
                                name="status"
                                id="status"
                                class="form-control"
                                required
                            >
                                <option value="pending">Pendiente</option>
                                <option value="trial">Trial</option>
                                <option value="active">Activo</option>
                                <option value="grace">Grace</option>
                                <option value="expired">Expirado</option>
                                <option value="cancelled">Cancelado</option>
                            </select>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                Ciclo de facturación
                            </label>

                            <select
                                name="billingCycle"
                                id="billingCycle"
                                class="form-control"
                            >
                                <option value="monthly">
                                    Mensual
                                </option>
                            </select>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                Renovación automática
                            </label>

                            <select
                                name="isRecurring"
                                id="isRecurring"
                                class="form-control"
                            >
                                <option value="no">
                                    No
                                </option>

                                <option value="yes">
                                    Sí
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                <hr>

                <h5>
                    Prueba gratuita
                </h5>

                <div class="row">

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                Activar prueba
                            </label>

                            <select
                                name="trialEnabled"
                                id="trialEnabled"
                                class="form-control"
                            >

                                <option value="0">
                                    No
                                </option>

                                <option value="1">
                                    Sí
                                </option>

                            </select>

                        </div>

                    </div>

                    <div
                        class="col-md-6"
                        id="trialDaysContainer"
                        style="display:none;"
                    >

                        <div class="form-group">

                            <label>
                                Días de prueba
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                name="trialDays"
                                id="trialDays"
                                value="30"
                                min="1"
                                max="90"
                            >

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                Fecha inicio
                            </label>

                            <input
                                type="date"
                                id="startsAt"
                                name="startsAt"
                                class="form-control"
                                required
                            >

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                Fecha vencimiento
                            </label>

                            <input
                                type="date"
                                id="expiresAt"
                                name="expiresAt"
                                class="form-control"
                                required
                            >

                        </div>

                    </div>

                </div>

            </div>


            <input
                type="hidden"
                id="selectedSalon"
                name="idSalon"
            >

            <input
                type="hidden"
                id="idSubscription"
                name="idSubscription"
            >

            <input
                type="hidden"
                id="action-subscription"
                name="action"
            >

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-default"
                    data-dismiss="modal"
                >
                    Cancelar
                </button>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Guardar
                </button>

            </div>

        </form>

    </div>

</div>-->