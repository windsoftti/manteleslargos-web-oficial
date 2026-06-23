let $currentMonthCount = 0;

const $eventItem = ({
  data,
  title
}) => `
    <button class="cs-calendar-btn" title="${title}" data-info="${data}">
      <h3>${title}</h3>
    </button>
`;

const $goNextMonth = () => {
  if ($currentMonthCount == 11) return;

  $currentMonthCount = $currentMonthCount + 1;

  const months = document.querySelectorAll('.cs-calendar-month.active');
  const inlineMonths = document.querySelectorAll('.cs-inline-month.active');

  months.forEach(month => month.classList.remove('active'));
  inlineMonths.forEach(inlineMonth => inlineMonth.classList.remove('active'));

  document.getElementById(`cs-calendar-month-${$currentMonthCount}`).classList.add('active');
  document.getElementById(`cs-inline-month-${$currentMonthCount}`).classList.add('active');

  document.getElementById(`cs-inline-month-${$currentMonthCount}`).scrollIntoView({
    behavior: 'smooth',
    block: 'nearest',
    inline: 'start'
  });
}

const $goBackMonth = () => {
  if ($currentMonthCount == 0) return;

  $currentMonthCount = $currentMonthCount - 1;

  const months = document.querySelectorAll('.cs-calendar-month.active');
  const inlineMonths = document.querySelectorAll('.cs-inline-month.active');

  months.forEach(month => month.classList.remove('active'));
  inlineMonths.forEach(inlineMonth => inlineMonth.classList.remove('active'));

  document.getElementById(`cs-calendar-month-${$currentMonthCount}`).classList.add('active');
  document.getElementById(`cs-inline-month-${$currentMonthCount}`).classList.add('active');

  document.getElementById(`cs-inline-month-${$currentMonthCount}`).scrollIntoView({
    behavior: 'smooth',
    block: 'nearest',
    inline: 'end'
  });
}

const $csCalendarChangeMonth = monthKey => {
  $currentMonthCount = monthKey;

  const months = document.querySelectorAll('.cs-calendar-month.active');
  const inlineMonths = document.querySelectorAll('.cs-inline-month.active');

  months.forEach(month => month.classList.remove('active'));
  inlineMonths.forEach(inlineMonth => inlineMonth.classList.remove('active'));

  document.getElementById(`cs-calendar-month-${monthKey}`).classList.add('active');
  document.getElementById(`cs-inline-month-${monthKey}`).classList.add('active');
}

const $createCSCalendar = ({ locale }) => {
  return new Promise((resolve, reject) => {
    const currentYear = new Date().getFullYear();
    const currentMonth = new Date().getMonth();
    const currentDay = new Date().getDate();

    $currentMonthCount = currentMonth;

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
        <a id="cs-inline-month-${monthKey}" class="cs-inline-month ${activeMonth}" href="javascript:void(0)" onclick="$csCalendarChangeMonth(${monthKey})">
          <h3>${currentYear}</h3>
          <h1>${monthName}</h1>
        </a>
      `;
    }).join('');

    // Render calendar
    const htmlCalendar = calendar.map(({
      monthName,
      daysOnMonth,
      startsOn
    }, monthIndex) => {
      const title = `<div class="cs-calendar-title">${monthName} ${currentYear}</div>`;

      const days = [...Array(daysOnMonth).keys()];

      //console.log(startsOn);

      const firstDayAttributes = `class="cs-calendar-first-day" style="grid-column-start: ${startsOn}"`;

      const renderedDays = days.map((day, index) => {
        const dateMonth = (monthIndex + 1) < 10 ? `0${monthIndex + 1}` : (monthIndex + 1);
        const dateDay = (day + 1) < 10 ? `0${day + 1}` : (day + 1);

        const currentDayClass = (currentDay === (day + 1) && currentMonth === monthIndex) ? `class="active"` : ``;

        const currentDate = `${currentYear}-${dateMonth}-${dateDay}`;

        //console.log(index === 0 ? 0 : '');

        return `
          <li id="date-${currentDate}" ${currentDayClass} ${index === 0 ? firstDayAttributes : ''} data-date="${currentDate}">
            ${day + 1}
          </li>
        `
      }).join('');

      const renderedCalendar = `
        <div id="cs-calendar-month-${monthIndex}" class="cs-calendar-month ${monthIndex === $currentMonthCount && 'active'}">
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
        <a class="arrow back" onclick="$goBackMonth()"></a>
        <div class="inline-months">
          ${renderedMonths} 
        </div>
        <a class="arrow next" onclick="$goNextMonth()"></a>
      </div>

      <div class="cs-calendar-months">
        ${htmlCalendar}
      </div>
    `;

    resolve(true);
  });
}

/* ADD EVENTS :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
/* const $csCalendarAddEvents = events => events.map(item => {
  const date = item.date;
  const status = item.status;
  const title = item.title;
  const data = item.data;

  const dateId = `date-${date}`;

  document.getElementById(dateId).setAttribute('data-info', data);

  //console.log(dateId);

  document.getElementById(dateId).classList.add(status);
}); */

const $csCalendarAddEvents = events => events.map(item => {
  const date = item.date;
  const title = item.title;
  const data = item.data;

  const dateId = `date-${date}`;

  //document.getElementById(dateId).setAttribute('data-info', data);

  //document.getElementById(dateId).classList.add(status);

  console.log(dateId);

  const dateBox = document.getElementById(dateId);
  const eventItem = $eventItem({
    title,
    data
  });

  dateBox.innerHTML += eventItem;
});

const $csCalendarAddStatus = dateStatus => dateStatus.map(item => {
  const date = item.date;
  const status = item.status;
  const dateId = `date-${date}`;
  document.getElementById(dateId).classList.add(status);
});