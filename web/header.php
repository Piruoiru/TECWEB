<?php
    $context['orario'] = "10:00-17:00";
    $context['cart'] = '';
    $context['prezzo'] = 0;

    if(isset($_SESSION['username'])){
            $context['headerBtns'] = ['profile' => ['url' => "profile.php", 'text' => 'Il mio profilo'], 'logout'=> ['url' => "logout.php", 'text' => "Logout"]];
        }else{
            $context['headerBtns'] = ['login' => ['url' => "login.php", 'text' => 'Login'], 'register' => ['url' => "register.php", 'text' => 'Registrati']];
    }
?>
