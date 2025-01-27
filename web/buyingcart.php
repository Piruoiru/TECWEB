<?php
session_start();
include_once 'parser.php';
$context['cart'] = '';
$context['prezzo'] = 0;

if (!isset($_SESSION['username'])) {
    header('Location: login.php?loginRequest');
    exit();
}

include_once 'db.php';
include_once 'header.php';
unset($context['headerBtns']['cart']);
$prezzoInt = 34.99;
$prezzoRid = 24.99;
$sommaInt = 0;
$sommaRid = 0;

$db = new DatabaseClient();
$db->connect();


if (isset($_POST['addTrid'])) {
    $operation = 'add';
    $db->updateCart('1', $_SESSION['username'], $operation);
}

if (isset($_POST['rmvTrid'])) {
    $operation = 'rmv';
    $db->updateCart('1', $_SESSION['username'], $operation);
}

if (isset($_POST['rmvTint'])) {
    $operation = 'rmv';
    $db->updateCart('2', $_SESSION['username'], $operation);
}

if (isset($_POST['addTint'])) {
    $operation = 'add';
    $db->updateCart('2', $_SESSION['username'], $operation);
}

$risultato = $db->fetchCart($_SESSION['username']);
foreach ($risultato as $biglietti) {
    if ($biglietti['biglietto'] == "1") {
        if ($biglietti['quantita'] > 0) {
            $ticket = "Biglieto Ridotto";
            $context['prezzo'] += $prezzoRid * $biglietti['quantita'];
            $sommaRid = $prezzoRid * $biglietti['quantita'];
            $context['cart'] .= "<div class='rowCart'>" . "<div>" . "<li>" . $ticket . " x" .  $biglietti['quantita'] . "</li>" . "</div>"
                . "<div>" ."<input type='submit' value = '-' name='rmvTrid' class='btnAddRmv'>"
                . "<input type='submit' value = '+' name='addTrid' class='btnAddRmv'>" . "</div>" . "</div>";
        }
    } else {
        if ($biglietti['quantita'] > 0) {
            $ticket = "Biglieto Intero";
            $context['prezzo'] += $prezzoInt * $biglietti['quantita'];
            $sommaInt = $prezzoInt * $biglietti['quantita'];
            $context['cart'] .= "<div class='rowCart'>" . "<div>" . "<li>" . $ticket . " x" .  $biglietti['quantita'] . "</li>" . "</div>"
                . "<div>" . "<input type='submit' value = '-' name='rmvTint' class='btnAddRmv'>"
                . "<input type='submit' value = '+' name='addTint' class='btnAddRmv'>" . "</div>" . "</div>";
        } 
    }
}

if (isset($_POST['submit'])) {
    $db->confirmPayment($_SESSION['username']);
    $context['cart'] = '';
    $context['prezzo'] = 0.00;
}

$template = new Parser();
$template->render('buyingcart.html', $context);
