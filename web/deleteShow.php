<?php
    include_once 'db.php';
    
    session_start();
    if(!isset($_SESSION['username']) && $_SESSION['username'] !== 'admin'){
        header('Location: index.php');//FIXME: unhautorized?
        exit();
    }

    $title = $_GET['titolo'];
    $db = new DatabaseClient();
    $db->connect();
    $db->deleteShow($title);
    $context = [];
    $context['debugMessage'] = "Lo spettacolo è stato eliminato con successo!";
    $db->close();
    include_once 'header.php';

    include_once 'parser.php';
    $template = new Parser();
    $template->render("deleteShow.html", $context);
?>