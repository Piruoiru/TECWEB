<?php
    include_once 'header.php';
    include_once 'parser.php';
    include_once 'db.php';
    $db = new DatabaseClient();
    $db->connect();

    if(isset($_POST['submit'])){
        $title = $_POST['titolo'];
        $descrizione = $_POST['descrizione'];
        $imgPath = $_POST['img'];
        if($_POST['type']=='crea'){  
            $db->createShow($title,$descrizione,$imgPath);
        } else if($_POST['type']=='modifica'){
            $db->modifyShow($title,$title,$descrizione,$img);
        } else if($_POST['type']=='elimina'){
            $db->deleteShow($title);
        }
    }

    $template = new Parser();
    $context['result'] = [];
    $result = $db->fetchEvents();
    while($row = $result->fetch_assoc()){
        array_push($context['result'],$row);
    }
    $template->render("admin.html",$context);
?>