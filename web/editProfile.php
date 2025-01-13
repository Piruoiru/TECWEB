<?php
    include_once 'header.php';

    include_once 'db.php';

    if(!isset($_SESSION['username'])){
        header('Location: index.php');//FIXME: unhautorized?
        exit();
    }

    $db = new DatabaseClient();
    $db->connect();
    $userData = $db->fetchUser($_SESSION['username'])->fetch_assoc();
    $db->close();

    $context['userFirstName'] = $userData['nome'];
    $context['userSurname'] = $userData['cognome'];
    $context['username'] = $_SESSION['username'];

    $errorMessage = "";
    $context['updateErrorMessage'] = array();

    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $surname = $_POST['surname'];

        if(!preg_match("/^[A-Za-z\p{L}\ \']{2,}/u", $name)){//messo \p{L} per accettare anche caratteri accentati dato che non funziona come in JS
            $pregErrorOccured = true;
            array_push($context['updateErrorMessage'], "Nome non valido");
        }
        if(!preg_match("/^[A-Za-z\p{L}\ \']{2,}/u", $surname)){
            $pregErrorOccured = true;
            array_push($context['updateErrorMessage'], "Cognome non valido");
        }
        
        if(!empty($context['updateErrorMessage'])){
            // $errorMessage = '<div class="errorMessageBgPar"><p>Si sono verificati alcuni errori durante la modifica dei dati:</p><ul>'.$errorMessage.'</ul></div>';
            // $context['updateErrorMessage'] = $errorMessage;
            $context['userFirstName'] = $name;
            $context['userSurname'] = $surname;
        }else{
            $db->connect();
            if($db->updateUserDetails($name, $surname, $_SESSION['username'])){
                $context['updateSuccessMessage'] = 'Dati aggiornati con successo';
                $context['userFirstName'] = $name;
                $context['userSurname'] = $surname;
            }else{
                $context['updateErrorMessage'] = 'Errore interno durante la modifica';//FIXME: pagina errore 500?
            }
            $db->close();
        }
    }

    include_once 'parser.php';
    $template = new Parser();
    $template->render("editProfile.html", $context);
?>