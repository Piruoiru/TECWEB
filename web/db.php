<?php
// I metodi get restituiscono un oggetto mysqli_result, i metodi fetch restituiscono un array associativo
ini_set('display_errors',0);
include_once 'config.php';
class DatabaseClient
{
    private $conn;
    public function __construct() {}

    public function connect()
    {
        $this->conn = new mysqli(DB_CONFIG['db_host'], DB_CONFIG['db_user'], DB_CONFIG['db_pass'], DB_CONFIG['db_name']);

        // Check connection
        if ($this->conn->connect_error) {
            throw new \Exception("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function close()
    {
        $this->conn->close();
    }

    public function fetchUser($username)
    { //FIXME: cambiare in getUser
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $result = $stmt->execute();
        if (!$result) {
            throw new \Exception("Error querying the database");
        }
        return $stmt->get_result();
    }

    public function login($username, $password)
    {
        $sql = "SELECT * FROM users WHERE username = ? AND password=?";
        $stmt = $this->conn->prepare($sql);
        $password = hash('sha256', $password);
        $stmt->bind_param('ss', $username, $password);
        $result = $stmt->execute();
        if (!$result) {
            throw new \Exception("Error querying the database");
        }
        $result = $stmt->get_result()->num_rows;
        return $result === 0 ? false : true;
    }

    public function register($name, $surname, $username, $password)
    {
        $sql = "INSERT INTO users (nome, cognome, username, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $password = hash('sha256', $password);
        $stmt->bind_param('ssss', $name, $surname, $username, $password);
        return $stmt->execute(); //FIXME: chiusura prepared stmt
    }

    public function updateUserDetails($name, $surname, $username)
    {
        $sql = "UPDATE users SET nome=?, cognome=? WHERE username=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sss', $name, $surname, $username);
        return $stmt->execute();
    }

    public function deleteUser($username)
    {
        $sql = "DELETE FROM users WHERE username=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $username);
        return $stmt->execute();
    }

    public function fetchCart($username)
    {
        $sql = "SELECT * 
                    FROM bigliettiCarrello 
                    INNER JOIN biglietti ON bigliettiCarrello.biglietto = biglietti.id
                    WHERE utente=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $tickets = [];
        if (!$stmt->execute()) {
            throw new \Exception("Error querying the database");
        }
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            array_push($tickets, $row);
        }
        return $tickets;
    }

    public function fetchEvents()
    { //TODO: cambiare a getEvents
        $sql = "SELECT * FROM spettacoli";
        $result = $this->conn->query($sql);
        if (!$result) {
            throw new \Exception("Error querying the database");
        }
        return $result;
    }

    public function fetchTickets()
    {
        $sql = "SELECT * FROM tipiBiglietto";
        $result = $this->conn->query($sql);
        $tickets = [];
        if (!$result) {
            throw new \Exception("Error querying the database");
        }
        while ($row = $result->fetch_assoc()) {
            array_push($tickets, $row);
        }
        return $tickets;
    }

    public function fetchUserCartTickets($username)
    {
        $sql = "SELECT *
                    FROM bigliettiCarrello
                    INNER JOIN biglietti on bigliettiCarrello.biglietto = biglietti.id 
                    WHERE utente=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $result = $stmt->execute();
        if (!$result) {
            throw new \Exception("Error querying the database");
        }
        $result = $stmt->get_result();
        $tickets = [];
        while ($row = $result->fetch_assoc()) {
            array_push($tickets, $row);
        }
        return $tickets;
    }

    public function fetchUserAcquiredTickets($username)
    {
        $sql = "SELECT bigliettiAcquistati.id as idBiglietto, tipoBiglietto, ordine as idOrdine, sommaPagata, utente, dataOrarioOrdine FROM bigliettiAcquistati
                    INNER JOIN ordini ON bigliettiAcquistati.ordine = ordini.id
                    WHERE ordini.utente=?
                    ORDER BY ordini.dataOrarioOrdine DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $result = $stmt->execute();
        if (!$result) {
            throw new \Exception("Error querying the database");
        }
        $result = $stmt->get_result();
        $tickets = [];
        while ($row = $result->fetch_assoc()) {
            array_push($tickets, $row);
        }
        return $tickets;
    }

    public function fetchUserLastOrderTickets($username)
    {
        $sql = "SELECT bigliettiAcquistati.id as idBiglietto, tipoBiglietto, ordine as idOrdine, sommaPagata, utente, dataOrarioOrdine FROM bigliettiAcquistati
                    INNER JOIN (SELECT id as idOrdine, utente, dataOrarioOrdine FROM ordini
                    WHERE utente=?
                    ORDER BY dataOrarioOrdine DESC
                    LIMIT 1) AS ordini
                    ON bigliettiAcquistati.ordine = ordini.idOrdine";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $result = $stmt->execute();
        if (!$result) {
            throw new \Exception("Error querying the database");
        }
        $result = $stmt->get_result();
        $tickets = [];
        while ($row = $result->fetch_assoc()) {
            array_push($tickets, $row);
        }
        return $tickets;
    }

    public function fetchOrdersByUser($username)
    {
        $sql = "SELECT * FROM ordini WHERE utente=? ORDER BY dataOrarioOrdine DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $result = $stmt->execute();
        if (!$result) {
            throw new \Exception("Error querying the database");
        }
        $result = $stmt->get_result();
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            array_push($orders, $row);
        }
        return $orders;
    }

    public function fetchTicketsByOrder($orderID)
    {
        $sql = "SELECT * FROM bigliettiAcquistati WHERE ordine=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $orderID);
        $result = $stmt->execute();
        if (!$result) {
            throw new \Exception("Error querying the database");
        }
        $result = $stmt->get_result();
        $tickets = [];
        while ($row = $result->fetch_assoc()) {
            array_push($tickets, $row);
        }
        return $tickets;
    }

    public function createShow($title, $description, $imgPath, $imgDescription)
    {
        $sql = "SELECT * FROM spettacoli WHERE titolo=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $title);
        $status = $stmt->execute();
        if (!$status) {
            return "Error querying the database";
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
            return "Error querying the database";
        }
        return "Inserimento avvenuto con successo!";
    }

    public function modifyShow($oldTitle, $title, $description, $imgPath, $imgDescription)
    {
        $sql = "UPDATE spettacoli 
                    SET titolo=?, descrizione=?, percorso_immagine=?, descrizione_immagine=?
                    WHERE titolo=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sssss', $title, $description, $imgPath, $imgDescription,$oldTitle);
        $status = $stmt->execute();
        if (!$status) {
            throw new \Exception("Error querying the database");
        }
    }

    public function deleteShow($title)
    {
        $sql = "DELETE FROM spettacoli WHERE titolo=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $title);
        $status = $stmt->execute();
        if (!$status) {
            throw new \Exception("Error querying the database");
        }
    }

    public function addTicketToCart($ticketID, $username, $quantita)
    {
        $sql = "SELECT *
                    FROM bigliettiCarrello
                    WHERE biglietto=? AND utente=?";
        $stmt = $this->conn->prepare($sql);
        $currDate = date("Y-m-d H:i:s");
        $stmt->bind_param('ss', $ticketID, $username);
        $status = $stmt->execute();
        if (!$status) {
            throw new \Exception("Error querying the database");
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
                throw new \Exception("Error querying the database");
            }
        }

        $sql = "INSERT INTO bigliettiCarrello(biglietto,utente,dataOrarioOrdine,quantita) VALUES (?,?,?,?);";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sssi', $ticketID, $username, $currDate, $quantita);
        $status = $stmt->execute();
        if (!$status) {
            throw new \Exception("Error querying the database");
        }
    }

    public function confirmPayment($username,$sommaInt,$sommaRid)
    {
        $sql = "INSERT INTO ordini(utente,dataOrarioOrdine) VALUES (?,?)";
        $stmt = $this->conn->prepare($sql);
        $currDate = date("Y-m-d H:i:s");
        $stmt->bind_param('ss', $username, $currDate);
        $status = $stmt->execute();
        if (!$status) {
            throw new \Exception("Error querying the database");
        }

        $sql = "SELECT  *
                    FROM ordini
                    WHERE utente=? AND dataOrarioOrdine=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $username, $currDate);
        $result = $stmt->execute();
        if (!$result) {
            throw new \Exception("Error querying the database");
        }
        
        $row = $stmt->get_result();
        while ($ordine = $row->fetch_assoc()) {
            $sql = "SELECT * 
                    FROM bigliettiCarrello
                    WHERE utente=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $result = $stmt->execute();
            if (!$result) {
                throw new \Exception("Error querying the database");
            }
            $result = $stmt->get_result();
            while ($ticket = $result->fetch_assoc()) {
                    if ($ticket['biglietto'] == '1') {
                    $biglietto = 'Biglietto Ridotto';
                    $sql = "INSERT INTO bigliettiAcquistati(tipoBiglietto,ordine,sommaPagata) VALUES (?,?,?)";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bind_param('sid',$biglietto,$ordine['id'],$sommaRid);
                    $status = $stmt->execute();
                    if (!$status) {
                        throw new \Exception("Error querying the database");
                    }
                } else {
                    $biglietto = 'Biglietto Intero';
                    $sql = "INSERT INTO bigliettiAcquistati(tipoBiglietto,ordine,sommaPagata) VALUES (?,?,?)";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bind_param('sid',$biglietto,$ordine['id'],$sommaInt);
                    $status = $stmt->execute();
                    if (!$status) {
                        throw new \Exception("Error querying the database");
                    }
                }
            }
        }
        $this->clearCart($username);
    }

    public function clearCart($username)
    {
        $sql = "DELETE FROM bigliettiCarrello WHERE utente=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $result = $stmt->execute();
        if (!$result) {
            throw new \Exception("Error querying the database");
        }
    }

    function updateCart($ticketID, $username, $operation)
    {
        $sql = "SELECT *
        FROM bigliettiCarrello
        WHERE biglietto=? AND utente=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $ticketID, $username);
        $status = $stmt->execute();
        if (!$status) {
            throw new \Exception("Error querying the database");
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
                throw new \Exception("Error querying the database");
            }
        }
    }
}
