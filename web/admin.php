<?php
    session_start();
    include_once 'header.php';
    include_once 'parser.php';
    include_once 'db.php';
    $db = new DatabaseClient();
    $db->connect();
    $debugMessage = '';

    if(isset($_POST['submit'])){
        $title = $_POST['titolo'];
        $descrizione = $_POST['descrizione'];
        $imgPath = $_POST['img'];
        if($_POST['type']=='crea'){  
            $db->createShow($title,$descrizione,$imgPath);
            $debugMessage = "Lo spettacolo è stato aggiunto con successo!";
        } else if($_POST['type']=='modifica'){
            $old_title = $_POST['vecchio_titolo'];
            $db->modifyShow($old_title,$title,$descrizione,$imgPath);
            $debugMessage = "Lo spettacolo è stato modificato con successo!";
        } else if($_POST['type']=='elimina'){
            $db->deleteShow($title);
            $debugMessage = "Lo spettacolo è stato eliminato con successo!";
        }
    }

    $template = new Parser();
    $context['debugMessage'] = $debugMessage;
    $context['result'] = [];
    $result = $db->fetchEvents();
    while($row = $result->fetch_assoc()){
        array_push($context['result'],$row);
    }
    $template->render("admin.html",$context);
?>