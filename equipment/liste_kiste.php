<?php
########################################################################
# Verleih Modul for dotlan                                 			   #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/leisystem/export.php - Version 1.0                             #
########################################################################

$MODUL_NAME = "equipment";
include_once("../../../global.php");
include("../functions.php");
include("equipment_functions.php");
require('../fpdf16/fpdf.php');
global $global;

if(!$DARF["view"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));
else
{// $module_admin_check
$URL = "http://".$_SERVER["SERVER_NAME"].$global['project_path'];
//echo $URL;
$wo = "";
if(isset($_GET['kiste'])){ 		$wo = " WHERE  kiste 	= '".$_GET['kiste']."' 		";}


$sql = mysql_query("SELECT * FROM `project_equipment` ".$wo."; ");


// bezeichnung, eq ID,  Hersteller, 


 $pdf= new FPDF('P', 'mm', 'A5');
$pdf->AddPage();

$daten = list_equipment_single($_GET['kiste']);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,0, $daten['bezeichnung'], 0, 0, 'C');
$pdf->Cell(0,5,'',0,1); //<hr />
$pdf->Cell(0,5,'',0,1); //<hr />

$pdf->SetFont('Arial','B',10);				
				$pdf->Cell(45,5,	"Bezeichnung");
				$pdf->Cell(45,5,	"Equipment ID");
				$pdf->Cell(20,5,	"Hersteller");
				$pdf->Cell(0,5,'',0,1); //<hr />
				$pdf->Cell(0,0,'',1,1); //<hr />
				
while($out_equip = $DB->fetch_array($sql))
{	$pdf->SetFont('Arial','',10);				
	//$pdf->Cell(80,0,'',1,1); //<hr />
	$pdf->Cell(45,5,	$out_equip['bezeichnung'] );
	$pdf->Cell(45,5,	"eq".sprintf("%06d",$out_equip['id']));
	$pdf->Cell(20,5,	$out_equip['hersteller']);
	$pdf->Cell(0,5,'',0,1); //<hr />
	$pdf->Cell(0,0,'',1,1); //<hr />
}

	

  
  # Barcode

	// $pdf->Image($URL."/barcode/img_string.php?text=eq".$barcode."&tmp=.png",40,10,50,10);
	// $pdf->SetFont('Arial','',8);
	// $pdf->text(60,23,"eq".$barcode);
	// $pdf->SetFont('Arial','B',5);
	// $pdf->text(4,3,"Bezeichnung");
	// $pdf->SetFont('Arial','',9);
	// //$pdf->text(2,5,$out_equip['invnr']);
	// $pdf->text(4,6,$out_equip['bezeichnung']);
	// //$pdf->text(50,5,$out_equip['bezeichnung']);
		// $pdf->SetFont('Arial','B',5);
		// $pdf->text(4,10,"Lagerort");
		// $pdf->SetFont('Arial','',7);
		// $pdf->text(4,13,$lagerort['bezeichnung']);
	// $pdf->SetFont('Arial','B',5);
	// $pdf->text(4,16,"Zusatzinfo");
	// $pdf->SetFont('Arial','',7);
	// $pdf->text(4,19,$out_equip['zusatzinfo']);
	// $pdf->SetFont('Arial','B',7);
	// $pdf->text(69,26,"www.maxlan.de");
	
	//$pdf->Image($URL."/barcode/img_string.php?text=eq".$barcode."&tmp=.png",1,3,48,10);
	//$pdf->SetFont('Arial','',8);
	//$pdf->text(17,16,"eq".$barcode);
	//$pdf->SetFont('Arial','B',9);
	//$pdf->text(17,22,$out_equip['invnr']);
	//$pdf->SetFont('Arial','B',6);
	//$pdf->text(2,27,$out_equip['kiste']);


$pdf->Output();

}
?>
