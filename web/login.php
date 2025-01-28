<?php
    session_start();
    include_once 'header.php';
    if(isset($_SESSION['username'])){
       header('Location: index.php');
       exit();
    }
    
    include_once 'db.php';

    unset($context['headerBtns']['login']);//non mostro il pulsante login in alto
    unset($context['essentialHeaderBtns']['login']);//tolgo  accedi o registrati nel menù mobile
    unset($context['headerBtns']['register']);//non mostro il pulsante regster in alto

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
            if(isset($_GET['loginRequest'])&&!empty($_GET['loginRequest'])){
                header('Location: '.$_GET['loginRequest'].'.php');
                exit();
            }
            header('Location: index.php');
            exit();
        }else{
            $db->close();
            $context['oldUsername'] = $username;
            $context['loginErrorMessage'] = 'Username o password errati o mancanti';
        }
    }else if(isset($_GET['loginRequest'])){
        header('HTTP/1.1 401 Unauthorized');
        $context['loginErrorMessage'] = 'Devi effettuare il login per accedere alla pagina richiesta';
    }

    include_once 'parser.php';
    $template = new Parser();
    $template->render("login.html", $context);
?>