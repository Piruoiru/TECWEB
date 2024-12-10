<?php
    /*include_once 'parser.php';
    $template = new Parser();
    $template->render('calendar.html.php');
    class Calendar*/
{
  public static function create($month)
  {
    $data = $month;
    $giorniSett = ['Lunedì','Martedì','Mercoledì','Giovedì','Venerdì','Sabato','Domenica'];
    $giorniDelMese = date("t",strtotime($data));
    $primoGiorno = date("N",strtotime($data));
    $giorni = array_fill(0, ($primoGiorno - 1), '');

    for ($i = 1; $i <= $giorniDelMese; $i++) {
      $giorni[] = $i;
    }

    $calendario = array_chunk($giorni, 7);
    $nextMese = date("Y-M", strtotime($data . "+ 1 month"));
    $lastMese = date("Y-M", strtotime($data . "- 1 month"));
    $mese = date("M", strtotime($data));
    $annoMese = date("Y", strtotime($data));

    $data = array(
      'next' => $nextMese,
      'mese' => $mese,
      'anno' => $annoMese,
      'last' => $lastMese,
      'calendario' => $calendario,
      'settimane' => $giorniSett,
    );

    return $data;
  }
}
?>
