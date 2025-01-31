<?php
    include_once 'db.php';
    include_once 'header.php';

    session_start();
    if(!isset($_SESSION['username']) && $_SESSION['username'] !== 'admin'){
        header('Location: 401.php');
        exit();
    }

    function sanitizeInput($input){
        $sanitizedInput = trim($input);
        $sanitizedInput = htmlspecialchars($sanitizedInput,ENT_QUOTES);
        return $sanitizedInput;
    }
    
    $titolo = sanitizeInput($_GET['titolo']);
    $context['titolo'] = $titolo;
    $db = new DatabaseClient();
    if(isset($_POST['submit'])){
        $db->connect();
        if(empty($db->fetchShow($titolo))){
            $context['deletionInfoMessage'] = "Si sta provando a eliminare uno spettacolo che non esiste";
        } else {
            $db->connect();
            if($db->deleteShow($titolo)){
                $context['deletionInfoMessage'] = "Lo spettacolo è stato eliminato con successo!";
            } else {
                $context['deletionErrorMessage'] = "Si è verificato un errore nella cancellazione dello spettacolo.";
            }
            $db->close();
        }
    }
    include_once 'parser.php';
    $template = new Parser();
    $template->render("deleteShow.html", $context);
?>