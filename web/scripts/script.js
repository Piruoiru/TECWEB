"use strict";

function initCalendar(){
  const month = document.getElementById("month");
  const calendar = document.getElementById("calendar");

  const DATE = new Date();
  let thisMonth = DATE.getMonth();
  let year = DATE.getFullYear();

  const MONTHS = [
    "Gennaio",
    "Febbraio",
    "Marzo",
    "Aprile",
    "Maggio",
    "Giugno",
    "Luglio",
    "Agosto",
    "Settembre",
    "Ottobre",
    "Novembre",
    "Dicembre",
  ];

  // Array con i giorni di chiusura del parco (in formato [giorno, mese, anno])
  const closedDays = [
    { day: 25, month: 12, year: 2024 },  // Natale 2024
    { day: 1, month: 1, year: 2025 },    // Capodanno 2025
    { day: 6, month: 1, year: 2025 }    // Epifania 2025
    // Aggiungi altri giorni di chiusura se necessario
  ];

  window.month = month;
  window.calendar = calendar;
  window.DATE = DATE;
  window.thisMonth = thisMonth;
  window.year = year; 
  window.MONTHS = MONTHS;
  window.closedDays = closedDays;
  buildCalendar();
}

function buildCalendar(){
  month.innerHTML = `${MONTHS[thisMonth]} ${year}`;

  const dayOne = (new Date(year, thisMonth).getDay()+6)%7;
  const monthDays = 32 - new Date(year, thisMonth, 32).getDate();

  let date = 1;
  for (let i = 0; i < 6; i++) {
    let row = document.createElement("tr");
    for (let j = 0; j < 7; j++) {
      let cell = document.createElement("td");
      if (date > monthDays) break;
      else if (i === 0 && j < dayOne) {
        let cellText = document.createTextNode("");
        cell.appendChild(cellText);
        row.appendChild(cell);
      } else {
        let columnText = document.createTextNode(date);
        cell.appendChild(columnText);

        let hoursText = document.createElement("p");
        hoursText.textContent = "9:00 - 18:00";
        cell.appendChild(hoursText);

        if(date === DATE.getDate() && thisMonth === DATE.getMonth() && year === DATE.getFullYear()){
          cell.classList.add("today")
        }

        let cellDay = `${date}`;
        let cellMonth = `${thisMonth + 1}`;
        let cellYear = `${year}`;


        cell.onclick = () => dateSelected(cellDay, cellMonth, cellYear);

        row.appendChild(cell);

        // Aggiungi tabIndex per navigazione
        // column.setAttribute("tabindex", "0");
        // column.setAttribute("role", "button");
        // column.setAttribute("aria-label", `Giorno ${date}`);

        date++;
      }
    }
    calendar.appendChild(row);
  }
};

function dateSelected(date, month, year){
  // Verifica se la data selezionata è un giorno di chiusura
  let isClosed = closedDays.some(day => day.day === parseInt(date) && day.month === parseInt(month) && day.year === parseInt(year));

  const closedParkCalendar = window.closedParkCalendar;

  if (isClosed) {
    closedParkCalendar.textContent = "Il parco è chiuso in questa data, ci dispiace!";
  } else {
    closedParkCalendar.textContent = "Il parco è aperto, Buona visita!";
  }

//   alert(`${date}/${month}/${year}`);
}

const nextMonth = () => {
  thisMonth = thisMonth + 1;
  calendar.innerHTML = "";

  if(thisMonth > 11){
    year = year + 1;
    thisMonth = 0;
  }
  buildCalendar();
  return thisMonth;
};

const prevMonth = () => {
  thisMonth = thisMonth - 1;
  calendar.innerHTML = "";

  if(thisMonth < 0){
    year = year - 1;
    thisMonth = 11;
  }
  buildCalendar();
  return thisMonth;
};