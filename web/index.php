<?php
    session_start();

    if(isset($_SESSION['user'])){
        echo '<p>Hello '.$_SESSION['user'].'!';
    } else {
        echo '<p>Hello Stranger!</p>';
    }

    include_once 'header.php';
?>
    <!--HTML per body-->
    


<?php

include_once 'footer.php';

?>
