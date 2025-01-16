<?php
    session_start();
    include_once 'header.php';
    $context['orario'] = "10:00-17:00";

    include_once 'parser.php';
    $template = new Parser();
    $template->render('calendar.html',$context);
?>