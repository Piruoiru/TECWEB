/*
---------------------
    FORMS
---------------------
*/

function loadGenericForm(formDetails) {
    for(var key in formDetails){
        var input = document.getElementById(key);
        onEmptyField(input);
        // messaggio(input, 0); se si vuole aggiungere un messaggio di aiuto
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
            this.previousElementSibling.classList.add("nonEmptyFieldLabel");
        }
    }
}

function onEmptyField(input) {
    if(input.value.length != 0){
        input.previousElementSibling.classList.add("nonEmptyFieldLabel");
    }else{
        input.previousElementSibling.classList.remove("nonEmptyFieldLabel");
    }
}
    

function validateField(input, formDetails) {
    var regex = formDetails[input.id][1];
    var text = input.value;

    //tolgo suggerimento o errore precedente
    var inputLabelContainer = input.parentNode;
    if(inputLabelContainer.nextElementSibling !== null && inputLabelContainer.nextElementSibling.classList.contains("errorMessagePar")){
        inputLabelContainer.nextElementSibling.remove();
    }

    if(text.search(regex)!=0){
        input.setCustomValidity(formDetails[input.id][2]);
        messaggio(input, 1, formDetails);
        return false;
    }
    input.setCustomValidity("");

    return true;
}
    
function validateGenericForm(formDetails) {
    for(var key in formDetails){
        var input = document.getElementById(key);
        if(!validateField(input, formDetails)){
            return false;
        }
    }
    return true;
}
    
function messaggio(input, mode, formDetails) {
/* mode = 0, modalità input
   mode = 1, modalità errore */
    var node;//tag con il messaggio
    var inputLabelContainer=input.parentNode;

    if(!mode){
        //creo messaggio di aiuto
        node=document.createElement("p");
        // node.className="default-text";
        node.appendChild(document.createTextNode(formDetails[input.id][0]));
        // inputLabelContainer.insertAdjacentElement('afterend', node);
    }else{
        //creo messaggio di errore
        node=document.createElement("p");
        node.className="errorMessagePar";
        node.appendChild(document.createTextNode(formDetails[input.id][2]));
        inputLabelContainer.insertAdjacentElement('afterend', node);
    }
}

    

/*
---------------------
    LOGIN FORM
---------------------
*/

var loginFormDetails = {
    "username":["", /^./, "Inserisci un username"],
    "password":["", /^./, "Inserisci una password"],
};

/*
---------------------
    REGISTRATION FORM
---------------------
*/
/*
    - chiave: id dell'input del form
    - [0]: Feed forward per la compilazione
    - [1]: Espressione regolare da controllare
    - [2]: Messaggio errore
 */
            
var registrationFormDetails = {
    "nome":["", /^[A-Za-z\u00C0-\u024F\ \']{2,}/, "Inserire un nome composto da almeno due tra lettere, spazi e apostrofi"],
    "cognome":["", /^[A-Za-z\u00C0-\u024F\ \']{2,}/, "Inserire un cognome composto da almeno due tra lettere, spazi e apostrofi"],
    "username":["", /^[A-Za-z0-9_\.\@]{4,20}/, "Inserire un username composto da 4 a 20 caratteri alfanumerici, . o @"],
    "password":["", /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{4,20}/, "Inserire una password composta da 4 a 20 caratteri, di cui almeno una lettera maiuscola, una minuscola e un numero"],
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
        input.setCustomValidity("");
    }
    input.onfocus = function() {
        this.previousElementSibling.classList.add("nonEmptyFieldLabel");
    }

    //Reset password confirmation on password input
    document.getElementById("password").oninput = function() {
        if(!this.firstInput){
            validateField(this, registrationFormDetails);
            //Reset password confirmation
            var passwordConfirmationInput = document.getElementById("passwordConfirmation");
            passwordConfirmationInput.value = "";
            passwordConfirmationInput.setCustomValidity("");
            //Rimozione messaggio errore eventualmente presente
            var inputLabelContainer = passwordConfirmationInput.parentNode;
            if(inputLabelContainer.nextElementSibling !== null && inputLabelContainer.nextElementSibling.classList.contains("errorMessagePar")){
                inputLabelContainer.nextElementSibling.remove();
            }
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
    return genericValidation && usernameValidation && !passwordConfirmationMatch();

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
        messaggio(input, 1, {"passwordConfirmation":["", /^.*/, "Le password non coincidono"]});
        input.setCustomValidity("Le password non coincidono");
        return true;
    }
    input.setCustomValidity("");
    return false;
}

function userExists(input) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            if(this.responseText === "1"){
                messaggio(input, 1, {"username":["", /^.*/, "Username già in uso"]});
                input.setCustomValidity("Username già in uso");
                return true;
            }
            input.setCustomValidity("");
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
    var attractions = document.querySelectorAll('#attractionList li');

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
            attractions.forEach(attraction => {
                // Mostra tutte le attrazioni se la categoria è "tutte"
                if (category === 'tutte' || attraction.getAttribute('data-category') === category) {
                    attraction.style.display = 'flex';
                } else {
                    attraction.style.display = 'none';
                }
            });
        };
    });
};

/*
---------------------
    ADMIN
---------------------
*/

function changeTypeOfAdminControlPanel(type,titolo="",descrizione="",image=""){
    document.getElementById('type').value = type;
    if(type === 'modifica'){
        document.getElementById('vecchioTitoloContainer').style = '';
        document.getElementById('descrizioneContainer').style = '';
        document.getElementById('immagineContainer').style = '';
        document.getElementById('vecchio_titolo').value = titolo;
        document.getElementById('descrizione').value = descrizione;
        document.getElementById('img').value = image;
        document.getElementById('loginButton').value = 'Modifica'; //da cambiare l'id del bottone ovviamente + css
    } else if(type === 'elimina'){
        document.getElementById('vecchioTitoloContainer').style = 'display: none';
        document.getElementById('descrizioneContainer').style = 'display: none';
        document.getElementById('immagineContainer').style = 'display: none';
        document.getElementById('titolo').value = titolo;
        document.getElementById('loginButton').value = 'Elimina';
    } else {
        document.getElementById('descrizioneContainer').style = '';
        document.getElementById('immagineContainer').style = '';
        document.getElementById('titolo').value = '';
        document.getElementById('descrizione').value = '';
        document.getElementById('img') = '';
        document.getElementById('loginButton').value = 'Crea';
    }
}



/*
---------------------
TICKET AND CART PAGE
---------------------
*/

window.onload = function () {
    let nInteri = 0;
    let nRidotti = 0;

    // Seleziona tutti i bottoni dei biglietti e il carrello
    const buttons = document.querySelectorAll('.cardTicket');
    const carrello = document.getElementById('carrelloUL');
    const hiddenInt = document.getElementById("intero");
    const hiddenRid = document.getElementById("ridotto");

    // Funzione per aggiungere biglietti al carrello
    function aggiungiAlCarrello(tipoBiglietto) {
        // Crea un nuovo elemento della lista
        const li = document.createElement('li');
        li.textContent = tipoBiglietto;
        if (tipoBiglietto == "Biglietto Intero 34,99€") {
            nInteri++;
            hiddenInt.setAttribute('value', nInteri);
        }
        else {
            nRidotti++;
            hiddenRid.setAttribute('value', nRidotti);
        }
        // Aggiungi il nuovo elemento alla lista del carrello
        carrello.appendChild(li);
    }

    // Aggiungi un evento "click" a ogni bottone
    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            // Leggi il tipo di biglietto dall'attributo "data-ticket-type"
            const tipoBiglietto = button.getAttribute('data-ticket-type');
            aggiungiAlCarrello(tipoBiglietto);
        });
    });
    /*
    const btnCart = document.getElementById("btnCart");
    
    btnCart.addEventListener('click', () => {
        const backdrop = document.getElementById("backdrop");
        backdrop.style.display = "block";

        let loader = document.querySelector(".loader");
        let output = document.querySelector(".output");
        let riempi = document.querySelector(".riempi");

        loader.style.display = "block";
        loader.style.position = "absolute";
        var n = 0;
        var run = setInterval(frames, 25);
        function frames() {
            n = n + 1;
            if (n == 101) {
                clearInterval(run);
                loader.style.display = "none";
                output.style.display = "block";
                output.style.position = "absolute";
            } else {
                var contatore = document.querySelector(".contatore");
                contatore.textContent = n + "%";
                riempi.style.width = n + "%";
            }
        }
    })*/
}
