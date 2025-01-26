<?php
    header('HTTP/1.1 401 Unauthorized');
    session_start();

    include_once 'header.php';
    include_once 'parser.php';

    $template = new Parser();
    $template->render("401.html",$context);
?>
