"use strict";

function initCalendar(){
window.orari = [
    { apertura: "10:00", chiusura: "18:00" },  // 0 = Lunedì
    { apertura: "10:00", chiusura: "18:00" },
    { apertura: "10:00", chiusura: "18:00" },
    { apertura: "10:00", chiusura: "18:00" },
    { apertura: "09:00", chiusura: "20:00" },
    { apertura: "09:00", chiusura: "21:30" },
    { apertura: "09:00", chiusura: "21:00" },
    { apertura: "10:00", chiusura: "18:00" }
    ];
  const month = document.getElementById("month");
  const calendar = document.getElementById("calendar");

  const today = new Date();
  let currentMonth = today.getMonth();
  let currentYear = today.getFullYear();

  window.months = [
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

  window.month = month;
  window.calendar = calendar;
  window.today = today;
  window.currentMonth = currentMonth;
  window.currentYear = currentYear;
  buildCalendar();
}

function buildCalendar(){
  calendar.innerHTML = "";
  month.innerHTML = `${months[currentMonth]} ${currentYear}`;

  const dayOne = (new Date(currentYear, currentMonth).getDay()+6)%7;//Primo giorno della settimana del mese selezionato (0 = Lunedì, 6 = Domenica)
  let weekDay = dayOne; //Giorno della settimana corrente (0 = Lunedì, 6 = Domenica)
  const monthDays = 32 - new Date(currentYear, currentMonth, 32).getDate();//Numero di giorni del mese selezionato
  updateCalendarButtons();
  let currentDay = 1;
  for (let i = 0; i < 6; i++) {
    let row = document.createElement("tr");
    let monthCompleted = false;
    for (let j = 0; j < 7; j++) {
      let cell = document.createElement("td");
      if (currentDay > monthDays){
        break;
      }else if (i === 0 && j < dayOne) {
        cell.setAttribute("aria-label", "Giorno del mese precedente");
        row.appendChild(cell);
      } else {
        let columnText = document.createElement("p");
        switch (weekDay) {
            case 0:
                columnText.setAttribute("data-title", "Lunedì");
                break;
            case 1:
                columnText.setAttribute("data-title", "Martedì");
                break;
            case 2:
                columnText.setAttribute("data-title", "Mercoledì");
                break;
            case 3:
                columnText.setAttribute("data-title", "Giovedì");
                break;
            case 4:
                columnText.setAttribute("data-title", "Venerdì");
                break;
            case 5:
                columnText.setAttribute("data-title", "Sabato");
                break;
            case 6:
                columnText.setAttribute("data-title", "Domenica");
                break;
        }

        columnText.textContent = currentDay;
        cell.appendChild(columnText);

        let hoursText = document.createElement("p");
        hoursText.textContent = `${orari[weekDay].apertura} - ${orari[weekDay].chiusura}`;
        cell.appendChild(hoursText);

        if(currentDay === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear()){
          cell.classList.add("today");
          cell.setAttribute("aria-label", "Oggi");
        }
        row.appendChild(cell);

        currentDay++;
        weekDay = (weekDay + 1) % 7;
      }
    }
    if (row.children.length > 0) {
        calendar.appendChild(row);
    }
  }
};

function nextMonth(){
    currentMonth = currentMonth + 1;

    if(currentMonth > 11){
        currentYear = currentYear + 1;
        currentMonth = 0;
    }
    updateCalendarButtons();
    buildCalendar();
    return currentMonth;
};

function prevMonth(){
    currentMonth = currentMonth - 1;
    if(currentMonth < 0){
        currentYear = currentYear - 1;
        currentMonth = 11;
    }
    updateCalendarButtons();
    buildCalendar();

    return currentMonth;
};

function updateCalendarButtons(){
    let previousMonthButton = document.getElementById("previousMonthBtn");
    let nextMonthButton = document.getElementById("nextMonthBtn");
    if(currentYear === today.getFullYear() && currentMonth === today.getMonth()){
        previousMonthButton.disabled=true;
        previousMonthButton.setAttribute("aria-label", "Non è possibile selezionare i mesi precedenti a quello attuale");
        previousMonthButton.classList.add("hiddenCalendarBtn");
        nextMonthButton.setAttribute("title", "Vai al mese successivo a " + `${months[currentMonth]} ${currentYear}`);
    }else{
        previousMonthButton.disabled=false;
        previousMonthButton.setAttribute("title", "Vai al mese precedente a " + `${months[currentMonth]} ${currentYear}`);
        previousMonthButton.classList.remove("hiddenCalendarBtn");
        nextMonthButton.setAttribute("title", "Vai al mese successivo a " + `${months[currentMonth]} ${currentYear}`);
    }
}
/*
---------------------
    FORMS
---------------------
*/

function loadGenericForm(formDetails) {
    for(var key in formDetails){
        var input = document.getElementById(key);
        onEmptyField(input);
        input.firstInput = true;
        input.onblur = function() {
            validateField(this, formDetails);
            onEmptyField(this);
            this.firstInput = false;
        }
        input.oninput = function() {
            if(!this.firstInput){
                validateField(this, formDetails);
            }
        }
        input.onfocus = function() {
            this.previousElementSibling.classList.remove("emptyFieldLabel");
        }
    }
}

function onEmptyField(input) {
    if(input.value.length === 0){
        input.previousElementSibling.classList.add("emptyFieldLabel");
    }else{
        input.previousElementSibling.classList.remove("emptyFieldLabel");
    }
}
    

function validateField(input, formDetails) {
    var regex = formDetails[input.id][0];
    var text = input.value;

    //tolgo errore precedente
    var inputLabelContainer = input.parentNode;
    if(inputLabelContainer.nextElementSibling !== null && inputLabelContainer.nextElementSibling.classList.contains("errorMessagePar")){
        inputLabelContainer.nextElementSibling.remove();
    }

    if(text.search(regex)!=0){
        input.classList.add("invalidInput");
        messaggio(input, formDetails);
        return false;
    }
    input.classList.remove("invalidInput");

    return true;
}
    
function validateGenericForm(formDetails) {
    let validForm = true;
    let firstInvalidInput = true;
    for(var key in formDetails){
        var input = document.getElementById(key);
        if(!validateField(input, formDetails)){
            if(firstInvalidInput){
                input.focus();
                firstInvalidInput = false;
            }
            validForm = false;
        }
    }
    return validForm;
}
    
function messaggio(input, formDetails) {
    var node;
    var inputLabelContainer=input.parentNode;
    node=document.createElement("p");
    node.className="errorMessagePar";
    node.appendChild(document.createTextNode(formDetails[input.id][1]));
    inputLabelContainer.insertAdjacentElement('afterend', node);
}

    

/*
---------------------
    LOGIN FORM
---------------------
*/

var loginFormDetails = {
    "username":[/^./, "Inserisci un username"],
    "password":[/^./, "Inserisci una password"],
};

/*
---------------------
    REGISTRATION FORM
---------------------
*/
/*
    - chiave: id dell'input del form
    - [0]: Espressione regolare da controllare
    - [1]: Messaggio errore
 */
            
var registrationFormDetails = {
    "nome":[/^[A-Za-z\u00C0-\u024F\ \']{2,}/, "Inserire un nome composto da almeno due tra lettere, spazi e apostrofi"],
    "cognome":[/^[A-Za-z\u00C0-\u024F\ \']{2,}/, "Inserire un cognome composto da almeno due tra lettere, spazi e apostrofi"],
    "username":[/^[A-Za-z0-9_\.\@]{4,20}/, "Inserire un username composto da 4 a 20 caratteri alfanumerici, . o @"],
    "password":[/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{4,20}/, "Inserire una password composta da 4 a 20 caratteri, di cui almeno una lettera maiuscola, una minuscola e un numero"],
};

function loadRegistrationForm() {
    loadGenericForm(registrationFormDetails);
    //Password confirmation behaviour
    var input = document.getElementById("passwordConfirmation");
    onEmptyField(input);
    input.onblur = function() {
        onEmptyField(this);
        passwordConfirmationMatch();
    }
    input.oninput = function() {
        var inputLabelContainer = input.parentNode;
        if(inputLabelContainer.nextElementSibling !== null && inputLabelContainer.nextElementSibling.classList.contains("errorMessagePar")){
            inputLabelContainer.nextElementSibling.remove();
        }
        input.classList.remove("invalidInput");
    }
    input.onfocus = function() {
        this.previousElementSibling.classList.remove("emptyFieldLabel");
    }

    document.getElementById("password").oninput = function() {
        if(!this.firstInput){
            validateField(this, registrationFormDetails);
        }
        //Check password confirmation on password input
        if(input.value.length > 0){
            passwordConfirmationMatch();
        }
    }

    //Check if username already exists

    document.getElementById("username").oninput = function() {
        var inputLabelContainer = document.getElementById("username").parentNode;
        if(inputLabelContainer.nextElementSibling !== null && inputLabelContainer.nextElementSibling.classList.contains("errorMessagePar")){
            inputLabelContainer.nextElementSibling.remove();
        }
        if(!this.firstInput){
            if(validateField(this, registrationFormDetails)){
                userExists(this);
            }
        }
    }
    document.getElementById("username").onblur = function() {
        if(validateField(this, registrationFormDetails)){
            userExists(this);
        }
        onEmptyField(this);
        this.firstInput = false;
    }
}

function validateRegistrationForm() {
    var genericValidation = validateGenericForm(registrationFormDetails);
    //Username validation
    var usernameValidation = false;
    var input = document.getElementById("username");
    if(validateField(input, registrationFormDetails) && !userExists(input)){
        usernameValidation = true;
    }
    //Password confirmation validation
    var passwordMatch = passwordConfirmationMatch();
    return genericValidation && usernameValidation && passwordMatch;

}

function passwordConfirmationMatch() {
    var input = document.getElementById("passwordConfirmation");
    //Rimozione messaggio password non coincidenti
    var inputLabelContainer = input.parentNode;
    if(inputLabelContainer.nextElementSibling !== null && inputLabelContainer.nextElementSibling.classList.contains("errorMessagePar")){
        inputLabelContainer.nextElementSibling.remove();
    }
    if(document.getElementById("password").value !== input.value){
        //Messaggio password non coincidenti
        messaggio(input, {"passwordConfirmation":[/^.*/, "Le password non coincidono"]});
        input.classList.add("invalidInput");
        return false;
    }
    input.classList.remove("invalidInput");
    return true;
}

function userExists(input) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            if(this.responseText === "1"){
                messaggio(input, {"username":[/^.*/, "Username già in uso"]});
                input.classList.add("invalidInput");
                return true;
            }
            input.classList.remove("invalidInput");
            return false;
       }
    };
    xhttp.open("GET", "checkIfUserExists.php?username="+input.value, true);
    xhttp.send();
}

/*
---------------------
    EDIT PROFILE FORM
---------------------
*/
var editUserFormDetails = {
    "nome":["", /^[A-Za-z\u00C0-\u024F\ \']{2,}/, "Inserire un nome composto da almeno due tra lettere, spazi e apostrofi"],
    "cognome":["", /^[A-Za-z\u00C0-\u024F\ \']{2,}/, "Inserire un cognome composto da almeno due tra lettere, spazi e apostrofi"],
    "username":["", /^[A-Za-z0-9_\.\@]{4,20}/, "Inserire un username composto da 4 a 20 caratteri alfanumerici, . o @"],
};

/*
---------------------
    MAP 
---------------------
*/

function loadRidesFilter() {
    var filterButtons = document.querySelectorAll('#filtersContainer button');
    var rides = document.querySelectorAll('#rideList li');

    filterButtons.forEach(clickedButton => {
        clickedButton.onclick = () => {
            var category = clickedButton.getAttribute('data-category');
            var filterButtons = document.querySelectorAll('#filtersContainer button');
            //Disabilita il bottone cliccato e cambia lo stile di tutti
            filterButtons.forEach(btn => {
                if(clickedButton===btn){
                    btn.disabled = true;
                    btn.classList.add(btn.id + 'Selected');
                }else{
                    btn.disabled = false;
                    btn.classList.remove(btn.id + 'Selected');
                }
            });

            //Mostra solo le attrazioni della categoria selezionata
            rides.forEach(ride => {
                // Mostra tutte le attrazioni se la categoria è "tutte"
                if (category === 'tutte' || ride.getAttribute('data-category') === category) {
                    ride.classList.remove('hiddenRide');
                } else {
                    ride.classList.add('hiddenRide');
                }
            });
        };
    });
};

/*
---------------------
    ADMIN MODIFICA ED ELIMINAZIONE
---------------------
*/
var createEditShowFormDetails = {
    "titolo":["", /^[A-Za-z.,;']+/, "Inserisci un titolo"],
    "descrizione":["", /^[A-Za-z.,;']+/, "Inserisci una descrizione"],
    "immagine": ["", /^.+/, "Inserisci un'immagine"],
    "descrizione_immagine":["", /^[A-Za-z.,;']+/, "Inserisci una breve descrizione dell'immagine"],
};

var editShowFormDetails = {
    "nuovo_titolo":["", /^[A-Za-z.,;']+/, "Inserisci un titolo"],
    "descrizione":["", /^[A-Za-z.,;']+/, "Inserisci una descrizione"],
    "immagine": ["", /^.+/, "Inserisci un'immagine"],
    "descrizione_immagine":["", /^[A-Za-z.,;']+/, "Inserisci una breve descrizione dell'immagine"],
};



/*
---------------------
    TICKET AND CART PAGE
---------------------
*/

function loadTicketAndCart(){
    let nInteri = 0;
    let nRidotti = 0;

    // Seleziona tutti i bottoni dei biglietti e il carrello
    const buttons = document.querySelectorAll('.btnTicket');
    const hiddenInt = document.getElementById("intero");
    const hiddenRid = document.getElementById("ridotto");

    // Funzione per aggiungere biglietti al carrello
    function aggiungiAlCarrello(tipoBiglietto, operation) {
        // Crea un nuovo elemento della lista
        if (tipoBiglietto == "Biglietto Intero") {
            const li = document.getElementById('rowInt');
            if (operation == "add") {
                nInteri++;
            }
            if (operation == "rmv"){
                nInteri--;
                if(nInteri <= 0){
                    nInteri = 0;
                    li.textContent = "";
                    hiddenInt.setAttribute('value', nInteri);
                    return;
                }
            }
            li.textContent = tipoBiglietto + " x" + nInteri;
            hiddenInt.setAttribute('value', nInteri);
        }
        else {
            const li = document.getElementById('rowRid');
            if (operation == "add"){
                nRidotti++;
            }
            if (operation == "rmv"){
                nRidotti--;
                if(nRidotti <= 0){
                    nRidotti = 0;
                    li.textContent = "";
                    hiddenRid.setAttribute('value', nRidotti);
                    return;
                }
            }
            li.textContent = tipoBiglietto + " x" + nRidotti;
            hiddenRid.setAttribute('value', nRidotti);
        }
    }

    // Aggiungi un evento "click" a ogni bottone
    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            // Leggi il tipo di biglietto dall'attributo "data-ticket-type"
            const tipoBiglietto = button.getAttribute('data-ticket-type');
            const operation = button.getAttribute('operation');
            aggiungiAlCarrello(tipoBiglietto, operation);
        });
    });

    const btnCart = document.getElementById("btnCart");

    btnCart.addEventListener('click', () => {
        if(document.getElementById("carrelloUL").innerHTML.trim() != ""){
            const backdrop = document.getElementById("backdrop");
            backdrop.style.display = "block";
    
            let output = document.querySelector(".output");
    
            output.style.display = "block";
            output.style.position = "absolute";
        }
    })
}

/*
---------------------
HOMEPAGE
---------------------
*/
function toggleMobileNav(){
    document.getElementById("menuUl").classList.toggle("visible");
    document.getElementById("closeIcon").classList.toggle("visible");
    document.getElementById("hamburgerIcon").classList.toggle("visible");
}

/*
---------------------
PULSANTE TORNA SU
---------------------
*/
function scrollUp(){
    document.documentElement.scrollTo({
        top: 0,
        behavior: "smooth"
    });
}