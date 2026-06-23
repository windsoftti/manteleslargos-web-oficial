class Calendar {
  constructor({
    id,
    events = [],
    reminders = [],
    dateStatus = [],
    onChangeYear = () => null,
    onPressDate = () => null
  }) {
    this.state = {
      id,
      events,
      reminders,
      dateStatus,

      monthReminders: [],
      monthEvents: [],
      monthDateStatus: [],

      currentYear: new Date().getFullYear(),
      currentMonth: new Date().getMonth(),
      currentDay: new Date().getDate(),

      year: new Date().getFullYear(),
      month: new Date().getMonth(),
      day: new Date().getDate(),
    };

    this._onChangeYear = newYear => onChangeYear(newYear);
    this._onPressDate = data => onPressDate(data);
    this._setYearVisible = year => this.state.year = year;
    this._setMonthVisible = month => this.state.month = month;
    this._setReminders = reminders => this.state.reminders = reminders;
    this._setDateStatus = dateStatus => this.state.dateStatus = dateStatus;
    this._setEvents = events => this.state.events = events;

    this._setMonthEvents = events => this.state.monthEvents = events;
    this._setMonthReminders = reminders => this.state.monthReminders = reminders;
    this._setMonthDateStatus = dateStatus => this.state.monthDateStatus = dateStatus;
  }

  setReminders = reminders => this.state.reminders = reminders;
  setDateStatus = dateStatus => this.state.dateStatus = dateStatus;
  setEvents = events => this.state.events = events;

  _createNextMonth = async () => {
    let newMonth = this.state.month + 1;
    let newYear = this.state.year;

    if (newMonth > 11) {
      newYear = newYear + 1;
      newMonth = 0;
      this._onChangeYear(newYear);
      this._setYearVisible(newYear);
      this._setMonthVisible(0);
    } else {
      this._setMonthVisible(newMonth);
    }

    this._createMonth({
      year: newYear,
      month: newMonth,
      day: this.state.currentDay
    });
  }

  _createBackMonth = async () => {
    let newMonth = this.state.month;
    let newYear = this.state.year;

    if (newMonth <= 0) {
      newYear = newYear - 1;
      newMonth = 11;

      await this._setYearVisible(newYear);
      await this._setMonthVisible(11);
      this._onChangeYear(newYear);
    } else {
      newMonth = newMonth - 1;
      this._setMonthVisible(newMonth);
    }

    this._createMonth({
      year: newYear,
      month: newMonth,
      day: this.state.currentDay
    });
  }

  _createMonth = ({
    year,
    month,
    day
  }) => {
    const elementToReplace = document.getElementById(this.state.id);

    this._setYearVisible(year);
    this._setMonthVisible(month);

    const intlMonths = new Intl.DateTimeFormat('es', {
      month: 'long'
    });

    const monthName = intlMonths.format(new Date(year, month));
    const daysOnMonth = new Date(year, (month + 1), 0).getDate();

    const dayStart = new Date(year, month, 1).getDay();
    const startsOn = dayStart === 0 ? 7 : dayStart;

    const days = [...Array(daysOnMonth).keys()];

    const dateMonth = (month + 1) < 10 ? `0${month + 1}` : (month + 1);

    const renderedDays = days.map((item, index) => {
      const dateDay = (item + 1) < 10 ? `0${item + 1}` : (item + 1);
      const firstDayStyle = index === 0 ? `style="grid-column-start: ${startsOn};"` : ``;
      const currentDayClass = ((item + 1) === day && month === this.state.currentMonth && year === this.state.currentYear) ? `current` : ``;

      const currentDate = `${year}-${dateMonth}-${dateDay}`;
      const currentDateFormat = `${dateDay}/${dateMonth}/${year}`;

      return `
        <li id="cs-calendar-day-${this.state.id}-${currentDate}" ${firstDayStyle}>
          <a
            id="${this.state.id}-${currentDate}-day-container"
            class="${this.state.id}-day-container ${currentDayClass ? `selected` : ``}"
            data-date="${currentDate}"
            data-dateFormat="${currentDateFormat}"
            href="javascript:void(0)"
          >
            <span class="cs-calendar-day-label ${currentDayClass}">${item + 1}</span>

            <div
              id="cs-calendar-reminders-${this.state.id}-${currentDate}"
              class="cs-calendar-reminders cs-calendar-reminders-${this.state.id}"
            ></div>

            <div
              id="cs-calendar-events-${this.state.id}-${currentDate}"
              class="cs-calendar-events cs-calendar-events-${this.state.id}"
            ></div>
          </a>
        </li>
      `;
    }).join('');

    const calendar = `
      <div id="${this.state.id}" class="cs-calendar">
        <div class="cs-calendar-header">
          <a
            id="${this.state.id}-back"
            class="cs-calendar-arrow-icon left"
            href="javascript:void(0)"
          ></a>

          <h1 class="cs-calendar-title">${monthName} ${year}</h1>

          <a
            id="${this.state.id}-next"
            class="cs-calendar-arrow-icon right"
            href="javascript:void(0)"
          ></a>
        </div>

        <ul class="cs-calendar-weeks">
          <li>Lun</li>
          <li>Mar</li>
          <li>Mie</li>
          <li>Jue</li>
          <li>Vie</li>
          <li>Sab</li>
          <li>Dom</li>
        </ul>

        <ul class="cs-calendar-days">
          ${renderedDays}
        </ul>
      </div>
    `;

    elementToReplace.outerHTML = calendar;

    const nextMonth = () => this._createNextMonth();
    const backMonth = () => this._createBackMonth();

    const nextButton = document.getElementById(`${this.state.id}-next`);
    const backButton = document.getElementById(`${this.state.id}-back`);

    nextButton.addEventListener('click', () => {
      nextMonth();
      nextButton.removeEventListener('click', nextMonth);
    });

    backButton.addEventListener('click', () => {
      backMonth();
      nextButton.removeEventListener('click', backMonth);
    });

    if (this.state.reminders.length) this.addReminders(this.state.reminders);
    if (this.state.dateStatus.length) this.addDateStatus(this.state.dateStatus);
    if (this.state.events.length) this.addEvents(this.state.events);

    document.querySelectorAll(`.${this.state.id}-day-container`).forEach(item => {
      item.addEventListener('click', () => {
        const date = item.getAttribute('data-date');
        const dateWithFormat = item.getAttribute('data-dateFormat');
        const events = this.state.monthEvents[date];
        const reminders = this.state.monthReminders[date];
        const dateStatus = this.state.monthDateStatus[date];

        const data = {
          date,
          dateWithFormat,
          events,
          reminders,
          dateStatus
        };

        document.querySelectorAll(`.${this.state.id}-day-container`).forEach(item => item.classList.remove('selected'));
        document.getElementById(`${this.state.id}-${date}-day-container`).classList.add('selected');

        this._onPressDate(data);
      });
    });
  }

  createCalendar = () => this._createMonth({
    year: this.state.currentYear,
    month: this.state.currentMonth,
    day: this.state.currentDay
  });

  cleanReminders = () => {
    this._setReminders([]);
    document.querySelectorAll(`.cs-calendar-reminders-${this.state.id}`).forEach(container => container.innerHTML = '');
  }

  addReminders = reminders => {
    this._setReminders(reminders);
    document.querySelectorAll(`.cs-calendar-reminders-${this.state.id}`).forEach(container => container.innerHTML = '');

    const numReminders = reminders.length;
    const reminderContainers = [];
    let numMonthReminders = 0;
    let monthReminders = [];
    let reminderPosition = 1;

    reminders.map((reminder, index) => {
      const dates = reminder.dates;
      const datesLength = dates.length;
      const title = reminder.title;
      const description = reminder.description;
      const color = reminder.color;
      const data = reminder;
      let searchedReminder = true;
      let searchedReminderPosition = false;

      dates.map((date, dateIndex) => {
        const reminderContainer = document.getElementById(`cs-calendar-reminders-${this.state.id}-${date}`);

        const firstReminderClass = dateIndex === 0 ? `first` : ``;
        const lastReminderClass = (dateIndex + 1) === datesLength ? `last` : ``;

        if (reminderContainer) {
          searchedReminderPosition = true;
          reminderContainer.innerHTML += `<div
            class="cs-calendar-reminder ${firstReminderClass} ${lastReminderClass}"
            style="
              background-color: ${color};
              grid-row-start: ${reminderPosition};
            "></div>
          `;

          //reminderContainer.style = `grid-template-rows: repeat(${numReminders}, 1fr)`;
          reminderContainers.push(reminderContainer);

          if (!monthReminders[date]) monthReminders[date] = [];
          monthReminders[date].push(data);

          if (searchedReminder) {
            searchedReminder = false;
            numMonthReminders = numMonthReminders + 1;
          }
        }
      });

      if (searchedReminderPosition) reminderPosition = reminderPosition + 1;
    });

    reminderContainers.map(container => container.style = `grid-template-rows: repeat(${numMonthReminders}, 1fr)`);
    this._setMonthReminders(monthReminders);
  };

  addDateStatus = dateStatus => {
    this._setDateStatus(dateStatus);
    let monthDateStatus = [];

    dateStatus.map(item => {
      const date = item.date;
      const dateContainer = document.getElementById(`${this.state.id}-${date}-day-container`);
      if (dateContainer) {
        dateContainer.classList.add(item.status);

        //if (!monthDateStatus[date]) monthDateStatus[date] = [];
        monthDateStatus[date] = item.status;
      }
    });

    this._setMonthDateStatus(monthDateStatus);
  }

  addEvents = events => {
    this._setEvents(events);
    let monthEvents = [];

    events.map(item => {
      const date = item.date;
      const title = item.title;
      const data = item.data;

      const eventsContainer = document.getElementById(`cs-calendar-events-${this.state.id}-${date}`);

      if (eventsContainer) {
        eventsContainer.innerHTML += `<div class="cs-calendar-event"></div>`;

        if (!monthEvents[date]) monthEvents[date] = [];
        monthEvents[date].push(data);
      }
    });

    this._setMonthEvents(monthEvents);
  }

  getCalendarDataByDate = date => ({
    date,
    events: this.state.monthEvents[date],
    reminders: this.state.monthReminders[date],
    dateStatus: this.state.monthDateStatus[date]
  });

  setReminders = reminders => this.state.reminders = reminders;
  setDateStatus = dateStatus => this.state.dateStatus = dateStatus;
  setEvents = events => this.state.events = events;

  reloadCalendar = () => this._createMonth({
    year: this.state.year,
    month: this.state.month,
    day: this.state.currentDay
  });

  setCalendarData = ({
    reminders = [],
    dateStatus = [],
    events = []
  }) => {
    this.setReminders(reminders);
    this.setEvents(events);
    this.setDateStatus(dateStatus);

    this.reloadCalendar();
  }

  getYear = () => this.state.year;
  getMonth = () => this.state.month;
}