<?php
    session_start();
    include_once 'header.php';
    include_once 'db.php';

    if(isset($_SESSION['username'])){
       header('Location: index.php');
       exit();
    }
    
    unset($context['headerBtns']['login']);//non mostro il pulsante login in alto
    unset($context['essentialHeaderBtns']['login']);//tolgo  accedi o registrati nel menù mobile
    unset($context['headerBtns']['register']);//non mostro il pulsante regster in alto

    $context['loginErrorMessage'] = [];
    $context['oldUsername'] = '';

    unset($_SESSION['lastLoginUsernameInserted']);

    function validate($username,$password){
        global $context;
        $isValid = true;

        if(!preg_match('/^[A-Za-z0-9_\.\@]{4,20}/u', $username)){
            $isValid = false;
            array_push($context['loginErrorMessage'], "L'username deve essere composto da 4 a 20 caratteri alfanumerici, . o @");

        }
        if (!preg_match('/^[A-Za-z0-9_\.\@]{4,20}/u', $password)) {
            $isValid = false;
            array_push($context['loginErrorMessage'], "La password deve essere composta da 4 a 20 caratteri alfanumerici, . o @");
        }

        return $isValid;
    }

    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        if(validate($username,$password)){
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
            } else {
                $db->close();
                $context['oldUsername'] = $username;
                array_push($context['loginErrorMessage'], 'Username o password errati');
            }
        }
    } else if(isset($_GET['loginRequest'])){
        array_push($context['loginErrorMessage'], 'Devi effettuare il login per accedere alla pagina richiesta');
    }

    include_once 'parser.php';
    $template = new Parser();
    $template->render("login.html", $context);
?>