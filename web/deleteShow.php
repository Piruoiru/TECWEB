<?php
    include_once 'db.php';
    include_once 'header.php';

    session_start();
    if(!isset($_SESSION['username']) && $_SESSION['username'] !== 'admin'){
        header('Location: 401.php');
        exit();
    }

    $context['titolo'] = htmlspecialchars($_GET['titolo'],ENT_QUOTES);
    $db = new DatabaseClient();
    if(isset($_POST['submit'])){
        $titolo = $_POST['titolo'];
        $db->connect();
        if($db->deleteShow($titolo)){
            $context['deletionInfoMessage'] = "Lo spettacolo è stato eliminato con successo!";
        } else {
            $context['deletionErrorMessage'] = "Si è verificato un errore nella cancellazione dello spettacolo.";
        }
        $db->close();
    }
    include_once 'parser.php';
    $template = new Parser();
    $template->render("deleteShow.html", $context);
?>