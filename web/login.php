<?php
    session_start();
    if(isset($_SESSION['username'])){
       header('Location: index.php');
       exit();
    }

    

    include_once 'db.php';

    include_once 'header.php';

    if(!isset($_SESSION['username'])){
        $context['userInfosHeader'] = "";
}

    if(isset($_SESSION['lastLoginUsernameInserted'])){
        $context['loginErrorMessage'] = '<p class="errorMessageBgPar">Username o password errati o mancanti</p>';
        $context['oldUsername'] = $_SESSION['lastLoginUsernameInserted'];
    }else{
        $context['loginErrorMessage'] = '';
        $context['oldUsername'] = '';
    }

    $_SESSION['lastLoginUsernameInserted'] = null;

    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $db = new DatabaseClient();
        $db->connect();
        if($db->login($username,$password)){
        $_SESSION['username'] = $username; 
            header('Location: index.php');
            $db->close();
            exit();
        } else {
            $_SESSION['lastLoginUsernameInserted'] = $username;
            header('Location: login.php');
            $db->close();
            exit();
        }
        exit();
    }

    include_once 'parser.php';
    $template = new Parser();
    $template->render("login.html", $context);
?>