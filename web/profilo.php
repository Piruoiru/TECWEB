<?php
    session_start();
    if(!isset($_SESSION['user'])){
        header('Location: login.php'); //da rimpiazzare con 401 non autorizzato
        exit();
    }

    include_once 'parser.php';
    $template = new Parser();
    $template->setTokenizer(new Tokenizer());
    $template->render("profilo.html",$_SESSION);

?>