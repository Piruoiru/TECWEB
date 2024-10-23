<?php
    session_start();
    //if(isset($_SESSION['user'])){
    //    header('Location: index.php');
    //    exit();
    //}
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
                $_SESSION['user'] = $email;
                header('Location: index.php');
                echo "Login successfully, redirecting to index..."; //di nuovo
                exit();
            } else {
                echo "Wrong email or password";
                $stmt->close();
                $conn->close();
                exit();
            }
        }
    }

    include_once 'header.php';
    
?>
    <form method="POST">
        <input type="text" id="email" name="email" required>
        <input type="password" id="password" name="password" required>
        <input type="submit" name="submit" value="Login">
    </form>

<?php
    include_once 'footer.php'
?>