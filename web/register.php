<?php
    session_start();

    if(isset($_SESSION['username'])){
       header('Location: index.php');
       exit();
    }

    include_once 'db.php';
    include_once 'header.php';

    $context['userInfosHeader'] = "";//non mostro i pulsanti login e registrati in alto
    $errorMessage = "";
    $context['registrationErrorMessage'] = "";

    $context['oldName'] = '';
    $context['oldSurname'] = '';
    $context['oldUsername'] = '';

    if(isset($_POST['submit'])){
        // da aggiungere controlli email password
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        $context['oldName'] = $name;
        $context['oldSurname'] = $surname;
        $context['oldUsername'] = $username;

        $pregErrorOccured = false;

        if(!preg_match("/^[A-Za-z\p{L}\ \']{2,}/u", $name)){//TODO: messo \p{L} per accettare anche caratteri accentati dato che non funziona come in JS
            $pregErrorOccured = true;
            $errorMessage .= "<li>Nome non valido</li>";
        }
        if(!preg_match("/^[A-Za-z\p{L}\ \']{2,}/u", $surname)){
            $pregErrorOccured = true;
            $errorMessage .= "<li>Cognome non valido</li>";
        }
        if(!preg_match('/^[A-Za-z0-9_\.\@]{4,20}/u', $username)){
            $pregErrorOccured = true;
            $errorMessage .= "<li>Username non valido</li>";

        }
        if (!preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{4,20}/u', $password)) {
            $pregErrorOccured = true;
            $errorMessage .= "<li>Password non valida</li>";
        }

        $db = new DatabaseClient();
        $db->connect();
        $result = $db->fetchUser($username);

        if($result->num_rows > 0){
            $errorMessage .= '<li>Il nome utente è già in uso</li>';
        }
        
        if(strlen($errorMessage>0)){
            $errorMessage = '<div class="errorMessageBgPar"><p>Si sono verificati alcuni errori durante la registrazione:</p><ul>'.$errorMessage.'</ul></div>';
            $context['registrationErrorMessage'] = $errorMessage;
        }else{
            if($db->register($name, $surname, $username,$password)){
                $_SESSION['username'] = $username;
                $db->close();
                header('Location: index.php');
                exit();
            }else{
                $context['registrationErrorMessage'] = '<p class="errorMessageBgPar">Errore durante la registrazione</p>';
            }
        }
        $db->close();
    }

    include_once 'parser.php';
    $template = new Parser();
    $template->render("register.html", $context);
?>