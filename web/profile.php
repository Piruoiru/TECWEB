<?php
    include_once 'header.php';
    unset($context['headerBtns']['profile']);
    if(!isset($_SESSION['username'])){
        header('Location: login.php'); //TODO: da rimpiazzare con 401 non autorizzato o 403
        exit();
    }

    
    include_once 'db.php';
    $db = new DatabaseClient();
    $db->connect();

    $userDetails = $db->fetchUser($_SESSION['username'])->fetch_assoc();
    $context['nome'] = $userDetails['nome'];
    $context['cognome'] = $userDetails['cognome'];
    $context['username'] = $userDetails['username'];


    $context['lastOrder'] = $db->fetchUserLastOrderTickets($_SESSION['username']);

    $db->close();


    include_once 'parser.php';
    $template = new Parser();
    $template->render("profile.html",$context);
?>