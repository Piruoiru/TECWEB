<?php
    session_start();
    if(isset($_SESSION['username'])){
       header('Location: index.php');
       exit();
    }

    

    include_once 'db.php';

    include_once 'header.php';

    $context['userInfosHeader'] = "";//non mostro i pulsanti login e registrati in alto

    $context['loginErrorMessage'] = '';
    $context['oldUsername'] = '';

    unset($_SESSION['lastLoginUsernameInserted']);

    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $db = new DatabaseClient();
        $db->connect();
        if($db->login($username,$password)){
            $_SESSION['username'] = $username; 
            $db->close();
            header('Location: index.php');
            exit();
        }
        $db->close();
        $context['oldUsername'] = $username;
        $context['loginErrorMessage'] = '<p class="errorMessageBgPar">Username o password errati o mancanti</p>';
    }

    include_once 'parser.php';
    $template = new Parser();
    $template->render("login.html", $context);
?>