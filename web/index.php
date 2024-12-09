<?php   
    session_start();

    include_once 'db.php';
    $db = new DatabaseClient();
    $db->connect();
    $result = $db->fetchEvents();
    $context = ['result'=>[], 'orario'=>"10:00-17:00"];
    while($row = $result->fetch_assoc()){
        array_push($context['result'],$row);
    }
    
    include_once 'parser.php';
    $template = new Parser();
    $template->render("index.html",$context);
?>
