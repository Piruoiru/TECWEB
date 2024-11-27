<?php
    session_start();
    //if(isset($_SESSION['user'])){
    //    header('Location: index.php');
    //    exit();
    //

    

    include_once 'db.php';

    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $db = new DatabaseClient();
        $db->connect();
        if($db->login($username,$password)){
            $_SESSION['username'] = $username; 
            echo "Login successfully, redirecting to index..."; //di nuovo non so se redirectare, non penso
            exit();
        } else {
            echo "Wrong email or password";
            $db->close();
            exit();
        }
    }


    include_once 'parser.php';
    $template = new Parser();
    $template->render("login.html");
?>