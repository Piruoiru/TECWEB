<?php
    session_start();

    if(!isset($_SESSION['username'])){
        header('Location: /login.php'); //da fixare o decidere se dare 401 o 403
    }

    include_once 'parser.php';
    include_once 'db.php';

    $debugMsg = '';
    $db = new DatabaseClient();
    $db->connect();

    if(isset($_POST['submit'])){
        $db->addTicketToCart($_POST['biglietto'],$_SESSION['username'],$_POST['quantita']);
        $debugMsg = 'Operazione avvenuta con successo!';
    }

    
    $result = $db->fetchTickets();
    $context = ['result'=>$result,'debugMsg'=>$debugMsg];

    $template = new Parser();
    $template->render("buyingticket.html",$context);
?>