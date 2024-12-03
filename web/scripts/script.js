// script.js
document.addEventListener('DOMContentLoaded', () => {
  const monthYearElement = document.getElementById('monthYear');
  const calendarGrid = document.getElementById('calendarGrid');
  const prevMonthButton = document.getElementById('prevMonth');
  const nextMonthButton = document.getElementById('nextMonth');
  const selectedDateText = document.getElementById('selectedDateText');

  let currentDate = new Date();
  let selectedDate = null;

  function renderCalendar() {
      calendarGrid.innerHTML = '';

      const year = currentDate.getFullYear();
      const month = currentDate.getMonth();
      const firstDayOfMonth = new Date(year, month, 1).getDay();
      const daysInMonth = new Date(year, month + 1, 0).getDate();

      // Aggiorna il titolo del mese e anno
      monthYearElement.textContent = currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });

      // Celle vuote per i giorni prima del primo del mese
      for (let i = 0; i < firstDayOfMonth; i++) {
          const emptyCell = document.createElement('div');
          calendarGrid.appendChild(emptyCell);
      }

      // Celle per i giorni del mese
      for (let day = 1; day <= daysInMonth; day++) {
          const dayElement = document.createElement('div');
          dayElement.textContent = day;
          dayElement.tabIndex = 0;
          dayElement.setAttribute('role', 'button');
          dayElement.setAttribute('aria-label', `Giorno ${day} ${monthYearElement.textContent}`);

          const today = new Date();
          if (
              day === today.getDate() &&
              month === today.getMonth() &&
              year === today.getFullYear()
          ) {
              dayElement.classList.add('today');
              dayElement.setAttribute('aria-current', 'date');
          }

          // Evidenzia la data selezionata
          if (
              selectedDate &&
              day === selectedDate.getDate() &&
              month === selectedDate.getMonth() &&
              year === selectedDate.getFullYear()
          ) {
              dayElement.classList.add('selected');
          }

          // Aggiungi evento click e tastiera
          dayElement.addEventListener('click', () => handleDateSelection(year, month, day));
          dayElement.addEventListener('keydown', (e) => {
              if (e.key === 'Enter' || e.key === ' ') {
                  handleDateSelection(year, month, day);
              }
          });

          calendarGrid.appendChild(dayElement);
      }
  }

  function handleDateSelection(year, month, day) {
      selectedDate = new Date(year, month, day);
      selectedDateText.textContent = `Data selezionata: ${selectedDate.toLocaleDateString('it-IT', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}`;
      renderCalendar();
  }

  // Navigazione mesi
  prevMonthButton.addEventListener('click', () => {
      currentDate.setMonth(currentDate.getMonth() - 1);
      renderCalendar();
  });

  nextMonthButton.addEventListener('click', () => {
      currentDate.setMonth(currentDate.getMonth() + 1);
      renderCalendar();
  });

  renderCalendar();
});
