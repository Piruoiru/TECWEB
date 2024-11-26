<?php   
    session_start();
    
    include_once 'parser.php';
    $template = new Parser();
    $template->setTokenizer(new Tokenizer());

    include_once 'db.php';
    $conn = db_connect();
    $sql = 'SELECT * FROM spettacoli';
    $result = $conn->query($sql);
    if(!$result){
        echo "Couldn't query the database";
        exit();
    }
    $context = ['result'=>[]];
    while($row = $result->fetch_assoc()){
        array_push($context['result'],$row);
        //echo '<div><h3>'.$row['titolo'].'</h3><p>'.$row['descrizione'].'</p></div>'; //@MPiron e @Salvi qua viene 
        //creata la singola card di uno spettacolo, se volete modificarla dovete farlo qui
    }
    $template->render("index.html",$context);
?>
