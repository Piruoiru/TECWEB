<?php
    session_start();
    include_once 'header.php';

    
    include_once 'parser.php';
    $template = new Parser();
    $template->render("index.html",$context);
?>
