<?php
    include_once 'db.php';
    include_once 'header.php';

    session_start();
    if(!isset($_SESSION['username']) && $_SESSION['username'] !== 'admin'){
        header('Location: 401.php');
        exit();
    }

    $title = $_GET['titolo'];
    $db = new DatabaseClient();
    $db->connect();
    if($db->deleteShow($title)){
        $context['debugMessage'] = "Lo spettacolo è stato eliminato con successo!";
    } else {
        $context['debugMessage'] = "Si è verificato un errore nella cancellazione dello spettacolo.";
    }
    $db->close();
    include_once 'parser.php';
    $template = new Parser();
    $template->render("deleteShow.html", $context);
?>