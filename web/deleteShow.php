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
    
    $context['titolo'] = sanitizeInput($_GET['titolo']);
    $db = new DatabaseClient();
    $db->connect();
    $context['spettacolo'] = $db->fetchShow($_GET['titolo']);
    $db->close();
    if(isset($_POST['submit'])){
        $titolo = $_POST['titolo'];
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