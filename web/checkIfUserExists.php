<?php
$username = $_REQUEST["username"];

include_once 'db.php';

if ($username !== ""){
    $db = new DatabaseClient();
    $db->connect();
    $result = $db->fetchUser($username);

    if($result->num_rows > 0){
        echo true;
        exit();
    }
}
echo false;
?>