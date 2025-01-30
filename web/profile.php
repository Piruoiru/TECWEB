<?php
    session_start();
    include_once 'header.php';
    unset($context['headerBtns']['profile']);
    // if(!isset($_SESSION['username'])){
    //     header('Location: login.php?loginRequest=profile');
    //     exit();
    // }

    
    include_once 'db.php';
    $db = new DatabaseClient();
    $db->connect();

    $userDetails = $db->fetchUser($_SESSION['username'])->fetch_assoc();
    $context['nome'] = $userDetails['nome'];
    $context['cognome'] = $userDetails['cognome'];
    $context['username'] = $userDetails['username'];


    $context['lastOrder'] = $db->fetchUserLastOrderTickets($_SESSION['username']);
    $db->close();
    if(!empty($context['lastOrder'])){
        $context['orderNumber'] = $context['lastOrder'][0]['idOrdine'];
        $splitOrderDateTime = explode(" ", $context['lastOrder'][0]['dataOrarioOrdine']);
        $context['orderStdDate'] = $splitOrderDateTime[0];
        $context['orderStdTime'] = $splitOrderDateTime[1];
        $orderDate = explode("-", $splitOrderDateTime[0]);
        $context['orderYear'] = $orderDate[0];
        $context['orderMonth'] = $orderDate[1];
        $context['orderDay'] = $orderDate[2];
        $orderTime = explode(":", $splitOrderDateTime[1]);
        $context['orderHour'] = $orderTime[0];
        $context['orderMinute'] = $orderTime[1];
        $context['orderSecond'] = $orderTime[2];
    }

    include_once 'parser.php';
    $template = new Parser();
    $template->render("profile.html",$context);
?>