<?php
    include_once 'parser.php';
    $context = ['orario'=>"10:00-17:00"];


    include_once 'db.php';
    include_once 'header.php';

    if(isset($_POST['submit'])){
        $intero = $_POST['intero'];
        $ridotto = $_POST['ridotto'];
        
        $db = new DatabaseClient();
        $db->connect();
        
        if($intero > 0){
            if($db->addTicketToCart(2,$username,$intero)){
                $_SESSION['username'] = $username; 
                $db->close();
                header('Location: index.php');
                exit();
            }
        }
        if($ridotto > 0){
            if($db->addTicketToCart(1,$username,$ridotto)){
                $_SESSION['username'] = $username; 
                $db->close();
                header('Location: index.php');
                exit();
            }
        }
        $db->close();
    }

    $template = new Parser();
    $template->render('buyticket.html',$context);
?>
