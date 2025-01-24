<?php
    session_start();
    include_once 'header.php';
    include_once 'parser.php';
    include_once 'db.php';

    
    $template = new Parser();
    $template->render("500.html",$context);
?>
