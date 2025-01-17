<?php
    session_start();
    include_once 'parser.php';
    
    if(!isset($_SESSION['username'])){
        header('Location: index.php'); //da fixare o decidere se dare 401 o 403
        exit();   
    }
    
    include_once 'db.php';
    include_once 'header.php';
    $prezzoInt = 34.99;
    $prezzoRid = 24.99;

    $db = new DatabaseClient();
    $db->connect();
    

    if(isset($_POST['addTrid'])){
        $operation = 'add';
        $db->updateCart('1',$_SESSION['username'],$operation);
    }
    
    if(isset($_POST['rmvTrid'])){
        $operation = 'rmv';
        $db->updateCart('1',$_SESSION['username'],$operation);
    }

    if(isset($_POST['rmvTint'])){
        $operation = 'rmv';
        $db->updateCart('2',$_SESSION['username'],$operation);
    }

    if(isset($_POST['addTint'])){
        $operation = 'add';
        $db->updateCart('2',$_SESSION['username'],$operation);
    }

    $risultato = $db->fetchCart($_SESSION['username']);
    foreach($risultato as $biglietti){
        if($biglietti['biglietto'] == "1"){
            $ticket = "Biglieto Ridotto";
            $context['prezzo'] += $prezzoRid * $biglietti['quantita'];
            $sommaRid = $prezzoRid * $biglietti['quantita'];
            $context['cart'] .= "<div id='boxDetails'>" . "<li>" . $ticket . " x" .  $biglietti['quantita'] . "</li>"
                            ."<input type='submit' value = '-' name='rmvTrid'>" 
                            ."<input type='submit' value = '+' name='addTrid'>" . "</div>";
        }
        else{
            $ticket = "Biglieto Intero";
            $context['prezzo'] += $prezzoInt * $biglietti['quantita'];
            $sommaInt = $prezzoInt * $biglietti['quantita'];
            $context['cart'] .= "<div id='boxDetails'>" . "<li>" . $ticket . " x" .  $biglietti['quantita'] . "</li>"
                            ."<input type='submit' value = '-' name='rmvTint'>"
                            ."<input type='submit' value = '+' name='addTint'>" . "</div>";
        }
        
    }

    if(isset($_POST['submit'])){
        $db->confirmPayment($_SESSION['username'],$sommaInt,$sommaRid);
        $context['cart'] = '';
        $context['prezzo'] = 0.00;
    }

    $template = new Parser();
    $template->render('buyingcart.html',$context);
?>
