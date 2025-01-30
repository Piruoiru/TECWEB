<?php
    include_once 'db.php';
    
    session_start();
    if(!isset($_SESSION['username'])){
        header('Location: login.php?loginRequest');
        exit();
    }


    if(strcmp($_SESSION['username'],'admin')==0){
        $context['deletionErrorMessage'] = "Impossibile eliminare l'utente amministratore";
        $context['admin'] = true;
    }else if(isset($_POST['submit'])){
        if(!preg_match('/^[A-Za-z0-9_\.\@]{4,20}/u', $_POST['username'])){
        $context['registrationErrorMessage'] = "Inserire un nome utente composto da 4 a 20 caratteri alfanumerici, . o @";
        }
        if(strcmp($_SESSION['username'],$_POST['username'])==0){
            $db = new DatabaseClient();
            $db->connect();
            $deletionSuccess = true;
            $deletionSuccess = $db->deleteUser($_SESSION['username']);
            $db->close();
            if($deletionSuccess){
                $context['deletionSuccess'] = 'true';
                session_unset();
                session_destroy();
            } else{
                $context['deletionErrorMessage'] = 'Errore interno durante la cancellazione';
            }
        }else{
            $context['deletionErrorMessage'] = 'Username digitato errato';
        }
    }
    include_once 'header.php';

    include_once 'parser.php';
    $template = new Parser();
    $template->render("deleteProfile.html", $context);
?>