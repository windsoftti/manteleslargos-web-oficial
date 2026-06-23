$(loadReservations());

async function loadReservations() {
  showPageLoading();

  const businessId = $('#desktop-business').val();
  const parameters = new FormData();

  parameters.append('businessId', businessId);
  parameters.append('action', 'list_reservations');

  const response = await fetchData({
    place: 'events_calendar',
    data: parameters
  });

  hidePageLoading();

  if (response.status === 'success') cscCreateCalendar({
    locale: 'es',
    handleAdd: () => showUpdatePlanAlert('¡Para agregar eventos usando el calendario de eventos, debes de actualizar al plan Básico'),
    handleUnlock: () => showUpdatePlanAlert()
  }).then(() => {
    cscAddEvents(response.events);
    if (response.dates) cscAddDateStatus(response.dates);
  });

  if (response.status != 'success') cscCreateCalendar({
    locale: 'es',
    handleAdd: () => showUpdatePlanAlert('¡Para agregar eventos usando el calendario de eventos, debes de actualizar al plan Básico'),
    handleUnlock: () => showUpdatePlanAlert()
  });
}

$(document).on('click', '.btn-csc-event', () => showUpdatePlanAlert('Para poder editar el evento, debes de actualizar al plan Básico'));