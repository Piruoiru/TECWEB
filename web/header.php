<?php
    $context['orario'] = "10:00-17:00";
    $context['cart'] = '';
    $context['prezzo'] = 0;

    if(isset($_SESSION['username'])){
            $context['headerBtns'] = ['cart' => ['url' => "buyingcart.php", 'text' => 'Carrello'], 'profile' => ['url' => "profile.php", 'text' => 'Il mio profilo'], 'logout'=> ['url' => "logout.php", 'text' => "Logout"]];
            $context['essentialHeaderBtns'] = ['cart' => ['url' => "buyingcart.php", 'text' => 'Carrello'], 'profile' => ['url' => "profile.php", 'text' => 'Il mio profilo'], 'logout'=> ['url' => "logout.php", 'text' => "Logout"]];
        }else{
            $context['headerBtns'] = ['login' => ['url' => "login.php", 'text' => 'Login'], 'register' => ['url' => "register.php", 'text' => 'Registrati']];
            $context['essentialHeaderBtns'] = ['login' => ['url' => "login.php", 'text' => 'Accedi o registrati']];

    }
?>
