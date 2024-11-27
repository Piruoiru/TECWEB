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
            echo $result;
            return $result === 0 ? false : true;
        }

        public function register($username,$password){
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $password = hash('sha256',$password);
            $stmt->bind_param('ss', $username ,$password);
            return $stmt->execute();
        }

        public function fetchEvents(){
            $sql = "SELECT * FROM spettacoli";
            $result = $this->conn->query($sql);
            if(!$result){
                throw new \Exception("Error querying the database");
            }
            return $result;
        }
    }
    
?>