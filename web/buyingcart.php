<?php
    session_start();
    include_once 'parser.php';
    
    if(/*!*/isset($_SESSION['username'])){
        header('Location: index.php'); //da fixare o decidere se dare 401 o 403
        exit();   
    }
    
    $debugMsg = '';
    include_once 'db.php';
    include_once 'header.php';

    $db = new DatabaseClient();
    $db->connect();

    $risultato = $db->fetchCart('user');
    foreach($risultato as $biglietti){
        if($biglietti['biglietto'] == "1"){
            $ticket = "Biglieto Ridotto";
        }
        else{
            $ticket = "Biglieto Intero";
        }
        $context['cart'] .= "<li>" . $ticket . " x" .  $biglietti['quantita'] . "</li>";
    }

    /*
    if(isset($_POST['submit'])){
        $db->confirmPayment($_SESSION['username']);
        $debugMsg = 'Pagamento avvenuto con successo!';
    }*/

    /* 
    $result = $db->fetchCart($_SESSION['username']);
    $context = ['result'=>$result,'debugMsg'=>$debugMsg];
    */
    
    
    $template = new Parser();
    $template->render('buycart.html',$context);
?>
