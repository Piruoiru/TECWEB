<?php
    include_once 'config.php';
    
    function db_connect() {
        $conn = new mysqli(DB_CONFIG['db_host'],DB_CONFIG['db_user'],DB_CONFIG['db_pass'],DB_CONFIG['db_name']);
    
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        return $conn;
    }
?>