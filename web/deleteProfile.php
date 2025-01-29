<?php
    include_once 'db.php';
    
    session_start();
    if(!isset($_SESSION['username'])){
        header('Location: login.php?loginRequest');
        exit();
    }

    $db = new DatabaseClient();
    $db->connect();
    if(!($_SESSION['username']=='admin')){
        $deletionSuccess = $db->deleteUser($_SESSION['username']);
        $db->close();

        if($deletionSuccess){
            session_unset();
            session_destroy();
        }else{
            $context['deletionErrorMessage'] = 'Errore interno durante la cancellazione';
        }
    }else{
        $context['deletionErrorMessage'] = "Impossibile eliminare l'utente amministratore";
    }
    include_once 'header.php';

    include_once 'parser.php';
    $template = new Parser();
    $template->render("deleteProfile.html", $context);
?>