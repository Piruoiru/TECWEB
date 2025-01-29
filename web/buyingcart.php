<?php
    session_start();
    include_once 'parser.php';
    $context['totale'] = 'Non ha ancora selezionato alcun biglietto';
    $context['bigliettiInt'] = '';
    $context['bigliettiRid'] = '';
    $context['btnAcquista'] = '';
    $context['btnSelect'] = '<button id="btnSelect">Seleziona i biglietti</button>';

    if (!isset($_SESSION['username'])) {
        header('Location: login.php?loginRequest=buyingcart');
        exit();
    }

    include_once 'db.php';
    include_once 'header.php';
    unset($context['headerBtns']['cart']);
    $prezzoInt = 34.99;
    $prezzoRid = 24.99;
    $sommaInt = 0;
    $sommaRid = 0;
    $prezzo = 0;

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
                $prezzo += $prezzoRid * $biglietti['quantita'];
                $context['totale'] = 'Totale: ' . $prezzo . '€';
                $sommaRid = $prezzoRid * $biglietti['quantita'];
                $context['bigliettiRid'] .= $ticket . " x" .  $biglietti['quantita'];
                $context['btnAcquista'] = '<button id="btnCart">Acquista</button>';
                $context['btnSelect'] = '';
            }
        } else {
            if ($biglietti['quantita'] > 0) {
                $ticket = "Biglieto Intero";
                $prezzo += $prezzoInt * $biglietti['quantita'];
                $context['totale'] = 'Totale: ' . $prezzo . '€';
                $sommaInt = $prezzoInt * $biglietti['quantita'];
                $context['bigliettiInt'] .= $ticket . " x" .  $biglietti['quantita'];
                $context['btnAcquista'] = '<button id="btnCart">Acquista</button>';
                $context['btnSelect'] = '';
            } 
        }
    }

    if (isset($_POST['submit'])) {
        $db->confirmPayment($_SESSION['username']);
        $context['cart'] = '';
        $context['totale'] = 0.00;
        $db->close();
        header('Location: profile.php');
        exit();
    }

    $template = new Parser();
    $template->render('buyingcart.html', $context);
?>
