<?php
########################################################################
# Notiz Modul for dotlan             			                   	   #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/notiz/export.php - Version 1.0                                 #
########################################################################

$MODUL_NAME = "sponsoren";
include_once("../../../global.php");
include("../functions.php");
include("sponsoren_functions.php");

$zeilen = 0;
$sql_list_sponsor = sponsoren_list_sponsor($event_id);

	require('../fpdf16/fpdf.php');
	$pdf=new FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',20);
	$pdf->Cell(22,5,ucfirst($sitename)." ".ucfirst($MODUL_NAME)." Artikel");
	$pdf->Ln();
	# Leerraum
	$pdf->Cell(0,5,'');
	$pdf->Ln();
	# Header
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(90,5,"Name: ", 0, 0);
	$pdf->Cell(30,5,"Marke", 0, 0);
	$pdf->Cell(10,5,"Anzahl", 0, 0);
	$pdf->Cell(10,5,"Wert", 0, 0);
	$pdf->Cell(25,5,"Gesamt Wert", 0, 0);
	$pdf->Cell(18,5,"Vorhanden", 0, 0);
	$pdf->Cell(10,5,"Fehlt", 0, 0, 'R');
	
	while($out_sponsor = mysql_fetch_array($sql_list_sponsor))
		{// begin while
			$sql = sponsoren_list_artikel_by_sponsor($out_sponsor['s_id']);
			$out_sponsorendaten = sponsoren_list_sponsor_single($out_sponsor['s_id']);
			$zeilen = $zeilen + 2;
			$anz_artikel = $anz_artikel + mysql_num_rows($sql);
			
			if($anz_artikel >= 34)
			{$anz_artikel = 0;
				$pdf->AddPage();
				# Header
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(90,5," Name: ", 0, 0);
				$pdf->Cell(30,5,"Marke", 0, 0);
				$pdf->Cell(10,5,"Anzahl", 0, 0);
				$pdf->Cell(10,5,"Wert", 0, 0);
				$pdf->Cell(25,5,"Gesamt Wert", 0, 0);
				$pdf->Cell(18,5,"Vorhanden", 0, 0);
				$pdf->Cell(10,5,"Fehlt", 0, 0, 'R');
				
			}
			$pdf->Ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(0,5,$out_sponsorendaten['name'], 0, 0);
			$pdf->Ln();
			
			
			while($out = mysql_fetch_array($sql))
			{
				$ges_preis = sponsoren_ges_wert($out['sp_art_anz'],$out['sp_art_wert']);
				
				$pdf->SetFont('Arial','',8);
				$pdf->Cell(90,5,$out['sp_art_name'], 'TB', 0);
				$pdf->Cell(30,5,$out['sp_art_marke'], 'TB', 0);
				$pdf->Cell(10,5,$out['sp_art_anz'], 'TB', 0);
				$pdf->Cell(15,5,$out['sp_art_wert'].' €', 'TB', 0);
				$pdf->Cell(15,5,$ges_preis, 'TB', 0,'R');
				$pdf->Cell(10,5,'', '', 0,'R');
				$pdf->Cell(5,5,'', 'TBLR', 0,'R');
				$pdf->Cell(10,5,'', '', 0,'R');
				$pdf->Cell(5,5,'', 'TBLR', 0,'R');
				$pdf->Ln();
				
			
			}
		}

  $pdf->Output( ucfirst($sitename).' '.ucfirst($MODUL_NAME).' Artikel', 'I'); 

?>