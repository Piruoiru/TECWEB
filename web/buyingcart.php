<?php
    session_start();

    if(!isset($_SESSION['username'])){
        header('Location: /login.php'); //da fixare o decidere se dare 401 o 403
    }

    $debugMsg = '';
    include_once 'db.php';
    $db = new DatabaseClient();
    $db->connect();

    if(isset($_POST['submit'])){
        $db->confirmPayment($_SESSION['username']);
        $debugMsg = 'Pagamento avvenuto con successo!';
    }
    
    $result = $db->fetchCart($_SESSION['username']);
    $context = ['result'=>$result,'debugMsg'=>$debugMsg];

    include_once 'parser.php';
    $template = new Parser();
    $template->render("buyingcart.html",$context);
?>