<!DOCTYPE html>
<html>
    <head>
        <title>Mirabilia Park</title>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="parco, divertimento, giostre, weekend, mirabiliapark">

        <link id="pagestyle" rel="stylesheet" type="text/css" href="large.css">
        <script>
        function layoutHandler(){
            var styleLink = document.getElementById("pagestyle");
            if(window.innerWidth < 800){
                styleLink.setAttribute("href", "/style/resized.css");
            }else {
                styleLink.setAttribute("href", "/style/style.css");
            }
        }
        window.onresize = layoutHandler;
        layoutHandler();
        </script>
    </head>
    <body>
        <div class="time-bar">
            <p>Orario di oggi del parco: 10.00-23.00</p>
        </div> 
        <header>
            <nav id="menu">
            <ul class="nav-links">
                <div class="logo"><img src="" alt="Mirabilia Park"></div>
                <li><a href="#">Esplora Mirabilia Park</a></li>
                <li><a href="#">Show e Spettacoli</a></li>
                <li><a href="#">Informazioni Utili</a></li>
                <li><a href="#">Acquista Biglietti</a></li>
            </ul>
            </nav>


        </header>
    