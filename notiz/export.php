<?
########################################################################
# Notiz Modul for dotlan             			                   	   #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/notiz/export.php - Version 1.0                                 #
########################################################################

include_once("../../../global.php");
include("../functions.php");

$sql_list_kategorie = $DB->query("SELECT * FROM project_notizen WHERE id = '".$_GET['id']."'");


 require('../fpdf16/fpdf.php');
 $pdf=new FPDF();


  while($out_list_kategorie = $DB->fetch_array($sql_list_kategorie))
					{// begin while




						  $pdf->AddPage();
						  $pdf->SetFont('Arial','B',20);
						  $pdf->Cell(22,4,"Serious NetworX Notizen");
						  $pdf->Ln();
						  # Leerraum
						  $pdf->Cell(0,4,'');
						  $pdf->Ln();


						  $details = strip_tags ( $out_list_kategorie['text'] );

						  $pdf->SetFont('Arial','B',12);
						  $pdf->MultiCell(0,5,$out_list_kategorie['bezeichnung']);
						  $pdf->Ln();
						  $pdf->SetFont('Arial','',6);
						  $pdf->MultiCell(0,5,$details);
  						  $pdf->Ln();

						}

  $pdf->Output();

?>