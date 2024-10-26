<?php
    session_start();

    //if(isset($_SESSION['user'])){
    //    header('Location: index.php');
    //    exit();
    //}

    include_once 'header.php';
    include_once 'db.php';

    
    if(isset($_POST['submit'])){
        // da aggiungere controlli email password
        $email = $_POST['email'];
        $password = hash('sha256',$_POST['password']);
        if (!preg_match('/^[a-zA-Z0-9_\.\@]+$/', $email)) {
            echo "<div class='alert alert-danger'>Invalid email</div>";
        }
        $conn = db_connect();
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        

        if (!$stmt->execute()) {
            echo "Error fetching user"; //aggiungere descrizione dettagliata errore registrazione
            $stmt->close();
            $conn->close();
            exit();
        } else {
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                echo "Email already exists"; //migliorare il messaggio
                $stmt->close();
                $conn->close();
                exit();
            }
            $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $email , $password);
            if (!$stmt->execute()) {
                echo "Error while creating user"; //aggiungere descrizione dettagliata errore registrazione
                $stmt->close();
                $conn->close();
                exit();
            } 
            $_SESSION['user'] = $email;
            echo "User successfully created, redirecting you to index.php...";
            header('Location: index.php');
        }
    }
?>
    <div class="register-container"> 
        <form method="POST" class="register-form">
            <h2>MIRABILIA PARK<br>REGISTER</h2>
            <input type="text" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="submit" name="submit" value="Register">
        </form>
    </div>

<?php
    include_once 'footer.php'
?>