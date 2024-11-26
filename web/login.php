<?php
    session_start();
    //if(isset($_SESSION['user'])){
    //    header('Location: index.php');
    //    exit();
    //

    

    include_once 'db.php';

    if(isset($_POST['submit'])){
        $email = $_POST['email'];
        $password = $_POST['password'];

        $conn = db_connect();
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s',$email);
        $result = $stmt->execute();
        if(!$result){
            echo "Error fetching user"; //aggiungere descrizione dettagliata errore registrazione
            $stmt->close();
            $conn->close();
            exit();
        } else {
            $result = $stmt->get_result();
            if($result->num_rows === 0){
                echo "Wrong email or password";
                $stmt->close();
                $conn->close();
                exit();
            }
            $row = $result->fetch_assoc();

            if(hash('sha256',$password)===$row['password']){
                $_SESSION['username'] = $row['username']; //da aggiunere i vari campi che si vogliono mostrare
                $_SESSION['email'] = $row['email'];
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
    }


    include_once 'parser.php';
    $template = new Parser();
    $template->setTokenizer(new Tokenizer());
    $template->render("login.html");
?>