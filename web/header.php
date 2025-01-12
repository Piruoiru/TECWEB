<?php
    //RICHIEDE CHE LA SESSIONE SIA STATA ATTIVATA PRIMA DELL'INCLUSIONE DI QUESTO FILE

    $context['orario'] = "10:00-17:00";

    if(isset($_SESSION['username'])){
            $context['headerBtns'] = ['profile' => ['url' => "profile.php", 'text' => 'Il mio profilo'], 'logout'=> ['url' => "logout.php", 'text' => "Logout"]];
        }else{
            $context['headerBtns'] = ['login' => ['url' => "login.php", 'text' => 'Login'], 'register' => ['url' => "register.php", 'text' => 'Registrati']];
    }
?>