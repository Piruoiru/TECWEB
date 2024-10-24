<?php
    session_start();

    if(isset($_SESSION['user'])){
        echo '<p>Hello '.$_SESSION['user'].'!';
    } else {
        echo '<p>Hello Stranger!</p>';
    }

    include_once 'header.php';
    
    echo "<p>Hello World!</p>"; 

    include_once 'footer.php';
?>
<a href="/profilo.php">Profilo</a>
<a href="/register.php">Register</a>
<a href="/login.php">Login</a>
<a href="/logout.php">Logout</a>
