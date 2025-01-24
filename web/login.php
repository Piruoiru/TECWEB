<?php
    session_start();
    include_once 'header.php';
    if(isset($_SESSION['username'])){
       header('Location: index.php');
       exit();
    }
    
    include_once 'db.php';

    unset($context['headerBtns']['login']);//non mostro il pulsante login in alto
    unset($context['essentialHeaderBtns']['login']);//sostituisco  accedi o registrati nel menù mobile con registrati
    $context['essentialHeaderBtns']['register'] = ['url' => "register.php", 'text' => 'Registrati'];

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
        }else{
            $db->close();
            $context['oldUsername'] = $username;
            $context['loginErrorMessage'] = 'Username o password errati o mancanti';
        }
    }

    include_once 'parser.php';
    $template = new Parser();
    $template->render("login.html", $context);
?>