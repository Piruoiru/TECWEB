<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header('Location: login.php'); //TODO: da rimpiazzare con 401 non autorizzato o 403
        exit();
    }

    include_once 'header.php';
    
    include_once 'db.php';
    $db = new DatabaseClient();
    $db->connect();

    $userDetails = $db->fetchUser($_SESSION['username'])->fetch_assoc();
    $context['nome'] = $userDetails['nome'];
    $context['cognome'] = $userDetails['cognome'];
    $context['username'] = $userDetails['username'];



    // $cart = $db->fetchUserCartTickets($_SESSION['username']);
    $lastOrder = $db->getUsersLastOrderTickets($_SESSION['username']);
    // $context['cart'] = $cart;
    // $context['acquired'] = $acquired;

    $db->close();

    // $context['lastOrder'] = "";
    if(mysqli_num_rows($lastOrder) == 0){
        $context['lastOrder'] = "<h3>Nessun ordine effettuato finora</h3> <!-- FIXME: togliere style -->
                    <a href='#' class='buttonStyle translateUpOnHover outlineGradientBtn' style='width: 50%; margin: 0 auto;'>Acquista ora</a>";
    }else{
        $row = $lastOrder->fetch_assoc();
        $context['lastOrder'] = "<h3>Ultimo ordine effettuato</h3><p>Ordine numero ".$row['idOrdine']." del ".$row['dataOrarioOrdine']."</p>";
        do{
            //FIXME: togliere style
            $context['lastOrder'] .= "<div class='card' style='margin: 0.5em; box-shadow: none; background-color: var(--secondaryColor); color: #000;'>";
            // foreach($row as $field => $value){
            //     $context['lastOrder'] .= $field."   ";
            //     $context['lastOrder'] .= $value."   ";
            // }
            $context['lastOrder'] .= "<h4>".$row['tipoBiglietto']."</h4> <p>#".$row['id']."</p>";
            $context['lastOrder'] .= "</div>";

        }while($row = $lastOrder->fetch_assoc());
        $context['lastOrder'] .= "<a href='#' class='buttonStyle translateUpOnHover outlineGradientBtn' style='width: 50%; margin: 0 auto;'>Visualizza tutti gli acquisti</a>"; 
    }


    include_once 'parser.php';
    $template = new Parser();
    $template->render("profile.html",$context);
?>