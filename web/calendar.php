<?php
    include_once 'parser.php';
    $template = new Parser();
    $template->render('calendar.html');
?>