<?php
require 'calendar.php';
if (isset($_GET['month'])) {
    $meseCorrente = $_GET['month'];
} else {
    $meseCorrente = date("Y-m-d");
}
$calendario = Calendar::create($meseCorrente);
?>
<!DOCTYPE html>
<html>

<head>
    <title>DreamVille</title>
    <link rel="shortcut icon" href="assets/logo.png" />
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="parco, divertimento, giostre, weekend, DreamVille, famiglie, attività">

    <link id="pagestyle" rel="stylesheet" type="text/css" href="style/style.css">
    <script src="scripts/script.js" defer></script>
    <style>
        .header-giorni {
            background: #E3E9E5;
            color: #536170;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .header-calendario {
            background: #8c9eff;
            color: white;
            height: 80px;
        }

        .box-giorno {
            border: 1px solid #E3E9E5;
            height: 15px;
            text-align: center;
            width: 600px;
        }

        .container {
            width: 525px;
        }
    </style>
</head>

<body>
    <header>
        <h2>Calendario di Apertura</h2>
        <p id="text">
            Controlla i nostri giorni di apertura e pianifica un soggiorno indimenticabile!<br>
            Nella sezione dedicata troverai tutte le informazioni sui giorni di apertura del Parco.<br>Stai cercando il calendario per programmare la tua visita o vuoi sapere gli orari per una data specifica? <br>Qui c’è tutto ciò che ti serve per organizzarti al meglio. Non vediamo l’ora di accoglierti!
            <br><br>
            Il parco si riserva il diritto di modificare giorni ed orari di apertura.
        </p>
    </header>
    <main>
        <h3>Calendario del Parco</h3>
        <header class="container">
            <header id="dateBox" class="header-calendario">
                <header class="col" style="display: flex; justify-content: space-between; padding: 10px;">
                    <a href="calendar.html.php?month=<?= $calendario["last"]; ?>">
                        <p style="font-size:20px;color:white;">Indietro</p>
                    </a>
                    <h2><?= $calendario["mese"]; ?> <small><?= $calendario["anno"]; ?></small></h2>
                    <a href="calendar.html.php?month=<?= $calendario["next"]; ?>">
                        <p style="font-size:20px;color:white;">Avanti</p>
                    </a>
                </header>
            </header>
            <table>
                <thead>
                    <tr>
                        <?php
                        foreach ($calendario['settimane'] as $nomeGiorno):
                        ?>
                            <th class="header-giorni">
                                <?php echo $nomeGiorno; ?>
                            </th>
                        <?php
                        endforeach;
                        ?>
                    </tr>
                    </article>
                <tbody>
                    <?php foreach ($calendario['calendario'] as $settimana): ?>
                        <tr>
                            <?php foreach ($settimana as $numGiorno): ?>
                                <td class="box-giorno">
                                    <?php echo $numGiorno; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </header>

        <h3>Show disponibili</h3>
        <dl>
            <dt id="title-show">Turbo Thrill: The Ultimate Stunt Show</dt>
            <dd>Descrizione: Preparati a rimanere senza fiato mentre i motori ruggiscono e le gomme bruciano nel più incredibile stunt show mai visto! Auto da corsa, moto acrobatiche e veicoli personalizzati sfidano le leggi della fisica con salti mozzafiato, inseguimenti ad alta velocità e manovre spettacolari. Uno show adrenalinico che ti farà battere il cuore a ogni curva!</dd>
            <dt id="title-show">Enigma: Il Mondo della Magia</dt>
            <dd>Enigma: Il Mondo della Magia
            <dd>Descrizione: Entra in un universo di incantesimi e mistero con Enigma, lo spettacolo di magia che trasforma l’impossibile in realtà. Illusionisti di fama mondiale ti stupiranno con levitazioni, sparizioni, e incredibili giochi di prestigio che coinvolgono il pubblico in un’esperienza unica. Un viaggio magico che lascerà grandi e piccoli a bocca aperta!</dd>
            <dt id="title-show">Fantasia Live: Un Classico da Sogno</dt>
            <dd>Descrizione: Lasciati trasportare in un mondo di musica, colori e avventure senza tempo con Fantasia Live, uno spettacolo teatrale che combina danza, acrobazie e scenografie mozzafiato. Ispirato ai grandi classici dell’intrattenimento, questo show celebra le storie che hanno fatto sognare generazioni, reinterpretandole in un formato unico, perfetto per tutta la famiglia. Un’esperienza emozionante e indimenticabile!</dd>
        </dl>
    </main>
    <footer>
        <h3>footer</h3>
        <ul>
            <li id="icon">Chi Siamo</li>
            <li id="icon">LOGO FACEBOOK</li>
            <li id="icon">LOGO INSTAGRAM</li>
            <li id="icon">LOGO TIK TOK</li>
            <li id="icon">LOGO YOUTUBE</li>
            <li href="pages/privicy.html">Privacy Policy</li>
        </ul>
    </footer>
</body>

</html>
