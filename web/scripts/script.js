/*
---------------------
    FORMS
---------------------
*/

function loadForm(formDetails) {
    for(var key in formDetails){
        var input = document.getElementById(key);
        onEmptyField(input);
        // messaggio(input, 0);
        input.onblur = function() {
            validateField(this, formDetails);
            onEmptyField(this);

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

    // if(text.length == 0){
    //     input.classList.toggle("emptyField", true);
    // }else{
    //     input.classList.toggle("emptyField", false);
    // }

    if(text.search(regex)!=0){//casi in cui match non è stato trovato proprio o all'inizio
        input.setCustomValidity(formDetails[input.id][2]);
        messaggio(input, 1);
        // input.focus();
        // input.select();//Seleziona tutto il testo. Si usa se l'input è corto ed è probabile che l'utente riscriva tutto. Aiuta chi usa input vocale
        return false;//non invia form
    }
    input.setCustomValidity("");
    return true;
}
    
function validateForm(formDetails) {
    for(var key in formDetails){
        var input = document.getElementById(key);
        if(!validateField(input, formDetails)){
            return false;
        }
    }
    return true;
}
    
function messaggio(input, mode) {
/* mode = 0, modalità input
   mode = 1, modalità errore */
    var node;//tag con il messaggio
    var inputLabelContainer=input.parentNode;

    if(!mode){
        //creo messaggio di aiuto
        node=document.createElement("p");
        // node.className="default-text";
        node.appendChild(document.createTextNode(registrationFormDetails[input.id][0]));
        // inputLabelContainer.insertAdjacentElement('afterend', node);
    }else{
        //creo messaggio di errore
        node=document.createElement("p");
        node.className="errorMessagePar";
        node.appendChild(document.createTextNode(registrationFormDetails[input.id][2]));
        inputLabelContainer.insertAdjacentElement('afterend', node);
    }
}

    

/*
---------------------
    LOGIN FORM
---------------------
*/

var loginFormDetails = {
    "username":["Ex: MarioRossi", /^.*/, ""],
    "password":["Ex: 1234abcd", /^.*/, ""],
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
    "nome":["Ex: Mario", /^[A-za-z\u00C0-\u024F\ \']{2,}/, "Inserire un nome composto da almeno due tra lettere, spazi e apostrofi"],
    "cognome":["Ex: Rossi", /^[A-za-z\u00C0-\u024F\ \']{2,}/, "Inserire un cognome composto da almeno due tra lettere, spazi e apostrofi"],
    "username":["Ex: MarioRossi", /^[A-Za-z0-9]{5,}/, "Inserire un username composto da almeno 5 caratteri alfanumerici"], //FIXME: generato da copilot
    "password":["Ex: 1234abcd", /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,}/, "Inserire una password composta da almeno 8 caratteri, di cui almeno una lettera maiuscola, una minuscola e un numero"], //FIXME: generato da copilot
    "passwordConfirm":["Ex: 1234abcd", /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,}/, "Inserire una password composta da almeno 8 caratteri, di cui almeno una lettera maiuscola, una minuscola e un numero"], //FIXME: generato da copilot
};