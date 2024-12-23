"use strict";
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
  alert(`${date}/${month}/${year}`);
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