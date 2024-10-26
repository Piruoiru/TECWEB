<?php
    session_start();

    /*if(isset($_SESSION['user'])){
        echo '<p>Hello '.$_SESSION['user'].'!';
    } else {
        echo '<p>Hello Stranger!</p>';
    }*/

    include_once 'header.php';
    include_once 'db.php';

    $conn = db_connect();
    $sql = 'SELECT * FROM spettacoli';
    $result = $conn->query($sql);
    if(!$result){
        echo "Couldn't query the database";
        exit();
    }
    while($row = $result->fetch_assoc()){
        //echo '<div><h3>'.$row['titolo'].'</h3><p>'.$row['descrizione'].'</p></div>'; //@MPiron e @Salvi qua viene 
        //creata la singola card di uno spettacolo, se volete modificarla dovete farlo qui
    }
?>
    <body>
        
    </body>
    


<?php

include_once 'footer.php';

?>
