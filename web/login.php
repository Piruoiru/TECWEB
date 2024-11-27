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
        if($db->login($email,$password)){
            $_SESSION['username'] = $row['username']; //da aggiunere i vari campi che si vogliono mostrare
            header('Location: index.php');
            echo "Login successfully, redirecting to index..."; //di nuovo non so se redirectare, non penso
            exit();
        } else {
            echo "Wrong email or password";
            $stmt->close();
            $conn->close();
            exit();
        }
    }


    include_once 'parser.php';
    $template = new Parser();
    $template->render("login.html");
?>