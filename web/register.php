<?php
    session_start();

    if(isset($_SESSION['username'])){
       header('Location: index.php');
       exit();
    }

    include_once 'db.php';
    include_once 'header.php';

    $context['userInfosHeader'] = "";//non mostro i pulsanti login e registrati in alto

    if(isset($_POST['submit'])){
        // echo "Submit";
        unset($_POST['submit']);
        // echo isset($_POST['submit'])? "true" : "false";
        // da aggiungere controlli email password
        // $username = $_POST['username'];
        // $password = $_POST['password'];
        // if (!preg_match('/^[a-zA-Z0-9_\.\@]+$/', $username)) {
        //     echo "<div class='alert alert-danger'>Invalid username</div>";
        // }
        // $db = new DatabaseClient();
        // $db->connect();
        // $result = $db->fetchUser($username);

        // if($result->num_rows > 0){
        //     echo "Email already exists"; //migliorare il messaggio
        //     $stmt->close();
        //     $conn->close();
        //     exit();
        // }
        // $result = $db->register($username,$password);
        // if (!$result) {
        //     echo "Error while creating user"; //aggiungere descrizione dettagliata errore registrazione
        //     $stmt->close();
        //     $conn->close();
        //     exit();
        // } 
        // $_SESSION['username'] = $username;
        // echo "User successfully created, redirecting you to index.php..."; //registrazione avvenuta con successo!
        //header('Location: index.php');
    }

    include_once 'parser.php';
    $template = new Parser();
    $template->render("register.html", $context);
?>