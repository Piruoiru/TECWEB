<?php
    session_start();
    include_once 'header.php';
    if(isset($_SESSION['username'])){
       header('Location: index.php');
       exit();
    }

    include_once 'db.php';

    unset($context['headerBtns']['register']);//non mostro il pulsante registrati in alto
    $context['essentialHeaderBtns']['login']['text'] = 'Accedi';//sostituisco  accedi o registrati nel menù mobile con accedi
    $errorMessage = "";
    $context['registrationErrorMessage'] = array();

    $context['oldName'] = '';
    $context['oldSurname'] = '';
    $context['oldUsername'] = '';

    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        $context['oldName'] = $name;
        $context['oldSurname'] = $surname;
        $context['oldUsername'] = $username;

        $pregErrorOccured = false;

        if(!preg_match("/^[A-Za-z\p{L}\ \']{2,}/u", $name)){//messo \p{L} per accettare anche caratteri accentati dato che non funziona come in JS
            $pregErrorOccured = true;
            array_push($context['registrationErrorMessage'], "Nome non valido");
        }
        if(!preg_match("/^[A-Za-z\p{L}\ \']{2,}/u", $surname)){
            $pregErrorOccured = true;
            array_push($context['registrationErrorMessage'], "Cognome non valido");
        }
        if(!preg_match('/^[A-Za-z0-9_\.\@]{4,20}/u', $username)){
            $pregErrorOccured = true;
            array_push($context['registrationErrorMessage'], "Username non valido");

        }
        if (!preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{4,20}/u', $password)) {
            $pregErrorOccured = true;
            array_push($context['registrationErrorMessage'], "Password non valida");
        }

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
                $context['registrationErrorMessage'] = 'Errore generico durante la registrazione';
            }
        }
        $db->close();
    }

    include_once 'parser.php';
    $template = new Parser();
    $template->render("register.html", $context);
?>