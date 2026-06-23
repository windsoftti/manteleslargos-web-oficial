$(function () {
    /* $createCSCalendar({
      locale: 'es'
    }).then(() => {
      //if (response.status == 'success') $csCalendarAddEvents(fechas)
    }); */
  
    loadReservations();
  });
  
  const loadReservations = async () => {
    showPageLoading();
  
    const parameters = new FormData();
  
    parameters.append('action', 'listar_reservations');
  
    const response = await fetchData({
      place: 'events_calendar',
      data: parameters
    });
  
    hidePageLoading();
  
    if (response.status === 'success') {
      const dates = response.dates;
  
      console.log(dates);
  
      $createCSCalendar({
        locale: 'es'
      }).then(() => {
        if (response.status == 'success') $csCalendarAddEvents(dates)
      });
    } else {
      $createCSCalendar({
        locale: 'es'
      })
    }
  }
  
  $(document).on('click', '.cs-calendar-days ul li', function () {
    resetForm('#add-event-form');
  
    const date = $(this).attr('data-date');
    const dataInfo = $(this).attr('data-info');
  
    var newdate = new Date(date);
    newdate.setDate(newdate.getDate() + 1);
  
    //var newdate = new Date(date);
    var dd = String(newdate.getDate()).padStart(2, '0');
    var mm = String(newdate.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = newdate.getFullYear();
  
    newdate = dd + '/' + mm + '/' + yyyy;
  
    console.log(newdate);
  
    $('#date').data("DateTimePicker").date(newdate);
    $('#action-events-calendar').val('add_reservation')
  
    changeModalState('initial');
  
    $('input:radio[name="status"]').attr('checked', false);
    $('input:radio[name="status"]').filter('[value="Libre"]').attr('checked', true);
  
    if (dataInfo) {
      const data = JSON.parse(atob(dataInfo));
  
      $('#business').val(data.idNegocio);
      $('#eventType').val(data.idTipoEvento);
      $('#name').val(data.NombreCompleto);
      $('#email').val(data.Correo);
      $('#phone').val(data.Telefono);
      $('#startTime').val(data.HoraInicio);
      $('#endTime').val(data.HoraFinal);
      $('#NPersons').val(data.NPersonas);
      $('#extras').val(data.Extras);
      $('#totalCost').val(data.CostoTotal);
      $('#deposit').val(data.Deposito);
      $('#advance').val(data.Anticipo);
      //$('#status').val(data.Status);
      $('input:radio[name="status"]').filter('[value="' + data.Status + '"]').attr('checked', true);
      $('#action-events-calendar').val('edit_reservation')
      $('#reservationId').val(data.idReservacion);
  
      console.log(data.Status);
  
      changeModalState('add-event');
      loadPackages(data.idNegocio, data.idPaquete);
    }
  
    $('#modal-add-edit-event-calendar').modal('show');
  });
  
  const changeModalState = state => {
    if (state === 'initial') $('#form-container').removeClass('event');
  
    if (state === 'add-event') {
      $('#form-container').removeAttr('class');
      $('#form-container').addClass('col-md-12 form-container event');
    }
  }
  
  async function loadPackages(bid, pid) {
    const businessId = !!bid ? bid : $(this).val();
  
    if (!businessId) return;
  
    showPageLoading();
  
    const parameters = new FormData();
  
    parameters.append('businessId', businessId);
    parameters.append('action', 'list_packages');
  
    const response = await fetchData({
      place: 'selects',
      data: parameters
    });
  
    console.log(response);
  
    if (response.content) {
      await $('#package').html(decodeURIComponent(escape(atob(response.content))));
  
      if (pid) $('#package').val(pid);
    }
  
    hidePageLoading();
  }
  
  async function sendReservationData(e) {
    e.preventDefault();
    showPageLoading();
  
    const parameters = new FormData($(this)[0]);
  
    const response = await fetchData({
      place: 'events_calendar',
      data: parameters
    });
  
    hidePageLoading();
  
    if (response.message) showBigAlert({
      icon: response.status,
      title: response.title,
      subtitle: response.message
    });
  
    if (response.status === 'success') {
      $('#modal-add-edit-event-calendar').modal('hide');
      loadReservations();
    }
  }
  
  $('#business').on('change', loadPackages);
  
  $('#add-event-form').on('submit', sendReservationData);