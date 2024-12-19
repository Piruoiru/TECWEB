<?php
/*include_once 'parser.php';
    $template = new Parser();
    $template->render('calendar.html.php');*/
class Calendar
{
  public static function create($month)
  {
    $data = $month;
    $giorniSett = ['Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato', 'Domenica'];
    $giorniDelMese = date("t", strtotime($data));
    $primoGiorno = date("N", strtotime($data));
    $giorni = array_fill(0, ($primoGiorno - 1), '');

    for ($i = 1; $i <= $giorniDelMese; $i++) {
      $giorni[] = $i;
    }

    $calendario = array_chunk($giorni, 7);
    $nextMese = date("Y-M", strtotime($data . "+ 1 month"));
    $lastMese = date("Y-M", strtotime($data . "- 1 month"));
    $mesee = date("M", strtotime($data));
    $annoMese = date("Y", strtotime($data));

    $data = array(
      'next' => $nextMese,
      'mese' => $mesee,
      'anno' => $annoMese,
      'last' => $lastMese,
      'calendario' => $calendario,
      'settimane' => $giorniSett,
    );

    return $data;
  }

  public static function meseIT($month)
  {
    $mese = $month;
    if ($month == "Jan") {
      $mese = "Gennaio";
    } elseif ($month == "Feb") {
      $mese = "Febbraio";
    } elseif ($month == "Mar") {
      $mese = "Marzo";
    } elseif ($month == "Apr") {
      $mese = "Aprile";
    } elseif ($month == "May") {
      $mese = "Maggio";
    } elseif ($month == "Jun") {
      $mese = "Giugno";
    } elseif ($month == "Jul") {
      $mese = "Luglio";
    } elseif ($month == "Aug") {
      $mese = "Agosto";
    } elseif ($month == "Sep") {
      $mese = "Settembre";
    } elseif ($month == "Oct") {
      $mese = "Ottobre";
    } elseif ($month == "Nov") {
      $mese = "Novembre";
    } elseif ($month == "Dec") {
      $mese = "Dicembre";
    } else {
      $mese = $month;
    }
    return $mese;
  }
}
