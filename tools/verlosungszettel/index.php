<?php
########################################################################
# Dienstplan Modul for dotlan       		                       #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/dienstplan/export.php - Version 1.0                            #
########################################################################

$MODUL_NAME = "tools";
include("../../../../global.php");
include("../../functions.php");
require('../../fpdf16/fpdf.php');

if(!$DARF["verlosungszettel"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

$event_id = $EVENT->next;

$out_event = $DB->fetch_array( $DB->query("SELECT * FROM `events` WHERE id = ".$event_id." LIMIT 1 ") );
$sql_user_anwesend = $DB->query("SELECT * FROM `event_teilnehmer` WHERE event_id = ".$event_id." AND anwesend > '".$out_event['begin']."' AND anwesend < '".$out_event['end']."' ");

if($DB->num_rows($sql_user_anwesend) < 1){
  echo "Es sind noch keine User anwesend.";
}else{
  $pdf=new FPDF();
  $pdf->AddPage();
  
  while($out_data = $DB->fetch_array($sql_user_anwesend)){
    $sql_user_data = $DB->query(" SELECT * FROM user WHERE id = '".$out_data['user_id']."' ");
    while($out_user_data = $DB->fetch_array($sql_user_data)){// begin while
      $pdf->SetFont('Arial','',14);
      $pdf->Ln();
      $pdf->Cell(30,5, "",0,1);
      $pdf->Cell(30,5,  $out_event['name']." - Verlosung",0,1 );
  
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(170,0,'',1,1); //<hr />
      $pdf->Ln();
      $pdf->Cell(30,5, "",0,1);
      $pdf->Cell(30,5,  "ID: ".$out_user_data['id'] );
      $pdf->Ln();
      $pdf->Cell(30,5,  "Nick: ".$out_user_data['nick']);
      $pdf->Ln();
      $pdf->Cell(30,5,  "Name: ".$out_user_data['vorname']." ".$out_user_data['nachname']);
  
      $pdf->Cell(30,5, "",0,1);
      $pdf->Ln();
      $pdf->Cell(170,0,'',1,1); //<hr />
      $pdf->Cell(30,5, "",0,1);
    }
  }
  $pdf->Output();
}
?>
