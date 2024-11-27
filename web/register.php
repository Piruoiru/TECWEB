<?php
    session_start();

    //if(isset($_SESSION['user'])){
    //    header('Location: index.php');
    //    exit();
    //}
    include_once 'db.php';

    if(isset($_POST['submit'])){
        // da aggiungere controlli email password
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (!preg_match('/^[a-zA-Z0-9_\.\@]+$/', $username)) {
            echo "<div class='alert alert-danger'>Invalid username</div>";
        }
        $db = new DatabaseClient();
        $db->connect();
        $result = $db->fetchUser($username);

        if($result->num_rows > 0){
            echo "Email already exists"; //migliorare il messaggio
            $stmt->close();
            $conn->close();
            exit();
        }
        $result = $db->register($username,$password);
        if (!$result) {
            echo "Error while creating user"; //aggiungere descrizione dettagliata errore registrazione
            $stmt->close();
            $conn->close();
            exit();
        } 
        $_SESSION['username'] = $username;
        echo "User successfully created, redirecting you to index.php..."; //registrazione avvenuta con successo!
        //header('Location: index.php');
    }

    include_once 'parser.php';
    $template = new Parser();
    $template->render("register.html");
?>