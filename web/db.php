<?php
    include_once 'config.php';
    class DatabaseClient{
        private $conn;
        public function __construct(){
        }

        public function connect() {
            $this->conn = new mysqli(DB_CONFIG['db_host'],DB_CONFIG['db_user'],DB_CONFIG['db_pass'],DB_CONFIG['db_name']);
        
            // Check connection
            if ($this->conn->connect_error) {
                throw new \Exception("Connection failed: " . $this->conn->connect_error);
            }
        }

        public function close() {
            $this->conn->close();
        }

        public function fetchUser($username){
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s',$username);
            $result = $stmt->execute();
            if(!$result){
                throw new \Exception("Error querying the database");
            }
            return $stmt->get_result();
        }

        public function login($username,$password){
            $sql = "SELECT * FROM users WHERE username = ? AND password=?";
            $stmt = $this->conn->prepare($sql);
            $password = hash('sha256',$password);
            $stmt->bind_param('ss',$username,$password);
            $result = $stmt->execute();
            if(!$result){
                throw new \Exception("Error querying the database");
            }
            $result = $stmt->get_result()->num_rows;
            return $result === 0 ? false : true;
        }

        public function register($name, $surname, $username, $password){
            $sql = "INSERT INTO users (name, surname, username, password) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $password = hash('sha256',$password);
            $stmt->bind_param('ssss', $name, $surname, $username ,$password);
            return $stmt->execute();//FIXME: chiusura prepared stmt
        }

        public function fetchCart($username){
            $sql = "SELECT * 
                    FROM bigliettiCarrello 
                    INNER JOIN biglietti ON bigliettiCarrello.biglietto = biglietti.id
                    WHERE utente=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s',$username);
            $tickets = [];
            if(!$stmt->execute()){
                throw new \Exception("Error querying the database");
            }
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
                array_push($tickets,$row);
            }
            return $tickets;
        }

        public function fetchEvents(){
            $sql = "SELECT * FROM spettacoli";
            $result = $this->conn->query($sql);
            if(!$result){
                throw new \Exception("Error querying the database");
            }
            return $result;
        }

        public function fetchTickets(){
            $sql = "SELECT * FROM biglietti";
            $result = $this->conn->query($sql);
            $tickets = [];
            if(!$result){
                throw new \Exception("Error querying the database");
            }
            while($row = $result->fetch_assoc()){
                array_push($tickets,$row);
            }
            return $tickets;
        }

        public function fetchUserCartTickets($username){
            $sql = "SELECT *
                    FROM bigliettiCarrello
                    INNER JOIN biglietti on bigliettiCarrello.biglietto = biglietti.id 
                    WHERE utente=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s',$username);
            $result = $stmt->execute();
            if(!$result){
                throw new \Exception("Error querying the database");
            }
            $result = $stmt->get_result();
            $tickets = [];
            while($row = $result->fetch_assoc()){
                array_push($tickets,$row);
            }
            return $tickets;
        }

        public function fetchUserAcquiredTickets($username){
            $sql = "SELECT *
                    FROM bigliettiAcquistati
                    INNER JOIN biglietti ON bigliettiAcquistati.biglietto = biglietti.id
                    WHERE utente=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s',$username);
            $result = $stmt->execute();
            if(!$result){
                throw new \Exception("Error querying the database");
            }
            $result = $stmt->get_result();
            $tickets = [];
            while($row = $result->fetch_assoc()){
                array_push($tickets,$row);
            }
            return $tickets;
        }

        public function addTicketToCart($ticketID,$username,$quantity){
            $sql = "SELECT *
                    FROM bigliettiCarrello
                    WHERE biglietto=? AND utente=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ss',$ticketID,$username);
            $status = $stmt->execute();
            if(!$status){
                throw new \Exception("Error querying the database");
            }
            $result = $stmt->get_result();
            if($result->num_rows !== 0){
                $sql = "UPDATE bigliettiCarrello
                        SET quantita=?
                        WHERE biglietto=? AND utente=?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param('iss',$quantity,$ticketID,$username);
                $status = $stmt->execute();
                if(!$status){
                    throw new \Exception("Error querying the database");
                }
                return;
            }
            $sql = "INSERT INTO bigliettiCarrello(biglietto,utente,quantita) VALUES (?,?,?);";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param('ssi',$ticketID,$username,$quantity);
                $status = $stmt->execute();
                if(!$status){
                    throw new \Exception("Error querying the database");
                }
        }

        public function confirmPayment($username){
            $sql = "SELECT * 
                    FROM bigliettiCarrello
                    WHERE utente=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s',$username);
            $result = $stmt->execute();
            if(!$result){
                throw new \Exception("Error querying the database");
            }
            $result = $stmt->get_result();
            while($ticket = $result->fetch_assoc()){
                $sql = "INSERT INTO bigliettiAcquistati(biglietto,utente,quantita,dataOrarioAcquisto) VALUES (?,?,?,?)";
                $stmt = $this->conn->prepare($sql);
                $currDate = date("Y-m-d H:i:");
                $stmt->bind_param('ssss',$ticket['biglietto'],$username,$ticket['quantita'],$currDate);
                $status = $stmt->execute();
                if(!$status){
                    throw new \Exception("Error querying the database");
                }
            }
            $this->clearCart($username);
        }

        public function clearCart($username){
            $sql = "DELETE FROM bigliettiCarrello WHERE utente=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s',$username);
            $result = $stmt->execute();
            if(!$result){
                throw new \Exception("Error querying the database");
            }
        }
    }    
?>
