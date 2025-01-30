<?php
    session_start();
    include_once 'parser.php';
    $context['bigliettiInt'] = '';
    $context['bigliettiRid'] = '';
    $context['btnSelect'] = 'Seleziona i biglietti';
    $context['hidePopUp'] = 'hiddenCart';

    if (!isset($_SESSION['username'])) {
        header('Location: login.php?loginRequest=buyingcart');
        exit();
    }

    include_once 'db.php';
    include_once 'header.php';
    unset($context['headerBtns']['cart']);
    $sommaInt = 0;
    $sommaRid = 0;
    $prezzo = 0;

    $db = new DatabaseClient();
    if(isset($_POST)){
        $db->connect();
        $tickets = $db->fetchTickets();
        $prezzoRid = $tickets[0]['costo'];
        $prezzoInt = $tickets[1]['costo'];
    }

    if (isset($_POST['addTrid'])) {
        $operation = 'add';
        $db->updateCart('1', $_SESSION['username'], $operation);
        header('Location: buyingcart.php');
    }

    if (isset($_POST['rmvTrid'])) {
        $operation = 'rmv';
        $db->updateCart('1', $_SESSION['username'], $operation);
        header('Location: buyingcart.php');
    }

    if (isset($_POST['rmvTint'])) {
        $operation = 'rmv';
        $db->updateCart('2', $_SESSION['username'], $operation);
        header('Location: buyingcart.php');
    }

    if (isset($_POST['addTint'])) {
        $operation = 'add';
        $db->updateCart('2', $_SESSION['username'], $operation);
        header('Location: buyingcart.php');
    }

    $risultato = $db->fetchCart($_SESSION['username']);
    foreach ($risultato as $biglietti) {
        if ($biglietti['biglietto'] == "1") {
            if ($biglietti['quantita'] > 0) {
                $ticket = "Biglieto Ridotto";
                $prezzo += $prezzoRid * $biglietti['quantita'];
                $context['totale'] = $prezzo;
                $sommaRid = $prezzoRid * $biglietti['quantita'];
                $context['bigliettiRid'] .= $ticket . " x" .  $biglietti['quantita'];
                $context['btnAcquista'] = 'Acquista';
                unset($context['btnSelect']);
            }
        } else {
            if ($biglietti['quantita'] > 0) {
                $ticket = "Biglieto Intero";
                $prezzo += $prezzoInt * $biglietti['quantita'];
                $context['totale'] = $prezzo;
                $sommaInt = $prezzoInt * $biglietti['quantita'];
                $context['bigliettiInt'] .= $ticket . " x" .  $biglietti['quantita'];
                $context['btnAcquista'] = 'Acquista';
                unset($context['btnSelect']);
            } 
        }
    }

    if (isset($_POST['submit'])) {
        $db->confirmPayment($_SESSION['username']);
        $context['cart'] = '';
        $context['totale'] = 0.00;
        $db->close();
        unset($context['hidePopUp']);
    }

    $template = new Parser();
    $template->render('buyingcart.html', $context);
?>
