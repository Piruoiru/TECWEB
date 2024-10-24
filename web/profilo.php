<?php
    session_start();
    if(!isset($_SESSION['user'])){
        header('Location: login.php');
        exit();
    }

    include_once 'header.php';
    echo 'La sburra ' . $_SESSION['user'];

?>



<?php 
    include_once 'footer.php';
?>