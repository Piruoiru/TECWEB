<?php
    session_start();
    include_once 'header.php';
    include_once 'parser.php';
    include_once 'db.php';
    
    $db = new DatabaseClient();

    $isAdmin = false;

    if(isset($_SESSION['username']) && $_SESSION['username'] === 'admin'){
        $isAdmin = true;
    }

    $template = new Parser();
    $context['isAdmin'] = $isAdmin;
    $context['result'] = [];
    $db->connect();
    $result = $db->fetchEvents();
    $db->close();
    while($row = $result->fetch_assoc()){
        array_push($context['result'],$row);
    }
    $template->render("shows.html",$context);
?>