<?php
    $date = new DateTime("now", new DateTimeZone("Europe/Rome"));
    $weekDay = $date->format('l');

    $openingHours = array(
        'Monday' => array('orarioApertura' => '10:00', 'orarioChiusura' => '18:00'),
        'Tuesday' => array('orarioApertura' => '10:00', 'orarioChiusura' => '18:00'),
        'Wednesday' => array('orarioApertura' => '10:00', 'orarioChiusura' => '18:00'),
        'Thursday' => array('orarioApertura' => '10:00', 'orarioChiusura' => '18:00'),
        'Friday' => array('orarioApertura' => '09:00', 'orarioChiusura' => '20:00'),
        'Saturday' => array('orarioApertura' => '09:00', 'orarioChiusura' => '21:30'),
        'Sunday' => array('orarioApertura' => '09:00', 'orarioChiusura' => '21:00')
    );
    $context['orario'] = $openingHours[$weekDay]['orarioApertura'] . "-" . $openingHours[$weekDay]['orarioChiusura'];
    $context['cart'] = '';
    $context['prezzo'] = 0;

    if(isset($_SESSION['username'])){
            $context['headerBtns'] = ['cart' => ['url' => "buyingcart.php", 'text' => 'Carrello'], 'profile' => ['url' => "profile.php", 'text' => 'Profilo'], 'logout'=> ['url' => "logout.php", 'text' => "Logout"]];
            $context['essentialHeaderBtns'] = ['cart' => ['url' => "buyingcart.php", 'text' => 'Carrello'], 'profile' => ['url' => "profile.php", 'text' => 'Il mio profilo'], 'logout'=> ['url' => "logout.php", 'text' => "Logout"]];
        }else{
            $context['headerBtns'] = ['login' => ['url' => "login.php", 'text' => 'Login'], 'register' => ['url' => "register.php", 'text' => 'Registrati']];
            $context['essentialHeaderBtns'] = ['login' => ['url' => "login.php", 'text' => 'Accedi o registrati']];
    }
?>
