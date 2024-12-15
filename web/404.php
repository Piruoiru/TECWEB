<?php
    include_once 'parser.php';
    $context = ['orario'=>"10:00-17:00"];

    $template = new Parser();
    $template->render("404.html",$context);
?>