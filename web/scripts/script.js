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