<?php
    session_start();
    if(isset($_SESSION['username'])){
       header('Location: index.php');
       exit();
    }

    

    include_once 'db.php';

    //BREADCRUMB
    $context = ['orario'=>"10:00-17:00"];

    if(isset($_SESSION['loginFailed'])){
        $context['loginErrorMessage'] = '<p class="errorMessageBgPar">Username o password errati o mancanti</p>';
    }else{
        $context['loginErrorMessage'] = '';
    }

    $_SESSION['loginFailed'] = null;


    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $db = new DatabaseClient();
        $db->connect();
        if($db->login($username,$password)){
        $_SESSION['username'] = $username; 
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['loginFailed'] = true;
            header('Location: login.php');
            exit();
        }
        $db->close();
        exit();
    }

    include_once 'parser.php';
    $template = new Parser();
    $template->render("login.html", $context);
?>