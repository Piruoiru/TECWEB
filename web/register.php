<?php
    session_start();
    include_once 'header.php';
    if(isset($_SESSION['username'])){
       header('Location: index.php');
       exit();
    }

    function sanitizeInput($input){
        $sanitizedInput = trim($input);
        $sanitizedInput = htmlspecialchars($input);
        return $sanitizedInput;
    }

    include_once 'db.php';

    unset($context['headerBtns']['register']);//non mostro il pulsante registrati in alto
    unset($context['headerBtns']['login']);
    unset($context['essentialHeaderBtns']['login']);
    $errorMessage = "";
    $context['registrationErrorMessage'] = array();

    $context['oldName'] = '';
    $context['oldSurname'] = '';
    $context['oldUsername'] = '';

    function validate($name,$surname,$username,$password, $confirm_password){
        global $context;
        $isValid = true;

        if(!preg_match("/^[A-Za-z\p{L}\ \']{2,}/u", $name)){
            $isValid = false;
            array_push($context['registrationErrorMessage'], "Inserire un nome composto da almeno due tra lettere, spazi e apostrofi");
        }
        if(!preg_match("/^[A-Za-z\p{L}\ \']{2,}/u", $surname)){
            $isValid = false;
            array_push($context['registrationErrorMessage'], "Inserire un cognome composto da almeno due tra lettere, spazi e apostrofi");
        }
        if(!preg_match('/^[A-Za-z0-9_\.\@]{4,20}/u', $username)){
            $isValid = false;
            array_push($context['registrationErrorMessage'], "Inserire un nome utente composto da 4 a 20 caratteri alfanumerici, . o @");
        }
        if (!preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{4,20}/u', $password)) {
            $isValid = false;
            array_push($context['registrationErrorMessage'], "Inserire una password composta da 4 a 20 caratteri, di cui almeno una lettera maiuscola, una minuscola e un numero");
        }
        if ($password !== $confirm_password) {
            $isValid = false;
            array_push($context['registrationErrorMessage'], "Le due password non corrispondono");  
        }

        return $isValid;
    }

    if(isset($_POST['submit'])){
        $name = sanitizeInput($_POST['name']);
        $surname = sanitizeInput($_POST['surname']);
        $username = sanitizeInput($_POST['username']);
        $password = sanitizeInput($_POST['password']);
        $confirm_password = sanitizeInput($_POST['confirm_password']);

        $context['oldName'] = $name;
        $context['oldSurname'] = $surname;
        $context['oldUsername'] = $username;
        
        if(validate($name,$surname,$username,$password, $confirm_password)){
            $db = new DatabaseClient();
            $db->connect();
            $result = $db->fetchUser($username);
    
            if($result->num_rows > 0){
                array_push($context['registrationErrorMessage'], "Username già in uso");
            }
            
            if(empty($context['registrationErrorMessage'])){
                if($db->register($name, $surname, $username,$password)){
                    $_SESSION['username'] = $username;
                    $db->close();
                    header('Location: index.php');
                    exit();
                }else{
                    array_push($context['registrationErrorMessage'],'Errore generico durante la registrazione');
                }
            }
            $db->close();
        }
    }

    include_once 'parser.php';
    $template = new Parser();
    $template->render("register.html", $context);
?>