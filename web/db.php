<?php
ini_set('display_errors',0);
include_once 'config.php';
class DatabaseClient
{
    private $conn;
    public function __construct() {}

    public function connect(){
        try{
            $this->conn = new mysqli(DB_CONFIG['db_host'], DB_CONFIG['db_user'], DB_CONFIG['db_pass'], DB_CONFIG['db_name']);
            // Check connection
            if ($this->conn->connect_error) {
                throw new \Exception("Connection failed: " . $this->conn->connect_error);
            }
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function close(){
        try{
            $this->conn->close();
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function fetchUser($username){
        try{
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $result = $stmt->execute();
            if (!$result) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
            return $stmt->get_result();
        }  catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function login($username, $password){
        try{
            $sql = "SELECT * FROM users WHERE username = ? AND password=?";
            $stmt = $this->conn->prepare($sql);
            $password = hash('sha256', $password);
            $stmt->bind_param('ss', $username, $password);
            $result = $stmt->execute();
            if (!$result) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
            $result = $stmt->get_result()->num_rows;
            return $result === 0 ? false : true;
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function register($name, $surname, $username, $password){
        try{
            $sql = "INSERT INTO users (nome, cognome, username, password) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $password = hash('sha256', $password);
            $stmt->bind_param('ssss', $name, $surname, $username, $password);
            return $stmt->execute(); //FIXME: chiusura prepared stmt
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function updateUserDetails($name, $surname, $username){
        try{
            $sql = "UPDATE users SET nome=?, cognome=? WHERE username=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('sss', $name, $surname, $username);
            return $stmt->execute();
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function deleteUser($username){
        try{
            $sql = "DELETE FROM users WHERE username=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $username);
            return $stmt->execute();
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function fetchCart($username){
        try{
            $sql = "SELECT * 
                        FROM bigliettiCarrello 
                        INNER JOIN biglietti ON bigliettiCarrello.biglietto = biglietti.id
                        WHERE utente=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $tickets = [];
            if (!$stmt->execute()) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                array_push($tickets, $row);
            }
            return $tickets;
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function fetchEvents(){
        try{
            $sql = "SELECT * FROM spettacoli";
            $result = $this->conn->query($sql);
            if (!$result) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
            return $result;
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function fetchTickets(){
        try{
            $sql = "SELECT * FROM biglietti";
            $result = $this->conn->query($sql);
            $tickets = [];
            if (!$result) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
            while ($row = $result->fetch_assoc()) {
                array_push($tickets, $row);
            }
            return $tickets;
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function fetchUserCartTickets($username){
        try{
            $sql = "SELECT *
                        FROM bigliettiCarrello
                        INNER JOIN biglietti on bigliettiCarrello.biglietto = biglietti.id 
                        WHERE utente=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $result = $stmt->execute();
            if (!$result) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
            $result = $stmt->get_result();
            $tickets = [];
            while ($row = $result->fetch_assoc()) {
                array_push($tickets, $row);
            }
            return $tickets;
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function fetchUserAcquiredTickets($username){
        try{
            $sql = "SELECT bigliettiAcquistati.id as idBiglietto, biglietti.titolo as tipoBiglietto, ordine as idOrdine, sommaPagata, utente, dataOrarioOrdine FROM bigliettiAcquistati
                        INNER JOIN ordini ON bigliettiAcquistati.ordine = ordini.id
                        INNER JOIN biglietti ON biglietti.id = bigliettiAcquistati.tipoBiglietto
                        WHERE ordini.utente=?
                        ORDER BY ordini.dataOrarioOrdine DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $result = $stmt->execute();
            if (!$result) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
            $result = $stmt->get_result();
            $tickets = [];
            while ($row = $result->fetch_assoc()) {
                array_push($tickets, $row);
            }
            return $tickets;
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function fetchUserLastOrderTickets($username){
        try{
            $sql = "SELECT bigliettiAcquistati.id as idBiglietto, biglietti.titolo as tipoBiglietto, ordine as idOrdine, sommaPagata, utente, dataOrarioOrdine FROM bigliettiAcquistati
                        INNER JOIN (SELECT id as idOrdine, utente, dataOrarioOrdine FROM ordini
                        WHERE utente=?
                        ORDER BY dataOrarioOrdine DESC
                        LIMIT 1) AS ordini
                        ON bigliettiAcquistati.ordine = ordini.idOrdine
                        INNER JOIN biglietti ON biglietti.id = bigliettiAcquistati.tipoBiglietto";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $result = $stmt->execute();
            if (!$result) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
            $result = $stmt->get_result();
            $tickets = [];
            while ($row = $result->fetch_assoc()) {
                array_push($tickets, $row);
            }
            return $tickets;
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function fetchOrdersByUser($username){
        try{
            $sql = "SELECT * FROM ordini WHERE utente=? ORDER BY dataOrarioOrdine DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $result = $stmt->execute();
            if (!$result) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
            $result = $stmt->get_result();
            $orders = [];
            while ($row = $result->fetch_assoc()) {
                array_push($orders, $row);
            }
            return $orders;
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function fetchTicketsByOrder($orderID){
        try{
            $sql = "SELECT bigliettiAcquistati.id as id, biglietti.titolo as tipoBiglietto, sommaPagata, costo FROM bigliettiAcquistati
                    INNER JOIN biglietti ON bigliettiAcquistati.tipoBiglietto = biglietti.id
                    WHERE ordine=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('i', $orderID);
            $result = $stmt->execute();
            if (!$result) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
            $result = $stmt->get_result();
            $tickets = [];
            while ($row = $result->fetch_assoc()) {
                array_push($tickets, $row);
            }
            return $tickets;
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function fetchShow($title){
        try{
            $sql = "SELECT * 
                    FROM spettacoli
                    WHERE titolo=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $title);
            $result = $stmt->execute();
            if (!$result) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
            $result = $stmt->get_result();
            $show = [];
            while ($row = $result->fetch_assoc()) {
                $show = $row;
            }
            return $show;
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function createShow($title, $description, $imgPath, $imgDescription){
        try{
            $sql = "SELECT * FROM spettacoli WHERE titolo=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $title);
            $status = $stmt->execute();
            if (!$status) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
            $result = $stmt->get_result();
            if($result->num_rows !== 0){
                return "Non si può creare uno spettacolo con un titolo già esistente";
            }
            $sql = "INSERT INTO spettacoli(titolo,descrizione,percorso_immagine,descrizione_immagine) VALUES (?,?,?,?);";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ssss', $title, $description, $imgPath, $imgDescription);
            $status = $stmt->execute();
            if (!$status) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
            return 'Spettacolo aggiunto correttamente!';
        } catch(Throwable $exception) {
            header('Location: 500.php');
        }
    }

    public function modifyShow($oldTitle, $title, $description, $imgPath, $imgDescription){
        try{
            $sql = "UPDATE spettacoli 
                        SET titolo=?, descrizione=?, percorso_immagine=?, descrizione_immagine=?
                        WHERE titolo=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('sssss', $title, $description, $imgPath, $imgDescription,$oldTitle);
            $status = $stmt->execute();
            if (!$status) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
        } catch (Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function deleteShow($title){
        try{
            $sql = "DELETE FROM spettacoli WHERE titolo=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $title);
            $status = $stmt->execute();
            if (!$status) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
            return $status;
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function addTicketToCart($ticketID, $username, $quantita){
        try{
            $sql = "SELECT *
                        FROM bigliettiCarrello
                        WHERE biglietto=? AND utente=?";
            $stmt = $this->conn->prepare($sql);
            $currDate = date("Y-m-d H:i:s");
            $stmt->bind_param('ss', $ticketID, $username);
            $status = $stmt->execute();
            if (!$status) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
            $result = $stmt->get_result();
            if ($result->num_rows !== 0) {
                $sql = "DELETE 
                            FROM bigliettiCarrello
                            WHERE biglietto=? AND utente=?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param('ss', $ticketID, $username);
                $status = $stmt->execute();
                if (!$status) {
                    throw new \Exception("C'è stato un errore nella richiesta al database.");
                }
            }
    
            $sql = "INSERT INTO bigliettiCarrello(biglietto,utente,dataOrarioOrdine,quantita) VALUES (?,?,?,?);";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('sssi', $ticketID, $username, $currDate, $quantita);
            $status = $stmt->execute();
            if (!$status) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function confirmPayment($username){
        try{

            $sql = "INSERT INTO ordini(utente,dataOrarioOrdine) VALUES (?,?)";
            $stmt = $this->conn->prepare($sql);
            $date = new DateTime("now", new DateTimeZone("Europe/Rome"));
            $currDate = $date->format("Y-m-d H:i:s");
            $stmt->bind_param('ss', $username, $currDate);
            $status = $stmt->execute();
            if (!$status) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }

            $sql = "SELECT  *
                        FROM ordini
                        WHERE utente=? AND dataOrarioOrdine=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ss', $username, $currDate);
            $result = $stmt->execute();
            if (!$result) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
            
            $row = $stmt->get_result();
            while ($ordine = $row->fetch_assoc()) {
                $sql = "SELECT * 
                        FROM bigliettiCarrello
                        WHERE utente=?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param('s', $username);
                $cartResult = $stmt->execute();
                if (!$cartResult) {
                    throw new \Exception("C'è stato un errore nella richiesta al database.");
                }
                $cartResult = $stmt->get_result();
                $sql = "SELECT  * FROM biglietti WHERE id=?";
                $dettagliBigliettoStmt = $this->conn->prepare($sql);
                while ($ticket = $cartResult->fetch_assoc()) {
                    $dettagliBigliettoStmt->bind_param('s', $ticket['biglietto']);
                    $dettagliBigliettoStmt->execute();
                    $result = $dettagliBigliettoStmt->get_result();
                    $tipiBiglietti = $result->fetch_all(MYSQLI_ASSOC);
                    $costo = $tipiBiglietti[0]['costo'];
                    for($i=0; $i<$ticket['quantita']; $i++){  
                        $sql = "INSERT INTO bigliettiAcquistati(tipoBiglietto,ordine,sommaPagata) VALUES (?,?,?)";
                        $stmt = $this->conn->prepare($sql);
                        $stmt->bind_param('sid',$ticket['biglietto'],$ordine['id'],$costo);
                        $status = $stmt->execute();
                        if (!$status) {
                            throw new \Exception("C'è stato un errore nella richiesta al database.");
                        }
                    }
                }
            }
            $this->clearCart($username);
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    public function clearCart($username){
        try{
            $sql = "DELETE FROM bigliettiCarrello WHERE utente=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $result = $stmt->execute();
            if (!$result) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }

    function updateCart($ticketID, $username, $operation){
        try{
            $sql = "SELECT *
            FROM bigliettiCarrello
            WHERE biglietto=? AND utente=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ss', $ticketID, $username);
            $status = $stmt->execute();
            if (!$status) {
                throw new \Exception("C'è stato un errore nella richiesta al database.");
            }
    
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                if($operation == "add"){
                    $newQuantita = $row['quantita'] + 1;
                }
                else{
                    $newQuantita = $row['quantita'] - 1;
                }
                $sql = "UPDATE bigliettiCarrello
                        SET quantita = $newQuantita 
                        WHERE biglietto=? AND utente=?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param('ss', $ticketID, $username);
                $status = $stmt->execute();
                if (!$status) {
                    throw new \Exception("C'è stato un errore nella richiesta al database.");
                }
            }
        } catch(Throwable $exception){
            header('Location: 500.php');
        }
    }
}
