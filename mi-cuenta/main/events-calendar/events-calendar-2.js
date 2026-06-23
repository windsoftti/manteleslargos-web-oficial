const eventCalendarItem = data => `
  <div class="media d-flex flex-column mb-4 mx-0 px-0 border-bottom-1 pb-2">
    <div class="d-flex align-items-center">
      <div class="m1-0 mr-3 position-relative">
        <a href="javascript:void(0)">
          <img class="custom-img-thumbnail" src="../src/assets/images/listing/${data.Imagen}" alt="${data.Salon}">
        </a>
      </div>

      <div class="media-body">
        <a href="javascript:void(0)" class="text-dark hover-primary">
          <h5 class="fs-16 mb-0 lh-18">${data.Salon}</h5>
        </a>

        <p class="mb-1 fs-14">${data.Ciudad}, ${data.Estado}</p>

        <span class="text-heading lh-15 font-weight-bold fs-17">Paquete:</span>
        <span class="text-gray-light">${data.Paquete}</span>
      </div>
    </div>

    ${data.HoraInicio || data.HoraFinal ? `
      <div class="d-flex flex-column align-items-center w-100 mt-2">
        <div class="d-flex justify-content-center w-100 px-1" style="gap: 1rem;">
          ${data.HoraInicio ? `
            <div class="time-label">
              <span>Hora inicio</span>
              <p>${data.HoraInicio}</p>
            </div>
          ` : ``}

          ${data.HoraFinal ? `
            <div class="time-label">
              <span>Hora final</span>
              <p>${data.HoraFinal}</p>
            </div>
          ` : ``}
        </div>
      </div>
    ` : ``}

    <div class="d-flex flex-column align-items-center w-100 mt-2">
      <div class="business-total-cost">
        <span>Costo total: </span> $${data.CostoTotalFormat}
      </div>
    </div>

    <div class="d-flex align-items-center justify-content-center w-100 mt-3">
      <a href="javascript:void(0)" class="d-inline-block fs-18 text-dark hover-primary mr-5 btn-edit-reservation" data-reservation='${JSON.stringify(data)}'>
        <i class="fal fa-pencil-alt"></i>
      </a>

      <a href="javascript:void(0)" class="d-inline-block fs-18 text-dark hover-primary">
        <i class="fal fa-trash-alt"></i>
      </a>
    </div>
  </div>
`;

const reminderCalendarItem = data => `
  <div class="media d-flex flex-column mb-4 mx-0 px-0 border-bottom-1 pb-2">
    <div class="d-flex align-items-center">
      <div class="media-body">
        <a href="javascript:void(0)" class="text-dark hover-primary">
          <h5 class="fs-16 mb-0 lh-18">${data.title}</h5>
        </a>

        <p class="mb-1 fs-14">${data.description}</p>
      </div>
    </div>

    <div class="d-flex flex-column align-items-center w-100 mt-2">
      <div class="d-flex justify-content-center w-100 px-1" style="gap: 1rem;">
        ${data.dateDesde ? `
          <div class="time-label">
            <span>Hora inicio</span>
            <p>${data.dateDesdeFormat}</p>
          </div>
          ` : ``}
    
        ${data.dateHasta ? `
          <div class="time-label">
            <span>Hora final</span>
            <p>${data.dateHastaFormat}</p>
          </div>
        ` : ``}
      </div>
    </div>

    <div class="d-flex align-items-center justify-content-center w-100 mt-3">
      <a href="javascript:void(0)" class="d-inline-block fs-18 text-dark hover-primary mr-5 btn-edit-reminder" data-reminder='${JSON.stringify(data)}'>
        <i class="fal fa-pencil-alt"></i>
      </a>

      <a href="javascript:void(0)" class="d-inline-block fs-18 text-dark hover-primary">
        <i class="fal fa-trash-alt"></i>
      </a>
    </div>
  </div>
`;

let dateStatus = 'Libre';

const renderCalendarDateData = data => {
  dateStatus = data.dateStatus;

  const events = data.events;
  const reservationsContainer = $('#tab-reservations');

  reservationsContainer.html('');

  if (events) events.map(item => reservationsContainer.append(eventCalendarItem(item)));
  if (!events) reservationsContainer.html('No hay reservaciones en esta Fecha.');

  const reminders = data.reminders;
  const remindersContainer = $('#tab-reminders');

  remindersContainer.html('');

  if (reminders) reminders.map(item => remindersContainer.append(reminderCalendarItem(item)));
  if (!reminders) remindersContainer.html('No hay recordatorios en esta Fecha.');
}

$(document).on('click', '.btn-edit-reservation', () => showUpdatePlanAlert('Para poder editar el evento, debes de actualizar al plan Básico'));