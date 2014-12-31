<?php
########################################################################
# Verleih Modul for dotlan                                 			   #
#                                                                      #
# Copyright (C) Christian Egbers <christian@cegbers.de>                #
#                                                                      #
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
if(isset($_GET['id'])){ 		$wo = " WHERE  id 		= '".$_GET['id']."' 		";}
if(isset($_GET['category'])){ 	$wo = " WHERE  category = '".$_GET['category']."' 	";}
if(isset($_GET['kiste'])){ 		$wo = " WHERE  kiste 	= '".$_GET['kiste']."' 		";}

$sql = mysql_query("SELECT * FROM `project_equipment` ".$wo."; ");

if(isset($_GET['lagerort'])){ 
	$pdf= new FPDF('P','mm',array(60,12));
	$sql = mysql_query("SELECT * FROM `project_equipment_lagerort` WHERE id = '".$_GET['lagerort']."' ");

 }
 else{
	$pdf= new FPDF('P','mm',array(90,29));
	$sql = mysql_query("SELECT * FROM `project_equipment` ".$wo."; ");

 }

while($out_equip = mysql_fetch_array($sql))
{
	

  $pdf->AddPage();
  # Barcode
  
	if(isset($_GET['lagerort']))
	{ 
		$pdf->SetFont('Arial','B',16);
		$pdf->text(4,8,$out_equip['bezeichnung'],1,1,'C');
	}
	else
	{	$barcode = sprintf("%06d",$out_equip['id']);
		$kiste = list_equipment_single($out_equip['kiste']);
		$lagerort = list_equipment_lagerort_single($out_equip['lagerort']);

		$pdf->Image($URL."/barcode/img_string.php?text=eq".$barcode."&tmp=.png",39,10,50,10);
		$pdf->SetFont('Arial','',8);
		$pdf->text(60,23,"eq".$barcode);
		
			if($out_equip['ist_kiste'] == 0)
			{
				$pdf->SetFont('Arial','B',5);
				$pdf->text(4,3,"Artikelbezeichnung");
			}
		$pdf->SetFont('Arial','',10);
		$pdf->text(4,6,$out_equip['bezeichnung']);
		
			if(!$out_equip['lagerort']){
				$pdf->SetFont('Arial','B',5);
				$pdf->text(4,10,"Behälter");
				$pdf->SetFont('Arial','',8);
				$pdf->text(4,13,$kiste['bezeichnung']);
			}
			else{
				$pdf->SetFont('Arial','B',5);
				$pdf->text(4,10,"Lagerort");
				$pdf->SetFont('Arial','',8);
				$pdf->text(4,13,$lagerort['bezeichnung']);
			}

		$pdf->SetFont('Arial','B',5);
		$pdf->text(4,16,"Zusatzinfo");
		$pdf->SetFont('Arial','',8);
		$pdf->text(4,19,$out_equip['zusatzinfo']);
		$pdf->SetFont('Arial','B',7);
		$pdf->text(67,26,"www.maxlan.de");
	}

}
$pdf->Output();

}
?>
