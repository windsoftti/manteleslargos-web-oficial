let cscCurrentMonthCount = 0;

const cscGoNextMonth = () => {
  if (cscCurrentMonthCount == 11) return;

  cscCurrentMonthCount = cscCurrentMonthCount + 1;

  const months = document.querySelectorAll('.cs-calendar-month.active');
  const inlineMonths = document.querySelectorAll('.cs-inline-month.active');

  months.forEach(month => month.classList.remove('active'));
  inlineMonths.forEach(inlineMonth => inlineMonth.classList.remove('active'));

  document.getElementById(`cs-calendar-month-${cscCurrentMonthCount}`).classList.add('active');
  document.getElementById(`cs-inline-month-${cscCurrentMonthCount}`).classList.add('active');

  document.getElementById(`cs-inline-month-${cscCurrentMonthCount}`).scrollIntoView({
    behavior: 'smooth',
    block: 'nearest',
    inline: 'start'
  });
}

const cscGoBackMonth = () => {
  if (cscCurrentMonthCount == 0) return;

  cscCurrentMonthCount = cscCurrentMonthCount - 1;

  const months = document.querySelectorAll('.cs-calendar-month.active');
  const inlineMonths = document.querySelectorAll('.cs-inline-month.active');

  months.forEach(month => month.classList.remove('active'));
  inlineMonths.forEach(inlineMonth => inlineMonth.classList.remove('active'));

  document.getElementById(`cs-calendar-month-${cscCurrentMonthCount}`).classList.add('active');
  document.getElementById(`cs-inline-month-${cscCurrentMonthCount}`).classList.add('active');

  document.getElementById(`cs-inline-month-${cscCurrentMonthCount}`).scrollIntoView({
    behavior: 'smooth',
    block: 'nearest',
    inline: 'end'
  });
}

const cscChangeMonth = monthKey => {
  cscCurrentMonthCount = monthKey;

  const months = document.querySelectorAll('.cs-calendar-month.active');
  const inlineMonths = document.querySelectorAll('.cs-inline-month.active');

  months.forEach(month => month.classList.remove('active'));
  inlineMonths.forEach(inlineMonth => inlineMonth.classList.remove('active'));

  document.getElementById(`cs-calendar-month-${monthKey}`).classList.add('active');
  document.getElementById(`cs-inline-month-${monthKey}`).classList.add('active');
}

const cscCreateCalendar = ({
  locale,
  handleAdd,
  extraData = '',
  extraClassDay = ''
}) => {
  return new Promise((resolve, reject) => {
    const currentYear = new Date().getFullYear();
    const currentMonth = new Date().getMonth();
    const currentDay = new Date().getDate();

    cscCurrentMonthCount = currentMonth;

    // Months
    const months = [...Array(12).keys()];

    const intlMonths = new Intl.DateTimeFormat(locale, {
      month: 'long'
    });

    // Weeks
    const weekDays = [...Array(7).keys()];

    const intlWeekDay = new Intl.DateTimeFormat(locale, {
      weekday: 'short'
    });

    const weekDaysNames = weekDays.map(weekDayIndex => {
      const weekDayName = intlWeekDay.format(new Date(2021, 10, weekDayIndex + 1));
      /* console.log(weekDayName); */
      return weekDayName;
    });

    const cscCheckDayState = ({
      day,
      month,
      year
    }) => {
      let response = '';

      const date = new Date(year, month, day);
      const currentDate = new Date(currentYear, currentMonth, currentDay);

      if (date < currentDate) response = 'disabled';

      return response;
    }

    const renderedWeekDays = weekDaysNames.map(weekDayName => `<li class="day-name">${weekDayName}</li>`).join('');

    // Calendar data
    const calendar = months.map(monthKey => {
      const monthName = intlMonths.format(new Date(currentYear, monthKey));
      const nextMonthIndex = monthKey + 1;

      // Days on month
      const daysOnMonth = new Date(currentYear, nextMonthIndex, 0).getDate();

      // Position of the first day
      const starts = new Date(currentYear, monthKey, 1).getDay();
      const startsOn = starts === 0 ? 7 : starts;

      return {
        monthName,
        daysOnMonth,
        startsOn,
        monthKey
      }
    });

    const renderedMonths = calendar.map(({
      monthName,
      monthKey
    }) => {
      const activeMonth = monthKey === currentMonth ? `active` : ``;

      //console.log(currentMonth, ' - ', monthKey);

      return `
        <a
          id="cs-inline-month-${monthKey}"
          class="cs-inline-month ${activeMonth}"
          href="javascript:void(0)"
          onclick="cscChangeMonth(${monthKey})"
        >
          <h1>${monthName} ${currentYear}</h1>
        </a>
      `;
    }).join('');

    // Render calendar
    const htmlCalendar = calendar.map(({
      monthName,
      daysOnMonth,
      startsOn
    }, monthIndex) => {
      const title = `<div class="cs-calendar-title">SELECCIONA EL DÍA DE TU EVENTO</div>`;

      const days = [...Array(daysOnMonth).keys()];

      //console.log(startsOn);

      const firstDayAttributes = `class="cs-calendar-first-day" style="grid-column-start: ${startsOn}"`;

      const renderedDays = days.map((day, index) => {
        const dateMonth = (monthIndex + 1) < 10 ? `0${monthIndex + 1}` : (monthIndex + 1);
        const dateDay = (day + 1) < 10 ? `0${day + 1}` : (day + 1);

        const activeDay = (currentDay === (day + 1) && currentMonth === monthIndex) ? `active` : ``;
        const currentDate = `${currentYear}-${dateMonth}-${dateDay}`;
        const currentDateFormat = `${dateDay}/${dateMonth}/${currentYear}`;

        const disableDay = cscCheckDayState({
          day: dateDay,
          month: monthIndex,
          year: currentYear
        });

        //console.log(index === 0 ? 0 : '');

        return `
          <li id="day-${currentDate}"
            class="${activeDay} ${disableDay}"
            ${index === 0 ? firstDayAttributes : ''}
          >
            <a
              class="csc-add-event ${extraClassDay}"
              data-date="${currentDateFormat}"
              href="javascript:void(0)"
              ${extraData}
            >
              <span>${day + 1}</span>
            </a>
          </li>
        `;
      }).join('');

      const renderedCalendar = `
        <div
          id="cs-calendar-month-${monthIndex}"
          class="cs-calendar-month ${monthIndex === cscCurrentMonthCount ? 'active' : ''}"
        >
          <div class="cs-calendar-title">
            ${title}
          </div>

          <div class="cs-calendar-weeks">
            <ul>
              ${renderedWeekDays}
            </ul>
          </div>

          <div class="cs-calendar-days">
            <ul>
              ${renderedDays}
            </ul>
          </div>
        </div>
      `;

      return renderedCalendar;
    }).join('');

    document.querySelector('.cs-calendar').innerHTML = `
      <div class="cs-calendar-inline-months">
        <a class="arrow back" onclick="cscGoBackMonth()"></a>
        <div class="inline-months">
          ${renderedMonths} 
        </div>
        <a class="arrow next" onclick="cscGoNextMonth()"></a>
      </div>

      <div class="cs-calendar-months">
        ${htmlCalendar}
      </div>
    `;

    !!handleAdd && document.querySelectorAll('.csc-add-event').forEach(btn => {
      btn.addEventListener('click', function () {
        const date = this.getAttribute('data-date');
        const parent = $(this).parent();
        const isOccupied = parent.hasClass('occupied');

        if (!isOccupied) !!handleAdd ? handleAdd(date) : alert(date);

        if (isOccupied) showSweetToast({
          icon: 'error',
          message: 'Este día no está disponible'
        });
      });
    });

    resolve(true);
  });
}

const cscEventItem = ({
  data,
  title,
  date
}) => `
  <a class="btn-csc-event" href="javascript:void(0)" data-info="${data}" data-date="${date}">
    <span>
      ${title}
    </span>
  </a>
`;

const cscAddEvents = events => events.map(item => {
  const date = item.date;
  const title = item.title;
  const data = item.data;

  const dateId = `csc-${date}`;

  const eventContainer = document.getElementById(dateId);

  const eventItem = cscEventItem({
    title,
    data,
    date
  });

  eventContainer.innerHTML += eventItem;
});

const cscAddDateStatus = states => states.map(item => {
  const date = item.date;
  const status = item.status;

  const dateId = `day-${date}`;

  document.getElementById(dateId).classList.add(status);
  document.getElementById(dateId).setAttribute('data-status', status);

  //if (status === 'occupied') {
  //  $(`#${dateId}`).addClass('cs-tooltip');
  //  $(`#${dateId}`).append('<span class="cs-tooltip-text">No disponible</span>');
  //}
});