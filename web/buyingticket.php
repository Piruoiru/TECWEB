<?php
    session_start();
include_once 'parser.php';
if (!isset($_SESSION['username'])) {
    header('Location: login.php?loginRequest=buyingticket');
    exit();
}

include_once 'db.php';
include_once 'header.php';

if (isset($_POST['submit'])) {
    $intero = $_POST['intero'];
    $ridotto = $_POST['ridotto'];

    $db = new DatabaseClient();
    $db->connect();

    if ($intero > 0) {
        if ($db->addTicketToCart(2,$_SESSION['username'],$intero)) {
            $db->close();
            exit();
        }
    }
    if ($ridotto > 0) {
        if ($db->addTicketToCart(1,$_SESSION['username'],$ridotto)) {
            $db->close();
            exit();
        }
    }
    $db->close();
    if($intero != 0 || $ridotto != 0){
        header('Location: buyingcart.php');
        exit();
    } 
}

$template = new Parser();
$template->render('buyingticket.html', $context);
?>
