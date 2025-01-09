<?php
    include_once 'header.php';

    include_once 'parser.php';
    

    $template = new Parser();
    $template->render('map.html',$context);
?>