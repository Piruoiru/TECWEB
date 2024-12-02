<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header('Location: login.php'); //da rimpiazzare con 401 non autorizzato o 403
        exit();
    }
    
    include_once 'db.php';
    $db = new DatabaseClient();
    $db->connect();

    $cart = $db->fetchUserCartTickets($_SESSION['username']);
    $acquired = $db->fetchUserAcquiredTickets($_SESSION['username']);
    $context = ['cart'=>$cart,'acquired'=>$acquired,'username'=>$_SESSION['username']];

    include_once 'parser.php';
    $template = new Parser();
    $template->render("profilo.html",$context);
?>