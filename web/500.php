<?php
    header('HTTP/1.1 500 Internal Server Error');
    session_start();

    include_once 'header.php';
    include_once 'parser.php';

    $template = new Parser();
    $template->render("500.html",$context);
?>
