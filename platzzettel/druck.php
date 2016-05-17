<?php
########################################################################
# Sitzpplatzzettel Modul for dotlan       		                         #
#                                                                      #
# Copyright (C) 2013 Torsten Amshove <torsten@amshove.net>             #
#                                                                      #
########################################################################

include("../../../global.php");
include("../functions.php");

require('../fpdf16/fpdf.php');
$event_id = $EVENT->next;
//$event_id = 6;

if(!$module_admin_check && !$ADMIN->check(IS_ADMIN)) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

$sitze = array();
$bloecke = array("A","B","C","D","E","F","G","H");
foreach($bloecke as $b) for($i=1;$i<=24;$i++) $sitze["Sitz: $b-".str_pad($i,2,"0",STR_PAD_LEFT)] = array(); // User Bloecke
for($i=1;$i<=18;$i++) $sitze["Sitz: V-".str_pad($i,2,"0",STR_PAD_LEFT)] = array(); // VIP

$sql_event_user = $DB->query("SELECT * FROM `event_teilnehmer` WHERE event_id = ".$event_id." AND sitz_nr <> '' ORDER BY sitz_nr ");

while($event_user = $DB->fetch_array($sql_event_user)){
  $user = $DB->query_first(" SELECT * FROM user WHERE id = '".$event_user['user_id']."' ");

  $sitze[$event_user["sitz_nr"]]["nick"] = $user["nick"];
  $sitze[$event_user["sitz_nr"]]["vorname"] = $user["vorname"];
  $sitze[$event_user["sitz_nr"]]["nachname"] = $user["nachname"];
}

$pdf=new FPDF();

$i=0;
foreach($sitze as $sitz => $user){
  preg_match("/([A-Z])-([0-9]+)/",$sitz,$matches);
  $switchport = intval($matches[2]);

  if($i % 2 == 0){ // Erster Block auf einer Seite
    $pdf->AddPage();
    $pdf->Cell(0,10,"");
    $pdf->Ln();
    $logo_y = 110;
  }else{ // Zweiter Block auf einer Seite
    $pdf->Ln();
    $pdf->Cell(0,50,"");
    $pdf->Ln();
    $logo_y = 253;
  }

  $pdf->SetFont('Arial','B',60);
  $pdf->Cell(0,40,$sitz,0,0,"C");
  $pdf->Ln();
  $pdf->SetFont('Arial','',11);

  if(empty($user["vorname"]) && empty($user["nachname"]) && empty($user["nick"])) $text = "Hier sitzt derzeit noch niemand. Don't Panic!";
  else $text = "Hier sitzt ".$user["vorname"]." ".$user["nachname"]." alias ".$user["nick"];

  $pdf->Cell(0,15,$text,0,0,"C");
  $pdf->Ln();
  $pdf->Cell(0,0,"Platz".utf8_decode("ä")."nderungen bitte beim Support-Team melden.",0,0,"C");
  $pdf->Ln();
  $pdf->SetFont('Arial','U',11);
  $pdf->Cell(0,40,"Bitte das bereitgestellte Netzwerkkabel mit der Nummer $switchport benutzen!",0,0,"C");
  #$pdf->Image("logo.gif",55,$logo_y,100);
  $pdf->Image("logo_graustufen.jpg",55,$logo_y,100);

  $i++;
}
$pdf->Output();
?>
