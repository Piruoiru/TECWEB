<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="/style/style.css">
    </head>
    <body>
        <?php
            define("DB_CONFIG", [
                'db_host' => 'db',
                'db_name' => getenv("MYSQL_DATABASE"),
                'db_user' => getenv("MYSQL_USER"),
                'db_pass' => getenv("MYSQL_PASSWORD"),
            ]);

            var_dump(DB_CONFIG);
            echo "<br>";
            
            $conn = new mysqli(DB_CONFIG['db_host'],DB_CONFIG['db_user'],DB_CONFIG['db_pass'],DB_CONFIG['db_name']);

            if($conn->connect_errno) {
                echo 'Connessione fallita: '. $conn->connect_error.'.<br>';
                echo 'Contattare l\'assistenza.';
                exit();
            }

            $query = 'SELECT * FROM users';

            $result = $conn->query($query);

            if($result->num_rows > 0){
                while($tmp = $result->fetch_array(MYSQLI_ASSOC)){
                    echo $tmp['username'] . "<br>"; 
                }
            }
            
            echo "<p>Hello World!</p>"; 
        
        ?>
        <img src="/assets/cat.png" alt="cat"></img>
    </body>
    <script src="/scripts/script.js"></script>
</html>