<?php
    define("DB_CONFIG", [
        'db_host' => 'db',
        'db_name' => getenv("MYSQL_DATABASE"),
        'db_user' => getenv("MYSQL_USER"),
        'db_pass' => getenv("MYSQL_PASSWORD"),
    ]);
?>