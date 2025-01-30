<?php
    session_start();
    include_once 'header.php';
    if(!isset($_SESSION['username'])){
        header('Location: login.php?loginRequest=purchases');
        exit();
    }

    
    include_once 'db.php';
    $db = new DatabaseClient();
    $db->connect();

    $userDetails = $db->fetchUser($_SESSION['username'])->fetch_assoc();
    $context['nome'] = $userDetails['nome'];

    $userOrders = $db->fetchOrdersByUser($_SESSION['username']);
    $context['orders'] = array();
    foreach($userOrders as $order){
        $splitOrderDateTime = explode(" ", $order['dataOrarioOrdine']);
        $orderStdDate = $splitOrderDateTime[0];
        $orderStdTime = $splitOrderDateTime[1];
        $orderDate = explode("-", $splitOrderDateTime[0]);
        $orderYear = $orderDate[0];
        $orderMonth = $orderDate[1];
        $orderDay = $orderDate[2];
        $orderTime = explode(":", $splitOrderDateTime[1]);
        $orderHour = $orderTime[0];
        $orderMinute = $orderTime[1];
        $orderSecond = $orderTime[2];
        array_push($context['orders'],
            ['orderId' => $order['id'],
            'tickets' => $db->fetchTicketsByOrder($order['id']),
            'orderStdDate' => $orderStdDate,
            'orderStdTime' => $orderStdTime, 
            'orderYear' => $orderYear,
            'orderMonth' => $orderMonth,
            'orderDay' => $orderDay,
            'orderHour' => $orderHour,
            'orderMinute' => $orderMinute,
            'orderSecond' => $orderSecond]);
    }

    $context['lastOrder'] = array_shift($context['orders']);

    $db->close();
    include_once 'parser.php';
    $template = new Parser();
    $template->render("purchases.html",$context);
?>