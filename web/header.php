<?php
    session_start();

    session_start();

    $context['orario'] = "10:00-17:00";

    if(isset($_SESSION['username'])){
            $context['userInfosHeader'] = "<a href='profile.php' class='buttonStyle'>Il mio profilo</a><a href='logout.php' class='buttonStyle'>Logout</a>";
        }else{
            $context['userInfosHeader'] = "<a href='login.php' class='buttonStyle'>Login</a><a href='register.php' class='buttonStyle'>Registrati</a>";
    }
?>