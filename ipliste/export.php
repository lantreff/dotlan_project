<?
########################################################################
# Iplisten Modul for dotlan             			                   #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/ipliste/export.php - Version 1.0                               #
########################################################################


include_once("../../../global.php");
include("../functions.php");

$event_id = $EVENT->next;

$sql_list_category = $DB->query("SELECT category FROM project_ipliste GROUP BY category");



 require('../fpdf16/fpdf.php');
  $pdf=new FPDF();
  $pdf->AddPage();
  $pdf->SetFont('Arial','B',24);
  $pdf->Cell(22,4,"Serious NetworX IP-Liste");
  $pdf->Ln();
  # Leerraum
  $pdf->Cell(0,8,'');
  $pdf->Ln();


  while($out_list_category = $DB->fetch_array($sql_list_category))
					{// begin while

  # Ueberschrift
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(22,4,$out_list_category['category']);
  $pdf->Ln();
  # Leerraum
  $pdf->Cell(0,1,'');
  $pdf->Ln();
  # Header


						  $pdf->SetFont('Arial','B',10);
					  	  $pdf->Cell(30,4,"IP-Adresse: ");
					  	  $pdf->SetFont('Arial','B',10);
					  	  $pdf->Cell(100,4,"Bezeichnung");
						  $pdf->SetFont('Arial','B',10);
					  	  $pdf->Cell(0,4,"DNS");
					  	  $pdf->Ln();
			$sql_list_ip = $DB->query("SELECT * FROM project_ipliste WHERE category = '".$out_list_category['category']."' ORDER BY inet_aton(ip)");
#Table
			while($out_list_ip = $DB->fetch_array($sql_list_ip))
						{// begin while

						  $pdf->SetFont('Arial','',10);
						  $pdf->Cell(30,4,$out_list_ip['ip'],1);
						  $pdf->SetFont('Arial','',10);
						  $pdf->Cell(100,4,$out_list_ip['bezeichnung'],1);
						  $pdf->SetFont('Arial','',10);
						  $pdf->Cell(0,4,$out_list_ip['dns'],1);
						  $pdf->Ln();

						}
					   	#Leerraum
  						  $pdf->Cell(0,5,'');
  						  $pdf->Ln();
						#Leerraum
  						  $pdf->Cell(0,5,'');
  						  $pdf->Ln();
						  $pdf->Cell(0,5,'');
  						  $pdf->Ln();
					}





  $pdf->Output();

?>